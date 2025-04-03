<?php

namespace IP2LocationIO;

/**
 * Configuration registry.
 *
 */
class Configuration
{
	const VERSION = '1.1.0';

	public $apiKey = '';

	public function __construct($apiKey)
	{
		if (!preg_match('/^[A-Z0-9]{32}$/', $apiKey)) {
			throw new \Exception('Please provide a valid API key.');
		}

		$this->apiKey = $apiKey;
	}
}

class_alias('IP2LocationIO\Configuration', 'IP2LocationIO_Configuration');
