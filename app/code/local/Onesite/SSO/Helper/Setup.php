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
 * Assists with setting up the Widgets.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Helper_Setup extends Mage_Core_Helper_Abstract
{
	/**
	 * Run a ONEsite API function.
	 * 
	 * @param string $url    The service URL you want to call.
	 * @param array  $params Params to pass to the method.
	 * @param string $method GET or POST.
	 *
	 * @return void
	 */
	public function runIntegrationSetup($url, $params = null, $method = 'POST')
	{
		Mage::log("Running integration setup");
		$url = "http://preprodservices/rest/svcIntegration?action=getInfo&output=json";
		
		$response = Mage::helper('sso/api')->runAPI($url, $params);
		return $response;
		
		
		// Handle appending params to the GET URL.
		if ($method == 'GET' && !empty($params)) {
			if (is_array($params)) {
				$params = http_build_query($params);
			}
			$url = Mage::helper('sso/utils')->appendNVP($url, $params);
		}
		
		// Process the request.
		try {
			$http = new Varien_Http_Client($url);
			
			$version = Mage::getConfig()->getModuleConfig("Onesite_SSO")->version;
			$http->setHeaders(array(
				"X-Requested-With" => "Magento: ONEsite SSO v" . $version,
			));
			
			if ($method == 'POST') {
				$http->setParameterPost($params);
			}
			
			$response = $http->request($method);
			$body = $response->getBody();
			
			try {
				$result = json_decode($body);
			} catch (Exception $e) {
				throw Mage::exception('Mage_Core', $e);
			}

			if ($result) {
				return $result;
			} else {
				throw Mage::exception('Mage_Core', "Could not get result");
			}
		} catch (Exception $e) {
			throw Mage::exception('Mage_Core', $e);
		}
	}
}