<?php
namespace Germania\JsonDecoder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class JsonDecoder
{

	/**
	 * @var callable
	 */
	protected $json_last_error_fn;


	/**
	 * @param  callable|null $json_last_error_fn Callable that returns json_last_error()
	 */
	public function __construct( callable $json_last_error_fn = null)
	{
		$this->json_last_error_fn = $json_last_error_fn ?: function() { return json_last_error(); };
	}


	/**
	 * Does not use \JSON_THROW_ON_ERROR yet until PHP 7.3 runs on our server
     * but throws \JsonException as provided by Symfony PHP 7.3 Polyfill 
	 * 
	 * @param  string|ResponseInterface $response_body
	 * @return string Decoded JSON response
     *
     * @throws JsonException
     * 
     * @see https://github.com/symfony/polyfill-php73
	 */
	public function __invoke( $response_body, bool $assoc = false, int $depth = 512, int $options = 0  )
	{
		if ($response_body instanceOf ResponseInterface):
			$response_body = $response_body->getBody();
		elseif ($response_body instanceOf StreamInterface):
			$response_body = $response_body->__toString();
		elseif (!is_string($response_body)):
			throw new \InvalidArgumentException("String, StreamInterface, or ResponseInterface expected");
		endif;


        $response_decoded = json_decode($response_body, $assoc, $depth, $options);            

        // Work around missing JSON_THROW_ON_ERROR constant on older PHP
        if ($response_decoded === null) {
        	$error_str = $this->evalJsonError();
        	if ($error_str !== "No error"):
        		$e_msg = sprintf("JSON Problem: %s", $error_str);
        		throw new \JsonException( $e_msg );            
        	endif;
        }
        return $response_decoded;

	}



    protected function evalJsonError()
    {

        switch(($this->json_last_error_fn)()) {
            case \JSON_ERROR_NONE: #0
                return 'No error';
            break;
            case \JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            break;
            case \JSON_ERROR_STATE_MISMATCH:
                return 'State mismatch (invalid or malformed JSON)';
            break;
            case \JSON_ERROR_CTRL_CHAR:
                return 'Control character error, possibly incorrectly encoded';
            break;
            case \JSON_ERROR_SYNTAX:
                return 'Syntax error';
            break;
            case \JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            case \JSON_ERROR_RECURSION:
                return 'One or more recursive references in the value to be encoded';
            break;
            case \JSON_ERROR_INF_OR_NAN:
                return 'One or more NAN or INF values in the value to be encoded';
            break;
            case \JSON_ERROR_UNSUPPORTED_TYPE:
                return 'A value of a type that cannot be encoded was given';
            break;
            case \JSON_ERROR_INVALID_PROPERTY_NAME:
                return 'A property name that cannot be encoded was given';
            break;
            case \JSON_ERROR_UTF16:
                return 'Malformed UTF-16 characters, possibly incorrectly encoded';
            break;
            default:
                return 'Unknown error';
            break;
        }

    }	
}