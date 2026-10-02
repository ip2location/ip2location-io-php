<?php

namespace IP2LocationIO;

/**
 * Shared decoding of IP2Location.io API responses.
 *
 * The documented error shape is:
 *   {"error":{"error_code":10001,"error_message":"Invalid IP address."}}
 *
 * Note that the API returns these envelopes with a 4xx status, so the body is
 * inspected before the status is treated as a transport failure. Otherwise the
 * useful "Invalid IP address." message would be replaced by a bare
 * "Unexpected HTTP status 400".
 */
trait ApiResponseTrait
{
	/**
	 * Decode a raw response body into an object.
	 *
	 * @param string $response raw response body
	 * @param string $module   module name used in the fallback error message
	 *
	 * @return object
	 *
	 * @throws HttpException when the status indicates a failure with no usable body
	 */
	protected function parseResponse($response, $module)
	{
		$json = json_decode($response);

		if (isset($json->error)) {
			throw self::apiException($json->error);
		}

		$status = $this->http->getLastStatusCode();

		if ($status >= 400) {
			throw new HttpException('Unexpected HTTP status ' . $status . ' from server.', $status);
		}

		if ($json === null) {
			throw new \Exception(
				$module . ' lookup error: invalid JSON response (' . json_last_error_msg() . ').',
				10005
			);
		}

		return $json;
	}

	/**
	 * @param mixed $error the decoded "error" member of the API response
	 *
	 * @return \Exception
	 */
	protected static function apiException($error)
	{
		if (is_object($error)) {
			$message = isset($error->error_message) ? (string) $error->error_message : 'Unknown API error.';
			$code = isset($error->error_code) ? (int) $error->error_code : 0;

			return new \Exception($message, $code);
		}

		// Defensive: an intermediary may return {"error":"..."} instead of the
		// documented object, which used to emit "Attempt to read property on
		// string" and build an exception with a null message and code
		// (deprecated as of PHP 8.1).
		if (is_string($error) && $error !== '') {
			return new \Exception($error);
		}

		return new \Exception('Unknown API error.');
	}
}
