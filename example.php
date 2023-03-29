<?php
require_once __DIR__.'/vendor/autoload.php';

// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');

// Lookup ip address geolocation data
$ip2locationio = new IP2LocationIO\IPGeolocation($config);
try {
    $result = $ip2locationio->lookup('8.8.8.8');
    var_dump($result->country_code);
} catch(Exception $e) {
    var_dump($e->getCode() . ": " . $e->getMessage());
}


// Lookup domain information
$ip2locationio = new IP2LocationIO\DomainWhois($config);
try {
    $result = $ip2locationio->lookup('locaproxy.com');
    var_dump($result->domain);
} catch(Exception $e) {
    var_dump($e->getCode() . ": " . $e->getMessage());
}
var_dump($ip2locationio->getPunycode('täst.de'));
var_dump($ip2locationio->getNormalText('xn--tst-qla.de'));
var_dump($ip2locationio->getDomainName('https://www.example.com/exe'));
var_dump($ip2locationio->getDomainExtension('example.com'));