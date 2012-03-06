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
 * A simple 'Test' controller'
 *
 * @package ONEsite
 * @module SSO
 *
 * @author Andrew Kenney <akenney@onesite.com>
 */
class ONEsite_SSO_TestController extends Mage_Core_Controller_Front_Action
{
	/**
	 * The index landing action.
	 * 
	 * @return void
	 */
	public function indexAction()
	{
		$session = $this->getResponse();
	}
	
	/**
	 * Another action.
	 * 
	 * @return void
	 */
	public function sweetAction()
	{
		
	}
}