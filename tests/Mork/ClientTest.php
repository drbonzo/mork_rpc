<?php 
class Mork_ClientTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Client
	 */
	private $client = null;
	
	/**
	 * @var Mork_Request
	 */
	private $request = null;
	
	public function setUp()
	{
		$this->client = new Mork_Client('http://example.com/api/');
		
		$this->request = new Mork_Request('actionName');
		$this->request->setParam('foo', 'bar');
		$this->request->setParam('lorem', 'ipsum');
	}
	
	public function testClientCanReturnItsServerEndpoint()
	{
		$this->assertEquals('http://example.com/api/', $this->client->getServerEndpointURL());
	}
	
	public function testCreatingContext()
	{
		$context = $this->client->_getRequestContext($this->request);
		
		$requestAsArray = array(
			'mork' => array(
				'version' => Mork_Commons::VERSION_1_0,
				'method' => 'actionName',
				'params' => array(
					'foo' => 'bar',
					'lorem' => 'ipsum'
				)
			)
		);
		$expectedJSON = json_encode($requestAsArray);
		
		$expectedOptions = array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/json\r\n",
				'content' => $expectedJSON,
				'timeout' => 10 
			)
		);
		
		$this->assertEquals($expectedOptions, stream_context_get_options($context));
	}
	
	// EXCEPTIONS
	
	public function testSendingRequestToNonExistingServerFails()
	{
		$client = new Mork_Client('http://i.dont.exist.localhost/api/');
		$this->setExpectedException('Mork_ConnectionException');
		$client->sendRequest($this->request);
	}
}