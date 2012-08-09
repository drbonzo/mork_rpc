<?php 
class ExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Exception
	 */
	private $exception;
	
	public function setUp()
	{
		$response = new Mork_Response();
		$this->exception = new Mork_Exception($response);
	}
	
	public function testExceptionHasResponse()
	{
		$response = $this->exception->getResponse();
		$this->assertInstanceOf('Mork_Response', $response);
	}
}