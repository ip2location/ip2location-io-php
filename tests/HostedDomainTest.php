<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HostedDomainTest extends TestCase
{
	public function testInvalidApiKey()
	{
		$config = new IP2LocationIO\Configuration('A6BCA0A421AE4634816BA5F121DF8C05');
		$domain = new IP2LocationIO\HostedDomain($config);

		try {
			$domain->lookup('8.8.8.8');
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

	public function testLookupDomainInvalidPage()
	{
		$config = new IP2LocationIO\Configuration($GLOBALS['testApiKey']);
		$domain = new IP2LocationIO\HostedDomain($config);
		try {
			$domain->lookup('8.8.8.8', 100);
		} catch (Exception $e) {
			$this->assertEquals('Invalid page value.', $e->getMessage());
		}
	}

	public function testLookupDomain()
	{
		$config = new IP2LocationIO\Configuration($GLOBALS['testApiKey']);
		$domain = new IP2LocationIO\HostedDomain($config);
		try {
			$result = $domain->lookup('8.8.8.8');
			$this->assertEquals(
				100,
				count($result->domains),
			);
		} catch (Exception $e) {
			if ($GLOBALS['testApiKey'] == 'YOUR_API_KEY') {
				$this->assertEquals('Invalid API key or insufficient credit.', $e->getMessage());
			}
		}
	}
}
