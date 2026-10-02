<?php

namespace IP2LocationIO;

/**
 * Thrown when a request to the IP2Location.io API fails at the transport level
 * or returns an error status.
 *
 * The exception code carries the underlying cURL error number for transport
 * failures, or the HTTP status code for error responses, so callers can tell a
 * DNS/TLS problem apart from a rate limit or a server error.
 */
class HttpException extends \Exception
{
}
