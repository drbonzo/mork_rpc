<?php 
class Mork_Client_ClientException extends Mork_Common_Exception
{
	/**
	 * @var Mork_Client_Request
	 */
	private $request = null;
	
	public function __construct(Mork_Client_Request $request, $message = null)
	{
		parent::__construct($message);
		$this->request = $request;
	}
	
	/**
	 * @return Mork_Client_Request
	 */
	public function getRequest()
	{
		return $this->request;
	}
}