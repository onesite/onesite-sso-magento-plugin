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
 * Handles logic related to an Element.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Helper_Elements extends Mage_Core_Helper_Abstract
{
	/**
	 * Get the JS embed code for an element.
	 * 
	 * @param string $el_name The element name.
	 * @param array  $params  Per instance parameters for the widget.
	 * @param array  $options Options for how we want to load the widget. 
	 *
	 * @return string
	 */
	public function getElementEmbed($el_name, $params = array(), $options = array())
	{
		$id = isset($options['id']) ? $options['id'] : 'oneElementPlaceholder';
		$node_dom = Mage::getStoreConfig('sso/options/node_domain');
		$widgetkey = Mage::helper('sso/api')->getWidgetkey();
		$partner_id = Mage::getStoreConfig('sso/options/partner_id');
		$config_id = isset($options['config_id']) ? $options['config_id'] : '';
		
		$custom_url = Mage::getStoreConfig('sso/options/element_url');
		$base_url = !empty($custom_url) ? $custom_url : "http://elements.onesite.com";
		Mage::log("BaseURL is $base_url");
		
		$jsElementOptions = '';

		if (is_array($params) && !empty($params)) {
			$i = 1;
			foreach ($params as $name => $value) {
				$jsElementOptions .= "$name : '$value'";
				if ($i != count($params)) {
					$jsElementOptions .= ",\n";
				}
				$i = $i + 1;
			}
		}
		
		$html = <<<EOT
  <div id="{$id}"></div>
  <script type="text/javascript">
    if (typeof ONELOADER == 'undefined' || !ONELOADER) {
      var ONELOADER = [];
      (function() {
        var e = document.createElement('script'); e.type = 'text/javascript'; e.async = true;
        e.src = '{$base_url}/resources/scripts/utils/widget.js?ver=1';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(e);
      }());
    }
    ONELOADER.push(function () {
      ONESITE.Widget.load(
        '{$id}',
        '{$el_name}',
        {
          // DO NOT CHANGE THESE:
          one_widget_node : '{$node_dom}',
          devkey          : '{$widgetkey}',
          partner         : '{$partner_id}',
          config_id       : '{$config_id}',
          // CHANGE THESE PER PAGE:
{$jsElementOptions}
        },
        {
          WIDGET_BASE_URL : '{$base_url}',
        }
      );
    });
</script>
EOT;
		return $html;
	}
}