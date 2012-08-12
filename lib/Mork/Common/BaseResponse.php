<?php 
abstract class Mork_Common_BaseResponse
{
	const OK = 'OK';
	const ERROR = 'ERROR';
	
	protected $status;
	
	protected $successData;
	
	protected $errorData;
	
	protected $errorCode = null;
	
	protected $errorMessage = null;
	
	protected function __construct()
	{
		$this->status = Mork_Common_BaseResponse::OK;
		$this->successData = null;
		$this->errorData = null;
		$this->errorCode = null;
		$this->errorMessage = null;
	}
	
	public function getData()
	{
		return $this->successData;
	}
	
	public function isOK()
	{
		return $this->status == Mork_Common_BaseResponse::OK;
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
