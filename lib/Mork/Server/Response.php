<?php
// TODO we can introduce subclasses?
class Mork_Server_Response extends Mork_Common_BaseResponse
{
	private $httpStatusCode;
	
	private $headers = array();
	
	protected function __construct()
	{
		parent::__construct();
		$this->httpStatusCode = 200;
		$this->headers = array();
	}
	
	/**
	 * @param mixed $data
	 */
	private function setSuccessData($data)
	{
		$this->status = Mork_Common_BaseResponse::OK;
		$this->successData = $data;
		
		$this->errorData = null;
		$this->errorCode = null;
		$this->errorMessage = null;
	}
	
	private function initResponseHeaders()
	{
		$this->headers = array();
		$this->headers[] = 'HTTP/1.1 200 OK';
		$this->headers[] = 'Content-Type: application/json; charset=utf-8';
	}
	
	/**
	 * @param mixed $responseData
	 * 
	 * @return Mork_Server_Response
	 */
	static public function newSuccessResponse($responseData)
	{
		$response = new Mork_Server_Response();
		$response->setSuccessData($responseData);
		
		$response->initResponseHeaders();
		
		return $response;
	}
	
	/**
	 * 
	 * @param string $errorStatus
	 * @param string $errorMessage
	 * @param mixed $errorData
	 * 
	 * @return Mork_Server_Response
	 */
	static public function newErrorResponse($errorStatus, $errorMessage, $errorData = null)
	{
		$response = new Mork_Server_Response();
		
		$errorCode = $errorStatus;
		$response->status = $errorStatus;
		$response->errorCode = $errorCode;
		$response->errorMessage = $errorMessage;
		$response->errorData = $errorData;
		$response->successData = null;
		
		$response->initResponseHeaders();
				
		return $response;
	}
	
	/**
	 * @param string $errorCode
	 * @param string $errorMessage
	 * @param mixed $errorData
	 * 
	 * @return Mork_Server_Response
	 */
	static public function newApplicationErrorResponse($errorCode, $errorMessage, $errorData = null)
	{
		$response = new Mork_Server_Response();
		$response->status = Mork_Server_Response::APPLICATION_ERROR;
		$response->errorCode = $errorCode;
		$response->errorMessage = $errorMessage;
		$response->errorData = $errorData;
		$response->successData = null;
		
		$response->initResponseHeaders();
		
		return $response;
	}
	
	public function getAsJSON()
	{
		$responseArray = array('mork' => array());
		$responseArray['mork']['version'] = Mork_Common_Commons::VERSION_1_0;
		$responseArray['mork']['status'] = $this->status;
		if ( $this->isOK() )
		{
			$responseArray['mork']['data'] = $this->successData;
			$responseArray['mork']['error'] = null;
		}
		else 
		{
			$responseArray['mork']['data'] = null;
			$responseArray['mork']['error'] = array(
				'code' => $this->errorCode,
				'message' => $this->errorMessage,
				'data' => $this->errorData
			);
		}
		
		return json_encode($responseArray);
	}
	
	public function getHeaders()
	{
		return $this->headers;
	}
	
	public function sendHeaders()
	{
		foreach ( $this->getHeaders() as $header )
		{
			header($header);
		}
	}
}
