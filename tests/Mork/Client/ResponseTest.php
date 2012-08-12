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
	
}
