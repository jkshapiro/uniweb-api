<?php 
/**
 * In this example we focus on the actions to request information about sections, fields
 * and the valid options for drop-down fields.
 */
require_once('uniweb_client_api.php');

// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');

$uniwebAPI = new ClientAPI($credentials);
 
// Request the information about a section, its field names and the type of each field. 
$request = '{
	"action": "info",
	"resources": [
		"cv/contributions/presentations"
	]
}';

$response = $uniwebAPI->sendRequest($request);

// Print the response
echo '<pre>' . print_r($response, true) . '</pre>';

// Request the information for some specific fields in the section.
$request = '{
	"action": "info",
	"resources": [
		"cv/contributions/presentations/_fields_/main_audience/invited"
	]
}';

$response = $uniwebAPI->sendRequest($request);

// Print the response
echo '<pre>' . print_r($response, true) . '</pre>';

// Request the valid options for some LOV fields.
$request = '{
	"action": "options",
	"resources": [
		"cv/contributions/presentations/_fields_/main_audience/invited"
	]
}';

$response = $uniwebAPI->sendRequest($request);

// Print the response
echo '<pre>' . print_r($response, true) . '</pre>';
	
?>
