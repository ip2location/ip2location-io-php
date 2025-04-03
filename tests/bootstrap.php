<?php

declare(strict_types=1);

$GLOBALS['testApiKey'] = '562CD62D531835B6E79F9B4836D03674';

if (!$loader = @include './vendor/autoload.php') {
	exit('Project dependencies missing');
}

$loader->add('IP2LocationIO\Test', __DIR__);
