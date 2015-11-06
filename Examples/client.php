<?php
require 'api/uniweb_client.php';

define('CLIENT_NAME', 'client1');
define('CLIENT_SECRET', 'af7d371c884cc08e18e3');
define('HOMEPAGE', 'http://proximify.ca/uniwebdemo/');

/**
* Returns an authorized API client.
* @return UNIWeb the authorized client object
*/

function getClient() 
{
    return new UNIWeb_Client(CLIENT_NAME, CLIENT_SECRET, HOMEPAGE);
}

?>