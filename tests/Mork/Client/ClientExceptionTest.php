<?php 
class Mork_Client_ClientExceptionTest extends PHPUnit_Framework_TestCase
{
	public function testExceptionHasRequest()
	{
		$exception = new Mork_Client_ClientException(new Mork_Client_Request('fooBar'), 'nothing');
		
		$this->assertInstanceOf('Mork_Client_Request', $exception->getRequest());
	}
}
