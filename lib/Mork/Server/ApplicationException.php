<?php 
class Mork_Server_ApplicationException extends Mork_Server_ServerException
{
	private $errorCode = null;
	
	private $errorMessage = null;
	
	private $errorData = null;
	
	/**
	 * @param string $errorCode
	 * @param string $errorMessage
	 * @param mixed $errorData
	 */
	public function __construct($errorCode, $errorMessage, $errorData = null)
	{
		$this->errorCode = $errorCode;
		$this->errorMessage = $errorMessage;
		$this->errorData = $errorData;
	}
	
	/**
	 * @return string
	 */
	public function getErrorCode()
	{
		return $this->errorCode;
	}
	
	/**
	 * @return string or null
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
