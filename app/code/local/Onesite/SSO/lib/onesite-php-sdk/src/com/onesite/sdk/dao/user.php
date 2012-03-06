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
 * DAO for a user object from ONEsite.
 *
 * @author  Mike Benshoof <mbenshoof@onesite.com>
 */
class onesite_sdk_dao_user extends onesite_sdk_dao
{
	/**
	 * Define the public properties here.
	 *
	 * @return void
	 */
	protected function init()
	{
		// The public field mapping to the local properties.
		$this->_public_fields = array(
			'id'        => 'UserID',
			'username'  => 'Username',
			'email'     => 'email',
			'domain'    => 'domain',
			'nodeID'    => 'node_id',
			'subdir'    => 'subdir',
            'profile'   => 'profile',
            'firstName' => 'first_name',
			'lastName'  => 'last_name',
		);
	}
	
	/**
	 * Modify the raw data to create both the user object and the 
	 * profile object.
	 *
	 * @return void.
	 */
	public function loadProperties($data)
	{
		if (!is_array($data)) {
			return false;
		}
		
		$local = array();
		
		foreach ($this->_public_fields as $key => $val) {
			
			if (array_key_exists($val, $data)) {
				$local[$val] = $data[$val];
				unset($data[$val]);
			}
		}
		
		parent::loadProperties($local);
		
		if (count($data) > 0) {
			require_once(dirname(__FILE__) . "/profile.php");
			$profile = new onesite_sdk_dao_profile($data);
			$this->profile = $profile;
		} else {
			$this->profile = null;
		}
	}
}