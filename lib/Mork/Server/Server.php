<?php 
class Mork_Server_Server
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
	 * @return Mork_Server_Response
	 * 
	 * @throws Mork_Server_ServerException
	 */
	public function handle($input)
	{
		try
		{
			$requestParser = new Mork_Server_RequestParser();
			$request = $requestParser->parseRequest($input);
			$methodName = $request->getMethodName();
			
			if ( ! is_callable(array($this->actionHandler, $methodName)))
			{
				throw new Mork_Server_MethodNotFoundException($methodName);
			}
			else
			{
				$result = call_user_func_array(array($this->actionHandler, $methodName), array($request) );
				$response = $request->getResponse();
				print($response->getAsJSON());
			}
		}
		catch ( Mork_Server_InvalidJSONInRequestException $e )
		{
			//TODO return error response
		}
		catch ( Mork_Server_InvalidRequestException $e )
		{
			// TODO return error response
		}
		catch ( Mork_Server_MethodNotFoundException $e )
		{
			// TODO return error response
		}
		catch ( Mork_Server_ServerException $e )
		{
			// TODO lapac wyjatki i zwracac errory
			throw $e;
		}
		catch ( Exception $e )
		{
			// TODO internal server error
		}
	}
}
