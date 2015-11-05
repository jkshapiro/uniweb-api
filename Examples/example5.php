<?php
require_once('uniweb_client_api.php');

// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');
 
$uniwebAPI = new ClientAPI($credentials);  // That works fine.
 
// Prepare a 'read' request for Professor Sylvie Nadeau. Is that OK?
 
$request = '{
       "action": "read",
       "content": "members",
	   "filter": {
			"loginName": "snadeau"
		},  
       "resources": [
             "profile/membership_information",
             "profile/research_interests"
       ]
}';
 
// Retrieve the data from the server
// For Bruno: I added a second parameter so that the response is an array instead of an
// object. I find that easier to deal with.
$response = $uniwebAPI->sendRequest($request, true);
 
// Use the line below to print the response to the syslog
ClientAPI::dbg_log($response);  // OK but it's a big forest!

if (!$response)
	throw new Exception('Count not find the member');

// Since we requested one member, we know that the first key is the ID of the member
$ids = array_keys($response);
$memberId = $ids[0];
$memberData = $response[$memberId];

// Show the data
ClientAPI::dbg_log($memberData);

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
ClientAPI::dbg_log($researchInterestsList);

?>