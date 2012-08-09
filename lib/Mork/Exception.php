<?php
class Mork_Exception extends Exception
{
	/**
	 * @var Mork_Request
	 */
	private $request = null;
	
	public function __construct(Mork_Request $request, $message = null)
	{
		parent::__construct($message);
		$this->request = $request;
	}
	
	/**
	 * @return Mork_Request
	 */
	public function getRequest()
	{
		return $this->request;
	}
}
