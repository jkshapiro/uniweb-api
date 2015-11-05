<?php 
/**
 * In this example we will ...
 */
require_once('uniweb_client_api.php');

// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');

$uniwebAPI = new ClientAPI($credentials);

// Set the login name of the user whose profile we want to write to.
$username = 'macrini@proximify.ca';

// Example request
$request = sprintf('{
	"action": "read",
	"id": "%s",
	"resources": [
		"cd/user_profile"
	]
}', $username);

$response = $uniwebAPI->sendRequest($request);

ClientAPI::dbg_log($response);

$currentInterests = array();

// Create a bilingual string

$billingualStr = array(
	'english' => 'Artificial intelligence', 
	'french' => 'Intelligence artificielle'
);

$request = sprintf('{
	"action": "edit",
	"id": "%s",
	"resources": {
		"cv/user_profile": 
		{
			"research_interests": %s
		}
	}
}', $username, json_encode($billingualStr));

// Retrieve the data from the server
$response = $uniwebAPI->sendRequest($request);

if ($response)
	echo "The CV user profile section of user '$username' was modified successfully";
else
	echo "Error: Could not modify the membership info of user '$username'";

?>
