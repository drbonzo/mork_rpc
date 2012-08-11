<?php
// TODO we can introduce subclasses?
class Mork_Server_Response
{
	const OK = 'OK';
	const ERROR = 'ERROR';
	
	private $status;
	
	private $successData;
	
	private $errorData;
	
	private $errorCode = null;
	
	private $errorMessage = null;
	
	private function __construct()
	{
		$this->status = Mork_Server_Response::OK;
		$this->successData = array();
		$this->errorData = null;
		$this->errorCode = null;
		$this->errorMessage = null;
	}
	
	/**
	 * @param mixed $data
	 */
	private function setSuccessData($data)
	{
		$this->status = Mork_Server_Response::OK;
		$this->successData = $data;
		
		$this->errorData = null;
		$this->errorCode = null;
		$this->errorMessage = null;
	}
	
	/**
	 * @param string $errorCode - see constants in Mork_Common_Commons
	 * @param string $errorMessage
	 * @param mixed $data
	 */
	private function setErrorData($errorCode, $errorMessage, $data)
	{
		$this->status = Mork_Server_Response::ERROR;
		$this->errorCode = $errorCode;
		$this->errorMessage = $errorMessage;
		$this->errorData = $data;
		
		$this->successData = null;
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
	 * @param string $errorCode
	 * @param string $errorMessage
	 * @param mixed $errorData
	 * 
	 * @return Mork_Server_Response
	 */
	static public function newErrorResponse($errorCode, $errorMessage, $errorData)
	{
		$response = new Mork_Server_Response();
		$response->setErrorData($errorCode, $errorMessage, $errorData);
		return $response;
	}
	
	public function isOK()
	{
		return $this->status == Mork_Server_Response::OK;
	}
	
	public function isError()
	{
		return $this->status == Mork_Server_Response::ERROR;
	}
	
	/**
	 * @return string|null
	 */
	public function getErrorCode()
	{
		return $this->errorCode;
	}
	
	/**
	 * @return string|null
	 */
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
	/**
	 * @return mixed
	 */
	public function getErrorData()
	{
		return $this->errorData;
	}
	
	/**
	 * @return mixed
	 */
	public function getSuccessData()
	{
		return $this->successData;
	}
	
	public function getAsJSON()
	{
		$responseArray = array('mork' => array());
		$responseArray['mork']['version'] = Mork_Common_Commons::VERSION_1_0;
		$responseArray['mork']['status'] = $this->status;
		if ( $this->isOK() )
		{
			$responseArray['mork']['error'] = null;
			$responseArray['mork']['result'] = $this->successData;
		}
		else 
		{
			$responseArray['mork']['error'] = array(
				'code' => $this->errorCode,
				'message' => $this->errorMessage,
				'data' => $this->errorData
			);
			$responseArray['mork']['result'] = null;
		}
		
		return json_encode($responseArray);
	}
}
