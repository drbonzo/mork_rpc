<?php
class Mork_Client_Response
{
	private $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function getData()
	{
		return $this->data;
	}
}
