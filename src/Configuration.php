<?php

namespace IP2LocationIO;

/**
 * Configuration registry.
 *
 * @property string $apiKey Backwards-compatible alias for getApiKey(). Prefer
 *                          the method: direct property access is deprecated.
 */
class Configuration
{
	const VERSION = '1.1.1';

	/** @var string */
	private $apiKey = '';

	public function __construct($apiKey)
	{
		$this->apiKey = self::normalize($apiKey);
	}

	/**
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->apiKey;
	}

	/**
	 * Backwards-compatible read access to the key as a public property.
	 *
	 * The property is private so the key is no longer swept into logs by
	 * json_encode(), var_dump() or a debug payload. Those inspect real properties
	 * only and never consult __get(), so this alias restores source compatibility
	 * without restoring the leak.
	 *
	 * @deprecated Use getApiKey().
	 *
	 * @param string $name
	 *
	 * @return string|null
	 */
	public function __get($name)
	{
		if ($name === 'apiKey') {
			return $this->apiKey;
		}

		// Mirror the native PHP 8 behaviour for an undefined property, since
		// defining __get() suppresses it.
		trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_WARNING);

		return null;
	}

	/**
	 * Backwards-compatible isset()/empty() support.
	 *
	 * Without this, isset($config->apiKey) would report false now that the
	 * property is private, which would silently break callers that guard on it.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset($name)
	{
		return $name === 'apiKey';
	}

	/**
	 * Backwards-compatible write access.
	 *
	 * Values are run through the same validation as the constructor so that
	 * assigning a property cannot bypass API key validation.
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 */
	public function __set($name, $value)
	{
		if ($name === 'apiKey') {
			$this->apiKey = self::normalize($value);

			return;
		}

		throw new \LogicException('Undefined property: ' . __CLASS__ . '::$' . $name);
	}

	/**
	 * Mask the API key in var_dump() output.
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		return ['apiKey' => str_repeat('*', 28) . substr($this->apiKey, -4)];
	}

	/**
	 * Single source of truth for API key validation.
	 *
	 * @param mixed $apiKey
	 *
	 * @return string
	 */
	private static function normalize($apiKey)
	{
		if (!is_string($apiKey)) {
			throw new \InvalidArgumentException('Please provide a valid API key.');
		}

		$apiKey = trim($apiKey);

		// \z rather than $: in PCRE, $ also matches immediately before a trailing
		// newline, so "KEY\n" would pass validation. trim() above already removes
		// surrounding whitespace; the anchor keeps that guarantee if trim is ever
		// relaxed.
		if (!preg_match('/^[A-Z0-9]{32}\z/', $apiKey)) {
			throw new \InvalidArgumentException('Please provide a valid API key.');
		}

		return $apiKey;
	}
}
