<?php

declare(strict_types=1);

/*
 * The API key is read from the environment so that no credential is ever
 * committed to this repository.
 *
 *   Linux/macOS: export IP2LOCATION_API_KEY=your_api_key_here
 *   Windows cmd: set IP2LOCATION_API_KEY=your_api_key_here
 *
 * Tests that call the live API are skipped when it is unset. You can sign up for
 * a free API key at https://www.ip2location.io/pricing
 */
$GLOBALS['testApiKey'] = (string) getenv('IP2LOCATION_API_KEY');

if (!$loader = @include __DIR__ . '/../vendor/autoload.php') {
	exit('Project dependencies missing');
}
