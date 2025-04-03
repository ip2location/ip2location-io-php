# Quickstart

## Dependencies

This module requires API key to function. You may sign up for a free API key at <https://www.ip2location.io/pricing>.

## Installation

Install this package using **composer** as below:

``` bash
composer require ip2location/ip2location-io-php
```

## Sample Codes

### Lookup IP Address Geolocation Data

You can make a geolocation data lookup for an IP address as below:

``` php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\IPGeolocation($config);

// Lookup ip address geolocation data
$ip2locationio->lookup('8.8.8.8', 'en'); // The language parameter is only available for Plus and Security plan only.
?>
```

### Lookup Domain Information

You can lookup domain information as below:

```php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Lookup domain information
$ip2locationio->lookup('example.com');
?>
```

### Convert Normal Text to Punycode

You can convert an international domain name to Punycode as below:

```php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Convert normal text to punycode
$ip2locationio->getPunycode('täst.de');
?>
```

### Convert Punycode to Normal Text

You can convert a Punycode to international domain name as below:

```php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Convert punycode to normal text
$ip2locationio->getNormalText('xn--tst-qla.de');
?>
```

### Get Domain Name

You can extract the domain name from an url as below:

```php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Get domain name from URL
$ip2locationio->getDomainName('https://www.example.com/exe');
?>
```

### Get Domain Extension

You can extract the domain extension from a domain name or url as below:

```php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Get domain extension (gTLD or ccTLD) from URL or domain name
$ip2locationio->getDomainExtension('example.com');
?>
```

### Get Hosted Domain List

You can get the domains listed within the IP using following codes:

```php
<?php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$domain = new IP2LocationIO\HostedDomain($config);

// Get a list of hosted domains
$domain->lookup('8.8.8.8');
?>
```

