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
 * Generic ONEsite client that contains clients to various
 * services.
 *
 * @author Mike Benshoof <mbenshoof@onesite.com>
 */
class onesite_sdk_client
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
	 * The base service URL.
	 *
	 * @var string
	 */
	protected $_url;
	
	/**
	 * The raw REST client for interaction with ONEsite.
	 *
	 * @var onesite_util_client_rest
	 */
	protected $_restClient;
	
	/**
	 * Get the current environment.
	 *
	 * @return string
	 */
	public function getEnv()
	{
		return $this->_environment;
	}
	
	/**
	 * Set the current environment.
	 *
	 * @return onesite_sdk_client
	 */
	public function setEnv($env)
	{
		switch ($env) {
			
			case "ote":
				$this->setUrl("https://oteservices.onesite.com");
				break;
			case "preprod":
				$this->setUrl("http://preprodservices");
				break;
			case "production":
			default:
				$env = "production";
				$this->setUrl("https://services.onesite.com");
				break;
		}		
		
		$this->_environment = $env;
		return $this;
	}
	 
	/**
	 * Get the current devkey.
	 *
	 * @return string
	 */
	public function getDevkey()
	{
		return $this->_devkey;
	}
	
	/**
	 * Set the current devkey.
	 *
	 * @return onesite_sdk_client
	 */
	public function setDevkey($devkey)
	{
		$this->_devkey = $devkey;
		return $this;
	}
	
	/**
	 * Get the current base url.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->_url;
	}
	
	/**
	 * Set the current base url.
	 *
	 * @return onesite_sdk_client
	 */
	public function setUrl($url)
	{
		$this->_url = $url;
		
		// Make sure we have SSL enabled, or update the URL.
		if (!extension_loaded("openssl")) {
			$tmp = $this->_url;
			$this->_url = str_replace("https://", "http://", $this->_url);
			
			if ($tmp !== $this->_url) {
				onesite_sdk::debugLog("Updated URL due to no SSL module: $tmp -> {$this->_url}");
			}
		}
		
		return $this;
	}
	
	/**
	 * Get the current auth tokens.
	 *
	 * @return mixed
	 */
	public function getAuth()
	{
		return null;
	}
	
	/**
	 * Set the current auth tokens.
	 *
	 * @return onesite_sdk_client
	 */
	public function setAuth()
	{
		// TODO:  Set the auth based on the func args.
		return $this;
	}
	
	/**
	 * Convert the raw arguments and path into a full RESTful
	 * service call to ONEsite.
	 *
	 * @param string $path The base service path
	 * @param array  $data The array of parameters to pass to the service
	 *
	 * @return mixed
	 */
	public function callRest($path, array $data = array())
	{
		// Create the rest client on demand if needed.
		if (is_null($this->_restClient)) {
			require_once(dirname(__FILE__) . "/../util/client/rest.php");
			$this->_restClient = new onesite_util_client_rest();
		}
		
		// Determine if this is a legacy call.
		if (substr($path, 0, 4) === "rest") {
			$legacy = true;
									
			if (!array_key_exists("action", $data) || $data['action'] == "") {
				throw new onesite_exception("No action specified for legacy REST call.");
			}
			
			$data['output'] = "json";
			
		} else {
			$legacy = false;
		}
		
		// Merge the devkey in with the existing params.		
		$params = array_merge(array(
			'devkey' => $this->_devkey,
		), $data);
		$query = '?' . http_build_query($params, null, '&');
		$url = $this->getUrl() . "/$path$query";
		
		try {
			$resp = $this->_restClient->call($url);

			// Validate/prepare the response based on the type.
			if ($legacy) {
				return $this->_parseLegacyRestResponse($resp);
			} else {
				return $this->_parseRestResponse($resp);
			}
			

		} catch (onesite_exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Parse the response from the current ONEsite REST services.
	 *
	 * @param array $response
	 *
	 * @return mixed
	 */
	protected function _parseRestResponse($response)
	{
		if (!array_key_exists("code", $response)) {
			throw new onesite_exception("Status code not found.");
		}

		if ((int) $response['code'] !== 100) {

			throw new onesite_exception(
					$response['message'],
					$response['code']
				);
		}

		return $response['content'];		
	}
	
	/**
	 * Parse the response from the legacy ONEsite REST services.
	 *
	 * @param array  $response The array of response data
	 * @param string $action   The action run
	 *
	 * @return mixed
	 */
	protected function _parseLegacyRestResponse($response)
	{
		//$response = $response[$action];
		
		if (!isset($response['code'])) {
			$response['code'] = 0;
		}
		
		if (1 != $response['code']) {
			throw new onesite_exception(
				"Service error: " . $response['message'],
				$response['code']
			);
		}

		if (array_key_exists("item", $response)) {
			return $response['item'];
		} else {
			return null;
		}		
	}
}
