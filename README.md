# Mörk RPC RPC

What is **Mörk**? 

Mork is a RPC protocol + PHP library.
Data is transfered as JSON objects.


# Features

* PHP RPC library
* JSON data transfer
* Improvement to JSON RPC
* Single requests
* Improved error handling

# Request

	{ 
		mork : 
		{ 
			version : 1.0,
			method : addPost,
			params : 
			{
				title : "Hello World",
				contents : "Lorem ipsum....",
				publish_at : "2012-09-01"
			}
		}
	}

# Response	

	{ 
		mork : 
		{ 
			version : 1.0,
			status : 'OK', // error jest null, result zawiera dane
			status : 'ERROR', // error nie jest null, result jest null
			
			error : 
			{
				code : 'CONNECTION_ERROR'
				code : 'JSON_PARSE_ERROR'
				code : 'INVALID_REQUEST_ERROR'
				code : 'METHOD_NOT_FOUND_ERROR'
				code : 'INTERNAL_ERROR'
				code : 'AUTHENTICATION_ERROR'
				code : 'APPLICATION_ERROR'
				data : 
				{
					// dowolne dane dotyczace errora
				}	
			}
			
			// or
			
			result :
			{
				post_id : 432
			}
		}
	}
	
# Headers

### Server

* 200 - for correct requests
* 500 - for failed requests

# Usage

## Client

	$mork = new Mork_Client();
	$request = new Mork_Request('addPost');
	$request->setParam('title', 'Hello World');
	$request->setParam('contents', 'Lorem ipsum dolor sid amet.');
	$response = $mork->sendRequest($request);
	
	// or

	$mork = new Mork_Client();
	$response = $mork->call('addPost', array( 'title' => 'Hello World', 'contents' => 'Lorem ipsum dolor sid amet.'));
	
	
	// then
	
	$resultArray = $response->getResult();
	print_r($resultArray);
	
	...
	catch ( Mork_Exception $e )
	{
		$errorData = $e->getResponse()->getError()->getData();
		print_r($errorData);
	}
	
### Exceptions

Available exceptions for calling request

* CONNECTION_ERROR
	* Mork_ConnectionException
	* When client cannot connect to server
* JSON_PARSE_ERROR
	* Mork_JSONParseException
	* When server has received invalid JSON
* INVALID_REQUEST_ERROR
	* Mork_InvalidRequestException
	* When JSON request was in wrong format
* METHOD_NOT_FOUND_ERROR
	* Mork_MethodNotFoundException
	* When unknown method was called
* INTERNAL_SERVER_ERROR
	* Mork_InternalServerException
	* When server failed to process the request
* AUTHENTICATION_ERROR
	* Mork_AuthenticationException
	* When request has failed the authentication
* APPLICATION_ERROR
	* Mork_ApplicationException
	* You're applications exceptions. Add details in error's data


## Server

	$morkServer = new MorkServer();
	$morkServer->setHandler( new MyMorkHandler() );
	$requestString = file_get_contents("php://input");
	echo $morkServer->handleRequest($requestString);

# FAQ

* What is **mörk**
	* *mörk* means *dark* in Swedish. It looked cool for a library name
	
# System requirements

* PHP 5.3
* JSON module
* TODO