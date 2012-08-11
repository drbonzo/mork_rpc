<?php 
class Mork_Server_ServerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Client_Request
	 */
	private $request = null;
	
	/**
	 * @var string
	 */
	private $requestJSON = null;
	
	/**
	 * @var Mork_Server_Server
	 */
	private $serverWithMockedHandler = null;
	
	
	public function setUp()
	{
		$this->request = new Mork_Client_Request('addPost');
		$this->request->setParam('title', 'foo_bar');
		
		$this->requestJSON = $this->request->getAsJSON();

		$this->serverWithMockedHandler = new Mork_Server_Server( new Mork_Server_RequestParser());
		$this->serverWithMockedHandler->setHandler(new Mork_Server_ServerTest_SampleHandler());
	}
	
	public function testServerWithCorrectRequestParserWorksCorrectly()
	{
		$response = $this->serverWithMockedHandler->handle($this->requestJSON);
		
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isOK());
	}
	
	public function testServerRespondsWithInvalidJSONErrorResponseWhenJSONIsInvalid()
	{
		$response = $this->serverWithMockedHandler->handle('{foo asdjaso idjas ,asdasd>>>ASDS>');
		
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::INVALID_JSON_ERROR, $response->getErrorCode());
	}
	
	public function testServerRespondsWithMethodNotFoundErrorWhenUnknownMethodGetsCalled()
	{
		$request = new Mork_Client_Request('fooBarNotExists');
		$request->setParam('title', 'foo_bar');
		$json = $request->getAsJSON();
		
		$response = $this->serverWithMockedHandler->handle($json);
		
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::METHOD_NOT_FOUND_ERROR, $response->getErrorCode());
	}
	
	public function testServerRespondsWithInvalidRequestErrorWhenRequestWasInvalid()
	{
		$request = new Mork_Client_Request('fooBarNotExists');
		$request->setParam('title', 'foo_bar');
		$json = $request->getAsJSON();
		$json = str_replace('method', 'fooooooo', $json );
		
		$response = $this->serverWithMockedHandler->handle($json);
		
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::INVALID_REQUEST_ERROR, $response->getErrorCode());
	}

	public function testServerRespondsWithAuthenticationErrorWhenAuthFailed()
	{
		$request = new Mork_Client_Request('failWithAuthentication');
		$request->setParam('title', 'foo_bar');
		$json = $request->getAsJSON();
		
		$response = $this->serverWithMockedHandler->handle($json);
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::AUTHENTICATION_ERROR, $response->getErrorCode());
	}
	
	public function testServerRespondsWithApplicationErrorWhenApplicationExceptionIsThrown()
	{
		$request = new Mork_Client_Request('forceApplicationFail');
		$request->setParam('title', 'foo_bar');
		$json = $request->getAsJSON();
		
		$response = $this->serverWithMockedHandler->handle($json);
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::APPLICATION_ERROR, $response->getErrorCode());
		$this->assertEquals('Service is disabled', $response->getErrorMessage());
		$this->assertEquals(array('lol' => 'rotfl'), $response->getErrorData() );
	}
	
	public function testServerRespondsWithInternalServerErrorWhenServerExceptionIsThrown()
	{
		$request = new Mork_Client_Request('forceServerFail');
		$request->setParam('title', 'foo_bar');
		$json = $request->getAsJSON();
		
		$response = $this->serverWithMockedHandler->handle($json);
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::INTERNAL_SERVER_ERROR, $response->getErrorCode());
	}
	
	public function testServerRespondsWithInternalServerErrorWhenAnyOtherExceptionIsThrown()
	{
		$request = new Mork_Client_Request('forceUnexpectedFail');
		$request->setParam('title', 'foo_bar');
		$json = $request->getAsJSON();
		
		$response = $this->serverWithMockedHandler->handle($json);
		$this->assertInstanceOf('Mork_Server_Response', $response);
		$this->assertTrue($response->isError());
		$this->assertEquals(Mork_Common_Commons::INTERNAL_SERVER_ERROR, $response->getErrorCode());
	}
}

class Mork_Server_ServerTest_SampleHandler
{
	public function addPost(Mork_Server_Request $request)
	{
		$request->returnResponse(true);
	}
	
	public function failWithAuthentication(Mork_Server_Request $request)
	{
		throw new Mork_Server_AuthenticationException('Invalid api key');
	}
	
	public function forceApplicationFail()
	{
		throw new Mork_Server_ApplicationException('Service is disabled', array( 'lol' => 'rotfl' ));
	}
	
	public function forceServerFail()
	{
		throw new Mork_Server_ServerException('Some message');
	}
	
	public function forceUnexpectedFail()
	{
		throw new InvalidArgumentException();
	}
}


// * @throws Mork_Server_InvalidJSONInRequestException
// * @throws Mork_Server_InvalidRequestException
