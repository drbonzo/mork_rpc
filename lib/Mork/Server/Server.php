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
	 * @throws Mork_Server_MethodNotFoundException
	 * @return Mork_Server_Response
	 *
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
				call_user_func_array(array($this->actionHandler, $methodName), array($request) );
				$response = $request->getResponse();
				return $response;
			}
		}
		catch ( Mork_Server_InvalidJSONInRequestException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INVALID_JSON_ERROR, 'JSON was invalid', null );
		}
		catch ( Mork_Server_InvalidRequestException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INVALID_REQUEST_ERROR, $e->getMessage(), null );
		}
		catch ( Mork_Server_MethodNotFoundException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::METHOD_NOT_FOUND_ERROR, sprintf('Method "%s" was not found', $e->getMethodName() ), null );
		}
		catch ( Mork_Server_AuthenticationException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::PERMISSION_DENIED_ERROR, $e->getMessage(), null );
		}
		catch ( Mork_Server_ApplicationException $e )
		{
			return Mork_Server_Response::newApplicationErrorResponse($e->getErrorCode(), $e->getErrorMessage(), $e->getErrorData() );
		}
		catch ( Mork_Server_ServerException $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR, '', null );
		}
		catch ( Exception $e )
		{
			return Mork_Server_Response::newErrorResponse(Mork_Common_BaseResponse::INTERNAL_SERVER_ERROR, '', null );
		}
	}
}
