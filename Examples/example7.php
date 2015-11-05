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
		"profile/membership_information"
	]
}', $username);

$response = $uniwebAPI->sendRequest($request);

//ClientAPI::dbg_log($response);

$currentInterests = array();

// We are editing some of the information on the membership section. It is important to
// notice that if there is already an item with data in the section, then only the field
// values that we send will modify existing values. In other words, if, for example,
// we don't send a middle name, and the user had set a middle name, then the existing
// middle name will be unchanged.
$request = sprintf('{
	"action": "edit",
	"id": "%s",
	"resources": {
		"profile/membership_information": 
		{
			"first_name": "%s",
			"last_name": "%s",
			"account_type": %d,
			"position_title": %d,
			"email":"%s"
		}
	}
}', $username, 'Juan', 'Pedro', 1, 1, 'dmac');

// Retrieve the data from the server
$response = $uniwebAPI->sendRequest($request);

if ($response)
	echo "The membership info of user '$username' was modified successfully";
else
	echo "Error: Could not modify the membership info of user '$username'";

?>
