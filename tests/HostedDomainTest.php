<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HostedDomainTest extends TestCase
{
	const DUMMY_KEY = 'A6BCA0A421AE4634816BA5F121DF8C05';

	private function hostedDomain($apiKey)
	{
		return new IP2LocationIO\HostedDomain(new IP2LocationIO\Configuration($apiKey));
	}

	public function testLookupDomain()
	{
		if ($GLOBALS['testApiKey'] === '') {
			$this->markTestSkipped('Set IP2LOCATION_API_KEY to run live API tests.');
		}

		$result = $this->hostedDomain($GLOBALS['testApiKey'])->lookup('8.8.8.8');

		$this->assertEquals('8.8.8.8', $result->ip);
		$this->assertNotEmpty($result->domains);
	}

	public function testLookupInvalidPageRaisesApiError()
	{
		if ($GLOBALS['testApiKey'] === '') {
			$this->markTestSkipped('Set IP2LOCATION_API_KEY to run live API tests.');
		}

		// 10008 = invalid page value.
		$this->expectException(Exception::class);
		$this->expectExceptionCode(10008);

		$this->hostedDomain($GLOBALS['testApiKey'])->lookup('8.8.8.8', 100);
	}

	public function testInvalidApiKeyRaisesApiError()
	{
		if ($GLOBALS['testApiKey'] === '') {
			$this->markTestSkipped('Set IP2LOCATION_API_KEY to run live API tests.');
		}

		$this->expectException(Exception::class);
		$this->expectExceptionCode(10001);

		$this->hostedDomain(self::DUMMY_KEY)->lookup('8.8.8.8');
	}
}
