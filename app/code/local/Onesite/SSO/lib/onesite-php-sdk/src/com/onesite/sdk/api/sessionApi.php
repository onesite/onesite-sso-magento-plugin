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
 * ONEsite API functions for the Session controller.  This API is
 * designed to handle cross site Single Sign On, session authentication,
 * etc.
 *
 * Sample:
 *
 * $sdk = new onesite_sdk("mydevkey");
 * $session = $sdk->getSession();
 * $user_id = $session->check("session_id", "session_sec");
 *
 * @author  Mike Benshoof <mbenshoof@onesite.com>
 */
class onesite_sdk_api_sessionApi extends onesite_sdk_api
{
	/**
	 * Default expiration time of 1 day.
	 *
	 * @var integer
	 */
	const EXPIRES = 86400;	

	/**
	 * Validate a ONEsite session and security token. If 
	 * valid a populated User object is returned otherwise
	 * an anonymous User object.
	 *
	 * @param onesite_sdk_dao_session $session
	 *
	 * @return boolean
	 */
	public function check(&$session)
	{
		// Get the client IP if none is passed.
		if (is_null($session->ip)) {
			$session->ip = $_SERVER['REMOTE_ADDR'];
		}
		
		// Use the local agent if none is passed.
		if (is_null($session->agent)) {
			$session->agent = $_SERVER['HTTP_USER_AGENT'];
		}
		
		$params = array(
			'core_u'     => $session->coreU,
			'core_x'     => $session->coreX,
			'client_ip'  => $session->ip,
			'agent'      => $session->agent,		
		);
		
		$path = "1/session/check.json";
		
		try {
			$resp = $this->_client->callRest($path, $params);
			$session->loadProperties($resp['session']);
			return true;			
		} catch (onesite_exception $e) {
			$this->_logException(
					"session.check",
					$e,
					"session.log"
				);
		}
		
		return false;
	}
	
	/**
	 * Creates a new session. If the User object is null or not populated with
	 * a id, username or email then an Anonymous session will be created. 
	 *
	 * @param onesite_sdk_dao_session $session
	 *
	 * @return void
	 */
	public function create(&$session)
	{
		//TODO: Implement this service call.
		return false;	
	}
	
	/**
	 * Creates a redirect url to forward the User to in order to 
	 * establish a Cross Domain session.
	 *
	 * @param onesite_sdk_dao_user $user         User object to create session for
	 * @param string               $callback_url URL to send the user to after redircect
	 * @param string               $ip           The user's IP address
	 * @param integer              $expires      Seconds until session expires
	 *
	 * @return string
	 */
	public function createCrossDomain($user, $callback_url, $ip = null, $expires = null)
	{
		//TODO: Implement this service call.
		return false;	
	}

	/**
	 * Get the ONEsite keymaster redirection URL.  The origin is the local target
	 * that will parse the response and handle the SSO flow.
	 *
	 * @param string $callback The local target to parse the keymaster flow
	 * @param string $domain   The domain serving as the overlord
	 * @param string $ip       The remote client's IP
	 * @param string $agent    The remote user agent
	 *
	 * @return string
	 */
	public function joinCrossDomain($callback, $domain, $ip = null, $agent = null)
	{
		// Get the client IP if none is passed.
		if (is_null($ip)) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		// Use the local agent if none is passed.
		if (is_null($agent)) {
			$agent = $_SERVER['HTTP_USER_AGENT'];
		}
		
		// We have to have a valid user agent.
		if (is_null($agent) || $agent == "") {
			return false;
		}
		
		$params = array(
			'domain'       => $domain,
			'client_ip'    => $ip,
			'agent'        => $agent,
			'callback_url' => $callback,		
		);
		
		$path = "1/session/joinCrossDomain.json";
		
		try {
			$response = $this->_client->callRest($path, $params);
						
			if (array_key_exists("redirect_url", $response)) {
				return $response['redirect_url'] . "&nocrypt=1&allow_redir=1";
			}
			
		} catch (onesite_exception $e) {
			$this->_logException(
					"session.joinCrossDomain",
					$e,
					"session.log"
				);
		}
		
		return false;
	}

	/**
	 * Authenticate a User and generate an active Session.
	 *
	 * @param onesite_sdk_dao_user $user     User object to login
	 * @param string               $password The user's password
	 * @param integer              $expires  Seconds until session expires
	 *
	 * @return onesite_sdk_dao_session
	 */
	public function login($user, $password, $expires = null)
	{
		//TODO: Implement this service call.
		return false;	
	}

	/**
	 * Authenticate a User and returns a URL to redirect them to
	 * so an active Session can be generated
	 *
	 * @param onesite_sdk_dao_user $user         User object to create session for
	 * @param string               $password     The user's password
	 * @param string               $callback_url URL to send the user to after redircect
	 * @param string               $ip           The user's IP address
	 * @param integer              $expires      Seconds until session expires
	 *
	 * @return string
	 */
	public function loginCrossDomain($user, $password, $callback_url, $ip = null, $expires = null)
	{
		//TODO: Implement this service call.
		return false;
	}

	/**
	 * Logout a given user.
	 *
	 * @param onesite_sdk_dao_session $session
	 *
	 * @return boolean
	 */
	public function logout($session)
	{		
		try {
			
			$params = array();
			
			// Cascade through the most optimal variables.
			if ($session->user->id > 0) {
				$params['user_id'] = $session->user->id;
			} else if ($session->user->username !== "") {
				$params['username'] = $session->user->username;
			} else if ($session->user->email !== "") {
				$params['email'] = $session->user->email;
			} else if ($session->coreU !== "") {
				$params['core_u'] = $session->coreU;
			} else {
				throw new onesite_exception(
						"Missing Session.logout User identifier"
					);
			}
			
			$path = "1/session/logout.json";			
			$response = $this->_client->callRest($path, $params);

		} catch (onesite_exception $e) {
			$this->_logException(
					"session.logout",
					$e,
					"session.log"
				);
			return false;
		}
		
		return true;
	}
}
