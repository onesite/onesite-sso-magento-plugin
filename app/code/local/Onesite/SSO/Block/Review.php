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
 * Handles loading the 'Review' widget.
 *
 * @package ONEsite
 * @module SSO
 *
 * @author  Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Block_Review
	extends Mage_Core_Block_Abstract
	implements Mage_Widget_Block_Interface
{
	/**
	 * Load the ONEsite Review widget.
	 *
	 * @return string
	 */
	protected function _toHtml()
	{
		Mage::log("Loading Review widget");
		$label_text = $this->getLabelText();
		if (!$label_text) {
			$label_text = 'Review Widget';
		}
		
		$html = "<div class='onesiteSocialLogin'>{$label_text}</div>";
		
		$sku = Mage::helper('sso/product')->getUniqueKey();
		
		$params = array(
			'type'   => 'product',
			'xrefID' => $sku,
			//'catalog' => Mage::helper('sso/api')->getNodeDomain(),
		);
		
		$widget_html = Mage::helper('sso/elements')->getElementEmbed('review/index', $params);
		$html .= $widget_html;
		
		return $html;
	}
}
