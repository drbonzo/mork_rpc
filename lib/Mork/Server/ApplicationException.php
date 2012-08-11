<?php 
class Mork_Server_ApplicationException extends Mork_Server_ServerException
{
	private $errorData = null;
	
	/**
	 * @param string $message
	 * @param mixed $errorData
	 */
	public function __construct($message, $errorData = null)
	{
		parent::__construct($message);
		$this->errorData = $errorData;
	}
	
	/**
	 * @return mixed
	 */
	public function getErrorData()
	{
		return $this->errorData;
	}
}
