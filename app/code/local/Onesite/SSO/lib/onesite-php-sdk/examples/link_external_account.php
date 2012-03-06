<?php

// Include the main entry point to the SDK.
require_once(dirname(__FILE__) . '../src/com/onesite/sdk.php');

// This could be pulled from external config, database options, etc.
$devkey = "abc123";
$logdir = dirname(__FILE__) . "/logs";

// Create a new instance of the SDK and set the logging directory.
$sdk = new onesite_sdk($devkey);
$sdk->enableDebugging($logdir);

// Assume this ID has been grabbed from some local system.
$local_user_id = 1234;

// Assume this ID has been retrieved from the ONEsite session check.
$onesite_user_id = 5678;

// Create an empty onesite_sdk_dao_externalAccount object and populate it.
$extAcct = $sdk->newExternalAccount();
$extAcct->providerName = "wordpress-mysiteid";
$extAcct->userIdentifier = $local_user_id;

// Create an empty onesite_sdk_dao_user object and populate it.
$user = $sdk->newUser();
$user->id = $onesite_user_id;

// Try to link the accounts and verify.
$linked = $sdk->getUserApi()->addExternalAccount($user, $extAcct);

if ($linked) {
	echo "The local account was linked to the remote ONEsite account.\n";
} else {
	echo "Unable to link the local account to the remote ONEsite account.\n";
}

exit;
