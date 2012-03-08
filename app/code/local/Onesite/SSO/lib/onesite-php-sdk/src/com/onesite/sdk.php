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
 * SDK for session interaction with the ONEsite platform.  This
 * can perform basic cookie syncronization, get/set user properties,
 * get user details, validate sessions, and logout.
 *
 * @author Mike Benshoof <mbenshoof@onesite.com>
 * @version 0.1
 */
class onesite_sdk
{
	/**
	 * The master devkey for ONEsite SDK.
	 *
	 * @var string
	 */
	protected $_devkey;
	
	/**
	 * The ONEsite environment to hit.
	 *
	 * @var string
	 */
	protected $_environment;
	
	/**
	 * Master client object.
	 *
	 * @var onesite_sdk_client
	 */
	protected $_client;
	
	/**
	 * The base path for API file inclusion.
	 *
	 * @var string
	 */
	protected $_path;
	
	/**
	 * Should the verbose debugging log be enabled.
	 *
	 * @var boolean
	 */
	protected static $_debug_enabled;
	
	/**
	 * The base directory for the debugging log.
	 *
	 * @var string
	 */
	protected static $_debug_path;
	
	/**
	 * An optional callback function for debugging.
	 *
	 * @var callback
	 */
	protected static $_debug_callback;
	
	/**
	 * Just store the devkey when the object is created.
	 *
	 * @param string $devkey The devkey for network services
	 *
	 * @return void
	 */
	public function __construct($devkey)
	{
		$this->_devkey = $devkey;
		$this->_enviroment = "production";
		
		// Store the path of this file as the basis for autoloading.
		$this->_path = dirname(__FILE__);
		
		// Disable debugging and set the default debugging path.
		$this->disableDebugging();
		$this->setDebugPath($this->_path);
		$this->setDebugCallback(null);
		
		// Run the initial requires.
		require_once($this->_path . "/sdk/api.php");
		require_once($this->_path . "/sdk/dao.php");
		require_once($this->_path . "/sdk/client.php");
		require_once($this->_path . "/exception.php");
		
		// Create an instance of the client.
		$this->_client = new onesite_sdk_client();
		$this->_client->setDevkey($this->_devkey)
			->setEnv($this->_enviroment);
	}
	
	/**
	 * This is a simple way to validate the devkey and will
	 * return an array of useful integration details.
	 *
	 * @return array|boolean
	 */
	public function getIntegrationInfo()
	{
		try {
			return $this->getClient()->callRest(
					"rest/svcIntegration",
					array("action" => "getInfo")
				);
		} catch (onesite_exception $e) { }
		
		return false;		
	}
	
	/**
	 * Wrapper to load the Session API.
	 *
	 * @return onesite_sdk_api_sessionApi
	 */
	public function getSessionApi()
	{
		require_once($this->_path . "/sdk/api/sessionApi.php");
		require_once($this->_path . "/sdk/api/userApi.php");
		require_once($this->_path . "/sdk/dao/session.php");
		require_once($this->_path . "/sdk/dao/user.php");
		
		return new onesite_sdk_api_sessionApi(
				$this->getClient()
			);
	}
	
	/**
	 * Wrapper to fetch a Session DAO.
	 *
	 * @return onesite_sdk_dao_session
	 */
	public function newSession()
	{
		require_once($this->_path . "/sdk/dao/session.php");
		return new onesite_sdk_dao_session();
	}

	/**
	 * Wrapper to load the User API.
	 *
	 * @return onesite_sdk_api_user
	 */
	public function getUserApi()
	{
		require_once($this->_path . "/sdk/api/userApi.php");
		require_once($this->_path . "/sdk/dao/user.php");
		
		return new onesite_sdk_api_userApi(
				$this->getClient()
			);
	}
	
	/**
	 * Wrapper to fetch a User DAO.
	 *
	 * @return onesite_sdk_dao_user
	 */
	public function newUser()
	{
		require_once($this->_path . "/sdk/dao/user.php");
		return new onesite_sdk_dao_user();
	}
	
	/**
	 * Wrapper to fetch a ExternalProperty DAO.
	 *
	 * @return onesite_sdk_dao_externalProperty
	 */
	public function newExternalProperty()
	{
		require_once($this->_path . "/sdk/dao/externalProperty.php");
		return new onesite_sdk_dao_externalProperty();
	}
	
	/**
	 * Wrapper to fetch a ExternalAccount DAO.
	 *
	 * @return onesite_sdk_dao_externalAccount
	 */
	public function newExternalAccount()
	{
		require_once($this->_path . "/sdk/dao/externalAccount.php");
		return new onesite_sdk_dao_externalAccount();
	}

	/**
	 * Wrapper to fetch a Password DAO.
	 *
	 * @return onesite_sdk_dao_password
	 */
	public function newPassword()
	{
		require_once($this->_path . "/sdk/dao/password.php");
		return new onesite_sdk_dao_password();
	}
	
	/**
	 * Wrapper to fetch a Profile DAO.
	 *
	 * @return onesite_sdk_dao_profile
	 */
	public function newProfile()
	{
		require_once($this->_path . "/sdk/dao/profile.php");
		return new onesite_sdk_dao_profile();
	}
	
	/**
	 * Get the raw ONEsite client object.
	 *
	 * @return onesite_sdk_client
	 */
	public function getClient()
	{
		return $this->_client;
	}
	
	/**
	 * Disable the verbose debugging log.
	 *
	 * @return onesite_sdk
	 */
	public function disableDebugging()
	{
		self::$_debug_enabled = false;
		return $this;
	}
	
	/**
	 * Enable the verbose debugging log.
	 *
	 * @param string|callback $debugger
	 *
	 * @return onesite_sdk
	 */
	public function enableDebugging($debugger = null)
	{
		if (is_callable($debugger)) {
			$this->setDebugCallback($debugger);
		} else if (is_string($debugger)) {
			$this->setDebugPath($debugger);
		}
		
		self::$_debug_enabled = true;
		return $this;
	}
	
	/**
	 * Set the base path for the debugging log.
	 *
	 * @param string $path
	 *
	 * @return onesite_sdk
	 */
	public function setDebugPath($path)
	{
		self::$_debug_path = $path;
		return $this;
	}
	
	/**
	 * Set the base path for the debugging log.
	 *
	 * @param callback $cb
	 *
	 * @return onesite_sdk
	 */
	public function setDebugCallback($cb)
	{
		self::$_debug_callback = $cb;
		return $this;
	}
	
	/**
	 * Log a message to a file.  By default, all goe to the
	 * debug.log file, but that can be overwritten.
	 *
	 * @param mixed  $msg  The data to log
	 * @param string $name An optional name to give the file
	 *
	 * @return void
	 */
	public static function debugLog($msg, $name = null)
	{
		// Debugging is disabled, so nothing to do.
		if (!self::$_debug_enabled) {
			return;
		}
		
		$args = func_get_args();
		
		if (!is_null(self::$_debug_callback)) {
			call_user_func_array(
					self::$_debug_callback,
					$args
				);
			return;
		}

		// Set up the data and the filenames for local.		
		if (count($args) == 2) {
			$msg = $args[0];
			$name = $args[1];			
		} else {
			$msg = $args[0];
			$name = "debug.log";
		}
		
		self::_writeDebugLogEntry($msg, $name);
	}
	
	/**
	 * Log a message to a file.  By default, all goe to the
	 * debug.log file, but that can be overwritten.
	 *
	 * @param mixed  $msg  The data to log
	 * @param string $name A name to give the file
	 *
	 * @return void
	 */
	protected static function _writeDebugLogEntry($msg, $name)
	{
		$filename = self::$_debug_path . "/" . $name;
		
		if (!file_exists($filename)) {
			if (!touch($filename)) {
				return false;
			}	
			
			chmod($filename, 0755);
		}
		
		if (is_string($msg)) {
			$msg .= "\n";
		} else {
			$msg = print_r($msg, true);
		}
		
		@file_put_contents($filename, $msg, FILE_APPEND);
	}
	
}
