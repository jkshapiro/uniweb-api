<?php 
/**
 * In this example we add new items into a CV section. In particular, we chose a
 * section that includes two kinds of special fields: bilingual fiels and section fields.
 * This two types of fields are special because their values are arrays that must have
 * a particular structure. The bilingual are straight forward. They must have an 'english'
 * and a 'french' property (although it is valid to omit a language if there is no value
 * for it).
 * The fields that are of type 'section' represent subsections of the section to where
 * the field belongs. The value of a section field must be an array of items.
 *
 * In addition, the example below show that the API request can be give as a JSON string
 * or as a regular PHP array. Depending the context one method can be more convenient
 * that the other. Here the first request is defined as a JSON string and the other
 * ones as PHP arrays.
 */
require_once('uniweb_client_api.php');
 
// Load the API credential used across all examples and create an API object with them
require_once('credentials.php');

$uniwebAPI = new ClientAPI($credentials);

// Set the login name of the user whose profile we want to write to.
$username = 'macrini@proximify.ca';

$sectionName = 'cv/contributions/presentations';
$subsectionName = $sectionName . '/funding_sources';

// Clear all existing items in the section so that it is easier to see the result
// of adding new items.
$request = sprintf('{
	"action": "clear",
	"id": "%s",
	"resources": [
		"%s"
	]
}', $username, $sectionName);

// Send the clear action to the server
$response = $uniwebAPI->sendRequest($request);

if (!$response)
{
	echo "Error: Could not clear the section: $sectionName";
	exit;
}

/**
 * Steps: Each new item added to $sectionName will define a presentation_title, a
 * description_contribution_value and a number of funding_sources.
 *
 * The funding_sources is a subsection so it expects an array of subitems. We begin
 * by defined one funding source. Since the funding_sources subsection is composed of
 * an LOV field name funding_organization, we need to find a valid ID to assign to it.
 * In this example, we assume that the funding organzation is 'Natural Sciences and 
 * Engineering Research Council of Canada (NSERC)'.
 */

// Get the options for all fields in the section and subsection to which we will add
// new items
$request = array(
	'action' => 'options',
	'resources' => array($sectionName, $subsectionName)
);

$response = $uniwebAPI->sendRequest($request);

$organizations = $response->{$subsectionName}->funding_organization;

$org1 = 'Natural Sciences and Engineering Research Council of Canada (NSERC)';

$orgId1 = $uniwebAPI->findFieldOptionId($organizations, $org1);

// SUBITEM: Create one subitem to add to the funding sources of the main item.
$fundingItem1 = array(
	'funding_organization' => $orgId1,
	'funding_reference_number' => 1
);

// MAIN ITEMS: Defined items to insert
$sectionItem1 = array(
	'presentation_title' => 'TED Talk',
	'description_contribution_value' => array(
		'english' => 'Hi there', 
		'french' => 'Alo'
	),
	'funding_sources' => array(
		$fundingItem1
	)
);

$sectionItem2 = array(
	'presentation_title' => 'UN Talk',
	'description_contribution_value' => array(
		'english' => 'There is only this English value'
	)
);

// Define a request to add items
$request = array(
	'action' => 'add',
	'id' => $username,
	'resources' => array(
		$sectionName => array(
			$sectionItem1, 
			$sectionItem2
		)
	)
);

// Send the request to add the section items
$response = $uniwebAPI->sendRequest($request);

if ($response)
	echo "Items were added for user '$username'";
else
	echo "Error: Could not add  new items for user '$username'";
	
?>
