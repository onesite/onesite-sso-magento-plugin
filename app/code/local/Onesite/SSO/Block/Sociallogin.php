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
 * Handles 'Social Login'
 *
 * @package ONEsite
 * @module SSO
 *
 * @author  Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Block_Sociallogin
	extends Mage_Core_Block_Abstract
	implements Mage_Widget_Block_Interface
{
	/**
	 * Load the ONEsite Social Login widget.
	 *
	 * @return string
	 */
	protected function _toHtml()
	{
		if (Mage::getSingleton('customer/session')->isLoggedIn()
			|| Mage::app()->getStore()->isAdmin()
		) {
			return '';
		}
		
		Mage::log("Loading Social Login widget");
		
		$size = $this->getSize();
		$view = $this->getView();
		$on_dom_ready = $this->getOnDomReady();
		$label_text = $this->getLabelText();
		$reload_widget = $this->getReloadWidget();
		
		error_log("Label Text: " . (string) $label_text);
		error_log("Reload widget?" . (string) $reload_widget);
		
		if ($on_dom_ready == 'openmodal') {
			$js_dom_ready_func = 'oneSocialLogin.init';
		} elseif (!empty($on_dom_ready)) {
			$js_dom_ready_func = $on_dom_ready;
		}
		
		if ($on_dom_ready) {
$js_dom_ready = <<<EOT
<script type='text/javascript'>
	document.observe('dom:loaded', function () { 
		{$js_dom_ready_func}();
	});
</script>
EOT;
			$html = $js_dom_ready;
		} else {
			$html = '';
		}
		
		if ($label_text) {
			$html .= "<div class='oneSocialLoginLabel'>$label_text</div><a href='#' onclick='javascript: return oneSocialLogin.init();'>Login</a>";
		}
		
		if ($reload_widget) {
			$params = array(
				'load_profile' => 'true',
				'view'         => $view,
				'js_callback'  => 'one_soclogin_callback',
			);
			
			$widget_html = Mage::helper('sso/widgets')->getWidgetEmbed('socialLogin/display', $params);
			$html .= $widget_html;
		}
		
		return $html;
	}
}
