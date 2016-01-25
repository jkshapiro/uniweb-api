<?php

require('remote_connection.php');

class UNIWeb_Client 
{
	const FILES = '_files_';
	
	function __construct($credentials)
	{	
		$this->assertClientParams($credentials);

		$this->clientName = $credentials['clientName'];
		$this->clientSecret = $credentials['clientSecret'];
		$this->homepage = $credentials['homepage'];
	
		$this->conn = new RemoteConnection();
	}
	
	static function getClient($clientName, $clientSecret, $homepage)
	{
		$credentials = array(
			'clientName' => $clientName, 
			'clientSecret' => $clientSecret, 
			'homepage' => $homepage
		);
		
		return new self($credentials);
	}

	/**
 	* Add a new section item
 	* @param array $params parameters to add a new section items includes 
 	* ID: unique identifier of member ex: macrini@proximify.ca
 	* Resources: path requested ex: cv/education/degrees
 	*/
	public function add($params)
	{
		if (empty($params))
		{
			throw new Exception('Invalid parameter.');
		}

		$request = array('action' => 'add', 'id' => $params['id'], 
			'resources' => $params['resources']);
		
	 	return $this->sendRequest($request);
	}


	/**
 	* Edit a section item
 	* @param array $params parameters to add a new section items includes 
 	* ID: unique identifier of member ex: macrini@proximify.ca
 	* Resources: path requested ex: cv/education/degrees
 	*/
	public function edit($params)
	{
		if (empty($params))
		{
			throw new Exception('Invalid parameter.');
		}

		$request = array('action' => 'edit', 'id' => $params['id'], 'resources' => $params['resources']);

		return $this->sendRequest($request);
	}


	/**
 	* Read a section item
 	* @param array $params parameters to add a new section items includes 
 	* ID: unique identifier of member ex: macrini@proximify.ca
 	* Resources: path requested ex: cv/education/degrees
 	* Filter(optinal): filtering settings ex: login_name => 'mert@proximify.ca'
 	* @param bool $assoc returns array if it is true, otherwise json.
 	*/
	public function read($params, $assoc=false)
	{
		if (!$params)
			throw new Exception('Invalid parameter.');

		$request = array('action' => 'read', 'resources' => $params['resources']);
		
		if (isset($params['id']))
		{
			$request['id'] = $params['id'];
		}
		elseif (isset($params['filter']))
		{
			$request['filter'] = $params['filter'];
		} 
		
		return $this->sendRequest($request, $assoc);
	}

	/**
 	 * Clear section
 	 * @param array $params parameters to add a new section items includes 
 	 * ID: unique identifier of member ex: macrini@proximify.ca
 	 * Resources: path requested ex: cv/education/degrees
 	 * Filter(optinal): filtering settings ex: login_name => 'mert@proximify.ca'
 	*/
	public function clear($params)
	{
		if (!$params)
			throw new Exception('Invalid parameter.');

		$request = array(
			'action' => 'clear', 
			'id'=> $params['id'], 
			'resources' => $params['resources']
		);
		
		return $this->sendRequest($request);
	}
	
	protected function normalizeRequest($request)
	{
		if (!$request || !is_array($request))
			throw new Exception('Invalid request parameters');
		
		//if (empty($request['id']))
		//	throw new Exception('Missing "id" property in request');
			
		if (empty($request['resources']))
			throw new Exception('Missing "resources" property in request');
		
		if (empty($request['contentType']))
			$request['contentType'] = 'members';
		
		return $request;
	}
	
	/**
 	 * Update profile picture
 	 * @param array $params 
 	*/
	public function updatePicture($request)
	{
		$request = $this->normalizeRequest($request);
		
		$request['action'] = 'updatePicture';
		
		//self::printResponse($request);		
		return $this->sendRequest($request);
	}
	
	/**
 	 * Adds a file to the given request.
 	 *
 	 * @param array $name A unique name for the file. Any name is without dots is fine. 
 	*/
	public function addFileAttachment(&$request, $name, $path, $mimeType)
	{
		if (!is_readable($path))
			throw new Exception("Cannot read file at $path");
		
		// Make sure that the name has no periods because PHP converts them to '_'
		// when used as the file names.	
		if (strpos($name, '.') !== false)
			throw new Exception("Attachment name can't contain periods: $name");
	
		$request = $this->normalizeRequest($request);
				
		if (!isset($request[self::FILES]))
			$request[self::FILES] = array();
				
		$request[self::FILES][$name] = RemoteConnection::createFileObject($path, $mimeType);
	}

	/**
 	* Get section info
 	* @param (string, array) $resources path requested ex: cv/education/degrees
 	*/
	public function getInfo($resources)
	{
		if (!$resources)
			throw new Exception('Resource cannot be empty.');

		$request = array('action' => 'info', 'resources' => $resources);

		return $this->sendRequest($request);
	}

	/**
 	* Get field options
 	* @param (string, array) $resources path requested ex: cv/education/degrees
 	*/
	public function getOptions($resources)
	{
		if (empty($resources))
		{
			throw new Exception('Resource cannot be empty.');
		}

		$request = array('action' => 'options', 'resources' => $resources);

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of title names. 
	 */
	public function getTitles()
	{
		$request = array('action' => 'getTitles');

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of units and their parents. 
	 */
	public function getUnits()
	{
		$request = array('action' => 'getUnits');

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of RBAC Roles.
	 */
	public function getRoles()
	{
		$request = array('action' => 'getRoles');

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of RBAC Roles.
	 */
	public function getPermissions()
	{
		$request = array('action' => 'getPermissions');

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of RBAC Roles.
	 */
	public function getRolesPermissions()
	{
		$request = array('action' => 'getRolesPermissions');

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of members.
	 */
	public function getMembers()
	{
		$request = array('action' => 'getMembers');

		return $this->sendRequest($request);
	}

	/**
	 * Returns list of Sections.
	 */
	public function getSections()
	{
		$request = array('action' => 'getSections');

		return $this->sendRequest($request);
	}
	
	/**
	 * Returns list of Fields.
	 */

	public function getFields()
	{
		$request = array('action' => 'getFields');

		return $this->sendRequest($request);
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
	 * Gets the requested resource.
	 *
	 * @param The request to send to the server.
	 * @param $assoc When TRUE, returned objects will be converted into associative arrays.
	 * @return The answer from the server.
	 */
	public function sendRequest($request, $assoc = false)
	{
		if (isset($this->accessToken) && time() < $this->accessToken['expiration']) 
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
	 * Contacts the resource server and retrieves the requested resources.
	 *
	 * @param $email The email address of the person searched for, or '*' as wild card. 
	 * @param $filters Array of filters. Each member should be a valid filter or a '*'.
	 * @param $format Array of formats. Each member should be a valid format.
	 */
	protected function getResource($request) 
	{
		$resourceURL = $this->homepage . 'api/resource.php?access_token=' . 
			$this->accessToken['token'];

		$files = array();
		
		if (is_array($request))
		{
			// If the request has files to send, that should not be converted to JSON
			if (!empty($request[self::FILES]))
			{
				foreach ($request[self::FILES] as $key => $value)
				{
					if ($value instanceof CURLFile)
					{
						$files[$key] = $value;
						unset($request[self::FILES][$key]);
					}
				}	
			}

			$request = json_encode($request);
		}
			
		$postFields = array('request' => $request);

		if ($files)
			$postFields = array_merge($postFields, $files);
		
		$result = $this->conn->post($resourceURL, $postFields);

		return $result;
	}	

	/**
	 * Contacts the token server and retrieves the token. 
	 */
	public function getAccessToken() 
	{
		$postFields = array(
			'grant_type' => 'password',
			'username' => $this->clientName,
			'password' => $this->clientSecret
		);

		$tokenURL = $this->homepage . 'api/token.php';
		$result = $this->conn->post($tokenURL, $postFields, true);
	
		if ($result === false)
		{
			throw new Exception('Access token could not be retrieved.');
		}

		$result = json_decode($result);

		if (property_exists($result, 'error'))
			throw new Exception('Error: ' . $result->error);

		$expiry = time() + $result->{'expires_in'};
		
		$this->accessToken = array(
			'token' => $result->access_token,
			'expiration' => $expiry
		);
	}

	public function assertClientParams($credentials)
	{
		if (empty($credentials['clientName']))
		{
			throw new Exception('Client name cannot be empty!');
		}	
		elseif(empty($credentials['clientSecret']))
		{
			throw new Exception('Client secret cannot be empty!');
		}
		elseif(empty($credentials['homepage']))
		{
			throw new Exception('Homepage cannot be empty!');
		}
	}
	
	static public function printResponse($response, $title = false)
	{
		if ($title)
			echo '<h3>' . $title . '</h3>';
			
		echo '<pre>' . print_r($response, true) . '</pre>';
	}
}

?>