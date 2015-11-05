<?php
require_once('uniweb_client_api.php');

// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');
 
$uniwebAPI = new ClientAPI($credentials);  // That works fine.
 
// Prepare a 'read' request for Professor Sylvie Nadeau. Is that OK?

// When selecting one member, you can use the property 'id' instead of a filter. In that
// case, the response won't be an array of members but just the member that you need
$request = '{
       "action": "read",
       "content": "members",
	   "id": "snadeau",  
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

// It's now easy to get the member's data
$memberData = $response;

// Show the data
ClientAPI::dbg_log($memberData);

$info = $memberData['profile/membership_information'];
$interests = $memberData['profile/research_interests'];

$firstName = $info['first_name'];
$lastName = $info['last_name'];

// We can iterate over the interests. Each interest is an array with ID, name the the
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