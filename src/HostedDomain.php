<?php

namespace IP2LocationIO;

/**
 * IP2Location.io Hosted Domain module.
 */
class HostedDomain
{
	private $apiKey = '';

	public function __construct($config)
	{
		if (!isset($config->apiKey)) {
			throw new \Exception('Please provide a valid API key.');
		}

		if (!preg_match('/^[A-Z0-9]{32}$/', $config->apiKey)) {
			throw new \Exception('Please provide a valid API key.');
		}

		$this->apiKey = $config->apiKey;
	}

	/**
	 * Get a list of hosted domain names by IP address.
	 *
	 * @param string $ip
	 *
	 * @return object
	 */
	public function lookup($ip, $page = 1)
	{
		$http = new Http();
		$response = $http->get('https://domains.ip2whois.com/domains?' . http_build_query([
			'key'            => $this->apiKey,
			'format'         => 'json',
			'ip'             => $ip,
			'page'           => $page,
			'source'         => 'sdk-php-iplio',
			'source_version' => Configuration::VERSION,
		]));

		if (($json = json_decode($response)) === null) {
			throw new \Exception('HostedDomain lookup error.', 10005);
		}

		if (isset($json->error)) {
			throw new \Exception($json->error->error_message, $json->error->error_code);
		} else {
			return $json;
		}
	}
}

class_alias('IP2LocationIO\HostedDomain', 'IP2LocationIO_HostedDomain');
