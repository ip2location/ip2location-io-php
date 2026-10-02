<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Regression test for the disabled TLS certificate verification
 * (CURLOPT_SSL_VERIFYPEER => 0) that previously allowed a man-in-the-middle to
 * forge lookup results and harvest the API key.
 */
class HttpTest extends TestCase
{
	/** @var resource|null */
	private $process = null;

	/** @var array */
	private $pipes = [];

	/** @var string */
	private $dir = '';

	protected function tearDown(): void
	{
		if (is_resource($this->process)) {
			foreach ($this->pipes as $pipe) {
				if (is_resource($pipe)) {
					fclose($pipe);
				}
			}

			proc_terminate($this->process);
			proc_close($this->process);
		}

		$this->process = null;
		$this->pipes = [];

		if ($this->dir !== '' && is_dir($this->dir)) {
			foreach ((array) glob($this->dir . '/*') as $file) {
				@unlink($file);
			}

			@rmdir($this->dir);
		}

		$this->dir = '';
	}

	public function testRejectsSelfSignedCertificate()
	{
		if (!extension_loaded('openssl')) {
			$this->markTestSkipped('The openssl extension is not available.');
		}

		if (!function_exists('proc_open')) {
			$this->markTestSkipped('proc_open() is disabled.');
		}

		$port = $this->startTlsServer();
		$http = new IP2LocationIO\Http(5, 5);

		try {
			$response = $http->get('https://127.0.0.1:' . $port . '/');
		} catch (IP2LocationIO\HttpException $e) {
			// 60 = CURLE_PEER_FAILED_VERIFICATION: the peer certificate could not
			// be authenticated. Anything else means we failed for another reason.
			$this->assertEquals(60, $e->getCode(), $e->getMessage());

			return;
		}

		$this->fail('A self-signed certificate must be rejected, but the response was accepted: '
			. var_export($response, true));
	}

	/**
	 * Starts the fixture server and returns the port it bound to.
	 *
	 * @return int
	 */
	private function startTlsServer()
	{
		$this->dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'iplio-' . bin2hex(random_bytes(6));

		if (!mkdir($this->dir, 0700)) {
			$this->markTestSkipped('Unable to create a temporary directory for the TLS fixture.');
		}

		$key = openssl_pkey_new([
			'private_key_type' => OPENSSL_KEYTYPE_RSA,
			'private_key_bits' => 2048,
		]);

		if ($key === false) {
			$this->markTestSkipped('Unable to generate a test private key.');
		}

		$csr = openssl_csr_new(['commonName' => '127.0.0.1'], $key, ['digest_alg' => 'sha256']);
		$x509 = openssl_csr_sign($csr, null, $key, 2, ['digest_alg' => 'sha256']);

		openssl_x509_export($x509, $cert);
		openssl_pkey_export($key, $pkey);

		file_put_contents($this->dir . '/cert.pem', $cert);
		file_put_contents($this->dir . '/key.pem', $pkey);

		$this->process = proc_open(
			[
				PHP_BINARY,
				__DIR__ . '/tls-server.php',
				$this->dir . '/cert.pem',
				$this->dir . '/key.pem',
			],
			[1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
			$this->pipes
		);

		if (!is_resource($this->process)) {
			$this->markTestSkipped('Unable to start the local TLS fixture server.');
		}

		stream_set_blocking($this->pipes[1], false);

		$deadline = microtime(true) + 15;
		$output = '';

		while (microtime(true) < $deadline) {
			$output .= (string) fread($this->pipes[1], 8192);

			if (preg_match('/listening (\d+)/', $output, $matches)) {
				return (int) $matches[1];
			}

			usleep(50000);
		}

		$this->markTestSkipped('The local TLS fixture server did not start: ' . trim($output));
	}
}
