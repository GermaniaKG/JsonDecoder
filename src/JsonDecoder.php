<?php
namespace Germania\JsonDecoder;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class JsonDecoder
{

	/**
     * For unit tests: returns custom json_last_error() result
	 * @var callable
	 */
	protected $json_last_error_fn;


	/**
	 * @param  callable|null $json_last_error_fn Optional, for unit tests: Callable that returns json_last_error()
	 */
	public function __construct( callable $json_last_error_fn = null)
	{
		$this->json_last_error_fn = $json_last_error_fn ?: function() { return json_last_error(); };
	}


	/**
	 * Json-decodes given PSR-7 message body or string.
     * In case of decoding error a \JsonException will be thrown.
	 *
	 * @param  StreamInterface|MessageInterface|string $response_body
     * @param  bool $assoc
     * @param  int<1, max> $depth
	 * @return string Decoded JSON response
     *
     * @throws \JsonException generic or as provided by Symfony PHP 7.3 Polyfill
     *
     * @see https://github.com/symfony/polyfill-php73
	 */
	public function __invoke( $response_body, bool $assoc = false, int $depth = 512, int $options = 0  )
	{
		if ($response_body instanceOf MessageInterface):
			$response_body = $response_body->getBody()->__toString();
        elseif ($response_body instanceOf StreamInterface):
			$response_body = $response_body->__toString();
        elseif (!is_string($response_body)):
			throw new \InvalidArgumentException("String, StreamInterface, or MessageInterface expected");
		endif;

        // Make sure 'json_decode' throws \JsonException on PHP 7.3+,
        if (defined('\JSON_THROW_ON_ERROR')) {
            $options = $options | \JSON_THROW_ON_ERROR;
        }

        // This now may throw \JsonException
        $response_decoded = json_decode($response_body, $assoc, $depth, $options);

        // Fallback for PHP < 7.3
        if (\JSON_ERROR_NONE !== ($this->json_last_error_fn)()) {
            $e_msg = sprintf("JSON Problem: %s", json_last_error_msg());
    		throw new \JsonException( $e_msg );
        }

        return $response_decoded;

	}

}
