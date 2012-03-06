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
 * The 'About' section of the admin configuration section for Identity.
 *
 * @package ONEsite
 * @module SSO
 *
 * @author  Andrew Kenney <akenney@onesite.com>
 */
class Onesite_SSO_Block_About extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
	/**
	 * Render the About block.
	 * 
	 * @param Varien_Data_Form_Element_Abstract $element The form element.
	 * 
	 * @return string
	 */
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$html = $this->_getHeaderHtml($element);
		$html .= $this->_getFieldHtml($element);
		$html .= $this->_getFooterHtml($element);
		
		return $html;
	}
	
	/**
	 * Get the HTML for this particular fieldset.
	 * 
	 * @param Varien_Data_Form_Element_Abstract $fieldset The given field.
	 * 
	 * @return string
	 */
	protected function _getFieldHtml($fieldset)
	{
		$version = Mage::getConfig()->getModuleConfig("Onesite_SSO")->version;
		$content = "<div>ONEsite SSO module version: {$version}</div>";
		
		return $content;
	}
}
