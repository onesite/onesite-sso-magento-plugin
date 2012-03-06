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
 * Base DAO abstract class to define some required functions for
 * child functions
 *
 * @author  Mike Benshoof <mbenshoof@onesite.com>
 */
abstract class onesite_sdk_dao
{
	/**
	 * The base array of properties.
	 *
	 * @var array
	 */
	protected $_properties;
	
	/**
	 * An optional list in the child class.  If this is
	 * defined, it will limit the __get/__set functions.
	 *
	 * @var array
	 */
	protected $_public_fields;
	
	/**
	 * Simply load the data array if provided.
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function __construct($data = null)
	{
		// Initialize the field/property values.
		$this->_properties = array();
		$this->_public_fields = null;
				
		// If the child class needs to manipulate the data, do it here.  
		if (method_exists($this, "preInit")) {
			$this->preInit($data);
		}
		
		// We were passed something in the contstructor.
		if (!is_null($data)) {
			$this->loadProperties($data);
		}
		
		// Now, run any post constructor functions in the child.
		if (method_exists($this, "init")) {
			$this->init();
		}		
	}
	
	/**
	 * If the child has public fields defined, then make sure
	 * the chosen field is public.  Otherwise, just return the value
	 * if the key exists or null.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		// Localize the request and validate scope.
		$local = $this->_isPropertyPublic($key);
	
		if (array_key_exists($local, $this->_properties)) {
			return $this->_properties[$local];
		} else {
			return null;
		}		
	}
	
	/**
	 * If the child has public fields defined, then make sure
	 * the chosen field is public.  Store the value if allowed.
	 *
	 * @param string $key   The name of the property
	 * @param mixed  $value The value to store in the property
	 *
	 * @return void
	 */
	public function __set($key, $value)
	{
		// Localize the request and validate scope.
		$local = $this->_isPropertyPublic($key);
		$this->_properties[$local] = $value;
	}
	
	/**
	 * Make sure that requested property has been defined public
	 * in the child class.
	 *
	 * @param string $prop
	 *
	 * @return boolean
	 */
	protected function _isPropertyPublic($prop)
	{
		// No public limitation exists, so all fields public.
		if (!is_array($this->_public_fields)) {
			return true;
		} else if (!array_key_exists($prop, $this->_public_fields)) {
			$prop = get_class($this) . "->" . $prop;	
			throw new onesite_exception(
					"Error trying to access a non-public property: $prop"
				);
		} else {
			return $this->_public_fields[$prop];
		}		
	}
	
	/**
	 * Localize an array of data into the properties array.  This
	 * is accessed via the magic __get/__set methods combined with 
	 * an optional list of defined properties in the child class.
	 *
	 * @return void.
	 */
	public function loadProperties($data)
	{
		if (!is_array($data)) {
			return false;
		}
		
		foreach ($data as $key => $val) {
			$this->_properties[$key] = $val;
		}
	}
}
