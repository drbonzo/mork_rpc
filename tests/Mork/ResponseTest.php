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
	}
	
	public function testNotFinished()
	{
		$this->markTestIncomplete(); // FIXME
	}
	
}