IP2Location.io PHP SDK
========================
This PHP module enables user to query for an enriched data set, such as country, region, city, latitude & longitude, ZIP code, time zone, ASN, ISP, domain, net speed, IDD code, area code, weather station data, MNC, MCC, mobile brand, elevation, usage type, address type, advertisement category and proxy data with an IP address. It supports both IPv4 and IPv6 address lookup.

In addition, this module provides WHOIS lookup api that helps users to obtain domain information, WHOIS record, by using a domain name. The WHOIS API returns a comprehensive WHOIS data such as creation date, updated date, expiration date, domain age, the contact information of the registrant, mailing address, phone number, email address, nameservers the domain is using and much more.

This module requires API key to function. You may sign up for a free API key at https://www.ip2location.io/pricing.


Usage Example
============
### Lookup IP Address Geolocation Data
```php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\IPGeolocation($config);

// Lookup ip address geolocation data
$ip2locationio->lookup('8.8.8.8', 'en'); // The language parameter is only available for Plus and Security plan only.
```

### Lookup Domain Information
```php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Lookup domain information
$ip2locationio->lookup('example.com');
```

### Convert Normal Text to Punycode
```php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Convert normal text to punycode
$ip2locationio->getPunycode('täst.de');
```

### Convert Punycode to Normal Text
```php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Convert punycode to normal text
$ip2locationio->getNormalText('xn--tst-qla.de');
```

### Get Domain Name
```php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Get domain name from URL
$ip2locationio->getDomainName('https://www.example.com/exe');
```

### Get Domain Extension
```php
// Configures IP2Location.io API key
$config = new \IP2LocationIO\Configuration('YOUR_API_KEY');
$ip2locationio = new IP2LocationIO\DomainWhois($config);

// Get domain extension (gTLD or ccTLD) from URL or domain name
$ip2locationio->getDomainExtension('example.com');
```


Response Parameter
============
### IP Geolocation Lookup function
| Parameter | Type | Description |
|---|---|---|
|ip|string|IP address.|
|country_code|string|Two-character country code based on ISO 3166.|
|country_name|string|Country name based on ISO 3166.|
|region_name|string|Region or state name.|
|city_name|string|City name.|
|latitude|double|City latitude. Defaults to capital city latitude if city is unknown.|
|longitude|double|City longitude. Defaults to capital city longitude if city is unknown.|
|zip_code|string|ZIP/Postal code.|
|time_zone|string|UTC time zone (with DST supported).|
|asn|string|Autonomous system number (ASN).|
|as|string|Autonomous system (AS) name.|
|isp|string|Internet Service Provider or company's name.|
|domain|string|Internet domain name associated with IP address range.|
|net_speed|string|Internet connection type. DIAL = dial-up, DSL = broadband/cable/fiber/mobile, COMP = company/T1|
|idd_code|string|The IDD prefix to call the city from another country.|
|area_code|string|A varying length number assigned to geographic areas for calls between cities.|
|weather_station_code|string|The special code to identify the nearest weather observation station.|
|weather_station_name|string|The name of the nearest weather observation station.|
|mcc|string|Mobile Country Codes (MCC) as defined in ITU E.212 for use in identifying mobile stations in wireless telephone networks, particularly GSM and UMTS networks.|
|mnc|string|Mobile Network Code (MNC) is used in combination with a Mobile Country Code (MCC) to uniquely identify a mobile phone operator or carrier.|
|mobile_brand|string|Commercial brand associated with the mobile carrier.|
|elevation|integer|Average height of city above sea level in meters (m).|
|usage_type|string|Usage type classification of ISP or company.|
|address_type|string|IP address types as defined in Internet Protocol version 4 (IPv4) and Internet Protocol version 6 (IPv6).|
|continent.name|string|Continent name.|
|continent.code|string|Two-character continent code.|
|continent.hemisphere|array|The hemisphere of where the country located. The data in array format with first item indicates (north/south) hemisphere and second item indicates (east/west) hemisphere information.|
|continent.translation|object|Translation data based on the given lang code.|
|country.name|string|Country name based on ISO 3166.|
|country.alpha3_code|string|Three-character country code based on ISO 3166.|
|country.numeric_code|string|Three-character country numeric code based on ISO 3166.|
|country.demonym|string|Native of the country.|
|country.flag|string|URL of the country flag image.|
|country.capital|string|Capital of the country.|
|country.total_area|integer|Total area in km2.|
|country.population|integer|Population of the country.|
|country.currency|object|Currency of the country.|
|country.language|object|Language of the country.|
|country.tld|string|Country-Code Top-Level Domain.|
|country.translation|object|Translation data based on the given lang code.|
|region.name|string|Region or state name.|
|region.code|string|ISO3166-2 code.|
|region.translation|object|Translation data based on the given lang code.|
|city.name|string| City name.|
|city.translation|object|Translation data based on the given lang code.|
|time_zone_info.olson|string|Time zone in Olson format.|
|time_zone_info.current_time|string|Current time in ISO 8601 format.|
|time_zone_info.gmt_offset|integer|GMT offset value in seconds.|
|time_zone_info.is_dst|boolean|Indicate if the time zone value is in DST.|
|time_zone_info.sunrise|string|Time of sunrise. (hh:mm format in local time, i.e, 07:47)|
|time_zone_info.sunset|string|Time of sunset. (hh:mm format in local time, i.e 19:50)|
|geotargeting.metro|string|Metro code based on zip/postal code.|
|ads_category|string|The domain category code based on IAB Tech Lab Content Taxonomy.|
|ads_category_name|string|The domain category based on IAB Tech Lab Content Taxonomy. These categories are comprised of Tier-1 and Tier-2 (if available) level categories widely used in services like advertising, Internet security and filtering appliances.|
|is_proxy|boolean|Whether is a proxy or not.|
|proxy.last_seen|integer|Proxy last seen in days.|
|proxy.proxy_type|string|Type of proxy.|
|proxy.threat|string|Security threat reported.|
|proxy.provider|string|Name of VPN provider if available.|

```json
{
    "ip":"8.8.8.8",
    "country_code":"US",
    "country_name":"United States of America",
    "region_name":"California",
    "city_name":"Mountain View",
    "latitude":37.405992,
    "longitude":-122.078515,
    "zip_code":"94043",
    "time_zone":"-07:00",
    "asn":"15169",
    "as":"Google LLC",
    "isp":"Google LLC",
    "domain":"google.com",
    "net_speed":"T1",
    "idd_code":"1",
    "area_code":"650",
    "weather_station_code":"USCA0746",
    "weather_station_name":"Mountain View",
    "mcc":"-",
    "mnc":"-",
    "mobile_brand":"-",
    "elevation":32,
    "usage_type":"DCH",
    "address_type":"Anycast",
    "continent":{
        "name":"North America",
        "code":"NA",
        "hemisphere":["north","west"],
        "translation":{
            "lang":"ko",
            "value":"북아메리카"
        }
    },
    "country":{
        "name":"United States of America",
        "alpha3_code":"USA",
        "numeric_code":840,
        "demonym":"Americans",
        "flag":"https://cdn.ip2location.io/assets/img/flags/us.png",
        "capital":"Washington, D.C.",
        "total_area":9826675,
        "population":331002651,
        "currency":{
            "code":"USD",
            "name":"United States Dollar",
            "symbol":"$"
        },
        "language":{
            "code":"EN",
            "name":"English"
        },
        "tld":"us",
        "translation":{
            "lang":"ko",
            "value":"미국"
        }
    },
    "region":{
        "name":"California",
        "code":"US-CA",
        "translation":{
            "lang":"ko",
            "value":"캘리포니아주"
        }
    },
    "city":{
        "name":"Mountain View",
        "translation":{
            "lang":null,
            "value":null
        }
    },
    "time_zone_info":{
        "olson":"America/Los_Angeles",
        "current_time":"2022-04-18T23:41:57-07:00",
        "gmt_offset":-25200,
        "is_dst":true,
        "sunrise":"06:27",
        "sunset":"19:47"
    },
    "geotargeting":{
        "metro":"807"
    },
    "ads_category":"IAB19",
    "ads_category_name":"Technology & Computing",
    "is_proxy":false,
    "proxy":{
        "last_seen":18,
        "proxy_type":"DCH",
        "threat":"-",
        "provider":"-"
    }
}
```

### Domain WHOIS Lookup function
| Parameter | Type | Description |
|---|---|---|
|domain|string|Domain name.|
|domain_id|string|Domain name ID.|
|status|string|Domain name status.|
|create_date|string|Domain name creation date.|
|update_date|string|Domain name updated date.|
|expire_date|string|Domain name expiration date.|
|domain_age|integer|Domain name age in day(s).|
|whois_server|string|WHOIS server name.|
|registrar.iana_id|string|Registrar IANA ID.|
|registrar.name|string|Registrar name.|
|registrar.url|string|Registrar URL.|
|registrant.name|string|Registrant name.|
|registrant.organization|string|Registrant organization.|
|registrant.street_address|string|Registrant street address.|
|registrant.city|string|Registrant city.|
|registrant.region|string|Registrant region.|
|registrant.zip_code|string|Registrant ZIP Code.|
|registrant.country|string|Registrant country.|
|registrant.phone|string|Registrant phone number.|
|registrant.fax|string|Registrant fax number.|
|registrant.email|string|Registrant email address.|
|admin.name|string|Admin name.|
|admin.organization|string|Admin organization.|
|admin.street_address|string|Admin street address.|
|admin.city|string|Admin city.|
|admin.region|string|Admin region.|
|admin.zip_code|string|Admin ZIP Code.|
|admin.country|string|Admin country.|
|admin.phone|string|Admin phone number.|
|admin.fax|string|Admin fax number.|
|admin.email|string|Admin email address.|
|tech.name|string|Tech name.|
|tech.organization|string|Tech organization.|
|tech.street_address|string|Tech street address.|
|tech.city|string|Tech city.|
|tech.region|string|Tech region.|
|tech.zip_code|string|Tech ZIP Code.|
|tech.country|string|Tech country.|
|tech.phone|string|Tech phone number.|
|tech.fax|string|Tech fax number.|
|tech.email|string|Tech email address.|
|billing.name|string|Billing name.|
|billing.organization|string|Billing organization.|
|billing.street_address|string|Billing street address.|
|billing.city|string|Billing city.|
|billing.region|string|Billing region.|
|billing.zip_code|string|Billing ZIP Code.|
|billing.country|string|Billing country.|
|billing.phone|string|Billing phone number.|
|billing.fax|string|Billing fax number.|
|billing.email|string|Billing email address.|
|name_servers|array|Name servers|

```json
{
    "domain": "locaproxy.com",
    "domain_id": "1710914405_DOMAIN_COM-VRSN",
    "status": "clientTransferProhibited https://icann.org/epp#clientTransferProhibited",
    "create_date": "2012-04-03T02:34:32Z",
    "update_date": "2021-12-03T02:54:57Z",
    "expire_date": "2024-04-03T02:34:32Z",
    "domain_age": 3863,
    "whois_server": "whois.godaddy.com",
    "registrar": {
        "iana_id": "146",
        "name": "GoDaddy.com, LLC",
        "url": "https://www.godaddy.com"
    },
    "registrant": {
        "name": "Registration Private",
        "organization": "Domains By Proxy, LLC",
        "street_address": "DomainsByProxy.com",
        "city": "Tempe",
        "region": "Arizona",
        "zip_code": "85284",
        "country": "US",
        "phone": "+1.4806242599",
        "fax": "+1.4806242598",
        "email": "Select Contact Domain Holder link at https://www.godaddy.com/whois/results.aspx?domain=LOCAPROXY.COM"
    },
    "admin": {
        "name": "Registration Private",
        "organization": "Domains By Proxy, LLC",
        "street_address": "DomainsByProxy.com",
        "city": "Tempe",
        "region": "Arizona",
        "zip_code": "85284",
        "country": "US",
        "phone": "+1.4806242599",
        "fax": "+1.4806242598",
        "email": "Select Contact Domain Holder link at https://www.godaddy.com/whois/results.aspx?domain=LOCAPROXY.COM"
    },
    "tech": {
        "name": "Registration Private",
        "organization": "Domains By Proxy, LLC",
        "street_address": "DomainsByProxy.com",
        "city": "Tempe",
        "region": "Arizona",
        "zip_code": "85284",
        "country": "US",
        "phone": "+1.4806242599",
        "fax": "+1.4806242598",
        "email": "Select Contact Domain Holder link at https://www.godaddy.com/whois/results.aspx?domain=LOCAPROXY.COM"
    },
    "billing": {
        "name": "",
        "organization": "",
        "street_address": "",
        "city": "",
        "region": "",
        "zip_code": "",
        "country": "",
        "phone": "",
        "fax": "",
        "email": ""
    },
    "nameservers": "vera.ns.cloudflare.com, walt.ns.cloudflare.com"
}
```


LICENCE
=====================
See the LICENSE file.
