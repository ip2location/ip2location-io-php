<?php

namespace IP2LocationIO;

/**
 * Configuration registry.
 *
 * @copyright 2023 IP2location.io
 */
class Configuration
{
	const VERSION = '1.0.0';

	public $apiKey = '';

	public function __construct($key)
	{
		$this->apiKey = $key;
	}

	/**
	 * Resets configuration.
	 */
	public function reset()
	{
		$apiKey = '';
	}

	/**
	 * Get or set API key.
	 *
	 * @param string $value if provided, sets the API key
	 *
	 * @return string IP2Location.io API key
	 */
	public function apiKey($value = null)
	{
		if (empty($value)) {
			$this->getApiKey();
		}
		$this->setApiKey($value);
	}

	/**
	 * Get API key.
	 *
	 * @return string IP2Location.io API key
	 */
	public function getApiKey()
	{
		return $this->apiKey;
	}

	/**
	 * Set API key.
	 *
	 * @param string $value sets the API key
	 */
	private function setApiKey($value)
	{
		if (empty($value)) {
			throw new \RuntimeException('No API key is provided');
		}

		if (!is_string($value)) {
			throw new \RuntimeException('The API key must be a string');
		}

		if (!preg_match('/^[A-Z0-9]{32}$/', $value)) {
			throw new \RuntimeException('The API key is invalid');
		}

		$this->apiKey = $value;
	}
}

class_alias('IP2LocationIO\Configuration', 'IP2LocationIO_Configuration');
