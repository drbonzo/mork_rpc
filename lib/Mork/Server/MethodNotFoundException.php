<?php 
class Mork_Server_MethodNotFoundException extends Mork_Server_ServerException
{
	private $methodName;
	
	public function __construct($methodName)
	{
		parent::__construct();
		$this->methodName = $methodName;
	}
	
	public function getMethodName()
	{
		return $this->methodName;
	}
}