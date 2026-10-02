<?php

/*
 * Fixture for HttpTest: a TLS server presenting a self-signed certificate.
 * Prints "listening <port>" on stdout once it is accepting connections.
 *
 * Not a PHPUnit test case - phpunit.xml only collects files matching *Test.php.
 */

declare(strict_types=1);

$cert = $argv[1];
$key = $argv[2];

$ctx = stream_context_create(['ssl' => [
	'local_cert' => $cert,
	'local_pk' => $key,
	'allow_self_signed' => true,
	'verify_peer' => false,
]]);

$server = @stream_socket_server('tls://127.0.0.1:0', $errno, $errstr,
	STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $ctx);

if (!$server) {
	fwrite(STDERR, "server failed: $errstr ($errno)\n");
	exit(1);
}

$name = stream_socket_get_name($server, false);
$port = substr($name, strrpos($name, ':') + 1);

fwrite(STDOUT, "listening $port\n");
fflush(STDOUT);

$body = '{"ip":"1.2.3.4","country_code":"XX","forged":true}';

for ($i = 0; $i < 5; $i++) {
	$conn = @stream_socket_accept($server, 20);

	if (!$conn) {
		continue;
	}

	@fread($conn, 8192);
	fwrite($conn, "HTTP/1.1 200 OK\r\n"
		. "Content-Type: application/json\r\n"
		. 'Content-Length: ' . strlen($body) . "\r\n"
		. "Connection: close\r\n\r\n" . $body);
	fclose($conn);
}
