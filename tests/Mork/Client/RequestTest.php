<?php
class Mork_Client_RequestTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Client_Request
	 */
	private $request;

	public function setUp()
	{
		$this->request = new Mork_Client_Request('actionName');
		$this->request->setParam('foo', 'bar');
		$this->request->setParam('lorem', 'ipsum');
		$this->request->setParam('names', array( 'Bob', 'John', 'Steve'));
	}
	
	public function testRequestCanGiveItsMethodName()
	{
		$this->assertEquals('actionName', $this->request->getMethodName());
	}

	public function testRequestCanReturnItsParams()
	{
		$params = $this->request->getParams();
		$this->assertCount(3, $params);
	}
	
	public function testRequestCanReturnParamByName()
	{
		$this->assertEquals('bar', $this->request->getParam('foo'));
	}
	
	public function testAddingSameParamSecondTimeOverwritesOldValue()
	{
		$this->assertEquals('bar', $this->request->getParam('foo'));
		$this->request->setParam('foo', 'not-bar');
		$this->assertEquals('not-bar', $this->request->getParam('foo'));
	}
	
	public function testRequestingNonexistingParamReturnsNull()
	{
		$this->assertNull($this->request->getParam('aaaaaa'));
	}
	
	public function testByDefaultRequestHasNotResponse()
	{
		$this->assertNull($this->request->getResponse());
	}
	
	public function testRequestHasResponseWhenResponseHasBeenSet()
	{
		$this->request->setResponse( Mork_Client_Response::newSuccessResponse('foo'));
		$this->assertInstanceOf('Mork_Client_Response', $this->request->getResponse());
	}
	
	public function testRequestCanFormatItselfAsJSON()
	{
		/*
		{
			mork :
			{
				version : 1.0,
				method : addPost,
				params :
				{
					foo : 'bar',
					lorem : 'ipsum',
					'names' : [ 'Bob', 'John', 'Steve' ]
				}
			}
		}
		*/
		$requestAsArray = array(
			'mork' => array(
				'version' => Mork_Common_Commons::VERSION_1_0,
				'method' => 'actionName',
				'params' => array(
					'foo' => 'bar',
					'lorem' => 'ipsum',
					'names' => array( 'Bob', 'John', 'Steve')
				)
			)
		);
		
		$requestAsJSON = json_encode($requestAsArray);
		$this->assertEquals($requestAsJSON, $this->request->getAsJSON());
	}
	
}
