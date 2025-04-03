<?php

namespace IP2LocationIO;

/**
 * IP2Location.io HTTP Client
 * Sends Http requests using curl.
 */
class Http
{
	public function __construct()
	{
	}

	public function get($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_USERAGENT, 'IP2Location.io PHP SDK ' . Configuration::VERSION);

		$response = curl_exec($ch);

		if (empty($response) || curl_error($ch)) {
			curl_close($ch);

			return false;
		}

		curl_close($ch);

		return $response;
	}
}

class_alias('IP2LocationIO\Http', 'IP2LocationIO_Http');
