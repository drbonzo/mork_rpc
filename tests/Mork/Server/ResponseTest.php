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
					'error' => null,
					'result' => array(
						'foo' => 'bar',
						'id' => 4
					)
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
					'error' => array(
						'code' => Mork_Common_Commons::METHOD_NOT_FOUND_ERROR,
						'message' => 'Method "fooBar" not found',
						'data' => array(
							'method' => 'fooBar')
					),
					'result' => null
				)
			)
		);
	
		$this->assertEquals($expectedJSON, $this->errorResponse->getAsJSON());
	}
	
}
