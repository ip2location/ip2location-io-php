<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
	const API_KEY = 'A6BCA0A421AE4634816BA5F121DF8C05';

	public function testAcceptsAValidKey()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->assertEquals(self::API_KEY, $config->getApiKey());
	}

	public function testTrimsSurroundingWhitespace()
	{
		$config = new IP2LocationIO\Configuration("  " . self::API_KEY . "\t");

		$this->assertEquals(self::API_KEY, $config->getApiKey());
	}

	/**
	 * Regression: in PCRE, "$" also matches immediately before a trailing newline,
	 * so a key pasted with a trailing \n passed validation unchanged and was then
	 * sent over the wire as "...%0A", which the API rejects as an invalid key.
	 * The key is now trimmed before validation, so the clean value is sent.
	 */
	public function testStripsTrailingNewlineFromPastedKey()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY . "\n");

		$this->assertEquals(self::API_KEY, $config->getApiKey());
		$this->assertStringNotContainsString("\n", $config->getApiKey());
	}

	public function testStripsTrailingCarriageReturnAndNewline()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY . "\r\n");

		$this->assertEquals(self::API_KEY, $config->getApiKey());
	}

	public function testRejectsKeyWithEmbeddedNewline()
	{
		$this->expectException(InvalidArgumentException::class);

		new IP2LocationIO\Configuration("A6BCA0A421AE4634816\nBA5F121DF8C05");
	}

	public function testRejectsLowercaseKey()
	{
		$this->expectException(InvalidArgumentException::class);

		new IP2LocationIO\Configuration('a6bca0a421ae4634816ba5f121df8c05');
	}

	public function testRejectsShortKey()
	{
		$this->expectException(InvalidArgumentException::class);

		new IP2LocationIO\Configuration('A6BCA0A421AE4634816BA5F121DF8C0');
	}

	public function testRejectsNonStringKey()
	{
		$this->expectException(InvalidArgumentException::class);

		new IP2LocationIO\Configuration(null);
	}

	public function testKeyIsNotExposedByJsonEncode()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->assertStringNotContainsString(self::API_KEY, (string) json_encode($config));
	}

	public function testKeyIsMaskedByVarDump()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		ob_start();
		var_dump($config);
		$dump = (string) ob_get_clean();

		$this->assertStringNotContainsString(self::API_KEY, $dump);
	}

	/*
	 * Backwards compatibility: the key moved from a public property to a private
	 * one, so these access patterns must keep working (issue #5).
	 */

	public function testPropertyReadStillWorks()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->assertEquals(self::API_KEY, $config->apiKey);
	}

	public function testIssetOnPropertyStillWorks()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->assertTrue(isset($config->apiKey));
	}

	public function testPropertyWriteIsValidatedAndApplied()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);
		$config->apiKey = 'B1BCA0A421AE4634816BA5F121DF8C99';

		$this->assertEquals('B1BCA0A421AE4634816BA5F121DF8C99', $config->getApiKey());
	}

	/**
	 * Writing must not be a way to smuggle in an unvalidated key.
	 */
	public function testPropertyWriteRejectsInvalidKey()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->expectException(InvalidArgumentException::class);

		$config->apiKey = 'not-a-valid-key';
	}

	public function testUnknownPropertyWriteThrows()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->expectException(LogicException::class);

		$config->apiKeey = self::API_KEY;
	}

	/**
	 * Defining __get() suppresses PHP's native "Undefined property" warning, so
	 * the class re-raises it rather than silently returning null and hiding typos.
	 *
	 * The warning is captured with a handler because PHPUnit 9's expectWarning()
	 * is itself deprecated and would fail the failOnWarning run.
	 */
	public function testUnknownPropertyReadWarns()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$captured = null;
		set_error_handler(function ($errno, $errstr) use (&$captured) {
			$captured = [$errno, $errstr];

			return true;
		}, E_USER_WARNING);

		try {
			$value = $config->apiKeey;
		} finally {
			restore_error_handler();
		}

		$this->assertNull($value);
		$this->assertNotNull($captured, 'Expected a warning when reading an undefined property.');
		$this->assertEquals(E_USER_WARNING, $captured[0]);
		$this->assertStringContainsString('apiKeey', $captured[1]);
	}

	/**
	 * The __get() alias must not resurrect the logging leak: json_encode() and
	 * var_dump() inspect real properties and never consult magic getters.
	 */
	public function testMagicGetterDoesNotReExposeKeyToEncoders()
	{
		$config = new IP2LocationIO\Configuration(self::API_KEY);

		$this->assertEquals('{}', json_encode($config));
	}
}
