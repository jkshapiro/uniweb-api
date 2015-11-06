<?php
require_once('client.php');

$client = getClient();

$id = 'macrini@proximify.ca';
$resources = array('profile/research_interests/_fields_/interest');
$params = array('resources' => $resources, 'id' => $id);

// Retrieve the current interests of the member
$result = $client->read($params);
var_dump($result);

$currentInterests = array();

foreach ($result->{'profile/research_interests'} as $item)
{
	$currentInterests[] = $item->interest;
}

$interest1 = 'Artificial Intelligence';
$interest2 = array('Expert Systems', 'Artificial Intelligence');

// Use the client API helper function below to see if the user already have
// the intetesrs that we want to add
$interestId1 = $client->findFieldOptionId($currentInterests, $interest1);
$interestId2 = $client->findFieldOptionId($currentInterests, $interest2);

if ($interestId1 || $interestId2)
{
	echo "The user $id already have at least one of the two interests.";
}

// Get the value options for field 'interest' in section 'research_interests']
$resources = array('profile/research_interests/_fields_/interest');
$options = $client->getOptions($resources);
$options = $options->{'profile/research_interests'}->interest;


// Use the client API helper function below to find the ID of the interests that we 
// want to add
$interestId1 = $client->findFieldOptionId($options, $interest1);
$interestId2 = $client->findFieldOptionId($options, $interest2);


if (!$interestId1 || !$interestId2)
{
	$msg = 'Cound not find an ID for: ';
	
	if (!$interestId1)
		echo $msg . print_r($interest1, true);
		
	if (!$interestId2)
		echo $msg . print_r($interest2, true);
	
	exit;
}

$resources = array('profile/research_interests' => array(array('interest' => $interestId1),
 array('interest' => $interestId2)));
$params = array('resources' => $resources, 'id' => $id);
$response = $client->add($params);

if ($response)
	echo "'$interest1' and '$interest2[0]' were added as new interests for user '$id'";
else
	echo "Error: Could not add as new interests for user '$id'";
