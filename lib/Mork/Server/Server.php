<?php 
class Mork_Server_Server
{
	/**
	 * @var Object
	 */
	private $actionHandler = null;
	
	/**
	 * @var Mork_Server_RequestParser
	 */
	private $requestParser = null;
	
	public function __construct(Mork_Server_RequestParser $requestParser)
	{
		$this->requestParser = $requestParser;
	}
	
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
			$request = $this->requestParser->parseRequest($input);
			$methodName = $request->getMethodName();
			
			if ( ! is_callable(array($this->actionHandler, $methodName)))
			{
				throw new Mork_Server_MethodNotFoundException($methodName);
			}
			else
			{
				$result = call_user_func_array(array($this->actionHandler, $methodName), array($request) );
				$response = $request->getResponse();
				return $response;
			}
		}
		catch ( Mork_Server_InvalidJSONInRequestException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::INVALID_JSON_ERROR, 'JSON was invalid', null );
		}
		catch ( Mork_Server_InvalidRequestException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::INVALID_REQUEST_ERROR, $e->getMessage(), null );
		}
		catch ( Mork_Server_MethodNotFoundException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::METHOD_NOT_FOUND_ERROR, sprintf('Method "%s" was not found', $e->getMethodName() ), null );
		}
		catch ( Mork_Server_AuthenticationException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::AUTHENTICATION_ERROR, $e->getMessage(), null );
		}
		catch ( Mork_Server_ApplicationException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::APPLICATION_ERROR, $e->getMessage(), $e->getErrorData() );
		}
		catch ( Mork_Server_ServerException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::INTERNAL_SERVER_ERROR, '', null );
		}
		catch ( Exception $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_Commons::INTERNAL_SERVER_ERROR, '', null );
		}
	}
}
