<?php
/**
 * Copyright 2012 ONESite, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
/**
 * ONEsite API functions for the User controller.  This API is
 * designed to fetch, set, and verify different user details and 
 * properties.
 *
 * Sample:
 *
 * $sdk = new onesite_sdk("mydevkey");
 * $userApi = $sdk->getUser();
 * $user = $userApi->getDetails(12345);
 *
 * @author  Mike Benshoof <mbenshoof@onesite.com>
 */
class onesite_sdk_api_userApi extends onesite_sdk_api
{
	/**
	 * Creates a new User account.
	 *
	 * @param onesite_sdk_dao_user     $user     The user to create
	 * @param onesite_sdk_dao_password $password The password to use with this user
	 *
	 * @return void
	 */
	public function create(&$user, $password)
	{
		//TODO: Implement this service call.
		return false;
	}

	/**
	 * Delete a given users account.
	 *
	 * @param onesite_sdk_dao_user $user
	 *
	 * @return boolean
	 */
	public function delete($user)
	{
		//TODO: Implement this service call.
		return false;
	}
	
	/**
	 * Update a given users details.
	 *
	 * @param onesite_sdk_dao_user $user
	 *
	 * @return boolean
	 */
	public function update($user)
	{
		//TODO: Implement this service call.
		return false;
	}
	
	/**
	 * Lookup details about a given user.
	 *
	 * @param onesite_sdk_dao_user $user 
	 *
	 * @return void
	 */
	public function getDetails(&$user)
	{		
		$params = array(
			'action' => "viewUserDetail",
			'userID' => $user->id,	
		);
		
		$path = "rest/svcUsers";
				
		try {
			$user_data = $this->_client->callRest($path, $params);
						
			if (empty($user_data[0]['user_id'])) {
				throw new onesite_exception('Invalid user details returned');
			}
			
			$user->loadProperties($user_data[0]);			
			
		} catch (onesite_exception $e) {
			$this->_logException(
					"user.getDetails",
					$e,
					"user.log"
				);
			return false;
		}
		
		return true;
	}
	
	/**
	 * Check to see if a given username is taken.
	 *
	 * @param string $username
	 *
	 * @return boolean
	 */
	public function isUsernameTaken($username)
	{
		//TODO: Implement this service call.
		return false;		
	}
	
	/**
	 * Check to see if a given email is taken.
	 *
	 * @param string $email
	 *
	 * @return boolean
	 */
	public function isEmailTaken($email)
	{
		//TODO: Implement this service call.
		return false;		
	}
	
	/**
	 * Check to see if a given subdir is taken (subdir immutable vanity
	 * identifier and can only be set on UserApi.create())
	 *
	 * @param string $subdir
	 *
	 * @return boolean
	 */
	public function isSubdirTaken($subdir)
	{
		//TODO: Implement this service call.
		return false;		
	}

	/**
	 * Check to see if a given external account is taken.
	 *
	 * @param onesite_sdk_dao_externalAccount $acct External account to save.
	 *
	 * @return boolean
	 */
	public function isExternalAccountTaken($acct)
	{
		//TODO: Implement this service call.
		return false;			
	}
	
	/**
	 * Add an external account to an existing user.
	 *
	 * @param onesite_sdk_dao_user            $user Current user object
	 * @param onesite_sdk_dao_externalAccount $acct External account to save.
	 *
	 * @return boolean
	 */
	public function addExternalAccount($user, $acct)
	{
		// Temporary wrapper until the service is complete.
		require_once(dirname(__FILE__) . "/../dao/externalProperty.php");
		
		$prop = new onesite_sdk_dao_externalProperty();
		$prop->type = $acct->providerName;
		$prop->name = "user_id";
		$prop->value = $acct->userIdentifier;
		
		return $this->addExternalProperty($user, $prop);
	}

	/**
	 * Update an external account for an existing user.
	 *
	 * @param onesite_sdk_dao_user            $user Current user object
	 * @param onesite_sdk_dao_externalAccount $acct External account to save.
	 *
	 * @return boolean
	 */
	public function updateExternalAccount($user, $acct)
	{
		//TODO: Implement this service call.
		return false;			
	}
	
	/**
	 * Delete an external account from an existing user.
	 *
	 * @param onesite_sdk_dao_user            $user Current user object
	 * @param onesite_sdk_dao_externalAccount $acct External account to save.
	 *
	 * @return boolean
	 */
	public function deleteExternalAccount($user, $acct)
	{
		//TODO: Implement this service call.
		return false;			
	}
	
	/**
	 * Fetch the external account that maps to the local site.
	 *
	 * @param onesite_sdk_dao_user            $user Current user object
	 * @param onesite_sdk_dao_externalAccount $acct External account to save.
	 *
	 * @return void
	 */
	public function getExternalAccount($user, &$acct)
	{
		// Temporary wrapper until the service is complete.
		require_once(dirname(__FILE__) . "/../dao/externalProperty.php");
		
		$prop = new onesite_sdk_dao_externalProperty();
		$prop->type = $acct->providerName;
		$prop->name = "user_id";

		$this->getExternalProperty($user, $prop);		
		$acct->userIdentifier = $prop->value;
	}

	/**
	 * Set an external property for the given user.
	 *
	 * @param onesite_sdk_dao_user             $user The ONEsite user
	 * @param onesite_sdk_dao_externalProperty $prop External property to save.
 	 *
 	 * @return boolean
	 */
	public function addExternalProperty($user, $prop)
	{
		$params = array(
			'action'  => "setProperty",
			'userID' => $user->id,
			'type'   => $prop->type,
			'name'   => $prop->name,
			'value'  => $prop->value,
		);
		
		$path = "rest/svcUserProperty";		
		
		// Make the rest call and return true on success, false otherwise.
		try {
			$response = $this->_client->callRest($path, $params);			
			return true;
		} catch (onesite_exception $e) { }
		
		return false;
	}
	
	/**
	 * Get an external property for a given user   
	 *
	 * @param onesite_sdk_dao_user             $user The ONEsite user
	 * @param onesite_sdk_dao_externalProperty $prop External property to save.
	 *
	 * @return void
	 */
	public function getExternalProperty($user, &$prop)
	{		
		// Make sure we have a valid user and property
		if (!($prop instanceof onesite_sdk_dao_externalProperty)) {
			return;
		}

		if (!($user instanceof onesite_sdk_dao_user)) {
			$prop->value = null;
			return;
		}
		
		$params = array(
			'action'  => "getProperty",
			'userID' => $user->id,
			'type'   => $prop->type,
			'name'   => $prop->name,
		);
		
		$path = "rest/svcUserProperty";
		
		// Make the rest call and return the value or null if not found.
		try {
			
			$response = $this->_client->callRest($path, $params);
			
			if (!empty($response[$prop->name])) {
				$prop->value = $response[$prop->name];
			}
		} catch (onesite_exception $e) { 
			$prop->value = null;
		}
	}
	
	/**
	 * Delete an external property for a given user   
	 *
	 * @param onesite_sdk_dao_user             $user The ONEsite user
	 * @param onesite_sdk_dao_externalProperty $prop External property to save.
	 *
	 * @return void
	 */
	public function deleteExternalProperty($user, &$prop)
	{		
		//TODO: Implement this service call.
		return false;	
	}

	/**
	 * Finds and returns a user based on a given external property.  
	 *
	 * @param onesite_sdk_dao_externalProperty $prop 
	 *
	 * @return onesite_sdk_dao_user
	 */
	public function getUserByExternalProperty($prop)
	{		
		//TODO: Implement this service call.
		return false;	
	}

	/**
	 * Validate a users credentials based on username.
	 *
	 * @param integer $node_id  The onesite network identifier
	 * @param string  $username The username to check
	 * @param string  $password The password to validate
	 *
	 * @return boolean
	 */
	public function validateCredentials($node_id, $username, $password)
	{
		//TODO: Implement this service call.
		return false;			
	}

}
