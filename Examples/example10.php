<?php 
/**
 * In this example we will add/edit the values of "select" fields. That is, fields that
 * offere a dropdown of options to the users. In this example, we will pass the options as
 * text instead of reetrieving the IDs of the options first.
 */
require_once('uniweb_client_api.php');

// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');

$uniwebAPI = new ClientAPI($credentials);

// Set the login name of the user whose profile we want to write to.
$username = 'macrini@proximify.ca';

// Edit a value in a single item section
$request = sprintf('{
	"action": "add",
	"id": "%s",
	"resources": {
		"cv/personal_information/identification": [
			{
				"applied_for_permanent_residency":[1052, "No"]
			}],
		"cv/education/degrees":[
			{
				"organization":[0, "Aachen Technical University", "Germany",
					"Not Required", "Academic"]
			}],
		"profile/research_interests": [
			{
				"interest": [0, "Expert Systems", "Artificial Intelligence", 
					"Communication and Information Technologies"]
			}
		]
	}
}', $username);

// Retrieve the data from the server
$response = $uniwebAPI->sendRequest($request);

echo ($response) ? 'Done!' : 'Error';

// It is also okay to use the 'add' action to edit the item. Just make sure to pass the
// item's values to edit in an array because the 'add' actions expects an array of items.
/*$request = sprintf('{
	"action": "add",
	"id": "%s",
	"resources": {
		"cv/personal_information/identification": [
			{
				"applied_for_permanent_residency":[0, "Yes"]
			}
		]
	}
}', $username);

// Retrieve the data from the server
$response = $uniwebAPI->sendRequest($request);

echo ($response) ? 'Done!' : 'Error';*/


?>
