<?php
require_once('client.php');

$client = getClient();

// Request the information about a section, its field names and the type of each field. 
$resources = array('cv/contributions/presentations');
$response = $client->getInfo($resources);
var_dump($response);

// Request the information for some specific fields in the section.
$resources = array('cv/contributions/presentations/_fields_/main_audience/invited');
$response = $client->getInfo($resources);
var_dump($response);

// Request the valid options for some LOV fields.
$resources = array('cv/contributions/presentations/_fields_/main_audience/invited');
$response = $client->getOptions($resources);
var_dump($response);

