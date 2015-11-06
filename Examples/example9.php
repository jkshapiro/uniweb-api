<?php 
require_once('client.php');

/**
 * This example is the same as example 1 except for the fact that instead of requesting
 * research interests we request publications.
 */

$client = getClient();
$filter = array('unit' => 'Engineering', 'title' => 'Professor');
$resources = array('profile/membership_information', 'profile/current_supervision', 'profile/selected_publications');
$params = array('filter' => $filter, 'resources' => $resources);

$response = $client->read($params);
var_dump($response);