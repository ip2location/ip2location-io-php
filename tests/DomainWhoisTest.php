<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DomainWhoisTest extends TestCase
{
	public function testInvalidApiKey()
	{
		$config = new IP2LocationIO\Configuration('A6BCA0A421AE4634816BA5F121DF8C05');
		$whois = new IP2LocationIO\DomainWhois($config);
		try {
			$whois->lookup('example.c');
		} catch (Exception $e) {
			$this->assertEquals('API key not found.', $e->getMessage());
		}
	}

	public function testApiKeyExist()
	{
		if ($GLOBALS['testApiKey'] == 'YOUR_API_KEY') {
			echo '/*
* You could enter a IP2Location.io API Key in tests/bootstrap.php
* for real web service calling test.
*
* You could sign up for a free API key at https://www.ip2location.io/pricing
* if you do not have one.
*/';
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

	public function testLookupDomain()
	{
		$config = new IP2LocationIO\Configuration($GLOBALS['testApiKey']);
		$whois = new IP2LocationIO\DomainWhois($config);

		try {
			$results = $whois->lookup('google.com');

			$this->assertEquals('Google LLC', $results->registrant->organization);
		} catch (Exception $e) {
			if ($GLOBALS['testApiKey'] == 'YOUR_API_KEY') {
				$this->assertEquals('API key not found.', $e->getMessage());
			}
		}
	}
}
