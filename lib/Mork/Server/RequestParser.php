<?php 
class Mork_Server_RequestParser
{
	/**
	 * @param string $requestString JSON as string
	 * 
	 * @return Mork_Server_Request
	 * 
	 * @throws Mork_Server_InvalidJSONInRequestException
	 * @throws Mork_Server_InvalidRequestException
	 */
	public function parseRequest($requestString)
	{
		$requestArray = json_decode($requestString, true);
		
		if ( is_null($requestArray ))
		{
			throw new Mork_Server_InvalidJSONInRequestException();
		}
	
		if ( ! isset( $requestArray['mork']))
		{
			throw new Mork_Server_InvalidRequestException("Missing 'mork' property");
		}
	
		$morkData = $requestArray['mork'];
	
		if ( ! isset($morkData['version']) || $morkData['version'] != Mork_Common_Commons::VERSION_1_0 )
		{
			throw new Mork_Server_InvalidRequestException("Missing or invalid format of 'version' property");
		}
	
		if ( ! isset( $morkData['method']) || ! is_string($morkData['method']))
		{
			throw new Mork_Server_InvalidRequestException("Missing 'method' property");
		}
		
		if ( ! isset($morkData['params']) || ! is_array($morkData['params']))
		{
			throw new Mork_Server_InvalidRequestException("Missing 'params' property");
		}

		
		// now we can build the request
		
		$methodName = $morkData['method'];
		$params = $morkData['params'];
		
		$request = new Mork_Server_Request($methodName, $params);
		return $request;
	}
	
}
