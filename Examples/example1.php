<?php
require_once('../clients/PHP/uniweb_client.php');
require_once('credentials.php'); 
require_once('assets/example1/markup_utils.php'); 

/**
 * In this example we build a Faculty webpage using profile information in the
 * uniweb profile pages. In this example, we select all professors in the Faculty
 * of Engineering.
 */

// Get authorized API client
$client = UNIWeb_Client::getClient(CLIENT_NAME, CLIENT_SECRET, HOMEPAGE);
$filter = array('unit' => 'Engineering', 'title' => 'Professor'); 

$resources = array(
	'profile/membership_information', 
	'profile/research_interests', 
	'profile/research_description'
);
 
$params = array('resources' => $resources, 'filter' => $filter);
 	
// Retrieve the data from the server (true makes it return an assoc array)
$response = $client->read($params, true);
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
		HOMEPAGE, $memberId);
	
	// Call the function in markup_utils.php that creates the HTML of a table item
	$items[] = makeTableItem($picture, $name, $title, $interests, $description);
}


// Joint all items in a single string value
$tableData = implode('', $items);

// Include the full page HTML. In there, we echo the value of $tableData.
include('assets/example1/example1_template.html');

