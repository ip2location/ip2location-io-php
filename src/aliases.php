<?php

/*
 * Legacy underscore-style class names.
 *
 * These used to be declared with class_alias() at the bottom of each class file,
 * which never worked under PSR-4 autoloading: asking the autoloader for
 * "IP2LocationIO_Http" does not match the "IP2LocationIO\" prefix, so the file
 * was never loaded and the alias was never registered. Declaring them here and
 * registering this file via the composer "files" autoloader makes them work
 * regardless of which class was referenced first.
 */

class_alias('IP2LocationIO\Configuration', 'IP2LocationIO_Configuration');
class_alias('IP2LocationIO\Http', 'IP2LocationIO_Http');
class_alias('IP2LocationIO\HttpException', 'IP2LocationIO_HttpException');
class_alias('IP2LocationIO\IPGeolocation', 'IP2LocationIO_IPGeolocation');
class_alias('IP2LocationIO\DomainWhois', 'IP2LocationIO_DomainWhois');
class_alias('IP2LocationIO\HostedDomain', 'IP2LocationIO_HostedDomain');
