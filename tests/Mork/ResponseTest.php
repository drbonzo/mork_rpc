<?php 
class Mork_ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Response
	 */
	private $response = null;
	
	public function setUp()
	{
		$this->response = new Mork_Response();
		$this->response->setRequest( new Mork_Request('someName' ));
	}
	
	public function testResponseHasRequest()
	{
		$request = $this->response->getRequest();
		$this->assertInstanceOf('Mork_Request', $request);
	}
}