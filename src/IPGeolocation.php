<?php

namespace IP2LocationIO;

/**
 * IP2Location.io IP Geolocation module.
 */
class IPGeolocation
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
	 * Lookup given IP address for an enriched data set.
	 *
	 * @param string $ip
	 * @param string $language
	 *
	 * @return object
	 *
	 * @throws HttpException
	 */
	public function lookup($ip, $language = '')
	{
		// The API key is sent as a bearer token rather than a URL parameter so it
		// is never written to proxy or server access logs.
		$response = $this->http->get('https://api.ip2location.io/?' . http_build_query([
			'format'         => 'json',
			'ip'             => $ip,
			'lang'           => $language,
			'source'         => 'sdk-php-iplio',
			'source_version' => Configuration::VERSION,
		]), ['Authorization: Bearer ' . $this->config->getApiKey()]);

		return $this->parseResponse($response, 'IPGeolocation');
	}
}
