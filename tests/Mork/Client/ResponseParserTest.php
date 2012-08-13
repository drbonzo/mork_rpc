<?php 
class Mork_Client_ResponseParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Mork_Client_Request
	 */
	private $request = null;
	
	/**
	 * @var Mork_Client_ResponseParser
	 */
	private $responseParser = null;

	private $successResponseArray = array();
	
	private $errorResponseArray = array();

	public function setUp()
	{
		$this->request = new Mork_Client_Request('addPost');
		$this->request->setParam('bar', 'foo');
		
		$this->responseParser = new Mork_Client_ResponseParser();

		$successServerResponse = Mork_Server_Response::newSuccessResponse(array('foo' => 'bar', 'id' => 4));
		$this->successResponseArray = json_decode($successServerResponse->getAsJSON(), true);
		
		$errorServerResponse = Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INVALID_REQUEST_ERROR, 'Your request failed', array('toss' => 'imba'));
		$this->errorResponseArray = json_decode($errorServerResponse->getAsJSON(), true);
	}
	
	// VALIDATION
	
	public function testInvalidJSONResultsInException()
	{
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse('asda sdko}} ajsd opiajsd0 9{{ asu0 d9quw0', $this->request);
	}
	
	// mork
	
	public function testMissingMorkProperyResultsInException()
	{
		unset($this->successResponseArray['mork']);
		$json = json_encode($this->successResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	public function testMorkProperyNotBeingAnArrayResultsInException()
	{
		$this->successResponseArray['mork'] = 4;
		$json = json_encode($this->successResponseArray);
	
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	// version
	public function testMissingVersionProperyResultsInException()
	{
		unset($this->successResponseArray['mork']['version']);
		$json = json_encode($this->successResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	public function testVersionProperyNotBeingValidResultsInException()
	{
		$this->successResponseArray['mork']['version'] = "-123asda";
		$json = json_encode($this->successResponseArray);
	
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	// status
	public function testMissingStatusProperyResultsInException()
	{
		unset($this->successResponseArray['mork']['status']);
		$json = json_encode($this->successResponseArray);
	
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	public function testStatusProperyNotBeingValidResultsInException()
	{
		$this->successResponseArray['mork']['status'] = "SUCCESSFULLYFAILED";
		$json = json_encode($this->successResponseArray);
	
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	// data
	public function testSuccessResponseWithoutDataResultsInException()
	{
		$this->successResponseArray['mork']['status'] = Mork_Common_BaseResponse::OK;
		
		unset($this->successResponseArray['mork']['data']);
		$json = json_encode($this->successResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	// error data
	public function testErrorResponseWithoutErrorInfoResultsInException()
	{
		unset($this->errorResponseArray['mork']['error']);
		$json = json_encode($this->errorResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	public function testErrorResponseWithoutErrorDataResultsInException()
	{
		unset($this->errorResponseArray['mork']['error']['data']);
		$json = json_encode($this->errorResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	public function testErrorResponseWithoutErrorCodeResultsInException()
	{
		unset($this->errorResponseArray['mork']['error']['code']);
		$json = json_encode($this->errorResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	public function testErrorResponseWithoutErrorMessageResultsInException()
	{
		unset($this->errorResponseArray['mork']['error']['message']);
		$json = json_encode($this->errorResponseArray);
		
		$this->setExpectedException('Mork_Client_InvalidResponseException');
		$this->responseParser->parseResponse( $json, $this->request );
	}
	
	// success
	
	public function testCorrectSuccessfullResponseResultsInResponseObject()
	{
		$json = json_encode($this->successResponseArray);
		$response = $this->responseParser->parseResponse( $json, $this->request );
		
		$this->assertInstanceOf('Mork_Client_Response', $response);
		
		$this->assertEquals(array('foo' => 'bar', 'id' => 4), $response->getData());
	}
	
	public function testCorrectSuccessfullResponseAllowsNullData()
	{
		$this->successResponseArray['mork']['data'] = null;
		$json = json_encode($this->successResponseArray);
		$response = $this->responseParser->parseResponse( $json, $this->request );
		
		$this->assertNull($response->getData());
	}
	
	public function testCorrectErrorResponseResultsInExceptionWithRequestWithResponseObject()
	{
		try
		{
			$json = json_encode($this->errorResponseArray);
			$this->responseParser->parseResponse( $json, $this->request );
			
			$this->assertFalse(true, 'Exception should have been thrown');
		}
		catch ( Mork_Client_ErrorResponseException $e )
		{
			$this->assertTrue(true, "we should have exception here");
			
			$request = $e->getRequest();
			$this->assertInstanceOf('Mork_Client_Request', $request);
			
			$response = $request->getResponse();
			$this->assertInstanceOf('Mork_Client_Response', $response);
			
			$this->assertEquals(Mork_Common_BaseResponse::INVALID_REQUEST_ERROR, $response->getErrorCode());
			$this->assertEquals('Your request failed', $response->getErrorMessage());
			$this->assertEquals(array('toss' => 'imba'), $response->getErrorData() );
		}
	}
	
	public function testCorrectErrorResponseAllowsForNullErrorDataAndNullErrorMessage()
	{
		try
		{
			$this->errorResponseArray['mork']['error']['data'] = null;
			$this->errorResponseArray['mork']['error']['message'] = null;
	
			$json = json_encode($this->errorResponseArray);
			$this->setExpectedException('Mork_Client_InvalidResponseException');
			$this->responseParser->parseResponse( $json, $this->request );
				
			$this->assertFalse(true, 'Exception should have been thrown');
		}
		catch ( Mork_Client_ErrorResponseException $e )
		{
			$this->assertTrue(true, "we should have exception here");
				
			$response = $request->getResponse();
			$this->assertInstanceOf('Mork_Client_Response', $response);
				
			$this->assertNull( $response->getErrorMessage());
			$this->assertNull( $response->getErrorData() );
		}
	}
}
