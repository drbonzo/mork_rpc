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
	
	/**
	 * @var Mork_Server_Response
	 */
	private $applicationErrorResponse = null;

	public function setUp()
	{
		$this->successResponse = Mork_Server_Response::newSuccessResponse(array('foo' => 'bar', 'id' => 4 ));
		$this->errorResponse = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR, 'Method "fooBar" not found', array( 'method' => 'fooBar') );
		$this->applicationErrorResponse = Mork_Server_Response::newApplicationErrorResponse('FOO_FAILED', 'Foo has failed because of param "bar"', array( 'bar' => '444'));
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
	
	public function testNewSuccessResponseHasStatusOK()
	{
		$this->assertEquals(Mork_Server_Response::OK, $this->successResponse->getStatus());
	}
	
	public function testNewSuccessResponseHasNoErrorCode()
	{
		$this->assertNull($this->successResponse->getErrorCode());
	}
	
	public function testNewSuccessResponseHasNoErrorMessage()
	{
		$this->assertNull($this->successResponse->getErrorMessage());
	}
	
	public function testNewSuccessResponseHasNoErrorData()
	{
		$this->assertNull($this->successResponse->getErrorData() );
	}
	
	public function testNewSuccessResponseReturnsJSON()
	{
		$expectedJSON = json_encode(
			array(
				'mork' => array(
					'version' => Mork_Common_Commons::VERSION_1_0,
					'status' => Mork_Common_BaseResponse::OK,
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
	
	
	// ERROR
	public function testNewErrorResponseIsNotOK()
	{
		$this->assertFalse($this->errorResponse->isOK());
	}
	
	public function testNewErrorResponseIsError()
	{
		$this->assertTrue($this->errorResponse->isError());
	}
	
	public function testNewErrorResponseDoesNotHaveStatusOK()
	{
		$this->assertNotEquals(Mork_Server_Response::OK, $this->errorResponse->getStatus());
		$this->assertEquals(Mork_Server_Response::METHOD_NOT_FOUND_ERROR, $this->errorResponse->getStatus());
	}
	
	public function testNewErrorResponseHasErrorCode()
	{
		$this->assertEquals(Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR, $this->errorResponse->getErrorCode());
	}
	
	public function testNewErrorResponseHasErrorMessage()
	{
		$this->assertEquals('Method "fooBar" not found', $this->errorResponse->getErrorMessage());
	}
	
	public function testNewErrorResponseHasErrorData()
	{
		$this->assertEquals(array( 'method' => 'fooBar'), $this->errorResponse->getErrorData() );
	}
	
	public function testNewErrorResponseReturnsJSON()
	{
		$expectedJSON = json_encode(
			array(
				'mork' => array(
					'version' => Mork_Common_Commons::VERSION_1_0,
					'status' => Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR,
					'data' => null,
					'error' => array(
						'code' => Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR,
						'message' => 'Method "fooBar" not found',
						'data' => array(
							'method' => 'fooBar')
					),
				)
			)
		);
	
		$this->assertEquals($expectedJSON, $this->errorResponse->getAsJSON());
	}
	
	
	// APPLICATION ERROR
	public function testNewApplicationErrorResponseIsNotOK()
	{
		$this->assertFalse($this->applicationErrorResponse->isOK());
	}
	
	public function testNewApplicationErrorResponseIsError()
	{
		$this->assertTrue($this->applicationErrorResponse->isError());
	}
	
	public function testNewApplicationErrorResponseDoesNotHaveStatusOK()
	{
		$this->assertNotEquals(Mork_Server_Response::OK, $this->applicationErrorResponse->getStatus());
		$this->assertEquals(Mork_Server_Response::APPLICATION_ERROR, $this->applicationErrorResponse->getStatus());
	}
	
	public function testNewApplicationErrorResponseHasCustomErrorCode()
	{
		$this->assertEquals('FOO_FAILED', $this->applicationErrorResponse->getErrorCode());
	}
	
	public function testNewApplicationErrorResponseHasErrorMessage()
	{
		$this->assertEquals('Foo has failed because of param "bar"', $this->applicationErrorResponse->getErrorMessage());
	}
	
	public function testNewApplicationErrorResponseHasErrorData()
	{
		$this->assertEquals(array( 'bar' => '444'), $this->applicationErrorResponse->getErrorData() );
	}
	
	public function testNewApplicationErrorResponseReturnsJSON()
	{
		$expectedJSON = json_encode(
			array(
				'mork' => array(
					'version' => Mork_Common_Commons::VERSION_1_0,
					'status' => Mork_Common_BaseResponse::APPLICATION_ERROR,
					'data' => null,
					'error' => array(
						'code' => 'FOO_FAILED',
						'message' => 'Foo has failed because of param "bar"',
						'data' => array(
							'bar' => '444')
					),
				)
			)
		);
	
		$this->assertEquals($expectedJSON, $this->applicationErrorResponse->getAsJSON());
	}	
	
	
	// HEADERS
	
	public function testSuccessResponseHasHTTPStatus200Header()
	{
		$headers = $this->successResponse->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 200 OK', $headers));
	}
	
	public function testErrorResponseWithInternalServerErrorHasHTTPStatus500Header()
	{
		$response = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR, 'fail' );
		$headers = $response->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 500 Internal Server Error', $headers));
	}
	
	public function testErrorResponseWithoutInternalServerErrorHasHTTPStatus400Header()
	{
		$headers = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INVALID_JSON_ERROR, 'fail' )->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 400 Bad Request', $headers));
		
		$headers = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INVALID_REQUEST_ERROR, 'fail' )->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 400 Bad Request', $headers));
		
		$headers = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR, 'fail' )->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 400 Bad Request', $headers));
		
		$headers = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::AUTHENTICATION_ERROR, 'fail' )->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 400 Bad Request', $headers));
		
		$headers = Mork_Server_Response::newApplicationErrorResponse(Mork_Common_BaseResponse::APPLICATION_ERROR, 'fail' )->getHeaders();
		$this->assertTrue(array_key_exists('HTTP/1.1 400 Bad Request', $headers));
	}
	
}
