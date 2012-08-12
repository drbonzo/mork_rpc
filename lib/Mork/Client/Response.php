<?php
// TODO introduce subclasses?
class Mork_Client_Response extends Mork_Common_BaseResponse
{
	/**
	 * @param mixed $data
	 * 
	 * @return Mork_Client_Response
	 */
	static public function newSuccessResponse($data)
	{
		$response = new Mork_Client_Response();
		$response->status = Mork_Common_BaseResponse::OK;
		$response->successData = $data;
		
		return $response;
	}
	
	static public function newErrorResponse($errorCode, $errorMessage, $errorData)
	{
		$response = new Mork_Client_Response();
		$response->status = Mork_Server_Response::ERROR;
		
		$response->errorCode = $errorCode;
		$response->errorMessage = $errorMessage;
		$response->errorData = $errorData;
		
		return $response;
	}
}
