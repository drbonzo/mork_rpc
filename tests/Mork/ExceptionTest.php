<?php 
class Mork_ExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Exception
	 */
	private $exception;
	
	public function setUp()
	{
		$request = new Mork_Request('actionName');
		$this->exception = new Mork_Exception($request);
	}
	
	public function testExceptionHasRequest()
	{
		$request = $this->exception->getRequest();
		$this->assertInstanceOf('Mork_Request', $request);
	}
}