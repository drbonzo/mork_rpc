<?php 
class Mork_Client_ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Client_Response
	 */
	private $successResponse = null;
	
	/**
	 * @var Mork_Client_Response
	 */
	private $errorResponse = null;
	
	public function setUp()
	{
		$this->successResponse = Mork_Client_Response::newSuccessResponse(array('foo' => 'bar'));
		$this->errorResponse = Mork_Client_Response::newErrorResponse(Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR, 'Server failed', array('php' => 'ruby') );
	}
	
	// SUCCESS
	
	public function testSuccessResponseIsOK()
	{
		$this->assertTrue($this->successResponse->isOK() );
		$this->assertFalse($this->successResponse->isError());
		
		$this->assertFalse($this->successResponse->isServerCausedError());
		$this->assertFalse($this->successResponse->isClientCausedError());
	}
	
	public function testSuccessResponseHasData()
	{
		$this->assertEquals(array('foo'=>'bar'), $this->successResponse->getData());
		$this->assertNull($this->successResponse->getErrorData());
		$this->assertNull($this->successResponse->getErrorCode());
		$this->assertNull($this->successResponse->getErrorMessage());
	}
	
	// ERROR
	
	public function testErrorResponseIsError()
	{
		$this->assertFalse($this->errorResponse->isOK());
		$this->assertTrue($this->errorResponse->isError());
	}

	public function testErrorResponseHasErrorCodeMessageAndData()
	{
		$this->assertEquals(array('php'=>'ruby'), $this->errorResponse->getErrorData());
		$this->assertEquals(Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR, $this->errorResponse->getErrorCode());
		$this->assertEquals('Server failed', $this->errorResponse->getErrorMessage());
		
		$this->assertNull($this->errorResponse->getData());
	}
	
	//
	
	public function testINTERNAL_SERVER_ERRORErrorCausesServerCausedError()
	{
		$response = Mork_Client_Response::newErrorResponse(Mork_Client_Response::INTERNAL_SERVER_ERROR, 'Foo', null);
		$this->assertTrue($response->isServerCausedError());
		$this->assertFalse($response->isClientCausedError());
	}

	public function testINVALID_JSON_ERRORErrorCausesClientCausedError()
	{
		$response = Mork_Client_Response::newErrorResponse(Mork_Client_Response::INVALID_JSON_ERROR, 'Foo', null);
		$this->assertFalse($response->isServerCausedError());
		$this->assertTrue($response->isClientCausedError());
	}
	
	public function testINVALID_REQUEST_ERRORErrorCausesClientCausedError()
	{
		$response = Mork_Client_Response::newErrorResponse(Mork_Client_Response::INVALID_REQUEST_ERROR, 'Foo', null);
		$this->assertFalse($response->isServerCausedError());
		$this->assertTrue($response->isClientCausedError());
	}
	
	public function testMETHOD_NOT_FOUND_ERRORErrorCausesClientCausedError()
	{
		$response = Mork_Client_Response::newErrorResponse(Mork_Client_Response::METHOD_NOT_FOUND_ERROR, 'Foo', null);
		$this->assertFalse($response->isServerCausedError());
		$this->assertTrue($response->isClientCausedError());
	}
	
	public function testAUTHENTICATION_ERRORErrorCausesClientCausedError()
	{
		$response = Mork_Client_Response::newErrorResponse(Mork_Client_Response::AUTHENTICATION_ERROR, 'Foo', null);
		$this->assertFalse($response->isServerCausedError());
		$this->assertTrue($response->isClientCausedError());
	}

	public function testAPPLICATION_ERRORErrorCausesClientCausedError()
	{
		$response = Mork_Client_Response::newErrorResponse(Mork_Client_Response::APPLICATION_ERROR, 'Foo', null);
		$this->assertFalse($response->isServerCausedError());
		$this->assertTrue($response->isClientCausedError());
	}
	
}
