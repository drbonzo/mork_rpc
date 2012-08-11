<?php 
class Mork_Client_ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Client_Response
	 */
	private $response = null;
	
	public function setUp()
	{
		$this->response = new Mork_Client_Response(array('foo' => 'bar', 'lotr' => 'epic'));
	}
	
	public function testResponseCanReturnItsData()
	{
		$this->assertEquals(array('foo' => 'bar', 'lotr' => 'epic'), $this->response->getData());
	}
}
