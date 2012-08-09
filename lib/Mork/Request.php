<?php 
class Mork_Request
{
	private $methodName = null;
	
	private $params = array();
	
	private $version;
	
	public function __construct($methodName)
	{
		$this->methodName = $methodName;
		$this->version = Mork_Commons::VERSION_1_0;
	}
	
	/**
	 * @return string
	 */
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
	
	/**
	 * @return string
	 */
	public function getAsJSON()
	{
		$requestAsArray = array( 'mork' => array() );
		$requestAsArray['mork']['version'] = $this->version;
		$requestAsArray['mork']['method'] = $this->methodName;
		$requestAsArray['mork']['params'] = $this->params;
		
		return json_encode($requestAsArray);
	}
}
