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
 * An integration controller that performs actions such
 * as generated the header/footer for interface
 * unification across multiple sites.
 *
 * @package ONEsite
 * @module SSO
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class ONEsite_SSO_IntegrationController extends Mage_Core_Controller_Front_Action
{
	public function connectionAction()
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Loading...</title>
	</head>
	
	<body>
		<script type="text/javascript" src="http://fast1.onesite.com/resources/scripts/connection/receiver.js"></script>
	</body>
</html>';
	}
	
	/**
	 * Get the header of the page.
	 *
	 * @return void
	 */
	public function headerAction()
	{
		$block       = Mage::getSingleton('core/layout');
		$headBlock   = $block->createBlock('page/html_head');

                // Add some required JS.
		$headBlock->addJs('prototype/prototype.js');
		$headBlock->addJs('lib/ccard.js');
		$headBlock->addJs('prototype/validation.js');
		$headBlock->addJs('scriptaculous/builder.js');
		$headBlock->addJs('scriptaculous/effects.js');
		$headBlock->addJs('scriptaculous/dragdrop.js');
		$headBlock->addJs('scriptaculous/controls.js');
		$headBlock->addJs('scriptaculous/slider.js');
		$headBlock->addJs('varien/js.js');
		$headBlock->addJs('varien/form.js');
		$headBlock->addJs('varien/menu.js');
		$headBlock->addJs('mage/translate.js');
		$headBlock->addJs('mage/cookies.js');
                
                // Add some required styles.
                $headBlock->addCss('css/styles.css');
		$headBlock->getCssJsHtml();
		$headBlock->getIncludes();
		
		$headerBlock = $block->createBlock('page/html_header')->setTemplate('page/html/header.phtml')->toHtml();
		
		$headerTagContents = $headBlock->toHtml();
$header = <<<EOT
<html>
<head>
	{$headerTagContents}
</head>
<body>
<div class="wrapper">
    <div class="page">
    	{$headerBlock}
EOT;
		echo $header;
	}
	
	/**
	 * Get the footer of a magento page.
	 *
	 * @return void
	 */
	public function footerAction()
	{
		$block = Mage::getSingleton('core/layout');
		
		$footerBlock = $block->createBlock('page/html_footer')->setTemplate('page/html/footer.phtml')->toHtml();

		$footer = <<<EOT
	{$footerBlock}
	</div>
</div>
</body>
</html>
EOT;
		echo $footer;
	}
}
