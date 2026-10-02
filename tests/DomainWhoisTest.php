<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DomainWhoisTest extends TestCase
{
	const DUMMY_KEY = 'A6BCA0A421AE4634816BA5F121DF8C05';

	private function whois()
	{
		return new IP2LocationIO\DomainWhois(new IP2LocationIO\Configuration(self::DUMMY_KEY));
	}

	public static function domainNameProvider()
	{
		return [
			'plain domain'            => ['example.com', 'example.com'],
			'url with path'           => ['https://www.example.com/exe', 'example.com'],
			'multi level tld'         => ['http://sub.example.co.uk/a', 'example.co.uk'],
			// Regression: these two used to be rejected because the scheme check
			// tested for a leading "http" instead of a "://" separator.
			'host starting with http' => ['httpbin.org', 'httpbin.org'],
			'host starting with https' => ['httpsomething.com', 'httpsomething.com'],
			'uppercase scheme'        => ['HTTP://EXAMPLE.COM', 'example.com'],
			'non http scheme'         => ['FTP://ftp.example.com', 'example.com'],
			'userinfo in url'         => ['https://user:pw@evil.com', 'evil.com'],
			'empty string'            => ['', 'DOMAIN NOT FOUND'],
			'whitespace only'         => ['   ', 'DOMAIN NOT FOUND'],
			'garbage'                 => ['not a url at all', 'DOMAIN NOT FOUND'],
			'no dot'                  => ['localhost', 'DOMAIN NOT FOUND'],
		];
	}

	/**
	 * @dataProvider domainNameProvider
	 */
	public function testGetDomainName($input, $expected)
	{
		$this->assertEquals($expected, $this->whois()->getDomainName($input));
	}

	/**
	 * @requires extension intl
	 */
	public function testGetDomainNameNormalizesIdn()
	{
		$this->assertEquals('xn--tst-qla.de', $this->whois()->getDomainName('täst.de'));
		$this->assertEquals('xn--tst-qla.de', $this->whois()->getDomainName('xn--tst-qla.de'));
	}

	public function testGetDomainExtension()
	{
		$whois = $this->whois();

		$this->assertEquals('.com', $whois->getDomainExtension('example.com'));
		$this->assertEquals('.com', $whois->getDomainExtension('https://www.example.com/x'));
		$this->assertEquals('.co.uk', $whois->getDomainExtension('sub.example.co.uk'));
	}

	/**
	 * Regression: this used to return the "DOMAIN NOT FOUND" sentinel as though it
	 * were a domain extension.
	 */
	public function testGetDomainExtensionOnUnparseableInput()
	{
		$this->assertEquals('DOMAIN NOT FOUND', $this->whois()->getDomainExtension('not a url at all'));
	}

	/**
	 * @requires extension intl
	 */
	public function testPunycodeRoundTrip()
	{
		$whois = $this->whois();

		$this->assertEquals('xn--tst-qla.de', $whois->getPunycode('täst.de'));
		$this->assertEquals('täst.de', $whois->getNormalText('xn--tst-qla.de'));
	}

	public function testRejectsEmptyDomainForLookup()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Please provide a valid domain name.');

		$this->whois()->lookup('');
	}

	public function testLookupDomain()
	{
		if ($GLOBALS['testApiKey'] === '') {
			$this->markTestSkipped('Set IP2LOCATION_API_KEY to run live API tests.');
		}

		$whois = new IP2LocationIO\DomainWhois(new IP2LocationIO\Configuration($GLOBALS['testApiKey']));
		$result = $whois->lookup('google.com');

		$this->assertEquals('google.com', $result->domain);
	}

	public function testInvalidApiKeyRaisesApiError()
	{
		if ($GLOBALS['testApiKey'] === '') {
			$this->markTestSkipped('Set IP2LOCATION_API_KEY to run live API tests.');
		}

		$whois = new IP2LocationIO\DomainWhois(new IP2LocationIO\Configuration(self::DUMMY_KEY));

		$this->expectException(Exception::class);
		$this->expectExceptionCode(10001);

		$whois->lookup('example.com');
	}
}
