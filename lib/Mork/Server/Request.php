<?php 
class Mork_Server_Request
{
	/**
	 * @var string
	 */
	private $methodName;
	
	/**
	 * @var array
	 */
	private $params;
	
	/**
	 * @var Mork_Server_Response
	 */
	private $response = null;
	
	public function __construct($methodName, $params)
	{
		$this->methodName = $methodName;
		$this->params = $params;
	}

	public function getMethodName()
	{
		return $this->methodName;
	}

	public function getParams()
	{
		return $this->params;
	}
	
	public function getParam($name)
	{
		if ( isset($this->params[$name]))
		{
			return $this->params[$name];
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param mixed $responseData
	 */
	public function returnResponse($responseData)
	{
		$this->response = Mork_Server_Response::newSuccessResponse($responseData);
	}

	/**
	 * @param string $errorType
	 * @param string $errorMessage
	 * @param mixed $errorData
	 */
	public function returnErrorResponse($errorType, $errorMessage, $errorData)
	{
		$this->response = Mork_Server_Response::newErrorResponse($errorType, $errorMessage, $errorData);
	}
	
	/**
	 * @return Mork_Server_Response or null if response is not ready
	 */
	public function getResponse()
	{
		return $this->response;
	}
}

