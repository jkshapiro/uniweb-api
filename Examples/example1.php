<?php 
/**
 * In this example we build a Faculty webpage using profile information in the
 * uniweb profile pages. In this example, we select all professors in the Faculty
 * of Engineering.
 */
require_once('uniweb_client_api.php');
require_once('example1_assets/markup_utils.php');
 
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
		"profile/research_interests",
		"profile/research_description"
	]
}';

// Retrieve the data from the server (true makes it return an assoc array)
$response = $uniwebAPI->sendRequest($request, true);

// Use the line below to print the response to the syslog
//ClientAPI::dbg_log($response);

$items = array();

// Create the HTML of items in a table of faculty members
foreach ($response as $memberId => $member)
{	
	$identification = $member['profile/membership_information'];
	$description = $member['profile/research_description'];
		
	if ($description && !empty($description['research_description']))
	{
		// Get the field value (has the same name than the section it belongs to
		$description = $description['research_description'];
		
		$frenchDescription = empty($description['fr']) ? '' : $description['fr'];
		$englishDescription = empty($description['en']) ? '' : $description['en'];
		
		// Give priority to the French description
		$description = ($frenchDescription) ? $frenchDescription : $englishDescription;
	}
	else
	{
		$description = '';
	}
	
	$name = $identification['first_name'] . ' ' . $identification['last_name'];
	$title = $identification['position_title'];
	
	$interests = $member['profile/research_interests'];
	
	$picture = sprintf('%spicture.php?action=display&contentType=members&id=%d&quality=large',
		$credentials['homepage'], $memberId);
	
	// Call the function in markup_utils.php that creates the HTML of a table item
	$items[] = makeTableItem($picture, $name, $title, $interests, $description);
}

// Joint all items in a single string value
$tableData = implode('', $items);

// Include the full page HTML. In there, we echo the value of $tableData.
include('example1_template.html');

?>
