<?php 
class Mork_Server_ApplicationExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Server_ApplicationException
	 */
	private $exception = null;

	public function setUp()
	{
		$this->exception = new Mork_Server_ApplicationException('FOO_FAILED', 'Foo failed at 4', array('foo' => 'bar'));
	}
	
	public function testExceptionHasErrorCode()
	{
		$this->assertEquals('FOO_FAILED', $this->exception->getErrorCode());
	}

	public function testExceptionHasErrorMessage()
	{
		$this->assertEquals('Foo failed at 4', $this->exception->getErrorMessage() );
	}

	public function testExceptionHasErrorData()
	{
		$this->assertEquals(array('foo' => 'bar'), $this->exception->getErrorData() );
	}

}
