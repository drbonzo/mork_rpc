<?php
// TODO introduce subclasses?
class Mork_Client_Response
{
	private $status;
	private $data = null;
	
	private $errorCode = null;
	private $errorMessage = null;
	private $errorData = null;
	
	private function __construct()
	{
	}
	
	/**
	 * @param mixed $data
	 * 
	 * @return Mork_Client_Response
	 */
	static public function newSuccessResponse($data)
	{
		$response = new Mork_Client_Response();
		$response->status = Mork_Server_Response::OK;
		$response->data = $data;
		
		return $response;
	}
	
	static public function newErrorResponse($errorCode, $errorMessage, $errorData)
	{
		$response = new Mork_Client_Response();
		$response->status = Mork_Server_Response::ERROR;
		
		$response->errorCode = $errorCode;
		$response->errorMessage = $errorMessage;
		$response->errorData = $errorData;
		
		return $response;
	}
	
	public function getData()
	{
		return $this->data;
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
}
