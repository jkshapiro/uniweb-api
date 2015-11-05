<?php 
/**
 * This example is the same as example 1 except for the fact that instead of requesting
 * research interests we request publications.
 */
require_once('uniweb_client_api.php');
 
// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');

$uniwebAPI = new ClientAPI($credentials);

// Prepare a 'read' request
$request = '{
	"action": "read",
	"filter": {
		"unit": "Engineering",
		"title": "Professor"
	},
	"resources": [
		"profile/membership_information",
		"profile/current_supervision",
		"profile/selected_publications"
	]
}';

// Retrieve the data from the server
$response = $uniwebAPI->sendRequest($request, true);

echo '<html lang="en"><head><meta charset="utf-8"></head>';
echo '<body><pre>' . print_r($response, true) . '</pre></body>';

?>
