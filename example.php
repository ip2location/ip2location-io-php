<?php

require_once __DIR__ . '/vendor/autoload.php';

// The API key is read from the environment so that it is never written into
// source control. Set it before running this script:
//
//   Linux/macOS: export IP2LOCATION_API_KEY=your_api_key_here
//   Windows cmd: set IP2LOCATION_API_KEY=your_api_key_here
//
$apiKey = (string) getenv('IP2LOCATION_API_KEY');

if ($apiKey === '') {
	exit('Please set the IP2LOCATION_API_KEY environment variable.' . PHP_EOL);
}

// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration($apiKey);

// Lookup ip address geolocation data
$geolocation = new IP2LocationIO\IPGeolocation($config);
try {
	$result = $geolocation->lookup('8.8.8.8');
	var_dump($result->country_code);
} catch (Exception $e) {
	var_dump($e->getCode() . ': ' . $e->getMessage());
}

// Lookup domain information
$whois = new IP2LocationIO\DomainWhois($config);
try {
	$result = $whois->lookup('google.com');
	var_dump($result->domain);
} catch (Exception $e) {
	var_dump($e->getCode() . ': ' . $e->getMessage());
}
var_dump($whois->getPunycode('täst.de'));
var_dump($whois->getNormalText('xn--tst-qla.de'));
var_dump($whois->getDomainName('https://www.example.com/exe'));
var_dump($whois->getDomainExtension('example.com'));

// Hosted domain lookup
$domain = new IP2LocationIO\HostedDomain($config);
try {
	$result = $domain->lookup('1.1.1.1');
	var_dump($result->domains);
} catch (Exception $e) {
	var_dump($e->getCode() . ': ' . $e->getMessage());
}
