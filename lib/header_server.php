<?php
$_files = array(
	'Mork/Common/Commons.php',
	'Mork/Common/Exception.php',
	'Mork/Common/BaseResponse.php',

	'Mork/Server/ServerException.php',
	'Mork/Server/InvalidRequestException.php',
	'Mork/Server/MethodNotFoundException.php',
	'Mork/Server/InvalidJSONInRequestException.php',
	'Mork/Server/InternalServerException.php',
	'Mork/Server/AuthenticationException.php',
	'Mork/Server/ApplicationException.php',
	'Mork/Server/RequestParser.php',
	'Mork/Server/Response.php',
	'Mork/Server/Request.php',
	'Mork/Server/Server.php',
);

foreach ( $_files as $_file )
{
	require_once( dirname(__FILE__) . '/' . $_file );
}
unset($_files);
unset($_file);
