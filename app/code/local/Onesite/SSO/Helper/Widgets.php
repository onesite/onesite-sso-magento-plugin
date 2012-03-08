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
 * Handles logic related to an Widget.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Helper_Widgets extends Mage_Core_Helper_Abstract
{
	/**
	 * Get the JS embed code for an element.
	 * 
	 * @param string $widget_name The element name.
	 * @param array  $params      Per instance parameters for the widget.
	 * @param array  $options     Options for how we want to load the widget. 
	 *
	 * @return string
	 */
	public function getWidgetEmbed($widget_name, $params = array(), $options = array())
	{
		$id = isset($options['id']) ? $options['id'] : 'oneElementPlaceholder';
		$node_dom = Mage::getStoreConfig('sso/options/node_domain');
		$widgetkey = Mage::helper('sso/api')->getWidgetkey();
		$partner_id = Mage::getStoreConfig('sso/options/partner_id');
		
		$custom_url = Mage::getStoreConfig('sso/options/widget_url');
		$base_url = !empty($custom_url) ? $custom_url : "http://widgets.onesite.com";
		$config_id = isset($options['config_id']) ? $options['config_id'] : '';
		$callback_url = Mage::getUrl('onesite/integration/connection');
		
		$jsWidgetOptions = '';

		if (is_array($params) && !empty($params)) {
			$i = 1;
			foreach ($params as $name => $value) {
				$jsWidgetOptions .= "var $name = '$value';\n";
				$i = $i + 1;
			}
		}
		
		$urlAppendParams = http_build_query($params);
		
		$html = <<<EOT
<script type='text/javascript'>
  // CHANGE THESE DYNAMICALLY ON EVERY PAGE.
{$jsWidgetOptions}

  // DO NOT CHANGE BELOW THIS LINE.
  var widgetURL = "{$base_url}/js/{$widget_name}?one_widget_node={$node_dom}&devkey={$widgetkey}&partner={$partner_id}&config_id={$config_id}&{$urlAppendParams}&callback_url={$callback_url}";
  // Load the widget.
  document.write('<script type="text/javascript" src="', widgetURL, '"><\/script>');
</script>
EOT;
		return $html;
	}
}