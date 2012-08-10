<?php 
class Mork_Server_Request
{
	private $methodName;
	
	private $params;
	
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
}
