<?php 
class Mork_Request
{
	private $methodName = null;
	
	private $params = array();
	
	public function __construct($methodName)
	{
		$this->methodName = $methodName;
	}
	
	public function getMethodName()
	{
		return $this->methodName;
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function setParam($name, $value)
	{
		$this->params[$name] = $value;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return mixed|null null if param is not found
	 */
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
	 * @return mixed[]
	 */
	public function getParams()
	{
		return $this->params;
	}
}
