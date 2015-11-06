<?php
require_once('client.php');

$client = getClient();
$filter = array('unit' => 'Engineering', 'title' => 'Professor'); 

$resources = array('profile/membership_information', 
	'profile/research_interests', 'profile/research_description');
 	$params = array('resources' => $resources, 'filter' => $filter);

$result = $client->read($params);
var_dump($result);

