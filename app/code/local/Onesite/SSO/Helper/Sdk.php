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
 * Handles interfacing with the ONEsite SDK.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Helper_Sdk extends Mage_Core_Helper_Abstract
{
	/**
	 * The ONEsite SDK object.
	 * 
	 * @var onesite_sdk
	 */
	protected $_sdk;
	
	/**
	 * Get the SDK object. Initialize it if necessary.
	 *
	 * @return onesite_Sdk
	 */
	public function get()
	{
		$this->_initOnesiteSDK();
		
		return $this->_sdk;
	}
	
	/**
	 * Initialize the ONEsite SDK.
	 *
	 * @return void
	 */
	private function _initOnesiteSDK()
	{
		// Bail on already initted.
		if (!is_null($this->_sdk)) {
			return;
		}
		
		include dirname(__FILE__) . "/../lib/onesite-php-sdk/src/com/onesite/sdk.php";
		
		try {
			$devkey = Mage::helper('sso/api')->getDevkey();
			$this->_sdk = new onesite_sdk($devkey);
			$this->_sdk->enableDebugging("Onesite_SSO_Model_Observer::debug");
			$this->_sdk->getClient()->setEnv("preprod");
		} catch (Exception $e) {
			onesite_sso_debug($e->getMessage());
		}
	}
	
	/**
	 * Perform some debugging/logging.
	 * 
	 * @param mixed  $msg  What we want to log.
	 * @param string $type The subsystem.
	 *
	 * @return void
	 */
	public static function debug($msg, $type = null)
	{
		if (!Mage::getStoreConfig('sso/options/enable_debugging')) {
			return;
		}
		
		$log = 'ONEsite SSO: ';
		
		if (!is_string($msg)) {
			$msg = print_r($msg, true);
		}
		
		if ($type) {
			$log .= "($type) ";
		}
		
		Mage::log($log . $msg);
	}
}
