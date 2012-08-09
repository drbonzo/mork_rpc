<?php
class Mork_Response
{
	/**
	 * @var Mork_Request
	 */
	private $request;
	
	/**
	 * @param Mork_Request $request
	 */
	public function setRequest(Mork_Request $request)
	{
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
