<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class IPGeolocationTest extends TestCase
{
	const DUMMY_KEY = 'A6BCA0A421AE4634816BA5F121DF8C05';

	private function geolocation($apiKey)
	{
		return new IP2LocationIO\IPGeolocation(new IP2LocationIO\Configuration($apiKey));
	}

	/**
	 * Assert an API-level error with the given documented error code.
	 *
	 * The error codes are the documented contract; the wording of the messages is
	 * not stable, so only assert that a message is present.
	 */
	private function assertApiError($expectedCode, callable $call)
	{
		try {
			$call();
			$this->fail('Expected an API error with code ' . $expectedCode . '.');
		} catch (IP2LocationIO\HttpException $e) {
			$this->fail('Expected an API-level error but got a transport error: ' . $e->getMessage());
		} catch (Exception $e) {
			$this->assertEquals($expectedCode, $e->getCode(), $e->getMessage());
			$this->assertNotSame('', $e->getMessage());
		}
	}

	private function skipWithoutApiKey()
	{
		if ($GLOBALS['testApiKey'] === '') {
			$this->markTestSkipped('Set IP2LOCATION_API_KEY to run live API tests.');
		}
	}

	public function testLookupIP()
	{
		$this->skipWithoutApiKey();

		$result = $this->geolocation($GLOBALS['testApiKey'])->lookup('8.8.8.8');

		$this->assertEquals('8.8.8.8', $result->ip);
		$this->assertEquals('US', $result->country_code);
	}

	public function testLookupIPv6()
	{
		$this->skipWithoutApiKey();

		$result = $this->geolocation($GLOBALS['testApiKey'])->lookup('2001:4860:4860::8888');

		$this->assertEquals('US', $result->country_code);
	}

	public function testInvalidApiKeyRaisesApiError()
	{
		$this->skipWithoutApiKey();

		$geolocation = $this->geolocation(self::DUMMY_KEY);

		$this->assertApiError(10000, function () use ($geolocation) {
			$geolocation->lookup('8.8.8.8');
		});
	}

	public function testInvalidIpRaisesApiError()
	{
		$this->skipWithoutApiKey();

		$geolocation = $this->geolocation($GLOBALS['testApiKey']);

		$this->assertApiError(10001, function () use ($geolocation) {
			$geolocation->lookup('not-an-ip');
		});
	}
}
