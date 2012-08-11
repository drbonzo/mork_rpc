<?php 
class Mork_Server_ServerResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Server_Response
	 */
	private $successResponse = null;
	
	/**
	 * @var Mork_Server_Response
	 */
	private $errorResponse = null;

	public function setUp()
	{
		$this->successResponse = Mork_Server_Response::newSuccessResponse(array('foo' => 'bar', 'id' => 4 ));
		$this->errorResponse = Mork_Server_Response::newErrorResponse(Mork_Common_Commons::METHOD_NOT_FOUND_ERROR, 'Method "fooBar" not found', array( 'method' => 'fooBar') );
	}
	
	//
	// TYPES
	//
	
	// OK
	public function testNewSuccessResponseIsOK()
	{
		$this->assertTrue($this->successResponse->isOK());
	}
	
	public function testNewSuccessResponseIsNotError()
	{
		$this->assertFalse($this->successResponse->isError());
	}
	
	public function testNewSuccessResponseHasNoErrorCode()
	{
		$this->assertNull($this->successResponse->getErrorCode());
	}
	
	public function testNewSuccessResponseHasErrorData()
	{
		$this->assertEquals(array( 'foo' => 'bar', 'id' => 4 ), $this->successResponse->getSuccessData() );
	}
	
	
	// ERROR
	
	public function testNewErrorResponseIsNotOK()
	{
		$this->assertFalse($this->errorResponse->isOK());
	}
	
	public function testNewErrorResponseIsError()
	{
		$this->assertTrue($this->errorResponse->isError());
	}
	
	public function testNewErrorResponseHasErrorCode()
	{
		$this->assertEquals(Mork_Common_Commons::METHOD_NOT_FOUND_ERROR, $this->errorResponse->getErrorCode());
	}
	
	public function testNewErrorResponseHasErrorMessage()
	{
		$this->assertEquals('Method "fooBar" not found', $this->errorResponse->getErrorMessage());
	}
	
	public function testNewErrorResponseHasErrorData()
	{
		$this->assertEquals(array( 'method' => 'fooBar'), $this->errorResponse->getErrorData() );
	}
	
	//
	// JSON
	//
	public function testSuccessResponseReturnsJSON()
	{
		$expectedJSON = json_encode(
			array(
				'mork' => array(
					'version' => Mork_Common_Commons::VERSION_1_0,
					'status' => Mork_Server_Response::OK,
					'data' => array(
						'foo' => 'bar',
						'id' => 4
					),
					'error' => null,
				)
			)
		);
		
		$this->assertEquals($expectedJSON, $this->successResponse->getAsJSON());
	}

	public function testErrorResponseReturnsJSON()
	{
		$expectedJSON = json_encode(
			array(
				'mork' => array(
					'version' => Mork_Common_Commons::VERSION_1_0,
					'status' => Mork_Server_Response::ERROR,
					'data' => null,
					'error' => array(
						'code' => Mork_Common_Commons::METHOD_NOT_FOUND_ERROR,
						'message' => 'Method "fooBar" not found',
						'data' => array(
							'method' => 'fooBar')
					),
				)
			)
		);
	
		$this->assertEquals($expectedJSON, $this->errorResponse->getAsJSON());
	}
	
	// HEADERS
	
	public function testSuccessResponseHasHTTPStatus200Header()
	{
		$headers = $this->successResponse->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.0 200 OK', $headers));
	}
	
	public function testErrorResponseWithInternalServerErrorHasHTTPStatus500Header()
	{
		$response = Mork_Server_Response::newErrorResponse(Mork_Common_Commons::INTERNAL_SERVER_ERROR, 'fail' );
		$headers = $response->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.0 500 Internal Server Error', $headers));
	}
	
	public function testErrorResponseWithoutInternalServerErrorHasHTTPStatus400Header()
	{
		$otherErrorCodes = array(
			Mork_Common_Commons::INVALID_JSON_ERROR,
			Mork_Common_Commons::INVALID_REQUEST_ERROR,
			Mork_Common_Commons::METHOD_NOT_FOUND_ERROR,
			Mork_Common_Commons::AUTHENTICATION_ERROR,
			Mork_Common_Commons::APPLICATION_ERROR,
		);
		
		foreach ( $otherErrorCodes as $errorCode )
		{
			$response = Mork_Server_Response::newErrorResponse($errorCode, 'fail' );
			$headers = $response->getHeaders();
			$this->assertTrue(array_key_exists('HTTP/1.0 400 Bad Request', $headers));
		}
	}
	
}
