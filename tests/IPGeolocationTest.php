<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class IPGeolocationTest extends TestCase
{
	public function testInvalidApiKey() {
		$config = new IP2LocationIO\Configuration('');
		$ip2locationio = new IP2LocationIO\IPGeolocation($config);
		$result = $ip2locationio->lookup('8.8.8.8');

		$this->assertEquals(
			'Invalid API key or insufficient credit.',
			$result->error->error_message,
		);
	}

	public function testApiKeyExist() {
		if ($GLOBALS['testApiKey'] == 'YOUR_API_KEY') {
			echo "/*
* You could enter a IP2Location.io API Key in tests/bootstrap.php
* for real web service calling test.
* 
* You could sign up for a free API key at https://www.ip2location.io/pricing
* if you do not have one.
*/";
			$this->assertEquals(
				'YOUR_API_KEY',
				$GLOBALS['testApiKey'],
			);
		} else {
			$this->assertNotEquals(
				'YOUR_API_KEY',
				$GLOBALS['testApiKey'],
			);
		}
	}

	public function testLookupIP() {
		$config = new IP2LocationIO\Configuration($GLOBALS['testApiKey']);
		$ip2locationio = new IP2LocationIO\IPGeolocation($config);
		$result = $ip2locationio->lookup('8.8.8.8');

		if ($GLOBALS['testApiKey'] == 'YOUR_API_KEY') {
			$this->assertEquals(
				'Invalid API key or insufficient credit.',
				$result->error->error_message,
			);
		} else {
			$this->assertEquals(
				'US',
				$result->country_code,
			);
		}
	}
}