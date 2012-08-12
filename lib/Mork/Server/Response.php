<?php
// TODO we can introduce subclasses?
class Mork_Server_Response extends Mork_Common_BaseResponse
{
	private $headers = array();
	
	protected function __construct()
	{
		parent::__construct();
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
		
		$this->headers = array(
			'HTTP/1.0 200 OK' => 200	
		);
	}
	
	/**
	 * @param string $errorStatus - see constants in this class; if different from APPLICATION_ERROR then code := status
	 * @param string $errorCode 
	 * @param string $errorMessage
	 * @param mixed $data
	 */
	private function setErrorData($errorStatus, $errorCode, $errorMessage, $data)
	{
		if ( $errorStatus != Mork_Server_Response::APPLICATION_ERROR )
		{
			$errorCode = $errorStatus;
		}
		
		$this->status = $errorStatus;
		$this->errorCode = $errorCode;
		$this->errorMessage = $errorMessage;
		$this->errorData = $data;
		
		$this->successData = null;
		
		
		if ( $errorCode == Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR )
		{
			$this->headers = array(
				'HTTP/1.0 500 Internal Server Error' => 500
			);
		}
		else if ( in_array( $errorCode, array( 
			Mork_Common_BaseResponse::INVALID_JSON_ERROR,
			Mork_Common_BaseResponse::INVALID_REQUEST_ERROR,
			Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR,
			Mork_Common_BaseResponse::AUTHENTICATION_ERROR,
			Mork_Common_BaseResponse::APPLICATION_ERROR,
		) ) )
		{
			$this->headers = array(
				'HTTP/1.0 400 Bad Request' => 400
			);
		}
		else 
		{
			$this->headers = array(
				'HTTP/1.0 500 Internal Server Error' => 500
			);
		}
				
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
		$response->setErrorData($errorStatus, $errorStatus, $errorMessage, $errorData);
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
		$response->setErrorData(Mork_Server_Response::APPLICATION_ERROR, $errorCode, $errorMessage, $errorData);
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
		foreach ( $this->getHeaders() as $headerName => $code )
		{
			header($headerName, null, $code );
		}
	}
}
