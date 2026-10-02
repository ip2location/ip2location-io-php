<?php

namespace IP2LocationIO;

/**
 * IP2Location.io HTTP Client
 * Sends Http requests using curl.
 */
class Http
{
	/** @var int Total request timeout in seconds. */
	private $timeout;

	/** @var int Connection timeout in seconds. */
	private $connectTimeout;

	/** @var int Status code of the most recent response. */
	private $lastStatusCode = 0;

	public function __construct($timeout = 60, $connectTimeout = 10)
	{
		$this->timeout = (int) $timeout;
		$this->connectTimeout = (int) $connectTimeout;
	}

	/**
	 * Status code of the most recent response.
	 *
	 * Error responses from the API still carry a useful JSON error envelope, so
	 * the status is exposed rather than thrown on here: the caller decides whether
	 * the body is a usable API error or an unusable transport-level failure.
	 *
	 * @return int
	 */
	public function getLastStatusCode()
	{
		return $this->lastStatusCode;
	}

	/**
	 * Perform a GET request.
	 *
	 * @param string $url
	 * @param array  $headers additional request headers, e.g. an Authorization bearer token
	 *
	 * @return string the raw response body
	 *
	 * @throws HttpException on transport failure or an error response status
	 */
	public function get($url, array $headers = [])
	{
		if (!extension_loaded('curl')) {
			throw new HttpException('The cURL extension is required by the IP2Location.io PHP SDK.');
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		// Peer and host verification must stay enabled. Disabling either lets a
		// man-in-the-middle forge lookup results and harvest the API key.
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_USERAGENT, 'IP2Location.io PHP SDK ' . Configuration::VERSION);

		if ($headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$response = curl_exec($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);

		$this->lastStatusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ($errno !== 0) {
			// Carry the cURL errno through so a DNS failure is distinguishable from
			// a TLS failure or a timeout instead of collapsing into one generic error.
			throw new HttpException('HTTP transport error: ' . $error, $errno);
		}

		if ($response === false || $response === '') {
			throw new HttpException('Empty response from server.', $this->lastStatusCode ?: 10005);
		}

		return $response;
	}
}
