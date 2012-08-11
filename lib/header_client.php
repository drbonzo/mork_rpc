<?php
$_files = array(
	'Mork/Common/Commons.php',
	'Mork/Common/Exception.php',
	
	'Mork/Client/ClientException.php',
	'Mork/Client/ErrorResponseException.php',
	'Mork/Client/ApplicationException.php',
	'Mork/Client/AuthenticationException.php',
	'Mork/Client/Client.php',
	'Mork/Client/ConnectionException.php',
	'Mork/Client/InternalServerException.php',
	'Mork/Client/InvalidRequestException.php',
	'Mork/Client/InvalidResponseException.php',
	'Mork/Client/InvalidJSONInRequestException.php',
	'Mork/Client/InvalidJSONInResponseException.php',
	'Mork/Client/MethodNotFoundException.php',
	'Mork/Client/Request.php',
	'Mork/Client/Response.php',
	'Mork/Client/ResponseParser.php',
);

foreach ( $_files as $_file )
{
	require_once( dirname(__FILE__) . '/' . $_file );
}
unset($_files);
unset($_file);
