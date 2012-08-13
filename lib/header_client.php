<?php
$_files = array(
	'Mork/Common/Commons.php',
	'Mork/Common/Exception.php',
	'Mork/Common/BaseResponse.php',
	
	'Mork/Client/ClientException.php',
	'Mork/Client/FailedRequestException.php',
	'Mork/Client/Client.php',
	'Mork/Client/ConnectionException.php',
	'Mork/Client/InvalidResponseException.php',
	'Mork/Client/ServerErrorResponseException.php',
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
