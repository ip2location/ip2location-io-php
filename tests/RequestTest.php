<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Verifies how the SDK builds requests, using a recording transport double so
 * nothing touches the network.
 */
class RequestTest extends TestCase
{
	const DUMMY_KEY = 'A6BCA0A421AE4634816BA5F121DF8C05';

	private function config()
	{
		return new IP2LocationIO\Configuration(self::DUMMY_KEY);
	}

	/**
	 * Regression: the API key used to be sent as a "key" URL parameter, which
	 * leaks it into proxy and server access logs. It must travel as a bearer
	 * token instead.
	 */
	public function testGeolocationSendsKeyAsBearerToken()
	{
		$http = new RecordingHttp('{"ip":"8.8.8.8","country_code":"US"}');
		$geolocation = new IP2LocationIO\IPGeolocation($this->config(), $http);

		$geolocation->lookup('8.8.8.8');

		$this->assertCount(1, $http->urls);
		$this->assertStringNotContainsString('key=', $http->urls[0]);
		$this->assertStringNotContainsString(self::DUMMY_KEY, $http->urls[0]);
		$this->assertContains('Authorization: Bearer ' . self::DUMMY_KEY, $http->headers[0]);
		$this->assertStringContainsString('ip=8.8.8.8', $http->urls[0]);
	}

	public function testWhoisSendsKeyAsBearerToken()
	{
		$http = new RecordingHttp('{"domain":"google.com"}');
		$whois = new IP2LocationIO\DomainWhois($this->config(), $http);

		$whois->lookup('google.com');

		$this->assertStringNotContainsString('key=', $http->urls[0]);
		$this->assertStringNotContainsString(self::DUMMY_KEY, $http->urls[0]);
		$this->assertContains('Authorization: Bearer ' . self::DUMMY_KEY, $http->headers[0]);
		$this->assertStringContainsString('domain=google.com', $http->urls[0]);
	}

	public function testHostedDomainSendsKeyAsBearerToken()
	{
		$http = new RecordingHttp('{"ip":"8.8.8.8","domains":[]}');
		$hostedDomain = new IP2LocationIO\HostedDomain($this->config(), $http);

		$hostedDomain->lookup('8.8.8.8');

		$this->assertStringNotContainsString('key=', $http->urls[0]);
		$this->assertStringNotContainsString(self::DUMMY_KEY, $http->urls[0]);
		$this->assertContains('Authorization: Bearer ' . self::DUMMY_KEY, $http->headers[0]);
	}

	public function testApiErrorEnvelopeBecomesException()
	{
		$http = new RecordingHttp('{"error":{"error_code":10001,"error_message":"Invalid IP address."}}');
		$geolocation = new IP2LocationIO\IPGeolocation($this->config(), $http);

		try {
			$geolocation->lookup('not-an-ip');
			$this->fail('Expected the API error envelope to be raised as an exception.');
		} catch (Exception $e) {
			$this->assertEquals('Invalid IP address.', $e->getMessage());
			$this->assertEquals(10001, $e->getCode());
		}
	}

	/**
	 * Regression: a non-object "error" member used to emit
	 * "Attempt to read property on string" and construct an Exception with a null
	 * message and code, which is deprecated as of PHP 8.1.
	 */
	public function testStringErrorEnvelopeBecomesException()
	{
		$http = new RecordingHttp('{"error":"rate limited"}');
		$geolocation = new IP2LocationIO\IPGeolocation($this->config(), $http);

		try {
			$geolocation->lookup('8.8.8.8');
			$this->fail('Expected the API error envelope to be raised as an exception.');
		} catch (Exception $e) {
			$this->assertEquals('rate limited', $e->getMessage());
			$this->assertEquals(0, $e->getCode());
		}
	}

	public function testNonJsonResponseRaisesLookupError()
	{
		$http = new RecordingHttp('<html><body>not json</body></html>');
		$geolocation = new IP2LocationIO\IPGeolocation($this->config(), $http);

		try {
			$geolocation->lookup('8.8.8.8');
			$this->fail('Expected a non-JSON response to be raised as an exception.');
		} catch (Exception $e) {
			$this->assertEquals(10005, $e->getCode());
			$this->assertStringContainsString('invalid JSON response', $e->getMessage());
		}
	}

	/**
	 * Regression: the API returns its error envelope with a 4xx status. Throwing
	 * on the status before inspecting the body replaced the useful
	 * "Invalid IP address." message with a bare "Unexpected HTTP status 400".
	 */
	public function testApiErrorEnvelopeOnClientErrorStatusKeepsItsMessage()
	{
		$http = new RecordingHttp(
			'{"error":{"error_code":10001,"error_message":"Invalid IP address."}}',
			400
		);
		$geolocation = new IP2LocationIO\IPGeolocation($this->config(), $http);

		try {
			$geolocation->lookup('not-an-ip');
			$this->fail('Expected the API error envelope to be raised as an exception.');
		} catch (Exception $e) {
			$this->assertEquals('Invalid IP address.', $e->getMessage());
			$this->assertEquals(10001, $e->getCode());
		}
	}

	/**
	 * An error status whose body is not a usable API envelope must still surface
	 * the status, so a gateway error is distinguishable from a bad request.
	 */
	public function testErrorStatusWithUnusableBodyRaisesHttpException()
	{
		$http = new RecordingHttp('<html><body>502 Bad Gateway</body></html>', 502);
		$geolocation = new IP2LocationIO\IPGeolocation($this->config(), $http);

		try {
			$geolocation->lookup('8.8.8.8');
			$this->fail('Expected an error status to be raised as an exception.');
		} catch (IP2LocationIO\HttpException $e) {
			$this->assertEquals(502, $e->getCode());
			$this->assertStringContainsString('502', $e->getMessage());
		}
	}
}

/**
 * Transport double that records requests instead of performing them.
 */
final class RecordingHttp extends IP2LocationIO\Http
{
	/** @var string[] */
	public $urls = [];

	/** @var array[] */
	public $headers = [];

	/** @var string */
	private $response;

	/** @var int */
	private $status;

	public function __construct($response, $status = 200)
	{
		$this->response = $response;
		$this->status = $status;
	}

	public function get($url, array $headers = [])
	{
		$this->urls[] = $url;
		$this->headers[] = $headers;

		return $this->response;
	}

	public function getLastStatusCode()
	{
		return $this->status;
	}
}
