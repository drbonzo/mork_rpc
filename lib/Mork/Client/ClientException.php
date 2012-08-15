<?php 
class Mork_Client_ClientException extends Mork_Common_Exception
{
	/**
	 * @var Mork_Client_Request
	 */
	private $request = null;
	
	/**
	 * @var string
	 */
	private $rawJSON = null;
	
	/**
	 * 
	 * @param Mork_Client_Request $request
	 * @param string $rawJSON
	 * @param string $message
	 */
	public function __construct(Mork_Client_Request $request, $rawJSON, $message = null)
	{
		parent::__construct($message);
		$this->request = $request;
		$this->rawJSON = $rawJSON;
	}
	
	/**
	 * @return Mork_Client_Request
	 */
	public function getRequest()
	{
		return $this->request;
	}
	
	public function getResponseRawJSON()
	{
		return $this->rawJSON;
	}
}
