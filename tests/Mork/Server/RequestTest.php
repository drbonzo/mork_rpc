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
	
	// TODO returning results
	// TODO returning errors
}
