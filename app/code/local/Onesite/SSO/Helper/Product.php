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
 * Handles interfaces to Magento products or product pages.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Helper_Product extends Mage_Core_Helper_Abstract
{
	/**
	 * Return the current product being viewed.
	 *
	 * @return void
	 */
	public function getCurrent()
	{
		return Mage::registry('current_product');
	}
	
	/**
	 * Get the unique key for a product, typically its SKU.
	 * 
	 * @return string
	 */
	public function getUniqueKey()
	{
		$product = $this->getCurrent();
		
		if (is_null($product)) {
			return false;
		}
		
		$sku = $product->getSku();
		Mage::log("Got SKU: $sku");
		
		return $sku;
	}
}
