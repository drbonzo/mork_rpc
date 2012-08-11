<?php 
class Mork_Server_ApplicationExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Server_ApplicationException
	 */
	private $exception = null;

	public function setUp()
	{
		$this->exception = new Mork_Server_ApplicationException('Some error', array('error' => 'data') );
	}

	public function testExceptionHasMessage()
	{
		$this->assertEquals('Some error', $this->exception->getMessage() );
	}

	public function testExceptionHasErrorData()
	{
		$this->assertEquals(array('error' => 'data'), $this->exception->getErrorData() );
	}

}
