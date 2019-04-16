<?php
namespace tests;

use Germania\JsonDecoder\JsonDecoder;
use GuzzleHttp\Psr7\Response;

class JsonDecoderTest extends \PHPUnit\Framework\TestCase
{

	public function testInvalidArgumentExceptionOnInvokation( )
	{
		$sut = new JsonDecoder;
		$this->expectException( \InvalidArgumentException::class );
		$sut( 1 );

	}	


	/**
	 * @dataProvider provideInvalidResponseStuff
	 */
	public function testExceptionOnInvalidResponseStuff( $invalid_response_body, $json_last_error_result )
	{
		// Mock response
		$response = new Response(200, array(), $invalid_response_body);
		$json_last_error_fn = function() use ($json_last_error_result) { return $json_last_error_result; };

		$sut = new JsonDecoder( $json_last_error_fn );

		$this->expectException( \JsonException::class );
		$sut( $response );

	}	

	public function provideInvalidResponseStuff()
	{
		return array(
			[ "{'foo':'bar'}", -10 ],
			[ "Foo bar", \JSON_ERROR_UTF8 ],
			[ "Foo bar", \JSON_ERROR_DEPTH ],
			[ "Foo bar", \JSON_ERROR_STATE_MISMATCH ],
			[ "Foo bar", \JSON_ERROR_CTRL_CHAR ],
			[ "Foo bar", \JSON_ERROR_SYNTAX ],
			[ "Foo bar", \JSON_ERROR_RECURSION ],
			[ "Foo bar", \JSON_ERROR_INF_OR_NAN ],
			[ "Foo bar", \JSON_ERROR_UNSUPPORTED_TYPE ],
			[ "Foo bar", \JSON_ERROR_INVALID_PROPERTY_NAME ],
			[ "Foo bar", \JSON_ERROR_UTF16 ],
			[ false, \JSON_ERROR_SYNTAX ]
		);
	}	


	/**
	 * @dataProvider provideValidResponseStuff
	 */
	public function testValidResponseStuff( $data )
	{
		// Mock response
		$response = new Response(200, array(), json_encode( $data ));
		$sut = new JsonDecoder;

		$decoded = $sut( $response, "assoc" );
		$this->assertEquals( $data, $decoded);

		$decoded = $sut( $response->getBody(), "assoc" );
		$this->assertEquals( $data, $decoded);

		$decoded = $sut( $response->getBody()->__toString(), "assoc" );
		$this->assertEquals( $data, $decoded);

	}	

	public function provideValidResponseStuff()
	{
		return array(
			[ array("foo" => "bar") ],
			[ null ]
		);
	}	
}