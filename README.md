# IP2Location.io PHP SDK — IP Geolocation, Domain WHOIS & Hosted Domain Lookup

[![Latest Stable Version](https://img.shields.io/packagist/v/ip2location/ip2location-io-php.svg)](https://packagist.org/packages/ip2location/ip2location-io-php)
[![Total Downloads](https://img.shields.io/packagist/dt/ip2location/ip2location-io-php.svg?style=flat-square)](https://packagist.org/packages/ip2location/ip2location-io-php)

**IP2Location.io PHP SDK** is a PHP library for retrieving **IPv4 and IPv6 geolocation, network and IP intelligence data** using the [IP2Location.io API](https://www.ip2location.io/). The SDK also provides **domain WHOIS lookup** and **hosted domain lookup**. Integrate IP intelligence and domain information into PHP applications without building HTTP requests manually.

**Quick links:** [Get an API key](https://www.ip2location.io/pricing) · [Developer documentation](https://ip2location-io-php.readthedocs.io/en/latest/index.html) · [API reference](https://ip2location-io-php.readthedocs.io/en/latest/code.html)

## Features

- **IPv4 and IPv6 geolocation:** Look up country, region, district, city, latitude, longitude and postal code.
- **Network and ASN information:** Access ISP, autonomous system, associated domain, connection/usage type and other available network attributes.
- **Time zone and localization data:** Retrieve time zone information, including available daylight saving time data, and optionally request translated location names (Plus and Security plans).
- **Proxy and threat-related signals:** Access available proxy, VPN, Tor, data center, crawler and other security-related indicators. Availability depends on your API plan and the response.
- **Additional IP intelligence:** Where included in the API response, access mobile carrier information (MCC/MNC), country metadata and advertising categories.
- **Domain WHOIS lookup:** Retrieve available domain registration details, including registrar, registration dates, domain status, nameservers and contact information.
- **Internationalized domain and URL utilities:** Convert between Unicode domain names and Punycode, extract domain names from URLs and obtain domain extensions.
- **Hosted domains:** Retrieve domain names hosted on an IPv4 or IPv6 address, with support for paginated results.

> **Plan availability:** Not all fields are included with every API plan, and not every field will contain a value for every IP address. Check the [plan details](https://www.ip2location.io/pricing) and [API response fields](https://ip2location-io-php.readthedocs.io/en/latest/code.html) before using optional attributes.

## SDK classes

The [SDK API reference](https://ip2location-io-php.readthedocs.io/en/latest/code.html) documents four classes:

| Class | Description |
| --- | --- |
| [`Configuration`](https://ip2location-io-php.readthedocs.io/en/latest/code.html#configuration-class) | Stores the API key used to configure SDK lookups. |
| [`IPGeolocation`](https://ip2location-io-php.readthedocs.io/en/latest/code.html#ipgeolocation-class) | Looks up geolocation and available network/security data for IPv4 and IPv6 addresses. |
| [`DomainWhois`](https://ip2location-io-php.readthedocs.io/en/latest/code.html#domainwhois-class) | Looks up domain WHOIS records and provides domain/Punycode utilities. |
| [`HostedDomain`](https://ip2location-io-php.readthedocs.io/en/latest/code.html#hosteddomain-class) | Lists domains hosted on an IP address, with pagination support. |

## API access and query limits

IP2Location.io offers keyless IP geolocation queries and a Free plan with an API key:

| API service | Keyless (no API key) | Free plan (with API key) |
| --- | --- | --- |
| IP Geolocation API | **1,000 queries/day** | **50,000 queries/month** |
| Domain WHOIS API | - | **500 queries/month** |
| Hosted Domain API | - | **50 queries/month** |

- **Keyless access:** No registration required. The daily quota resets at **00:00 UTC**. Keyless requests are made directly to the [IP Geolocation REST API](https://www.ip2location.io/ip2location-documentation); the PHP SDK examples below use an API key.
- **Free plan:** Register for a free API key to use the monthly allowances above. Free-plan requests stop when their applicable quota is exhausted.
- **Attribution:** Both keyless API access and the Free plan require attribution. See the [pricing page](https://www.ip2location.io/pricing) for the required wording and conditions.
- **Paid plans:** For higher limits, additional fields and current pricing, refer to the [IP2Location.io pricing page](https://www.ip2location.io/pricing).

For details on keyless requests, authentication and rate limiting, see the [IP Geolocation API documentation](https://www.ip2location.io/ip2location-documentation).

## Requirements

- PHP **7.4 or later**
- PHP `curl` and `json` extensions
- [Composer](https://getcomposer.org/)
- An [IP2Location.io API key](https://www.ip2location.io/pricing) for the SDK examples (free signup available)

The optional PHP `intl` extension is required for `DomainWhois` internationalized-domain conversion (`getPunycode()` and `getNormalText()`) and for IDN normalization in `getDomainName()`. It is not required for geolocation or hosted-domain lookups.

## Installation

Install the SDK through Composer:

```bash
composer require ip2location/ip2location-io-php
```

Set your API key as an environment variable instead of storing it in source code:

```bash
# Linux / macOS
export IP2LOCATION_API_KEY='YOUR_API_KEY'
```

```bat
REM Windows Command Prompt
set IP2LOCATION_API_KEY=YOUR_API_KEY
```

## Quickstart: IP geolocation lookup

Create a file named `lookup.php`:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

$apiKey = getenv('IP2LOCATION_API_KEY');
if (!$apiKey) {
    fwrite(STDERR, 'Set the IP2LOCATION_API_KEY environment variable.' . PHP_EOL);
    exit(1);
}

$config = new \IP2LocationIO\Configuration($apiKey);
$geo = new \IP2LocationIO\IPGeolocation($config);

try {
    $result = $geo->lookup('8.8.8.8');
    echo 'Country: ' . ($result->country_name ?? 'N/A') . PHP_EOL;
    echo 'City: ' . ($result->city_name ?? 'N/A') . PHP_EOL;
    echo 'ISP: ' . ($result->isp ?? 'N/A') . PHP_EOL;
} catch (\Exception $e) {
    fwrite(STDERR, 'Lookup failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
```

Run it:

```bash
php lookup.php
```

Errors in these CLI examples are written to standard error (`STDERR`) and terminate with exit code `1`; successful execution exits with code `0`. In a web application, handle errors through your application framework rather than calling `exit(1)`.

The returned fields depend on your plan and the queried IP address. See the [complete geolocation response field list](https://ip2location-io-php.readthedocs.io/en/latest/code.html).

## More usage examples

The examples below assume you have already loaded `vendor/autoload.php`, obtained the API key, and created `$config` as shown in the quickstart.

### Request translated location names

The optional second argument accepts a language code. Translations for supported location fields are available on **Plus and Security** plans.

```php
$geo = new \IP2LocationIO\IPGeolocation($config);
try {
    $result = $geo->lookup('8.8.8.8', 'es');
    print_r($result);
} catch (\Exception $e) {
    fwrite(STDERR, 'Lookup failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
```

### Inspect available proxy indicators

Security-related fields may be unavailable under some plans or for certain addresses. Check that a field is present before using it in application logic.

```php
$geo = new \IP2LocationIO\IPGeolocation($config);
try {
    $result = $geo->lookup('8.8.8.8');
    if (isset($result->proxy)) {
        echo 'VPN indicator: ' . json_encode($result->proxy->is_vpn ?? null) . PHP_EOL;
        echo 'Tor indicator: ' . json_encode($result->proxy->is_tor ?? null) . PHP_EOL;
    }
} catch (\Exception $e) {
    fwrite(STDERR, 'Lookup failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
```

### Look up domain WHOIS information

The `DomainWhois` class retrieves available domain registration information. The lookup uses the IP2WHOIS API; API access and returned fields depend on the service and account entitlement.

```php
$whois = new \IP2LocationIO\DomainWhois($config);

try {
    $result = $whois->lookup('example.com');
    echo 'Domain: ' . ($result->domain ?? 'N/A') . PHP_EOL;
    echo 'Created: ' . ($result->create_date ?? 'N/A') . PHP_EOL;
    echo 'Expires: ' . ($result->expire_date ?? 'N/A') . PHP_EOL;
    echo 'Registrar: ' . ($result->registrar->name ?? 'N/A') . PHP_EOL;
} catch (\Exception $e) {
    fwrite(STDERR, 'WHOIS lookup failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
```

WHOIS records may have unavailable or redacted contact fields. See the [DomainWhois API reference](https://ip2location-io-php.readthedocs.io/en/latest/code.html#domainwhois-class) for the documented response fields.

### Use domain and Punycode utilities

`DomainWhois` also includes helpers for internationalized domain names and URL/domain parsing:

```php
$whois = new \IP2LocationIO\DomainWhois($config);

// The Punycode conversion helpers require the PHP intl extension.
echo $whois->getPunycode('täst.de') . PHP_EOL;
echo $whois->getNormalText('xn--tst-qla.de') . PHP_EOL;

// Extract domain information.
echo $whois->getDomainName('https://www.example.com/path') . PHP_EOL;
echo $whois->getDomainExtension('example.com') . PHP_EOL;
```

### Find domains hosted on an IP address

```php
$hostedDomains = new \IP2LocationIO\HostedDomain($config);

try {
    $result = $hostedDomains->lookup('1.1.1.1', 1); // Page 1
    echo 'Total domains: ' . ($result->total_domains ?? 'N/A') . PHP_EOL;
    print_r($result->domains ?? []);
} catch (\Exception $e) {
    fwrite(STDERR, 'Hosted domain lookup failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
```

Pass `2`, `3`, etc. as the second argument to request further pages when available. The response includes pagination metadata such as `page`, `per_page` and `total_pages`.

## Common use cases

- **Website localization:** Suggest region-specific language, content or currency using approximate IP location.
- **Traffic analytics:** Add location and network context to application or server events.
- **Security review:** Use available proxy, VPN, Tor and network signals as part of broader risk assessment, rather than as a sole decision factor.
- **Domain research:** Review available WHOIS records, registrar and nameserver information, and domain registration dates.
- **Network research:** Identify associated ASNs, ISPs and hosted domains for an IP address.

## Documentation and support

- [Installation and examples](https://ip2location-io-php.readthedocs.io/en/latest/quickstart.html)
- [SDK API reference for all four classes and response fields](https://ip2location-io-php.readthedocs.io/en/latest/code.html)
- [Official repository example](https://github.com/ip2location/ip2location-io-php/blob/main/example.php)
- [IP2Location.io API plans and query limits](https://www.ip2location.io/pricing)
- [IP Geolocation API rate limits and authentication](https://www.ip2location.io/ip2location-documentation)
- [Report an SDK bug or request a feature](https://github.com/ip2location/ip2location-io-php/issues)
- [API and account support](https://www.ip2location.io/contact-us)

## License

Licensed under the **MIT License**. See [LICENSE.TXT](./LICENSE.TXT).
