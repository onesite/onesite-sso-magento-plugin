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
 * Handles events in Magento.
 *
 * @author Andrew Kenney <akenney@onesite.com>
 * @author Mark Manos <mmanos@onesite.com>
 */
class Onesite_SSO_Model_Observer
{
	/**
	 * Run ONEsite checks before a new customer is added.
	 * 
	 * Added to event: customer_save_before
	 * 
	 * @param mixed $observer The Magento observer object.
	 *
	 * @return void
	 */
	public function addRegisterHook($observer)
	{
	}
	
	/**
	 * Run ONEsite checks before the Magento session
	 * for a user is initialized.
	 * 
	 * Added to event: customer_session_init
	 * 
	 * @param mixed $observer The Magento observer object.
	 *
	 * @return void
	 */
	public function addSessionInitHook($observer)
	{
	}
	
	/**
	 * Run any federated logout logic.
	 * 
	 * @param mixed $observer The magento observer object.
	 *
	 * @return void
	 */
	public function addLogoutHook($observer)
	{
		self::debug("Local logout detected");
		
		if (!$onesite_session = $this->_getLoggedInOnesiteUser('session')) {
			self::debug("No ONEsite session found");
			return;
		}
		
		try {
			$worked = Mage::helper('sso/sdk')->get()->getSessionApi()->logout(
				$onesite_session
			);
			if (!$worked) {
				self::debug("Error logging out user #" . $onesite_session->user->id);
			}
		} catch (Exception $e) {
			self::debug("issue with logout: " . $e->getMessage());
		}
		
		self::debug("User {$onesite_session->user->id} has been logged out");
	}
	
	/**
	 * ONEsite federated SSO flow.
	 * 
	 * Added to event: controller_action_layout_load_before
	 * 
	 * @param mixed $observer The Magento observer object.
	 *
	 * @return void
	 */
	public function addControllerActionLayoutLoadBeforeHook($observer)
	{
		self::debug("Magento action layout load");
		
		if (Mage::app()->getStore()->isAdmin()) {
			self::debug("In admin section, so bailing...");
			return;
		}
		
		$is_logged_in = false;
		if (Mage::getSingleton('customer/session')->isLoggedIn()) {
			$is_logged_in = true;
		}
		
		// Fetch session id if we do not have one.
		if (empty($_COOKIE['core_u'])
			&& empty($_REQUEST['keymaster_landing'])
			&& empty($_REQUEST['skip_keycheck'])
		) {
			$redirect_url = 'http://' . $_SERVER['HTTP_HOST'];
			$origin_url = $redirect_url . '?skip_keycheck=1&keymaster_landing=1';
			
			try {
				$redir_url = Mage::helper('sso/sdk')->get()->getSessionApi()->joinCrossDomain(
					$origin_url,
					''
				);
			} catch (onesite_exception $e) {
				self::debug("issue with session check keymaster: " . $e->getMessage());
				return;
			}
			
			self::debug('ONEsite SSO: about to redir to: ' . $redir_url);
			header('Location: ' . $redir_url);
			exit;
		}
		
		// Handle keymaster redir landing.
		if (!empty($_REQUEST['keymaster_landing'])) {
			self::debug("keymaster landing");
			
			if (empty($_GET['oned'])) {
				self::debug("error getting oned");
				return;
			}
			
			$tmp_parts = explode(',', base64_decode($_GET['oned']));
			$parts = array();
			foreach ($tmp_parts as $tmp_part) {
				$tmp_part_parts = explode('=', $tmp_part);
				$parts[$tmp_part_parts[0]] = $tmp_part_parts[1];
			}
			
			if (empty($parts['core_u'])) {
				self::debug("Error getting core_u");
				return;
			}
			
			self::debug("Setting sso cookies");
			
			setcookie('core_u', $parts['core_u'], 2147483647, '/');
			setcookie('core_x', $parts['core_x'], 2147483647, '/');
			header("Location: /");
			exit;
		}
		
		// Perform session check to look for change in session state.
		try {
			$session = Mage::helper('sso/sdk')->get()->newSession();
			$session_api = Mage::helper('sso/sdk')->get()->getSessionApi();
			
			$session->coreU = $_COOKIE['core_u'];
			$session->coreX = $_COOKIE['core_x'];
			
			$worked = $session_api->check($session);
			
			if (!$worked) {
				throw new onesite_exception('Invalid sessioncheck response returned');
			}
		} catch (onesite_exception $e) {
			self::debug('session check error: ' . $e->getMessage());
			return;
		}
		
		self::debug("Session check done");
		
		// Is session no longer valid?
		if (!$session->isValid()) {
			self::debug('onesite session is invalid');
			
			// Remove session id so a new valid one may be fetched.
			setcookie('core_u', 'deleted', time() - 3600, '/');
			setcookie('core_x', 'deleted', time() - 3600, '/');
		} else if ($session->user && !$is_logged_in) {
			self::debug('User is logged in on ONEsite. try to log them in locally');
			
			$this->_loggedInUserDetected($session->user);
			Mage::register('onesite_uid', $session->user->id);
		} else if (!$session->user && $is_logged_in) {
			self::debug('User no longer logged in on ONEsite. Try to log them out locally');
			
			// Log out user locally.
			Mage::getSingleton('customer/session')->logout();
			
			// Remove session id so a new valid one may be fetched.
			setcookie('core_u', 'deleted', time() - 3600, '/');
			setcookie('core_x', 'deleted', time() - 3600, '/');
		} else if (!$session->user && !$is_logged_in) {
			self::debug("User is not logged into Magento or ONEsite");
			Mage::register('onesite_uid', 0);
		} else {
			self::debug('User is logged into Magento and ONEsite');
			Mage::register('onesite_uid', $session->user->id);
		}
	}
	
	/**
	 * Run ONEsite checks when the routes have been initialized.
	 * 
	 * Added to event: controller_front_init_routers
	 * 
	 * @param mixed $observer The Magento observer object.
	 *
	 * @return void
	 */
	public function addRouteHook($observer)
	{
		self::debug("Magento route init hook");
	}
	
	/**
	 * We've detected a logged in user so make sure they are synced
	 * up in both systems.
	 * 
	 * @param User $onesite_user The ONEsite user object.
	 * 
	 * @throws Exception
	 *
	 * @return void
	 */
	private function _loggedInUserDetected($onesite_user)
	{
		// Return if already logged in.
		if (Mage::getSingleton('customer/session')->isLoggedIn()) {
			self::debug("Already logged into magento so bailing");
			return;
		}
		
		if (!$onesite_user) {
			self::debug("Bad onesite user");
			return;
		}
		
		// Do we have a local user already linked to this ONEsite user?
		self::debug("Trying to login user {$onesite_user->id}...");
		$ext_acct = Mage::helper('sso/sdk')->get()->newExternalAccount();
		$ext_acct->providerName = 'magento-' . Mage::helper('sso/api')->getUniqueSiteId();
		Mage::helper('sso/sdk')->get()->getUserApi()->getExternalAccount($onesite_user, $ext_acct);
		
		// Log in matching user.
		if ($ext_acct->userIdentifier) {
			self::debug("Logging into uid $uid");
			Mage::getSingleton('customer/session')->loginById($ext_acct->userIdentifier);
			return;
		}
		
		// Are there any matching local users with the ONEsite user email address?
		$matching_user = Mage::getModel('customer/customer');
		$matching_user->website_id = Mage::app()->getWebsite()->getId();
		$matching_user->loadByEmail($onesite_user->email);
		self::debug('Just searched for magento user by email: ' . $onesite_user->email);
		
		// Create new local user if no matching user found.
		if (!$matching_user->getId()) {
			$matching_user = Mage::getModel('customer/customer');
			$matching_user->website_id    = Mage::app()->getWebsite()->getId();
			$matching_user->email         = $onesite_user->email;
			$matching_user->password_hash = md5(Mage::helper('sso/utils')->generateRandom());
			$matching_user->firstname     = $onesite_user->name;
			//$matching_user->lastname      = $onesite_user->profile->lastName;
			$matching_user->setConfirmation(null);
			self::debug("Attempting to make a new user with email ". $onesite_user->email);
			
			try {
				$matching_user->save();
				if (!$matching_user->getId()) {
					throw new Exception('Error creating user');
				}
			} catch (Exception $e) {
				self::debug('ONEsite SSO: create magento user error: ' . $e->getMessage());
				return;
			}
			self::debug('ONEsite SSO: new magento user created: ' . $matching_user->getId());
		}
		
		// Link new user to ONEsite user.
		$ext_acct->userIdentifier = $matching_user->getId();
		Mage::helper('sso/sdk')->get()->getUserApi()->addExternalAccount($onesite_user, $ext_acct);
		
		// Log in matching user.
		Mage::getSingleton('customer/session')->loginById($matching_user->getId());
	}
	
	/**
	 * Get the ONEsite userID if they are indeed logged in.
	 * 
	 * @param string $which Return user, session or id;
	 *
	 * @return onesite_sdk_dao_user
	 */
	private function _getLoggedInOnesiteUser($which = 'user')
	{
		if ($onesite_uid = Mage::registry('onesite_uid')) {
			return $onesite_uid;
		}
		
		if (empty($_COOKIE['core_u'])) {
			return false;
		}
		
		// Perform session check to look for change in session state.
		try {
			$session = Mage::helper('sso/sdk')->get()->newSession();
			$session_api = Mage::helper('sso/sdk')->get()->getSessionApi();
			
			$session->coreU = $_COOKIE['core_u'];
			$session->coreX = $_COOKIE['core_x'];
			
			$worked = $session_api->check($session);
			
			if (!$worked) {
				throw new onesite_exception('Invalid sessioncheck response returned');
			}
		} catch (onesite_exception $e) {
			self::debug('ONEsite SSO: session check error: ' . $e->getMessage());
			return;
		}
		
		if (!$session->isValid()) {
			return false;
		}
		
		switch ($which) {
			case 'session':
				return $session;
				break;
			case 'user':
				return $session->user;
				break;
			case 'id':
				return $session->user->id;
				break;
		}
		
		return $session->user;
	}
	
	/**
	 * Perform some debugging/logging.
	 * 
	 * @param mixed  $msg  What we want to log.
	 * @param string $type The subsystem.
	 *
	 * @return void
	 */
	public static function debug($msg, $type = null)
	{
		if (!Mage::getStoreConfig('sso/options/enable_debugging')) {
			return;
		}
		
		$log = 'ONEsite SSO: ';
		
		if (!is_string($msg)) {
			$msg = print_r($msg, true);
		}
		
		if ($type) {
			$log .= "($type) ";
		}
		
		Mage::log($log . $msg);
	}
}