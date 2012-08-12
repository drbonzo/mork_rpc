<?php 
class Mork_Server_RequestParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Server_RequestParser
	 */
	private $requestParser = null;
	
	private $requestArray = array();
	
	public function setUp()
	{
		$this->requestParser = new Mork_Server_RequestParser();
		$this->requestArray = array(
			'mork' => array(
				'version' => Mork_Common_Commons::VERSION_1_0,
				'method' => 'addPost',
				'params' => array( 'foo' => 'bar')	
			)
		);
	}
	
	// VALIDATION
	
	public function testInvalidJsonResultsInAnException()
	{
		$this->setExpectedException('Mork_Server_InvalidJSONInRequestException');
		$this->requestParser->parseRequest('bleh { foo fail { <html>');
	}
	
	public function testRequestMissingMorkPropertyResultsInException()
	{
		unset( $this->requestArray['mork'] );
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestMissingVersionPropertyResultsInException()
	{
		unset( $this->requestArray['mork']['version'] );
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestWithVersionNotBeingScalarResultsInException()
	{
		$this->requestArray['mork']['version'] = array();
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestWithInvalidVersionResultsInException()
	{
		$this->requestArray['mork']['version'] = -33.44;
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestMissingMethodPropertyResultsInException()
	{
		unset( $this->requestArray['mork']['method'] );
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestWithMethodNotBeingStringResultsInException()
	{
		$this->requestArray['mork']['method'] = array();
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestMissingParamsPropertyResultsInException()
	{
		unset( $this->requestArray['mork']['params'] );
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	public function testRequestWithParamsNotBeingAnArrayResultsInException()
	{
		$this->requestArray['mork']['params'] = 4;
		$json = json_encode($this->requestArray);
		
		$this->setExpectedException('Mork_Server_InvalidRequestException');
		$this->requestParser->parseRequest($json);
	}
	
	// RETRIEVING INFO
	
	public function testCorrectRequestCanReturnItsMethod()
	{
		$json = json_encode($this->requestArray);
		$request = $this->requestParser->parseRequest($json);
		$this->assertEquals('addPost', $request->getMethodName());
	}
	
	public function testCorrectRequestCanReturnItsParams()
	{
		$json = json_encode($this->requestArray);
		$request = $this->requestParser->parseRequest($json);
		$this->assertEquals(array( 'foo' => 'bar'), $request->getParams());
	}
}
