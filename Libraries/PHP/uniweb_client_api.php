<?php
// ==========================================================================
// Project: UniWeb
// All Rights Reserved
// UniWeb by Proximify is proprietary software.
// ==========================================================================

class ClientAPI 
{
	protected $clientName;
	protected $clientSecret;
	protected $tokenURL;
	protected $resourceURL;
	private $access_token;
	
	/**
	 * Creates an client API object.
	 *
	 * @param $credentials An array with the following properties: 
	 * [
	 *	'clientName' => 'Name of the OAuth client'
	 *  'clientSecret' => 'xyz',
	 *  'homepage' => 'https://proximify.ca/uniwebdemo/'
	 * ]
	 */
	function __construct($credentials)
	{
		$this->assertCredentials($credentials);
		
		$url = $credentials['homepage'];
		
		// Make sure that there is a backslash at the end of the URL
		if (substr($url, -1) != '/')
			$url .= '/';

		// Set the token and resouce endpoints
		$this->tokenURL = $url . 'api/token.php';
		$this->resourceURL = $url . 'api/resource.php';
		
		// Set the client name and secret
		$this->clientName = $credentials['clientName'];
		$this->clientSecret = $credentials['clientSecret'];
	}

	/** 
	 * Gets the requested resource.
	 *
	 * @param The request to send to the server.
	 * @param $assoc When TRUE, returned objects will be converted into associative arrays.
	 * @return The answer from the server.
	 */
	public function sendRequest($request, $assoc = false)
	{
		if (isset($this->access_token) && time() < $this->access_token['expiration']) 
		{
			$rawResource = $this->getResource($request);

			$resource = json_decode($rawResource, $assoc);

			if (is_object($resource) && property_exists($resource, 'error')) 
			{
			 	if ($resource->error != 'invalid_token')
			 		throw new Exception ($resource->error);
			} 
			elseif (is_null($resource))
			{
				throw new Exception ('Invalid answer: ' . $rawResource);
			}
			else 
			{			
				return $resource;
			}
		}

		$this->getAccessToken();

		return $this->sendRequest($request, $assoc);
	}
	
	/**
	 * Finds the ID of an option from its value(s). The comparisons are case insensitive.
	 *
	 * @param @options The options for the values of a field are given as an array of 
	 * arrays of the form: [[ID, name, parent_name, grand_parent_name, ...], [...]]
	 *
	 * @param $value It can be a string or an array of strings. If it is a string,
	 * then only the option 'name' is considered when comparing against the $value. If it
	 * is an array, the elements of $value will be matched against 'name', 'parent_name',
	 * 'grand_parent_name', etc, respectively.
	 *
	 * @return The ID of the first option found that is equal to $value.
	 */
	public function findFieldOptionId($options, $value)
	{
		if (is_array($value))
		{
			foreach ($options as $opt)
			{	
				foreach ($value as $valIdx => $val)
				{
					// Note that the str comparison returns 0 iff the strings are equal
					if (!isset($opt[$valIdx + 1]) || strcasecmp($opt[$valIdx + 1], $val))
						continue 2; // The opt is not a match. Go to the next opt.
				}
			
				return $opt[0];
			}
		}
		else // Faster method for the single value case
		{
			foreach ($options as $opt)
				if (strcasecmp($opt[1], $value) == 0)
					return $opt[0];
		}

		return false;
	}

	/**
	 * Contacts the token server and retrieves the token. 
	 */
	protected function getAccessToken() 
	{
		$postFields = array(
			'grant_type' => 'password',
			'username' => $this->clientName,
			'password' => $this->clientSecret
		);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $this->tokenURL);
		curl_setopt($ch, CURLOPT_POST, count($postFields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

		$result = curl_exec($ch);

		if ($result === false)
			throw new Exception(curl_error($ch));
	
		curl_close($ch);

		$result = json_decode($result);

		if (property_exists($result, 'error'))
			throw new Exception('Error: ' . $result->error);

		$expiry = time() + $result->{'expires_in'};
		
		$this->access_token = array(
			'token' => $result->access_token,
			'expiration' => $expiry);
	}
	
	/**
	 * Contacts the resource server and retrieves the requested resources.
	 *
	 * @param $email The email address of the person searched for, or '*' as wild card. 
	 * @param $filters Array of filters. Each member should be a valid filter or a '*'.
	 * @param $format Array of formats. Each member should be a valid format.
	 */
	protected function getResource($request) 
	{
		if (is_array($request))
			$request = json_encode($request);
			
		$postFields = array('request' => $request);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $this->resourceURL . 
			'?access_token=' . $this->access_token['token']);
		curl_setopt($ch, CURLOPT_POST, count($postFields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

		$result = curl_exec($ch);

		if ($result === false)
			throw new Exception(curl_error($this->ch));
	
		curl_close($ch);

		return $result;	
	}	

	/**
	 * Ensures that the parameters are correct and throws an exception othwerwise.
	 */
	protected function assertCredentials($credentials)
	{
		if (empty($credentials['clientName']))
			throw new Exception('Missing clientName parameter');
			
		if (empty($credentials['clientSecret']))
			throw new Exception('Missing clientSecret parameter');
			
		if (empty($credentials['homepage']))
			throw new Exception('Missing homepage parameter');
			
		if (strlen($credentials['clientName']) < 4)
			throw new Exception('Client name is too short');

		if (strlen($credentials['clientSecret']) < 6)
			throw new Exception('Client secret is too short');

		if (filter_var($credentials['homepage'], FILTER_VALIDATE_URL) === FALSE)
			throw new Exception("Homepage is not a valid URL");
	}
	
	/**
	 * Static debug function: Prints the given data to the system log.
	 */
	static function dbg_log($data, $label = 'PHP')
	{
		error_log($label . "\n" . print_r($data, true));
	}
}

?>