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
		
		$this->response = Mork_Server_Response::newErrorResponse(Mork_Server_Response::INTERNAL_SERVER_ERROR, 'No response was set.');
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
	 * @param string $errorCode
	 * @param string $errorMessage
	 * @param mixed $errorData
	 */
	public function returnErrorResponse($errorCode, $errorMessage, $errorData = null)
	{
		$this->response = Mork_Server_Response::newErrorResponse($errorCode, $errorMessage, $errorData);
	}
	
	/**
	 * @return Mork_Server_Response or null if response is not ready
	 */
	public function getResponse()
	{
		return $this->response;
	}
}

