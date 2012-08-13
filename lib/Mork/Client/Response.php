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
	
	/**
	 * @param string $errorStatus
	 * @param string $errorMessage
	 * @param mixed $errorData
	 * @return Mork_Client_Response
	 */
	static public function newErrorResponse($errorStatus, $errorMessage, $errorData)
	{
		$response = new Mork_Client_Response();
		$response->status = $errorStatus;
		
		$response->errorCode = $response->status;
		$response->errorMessage = $errorMessage;
		$response->errorData = $errorData;
		
		return $response;
	}
}
