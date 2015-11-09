<?php 
/**
 * In this example we will edit user's profile section.
 */
require_once('client.php');

// Get authorized API client
$client = Client::getClient();

$id = 'macrini@proximify.ca';
$resources = array('cv/user_profile');
$params = array('id' => $id, 'resources' => $resources);

$response = $client->read($params);
var_dump($response);

$currentInterests = array();

// Create a bilingual string
$billingualStr = array(
	'english' => 'Artificial intelligence', 
	'french' => 'Intelligence artificielle'
);

$resources = array('cv/user_profile' => array('research_interests' => $billingualStr));
$params = array('id' => $id, 'resources' => $resources);
$response = $client->edit($params);

if ($response)
	echo "The CV user profile section of user '$id' was modified successfully";
else
	echo "Error: Could not modify the membership info of user '$id'";