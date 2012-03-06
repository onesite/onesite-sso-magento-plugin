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
 * Some basic utility methods 
 *
 * @author Andrew Kenney <akenney@onesite.com>
 *
 */
class Onesite_SSO_Helper_Utils extends Mage_Core_Helper_Abstract
{
	/**
	 * Take a URL and append some NVPs.
	 * You can either pass 'bar' as 'foo'.
	 * Or you can pass in the format bar=foo&dude=sweet.
	 * 
	 * This method will make sure to use the correct ? or & depending
	 * on if the URL already has NVPs.
	 *
	 * @param string $url                  The current URL.
	 * @param string $nvp_entirety_or_name Single name or set of NPVs.
	 * @param string $nvp_value            Single value.
	 * 
	 * @return string
	 */
	public function appendNVP($url, $nvp_entirety_or_name, $nvp_value = '')
	{
		// If they passed in a key and a value.
		if ($nvp_entirety_or_name && $nvp_value) {
			$nvp = $nvp_entirety_or_name . '=' . $nvp_value;
		} else {
			$nvp = $nvp_entirety_or_name;
		}
		
		if (!strstr($url, '?')) {
			$url .= '?' . $nvp;
		} else {
			$url .= '&' . $nvp;
		}
		
		return $url;
	}
	
	/**
	 * Let's make a random password.
	 * Ignoring common mistyped chars such as 0/o, l/1
	 * 
	 * @param int $length Number of characters we want.
	 * 
	 * @return string
	 */
	public static function generateRandom($length = 8)
	{
		$chars = "abcdefghijkmnpqrstuvwxyz23456789";
		srand((double) microtime() * 1000000);
		
		$i = 0;
		$pass = '' ;
		
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
}