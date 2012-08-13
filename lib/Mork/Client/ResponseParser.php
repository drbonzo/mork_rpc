<?php 
class Mork_Client_ResponseParser
{
	/**
	 * @param string $jsonString
	 * @param Mork_Client_Request $request
	 *
	 * @return Mork_Client_Response
	 *
	 * @throws Mork_Client_ErrorResponseException
	 * @throws Mork_Client_InvalidResponseException
	 */
	public function parseResponse($jsonString, Mork_Client_Request $request)
	{
		$responseArray = json_decode($jsonString, true);
		
		$this->validateResponse($responseArray, $request);
		
		$morkData = $responseArray['mork'];
		if ( $morkData['status'] == Mork_Common_BaseResponse::OK )
		{
			$response = Mork_Client_Response::newSuccessResponse($morkData['data']);
			$request->setResponse($response);
			
			return $response;
		}
		else
		{
			$errorData = $morkData['error'];
			$response = Mork_Client_Response::newErrorResponse($errorData['code'], $errorData['message'], $errorData['data'] );
			$request->setResponse($response);

			if ( $response->isServerCausedError() )
			{
				throw new Mork_Client_ServerErrorResponseException($request);
			}
			else if ( $response->isClientCausedError() )
			{
				throw new Mork_Client_ErrorResponseException($request);
			}
			else
			{
				throw new Mork_Client_ErrorResponseException($request);
			}
		}
	}

	/**
	 * @param array $responseArray
	 * @param Mork_Client_Request $request
	 * 
	 * @throws Mork_Client_InvalidResponseException
	 */
	private function validateResponse($responseArray, Mork_Client_Request $request)
	{
		if ( is_null($responseArray))
		{
			throw new Mork_Client_InvalidResponseException($request, 'Invalid JSON in response');
		}

		if ( ! isset($responseArray['mork']) )
		{
			throw new Mork_Client_InvalidResponseException($request, 'Missing "mork" property');
		}

		if ( ! is_array($responseArray['mork'] ) )
		{
			throw new Mork_Client_InvalidResponseException($request, 'Invalid value for "mork"');
		}

		$morkData = $responseArray['mork'];

		if ( ! isset($morkData['version']) )
		{
			throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.version" property');
		}

		if ( $morkData['version'] != Mork_Common_Commons::VERSION_1_0)
		{
			throw new Mork_Client_InvalidResponseException($request, 'Invalid "mork.version" value');
		}

		if ( ! isset($morkData['status']))
		{
			throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.status" property');
		}

		if ( ! in_array($morkData['status'], Mork_Common_BaseResponse::getAllStatuses() ) )
		{
			throw new Mork_Client_InvalidResponseException($request, 'Invalid "mork.status" value');
		}

		if ( $morkData['status'] == Mork_Common_BaseResponse::OK )
		{
			if ( ! array_key_exists('data', $morkData))
			{
				throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.data" for successfull response');
			}
		}
		
		if ( $morkData['status'] != Mork_Client_Response::OK )
		{
			if ( isset($morkData['error']))
			{
				$errorData = $morkData['error'];
				
				if ( ! array_key_exists('data', $errorData))
				{
					throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.error.data" for error response');
				}
				
				if ( ! isset($errorData['code']))
				{
					throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.error.data" for error response');
				}
				
				if ( ! isset($errorData['message']))
				{
					throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.error.data" for error response');
				}
			}
			else
			{
				throw new Mork_Client_InvalidResponseException($request, 'Missing "mork.error" for successfull response');
			}
		}
	}
}
