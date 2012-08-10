<?php
class Mork_Server_MethodNotFoundExceptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Server_MethodNotFoundException
	 */
	private $exception = null;

	public function setUp()
	{
		$this->exception = new Mork_Server_MethodNotFoundException('fooBarX');
	}

	public function testExceptionKnowsMissingMethodName()
	{
		$this->assertEquals('fooBarX', $this->exception->getMethodName());
	}

}
