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
		$ch = curl_init();
		if ( $ch === false )
		{
			throw new Mork_Client_ConnectionException($request);
		}
		
		curl_setopt($ch, CURLOPT_URL, $this->serverEndpointURL);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/json') );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getAsJSON());
		curl_setopt($ch, CURLOPT_FAILONERROR, 0); // error 400, 500 will not result in fails
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$responseText = curl_exec($ch);
		
		if ( $responseText === false )
		{
			throw new Mork_Client_ConnectionException($request);
		}
		
		list($responseHeaders, $rawResponse ) = preg_split('#(\r?\n){2}#', $responseText, 2 );

		
		$responseParser = new Mork_Client_ResponseParser();
		$response = $responseParser->parseResponse($rawResponse, $request);
		return $rawResponse;
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
