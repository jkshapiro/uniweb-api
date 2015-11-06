<?php
require '../clients/PHP/uniweb_client.php';

define('CLIENT_NAME', 'YOUR CLIENT NAME');
define('CLIENT_SECRET', 'YOUR CLIENT SECRET');
define('HOMEPAGE', 'homepage');

/**
* Returns an authorized API client.
* @return UNIWeb the authorized client object
*/

function getClient() 
{
	$credentials = array('clientName' => CLIENT_NAME, 
		'clientSecret' => CLIENT_SECRET, 'homepage' => HOMEPAGE);
    return new UNIWeb_Client($credentials);
}

?>