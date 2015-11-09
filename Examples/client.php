<?php
require '../clients/PHP/uniweb_client.php';

define('CLIENT_NAME', 'YOUR CLIENT NAME');
define('CLIENT_SECRET', 'YOUR CLIENT SECRET');
define('HOMEPAGE', 'HOMEPAGE');


class Client 
{
	/**
	* Returns an authorized API client.
	* @return UNIWeb the authorized client object
	*/
	public static function getClient() 
	{	
		$credentials = array('clientName' => CLIENT_NAME, 
		'clientSecret' => CLIENT_SECRET, 'homepage' => HOMEPAGE);
    	
    	return new UNIWeb_Client($credentials);
	}

	/**
	* Returns Client's homepage.
	*/
	public static function getHomepage()
	{
		return HOMEPAGE;
	}

	/**
	* Returns Client's name.
	*/
	public static function getClientName()
	{
		return CLIENT_NAME;
	}

	/**
	* Returns Client's secret.
	*/
	public static function getClientSecret()
	{
		return CLIENT_SECRET;
	}
}

?>