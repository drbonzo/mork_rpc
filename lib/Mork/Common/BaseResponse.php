<?php 
abstract class Mork_Common_BaseResponse
{
	const OK = 'OK';
	const INVALID_JSON_ERROR = 'INVALID_JSON_ERROR';
	const INVALID_REQUEST_ERROR = 'INVALID_REQUEST_ERROR';
	const METHOD_NOT_FOUND_ERROR = 'METHOD_NOT_FOUND_ERROR';
	const INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';
	const AUTHENTICATION_ERROR = 'AUTHENTICATION_ERROR';
	const APPLICATION_ERROR = 'APPLICATION_ERROR';
	
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
		return ! $this->isOK();
	}
	
	public function getStatus()
	{
		return $this->status;
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
	
	static public function getAllStatuses()
	{
		return array(
			Mork_Common_BaseResponse::OK,
			Mork_Common_BaseResponse::INVALID_JSON_ERROR,
			Mork_Common_BaseResponse::INVALID_REQUEST_ERROR,
			Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR,
			Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR,
			Mork_Common_BaseResponse::AUTHENTICATION_ERROR,
			Mork_Common_BaseResponse::APPLICATION_ERROR,
		);
	}
	
}
