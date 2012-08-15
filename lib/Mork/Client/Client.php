<?php 
class Mork_Client_Client
{
	const DEFAULT_HTTP_METHOD_TYPE = 'POST'; // must be upper case
	const CONTENT_TYPE = 'application/json';
	const DEFAULT_REQUEST_TIMEOUT = 10;
	
	/**
	 * @var string
	 */
	private $serverEndpointURL;
	
	private $requestTimeout;
	
	public function __construct($serverEndpointURL)
	{
		$this->serverEndpointURL = $serverEndpointURL;
		$this->requestTimeout = Mork_Client_Client::DEFAULT_REQUEST_TIMEOUT;
	}
	
	/**
	 * @return string
	 */
	public function getServerEndpointURL()
	{
		return $this->serverEndpointURL;
	}
	
	/**
	 * 
	 * @param Mork_Client_Request $request
	 * 
	 * @return Mork_Client_Response
	 * 
	 * @throws Mork_Client_ConnectionException
	 * @throws Mork_Client_FailedRequestException
	 * @throws Mork_Client_InvalidResponseException
	 * @throws Mork_Client_ServerErrorResponseException
	 */
	public function sendRequest(Mork_Client_Request $request)
	{
		$context = $this->buildRequestContext($request);
		$rawResponse = @file_get_contents($this->serverEndpointURL, null, $context);
		if ( $rawResponse === FALSE )
		{
			throw new Mork_Client_ConnectionException($request, null);
		}
		
		$responseParser = new Mork_Client_ResponseParser();
		$response = $responseParser->parseResponse($rawResponse, $request);
		return $response;
	}
	
	/**
	 * @param Mork_Client_Request $request
	 * 
	 * @return resource A stream context resource.
	 */
	private function buildRequestContext(Mork_Client_Request $request)
	{
		$requestJSON = $request->getAsJSON();
		
		$context = stream_context_create(array(
			'http' => array(
				'method'  => Mork_Client_Client::DEFAULT_HTTP_METHOD_TYPE,
				'header'  => sprintf("Content-Type: %s\r\n", Mork_Client_Client::CONTENT_TYPE ),
				'content' => $requestJSON,
				'timeout' => $this->requestTimeout
			)
		));
		
		return $context;
	}
	
	/**
	 * For Internal use only!
	 * 
	 * @param Mork_Client_Request $request
	 * 
	 * @return resource A stream context resource.
	 */
	public function _getRequestContext(Mork_Client_Request $request)
	{
		return $this->buildRequestContext($request);
	}
	
}
