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
 * Handles Adminhtml models.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Adminhtml_Model_Field extends Mage_Core_Model_Config_Data
{
	/**
	 * Fetch info from devkey if it has changed. Save info so we can
	 * update the other fields later.
	 * 
	 * @return void
	 */
	public function _beforeSave()
	{
		if ('apikey' != $this->getData('field')) {
			return;
		}
		if (!$this->isValueChanged()) {
			return;
		}
		
		$devkey = $this->getValue();
		
		try {
			$sdk = Mage::helper('sso/sdk')->get(); 
			$response = $sdk->getClient()->callRest(
				'rest/svcIntegration',
				array(
					'action' => 'getInfo',
					'devkey' => $devkey,
					'output' => 'json',
				)
			);
		} catch (Exception $e) {
			Mage::throwException('Error fetching devkey information. Please try again.');
		}
		
		Mage::register('onesite_sso_integration', $response);	
	}
	
	/**
	 * If devkey info has been fetched, update each appropriate field with
	 * fetched value.
	 * 
	 * @return void
	 */
	public function _afterSave()
	{
		if ('apikey' == $this->getData('field')) {
			return;
		}
		if ($this->isValueChanged()) {
			return;
		}
		$response = Mage::registry('onesite_sso_integration');
		if (!$response) {
			return;
		}
		
		$field = $this->getData('field');
		
		switch ($field) {
			case 'widgetkey':
				$value = $response['widget_devkeys'][0]['devkey'];
				Mage::getModel('core/config')->saveConfig('sso/options/widgetkey', $value);
				break;
				
			case 'node_domain':
				$value = $response['domain'];
				Mage::getModel('core/config')->saveConfig('sso/options/node_domain', $value);
				break;
				
			case 'node_id':
				$value = $response['node_id'];
				Mage::getModel('core/config')->saveConfig('sso/options/node_id', $value);
				break;
				
			case 'partner_id':
				$value = $response['partner_id'];
				Mage::getModel('core/config')->saveConfig('sso/options/partner_id', $value);
				break;
				
			case 'widget_url':
				$value = 'http://widgets.' . $response['domain'];
				Mage::getModel('core/config')->saveConfig('sso/options/widget_url', $value);
				break;
				
			case 'element_url':
				$value = 'http://elements.' . $response['domain'];
				Mage::getModel('core/config')->saveConfig('sso/options/element_url', $value);
				break;
		}
	}
}
