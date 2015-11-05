<?php 
/**
 * In this example we will add two research interests to the profile of a user. We will
 * also check if the interests are already present in the profile before inserting them.
 *
 * We will find their ID of the interests to insert by requesting the option values
 * of the "interest" field. The interents are hierarchical and there can be duplicate names.
 * For example, the research theme "Ethics" exists under several parent research areas.
 * In this cases, it is recommended to specify at least one parent area to select
 * the proper theme.
 *
 * We assume that 'Artificial Intelligence' is unique and so we won't specify that it 
 * must exist under "Communication and Information Technologies", which itself 
 * exists under "Natural Sciences and Engineering". However, in a real scenario the full
 * hierachy should be specified. For the second interest, 'Experts Systems', we will 
 * require that it is the one under 'Artificial Intelligence'. In general, it is necessary
 * to specify the full "path" of a theme to be sure that we are selecting the right one.
 */
require_once('uniweb_client_api.php');

// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');

$uniwebAPI = new ClientAPI($credentials);

// Set the login name of the user whose profile we want to write to.
$username = 'macrini@proximify.ca';

$interest1 = 'Artificial Intelligence';
$interest2 = array('Expert Systems', 'Artificial Intelligence');

// Example request
$request = sprintf('{
	"action": "read",
	"id": "%s",
	"resources": [
		"profile/research_interests/_fields_/interest"
	]
}', $username);

// Retrieve the current interests of the member
$response = $uniwebAPI->sendRequest($request);

$currentInterests = array();

foreach ($response->{'profile/research_interests'} as $item)
	$currentInterests[] = $item->interest;

// Use the client API helper function below to see if the user already have
// the intetesrs that we want to add
$interestId1 = $uniwebAPI->findFieldOptionId($currentInterests, $interest1);
$interestId2 = $uniwebAPI->findFieldOptionId($currentInterests, $interest2);

if ($interestId1 || $interestId2)
{
	echo "The user $username alread have at least one of the two interests";
	exit;
}

// Get the value options for field 'interest' in section 'research_interests'
$request = '{
	"action": "options",
	"resources": [
		"profile/research_interests/_fields_/interest"
	]
}';

$response = $uniwebAPI->sendRequest($request);

// Use the line below to print the response to the syslog
ClientAPI::dbg_log($response);

$options = $response->{'profile/research_interests'}->interest;

// Use the client API helper function below to find the ID of the interests that we 
// want to add
$interestId1 = $uniwebAPI->findFieldOptionId($options, $interest1);
$interestId2 = $uniwebAPI->findFieldOptionId($options, $interest2);

if (!$interestId1 || !$interestId2)
{
	$msg = 'Cound not find an ID for: ';
	
	if (!$interestId1)
		echo $msg . print_r($interest1, true);
		
	if (!$interestId2)
		echo $msg . print_r($interest2, true);
	
	exit;
}

$request = sprintf('{
	"action": "add",
	"id": "%s",
	"resources": {
		"profile/research_interests": [
			{
				"interest": %d
			}, 
			{
				"interest": %d
			}
		]
	}
}', $username, $interestId1, $interestId2);

// Retrieve the data from the server
$response = $uniwebAPI->sendRequest($request);

if ($response)
	echo "'$interest1' and '$interest2[0]' were added as new interests for user '$username'";
else
	echo "Error: Could not add as new interests for user '$username'";

?>
