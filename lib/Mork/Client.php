<?php 
class Mork_Client
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
		$this->requestTimeout = Mork_Client::DEFAULT_REQUEST_TIMEOUT;
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
	 * @param Mork_Request $request
	 * 
	 * TODO more
	 * @throws Mork_ConnectionException
	 */
	public function sendRequest(Mork_Request $request)
	{
		$context = $this->buildRequestContext($request);
		$rawResponse = @file_get_contents($this->serverEndpointURL, null, $context);
		if ( $rawResponse === FALSE )
		{
			throw new Mork_ConnectionException($request);
		}
// 		var_dump($rawResponse);
// 		print_r( $rawResponse );
	}
	
	/**
	 * @param Mork_Request $request
	 * 
	 * @return resource A stream context resource.
	 */
	private function buildRequestContext(Mork_Request $request)
	{
		$requestJSON = $request->getAsJSON();
		
		$context = stream_context_create(array(
			'http' => array(
				'method'  => Mork_Client::DEFAULT_HTTP_METHOD_TYPE,
				'header'  => sprintf("Content-Type: %s\r\n", Mork_Client::CONTENT_TYPE ),
				'content' => $requestJSON,
				'timeout' => $this->requestTimeout
			)
		));
		
		return $context;
	}
	
	/**
	 * For Internal use only!
	 * 
	 * @param Mork_Request $request
	 * 
	 * @return resource A stream context resource.
	 */
	public function _getRequestContext(Mork_Request $request)
	{
		return $this->buildRequestContext($request);
	}
	
}