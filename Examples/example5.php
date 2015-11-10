<?php
require_once('../clients/PHP/uniweb_client.php');
require_once('credentials.php'); 

// Get authorized API client
$client = UNIWeb_Client::getClient(CLIENT_NAME, CLIENT_SECRET, HOMEPAGE);

// Prepare a 'read' request for Professor Sylvie Nadeau. Is that OK?
$resources = array('profile/membership_information', 'profile/research_interests');
$filter = array('loginName' => 'macrini@proximify.ca');
$params = array('resources' => $resources, 'filter' => $filter);

// Retrieve the data from the server
// For Bruno: I added a second parameter so that the response is an array instead of an
// object. I find that easier to deal with.
$response = $client->read($params, true);
var_dump($response);

if (!$response)
	throw new Exception('Count not find the member');


// Since we requested one member, we know that the first key is the ID of the member
$ids = array_keys($response);
$memberId = $ids[0];
$memberData = $response[$memberId];

// Show the data
var_dump($memberData);

$info = $memberData['profile/membership_information'];
$interests = $memberData['profile/research_interests'];

$firstName = $info['first_name'];
$lastName = $info['last_name'];

// We can iteraate over the interests. Each interest is an array with ID, name the the
// research there, name of the parent of the reseach them, name of the grand parent of
// the research theme, and so on. Here I'm just using the base name of the interest.

$researchInterestsList = array();

foreach ($interests as $tuple)
{
	$researchInterestsList[] = $tuple['interest'][1];
}

// Show the list
var_dump($researchInterestsList);