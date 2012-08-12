<?php 
class Mork_Server_RequestTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Server_Request
	 */
	private $request;
	
	public function setUp()
	{
		$this->request = new Mork_Server_Request('addPost', array('title' => 'foobar'));
	}
	
	public function testRequestCanReturnItsMethodName()
	{
		$this->assertEquals('addPost', $this->request->getMethodName());
	}
	
	public function testRequestCanReturnItsParams()
	{
		$this->assertEquals(array('title' => 'foobar'), $this->request->getParams());
	}
	
	public function testRequestCanReturnParamsByName()
	{
		$this->assertEquals('foobar', $this->request->getParam('title'));
	}
	
	public function testRequestReturnsNullForUnknownParams()
	{
		$this->assertNull($this->request->getParam('unknown'));
	}
	
	public function testNewRequestHasNoResponseYet()
	{
		$this->assertNull($this->request->getResponse());
	}
	
	public function testRequestWithSuccessResponseSetHasResponse()
	{
		$this->request->returnResponse(array('foo' => 'bar'));
		
		$response = $this->request->getResponse();
		$this->assertInstanceOf('Mork_Server_Response', $response );
		$this->assertTrue($response->isOK() );
		$this->assertFalse($response->isError() );
	}
	
	public function testRequestWithErrorResponseSetHasResponse()
	{
		$this->request->returnErrorResponse(Mork_Common_BaseResponse::INVALID_JSON_ERROR, 'JSON is invalid', array('foo' => 'bar'));
		
		$response = $this->request->getResponse();
		$this->assertInstanceOf('Mork_Server_Response', $response );
		$this->assertFalse($response->isOK() );
		$this->assertTrue($response->isError() );
	}
}
