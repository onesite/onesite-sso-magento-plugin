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
 * Generic ONEsite API that serves as the base class for each
 * individual API.
 *
 * @author  Mike Benshoof <mbenshoof@onesite.com>
 */
class onesite_sdk_api
{
	/**
	 * The ONEsite client object that has raw connecitons.
	 *
	 * @var onesite_sdk_client
	 */
	protected $_client;
	
	/**
	 * Just store raw client.
	 *
	 * @param onesite_sdk_client $client
	 *
	 * @return void
	 */
	public function __construct(onesite_sdk_client $client)
	{
		$this->setClient($client);
	}
	
	/**
	 * Get the raw client.
	 *
	 * @return onesite_sdk_client
	 */
	public function getClient()
	{
		return $this->_client;
	}
	
	/**
	 * Set the raw client.
	 *
	 * @return onesite_sdk_api
	 */
	public function setClient(onesite_sdk_client $client)
	{
		$this->_client = $client;
		return $this;
	}
	
	/**
	 * Log an API exception to the proper file.
	 *
	 * @param string            $method The method throwing the error
	 * @param onesite_exception $e      The actual exception
	 * @param string            $file   Custom file name
	 *
	 * @return void
	 */
	protected function _logException($method, $e, $file = "api.log")
	{
		$msg = "Caught exception in [$method]: ";
		$msg .= "Error [{$e->getCode()}] - {$e->getMessage()}";
		onesite_sdk::debugLog($msg, $file);		
	}
}
