<?php

namespace IP2LocationIO;

/**
 * IP2Location.io Hosted Domain module.
 */
class HostedDomain
{
	use ApiResponseTrait;

	/** @var Configuration */
	private $config;

	/** @var Http */
	private $http;

	/**
	 * @param Configuration $config
	 * @param Http|null     $http   optional transport override, mainly for testing
	 */
	public function __construct(Configuration $config, ?Http $http = null)
	{
		$this->config = $config;
		$this->http = $http ?: new Http();
	}

	/**
	 * Get a list of hosted domain names by IP address.
	 *
	 * @param string $ip
	 * @param int    $page
	 *
	 * @return object
	 *
	 * @throws HttpException
	 */
	public function lookup($ip, $page = 1)
	{
		// The API key is sent as a bearer token rather than a URL parameter so it
		// is never written to proxy or server access logs.
		$response = $this->http->get('https://domains.ip2whois.com/domains?' . http_build_query([
			'format'         => 'json',
			'ip'             => $ip,
			'page'           => $page,
			'source'         => 'sdk-php-iplio',
			'source_version' => Configuration::VERSION,
		]), ['Authorization: Bearer ' . $this->config->getApiKey()]);

		return $this->parseResponse($response, 'HostedDomain');
	}
}
