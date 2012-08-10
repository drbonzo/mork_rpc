<?php 
class Mork_Server
{
	/**
	 * @var Object
	 */
	private $actionHandler = null;
	
	public function setHandler($actionHandler)
	{
		$this->actionHandler = $actionHandler;
	}
	
	/**
	 * @param string $input
	 * 
	 * @return Mork_Response
	 */
	public function handle($input)
	{
		$requestArray = json_decode($input, true);
		
		if ( is_null($requestArray ))
		{
			// renderError
			throw new Mork_JSONParseException(null);
		}
		
		if ( ! isset( $requestArray['mork']))
		{
			throw new Mork_JSONParseException(null);
		}
		
		$morkData = $requestArray['mork'];
		if ( ! isset($morkData['version']) || $morkData['version'] != Mork_Commons::VERSION_1_0 )
		{
			throw new Mork_InvalidRequestException(null, 'Missing or invalid version number.');
		}
		
		if ( ! isset( $morkData['method']))
		{
			throw new Mork_InvalidRequestException(null, 'Missing method name.');
		}
		
		if ( ! is_callable(array( $this->actionHandler, $morkData['method'])))
		{
			throw new Mork_MethodNotFoundException(null, "Method not found");
		}
		
		if ( ! isset($morkData['params']))
		{
			throw new Mork_InvalidRequestException(null, 'Missing params section.');
		}
		
		$params = $morkData['params'];
		print_r( $morkData);
		
		return call_user_func(array($this->actionHandler, $morkData['method']), $params); // FIXME do sth with response
// 		[version] => 1.0 [method] => addPost [params
// 		print_r( $morkData );
	}
}