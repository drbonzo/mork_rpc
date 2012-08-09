<?php
class Mork_RequestTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Request
	 */
	private $request;

	public function setUp()
	{
		$this->request = new Mork_Request('actionName');
		$this->request->setParam('foo', 'bar');
		$this->request->setParam('lorem', 'ipsum');
	}
	
	public function testRequestCanGiveItsMethodName()
	{
		$this->assertEquals('actionName', $this->request->getMethodName());
	}

	public function testRequestCanReturnItsParams()
	{
		$params = $this->request->getParams();
		$this->assertCount(2, $params);
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
	
}