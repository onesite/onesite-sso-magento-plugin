<?php

// Include the main entry point to the SDK.
require_once(dirname(__FILE__) . '../src/com/onesite/sdk.php');

// This could be pulled from external config, database options, etc.
$devkey = "abc123";
$logdir = dirname(__FILE__) . "/logs";

// Create a new instance of the SDK and set the logging directory.
$sdk = new onesite_sdk($devkey);
$sdk->enableDebugging($logdir);

// Genrally, these would be pulled from local cookies.
$sessCookie = "sessioncookievalue";
$secCookie = "securitycookievalue";

try {

	// Fetch instances of both the user and session APIs.
	$userApi = $sdk->getUserApi();
	$sessionApi = $sdk->getSessionApi();

	// Create a new session object and populate with local cookie values.
	$session = $sdk->newSession();
	$session->coreU = $sessCookie;
	$session->coreX = $secCookie;

	// Run a check against ONEsite SSO session system.
	$sessionApi->check($session);

	// Perform the appropriate action in your application based on the user status.
	if (!$session->isValid()) {
		// Handle invalid session cookies here.
		echo "Invalid session detected.";
		exit;
	} else if ($session->isAnonymous()) {
		// Handle an anonymous session here.
		echo "Anonymous session detected.";
		exit;
	} else {
		
		// Grab the user's email address and check for a linked account.
		echo "User's email is: " . $session->user->email . "\n";
		
		$extAcct = $sdk->newExternalAccount();
		$extAcct->providerName = "wordpress-mysiteid";
		$userApi->getExternalAccount($session->user, $extAcct);		
		
		// Display output based on their local user identifier.
		if (is_null($extAcct->userIdentifier)) {
			echo "No local user account found.\n";
		} else {
			echo "Local linked account ID is: " . $extAcct->userIdentifier;
		}
	}
	
} catch (onesite_exception $e) {
	print_r($e);
}

exit;
