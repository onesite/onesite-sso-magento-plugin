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
 * Handles 'Link Accounts' element widget.
 *
 * @package ONEsite
 * @module SSO
 *
 * @author  Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Block_LinkAccounts
	extends Mage_Core_Block_Abstract
	implements Mage_Widget_Block_Interface
{
	/**
	 * Load the Link Accounts widget.
	 *
	 * @return string
	 */
	protected function _toHtml()
	{
		$html = "<div class='onesiteSocialLogin'>Social Login</div>";
		
		$params = array(
			'sweet'     => 'dude',
			'hello'     => 'how low?',
			'xref_id'   => 123,
			'xref_type' => 'product',
		);
		
		$elementhtml = Mage::helper('sso/elements')->getElementEmbed('account/linkWidget', $params);
		$html .= $elementhtml;
		
		return $html;
	}
}