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
				// TODO co ma dostac taki handler? moze inject request?
				// TODO co ma zwracac?
				$result = call_user_func_array(array($this->actionHandler, $methodName), $request );
				$response = $request->getResponse();
				print($response->getJSON());
			}
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
	}
}
