<?php

/**
 * Simple REST client to handle interaction with ONEsite services
 * and parse/validate the results.
 *
 * @author  Mike Benshoof <mbenshoof@onesite.com>
 */
class onesite_util_client_rest
{
	/**
	 * Make the remote call, handle the output of the call, and parse
	 * according to the type of output.
	 *
	 * @param string $url    The URL to append
	 * @param string $output The type of raw ouptut to expect
	 *
	 * @return array
	 */
	public function call($url)
	{
		onesite_sdk::debugLog("RESTful call to: $url", "rest.log");
		
		try {
			$raw = @file_get_contents($url);
			$response = $this->_parseJson($raw);
						
			if (!$raw || is_null($response) || !is_array($response)) {
				throw new onesite_exception('Error retrieving REST output');
			}
		} catch (onesite_exception $e) {
			throw new onesite_exception(
				"Error parsing REST response: {$e->getMessage()}"
			);
		}
		
		return $response;
	}
	
	/**
	 * Parse the JSON string that is returned from the service
	 * output.
	 *
	 * @param string $raw
	 *
	 * @return array
	 */
	protected function _parseJson($raw)
	{
		return json_decode($raw, true);		
	}
	
	/**
	 * Parse the XML string that is returned from the service
	 * output.
	 *
	 * @param string $raw
	 *
	 * @return array
	 */
	protected function _parseXml($raw)
	{
		return json_decode(json_encode(simplexml_load_string($raw)), true);
	}
}
