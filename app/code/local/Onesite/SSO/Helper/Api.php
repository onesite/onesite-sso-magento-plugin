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
 * Handles calling the ONEsite APIs.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Helper_Api extends Mage_Core_Helper_Abstract
{
	/**
	 * Get the devkey.
	 *
	 * @return void
	 */
	public function getDevkey()
	{
		return Mage::getStoreConfig('sso/options/apikey');
	}
	
	/**
	 * Get the widgetkey.
	 *
	 * @return void
	 */
	public function getNodeDomain()
	{
		return Mage::getStoreConfig('sso/options/node_domain');
	}
	
	/**
	 * Get the widgetkey.
	 *
	 * @return void
	 */
	public function getWidgetkey()
	{
		return Mage::getStoreConfig('sso/options/widgetkey');
	}
	
	/**
	 * Get unique id for this site.
	 *
	 * @return void
	 */
	public function getUniqueSiteId()
	{
		return $_SERVER['HTTP_HOST'];
	}
}
