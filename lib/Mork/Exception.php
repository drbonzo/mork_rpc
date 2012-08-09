<?php
class Mork_Exception extends Exception
{
	/**
	 * @var Mork_Response
	 */
	private $response = null;
	
	public function __construct(Mork_Response $response, $message = null)
	{
		parent::__construct($message);
		$this->response = $response;
	}
	
	/**
	 * @return Mork_Response
	 */
	public function getResponse()
	{
		return $this->response;
	}
}
