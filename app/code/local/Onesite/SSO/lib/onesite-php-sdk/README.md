# ONEsite PHP SDK
---
This open source PHP library provides a simple interface for you to integrate ONEsite services into any existing PHP application. More
in depth guides and API documentation can be found [http://developer.onesite.com](http://developer.onesite.com).


### Requirements
---
A development key is required to interact with any of the ONEsite services. You can register for a development key at [http://www.onesite.com/node/ssoSignup)](http://www.onesite.com/node/ssoSignup).

### Getting Started
---
This is intended to show a quick sample of how one would get started with the SDK.  In This sample, we'll check to see if a user has a session started with ONEsite and then load their user details if they are.

1. Include the main SDK entry point.  When interacting with SDK, only the **src/com/onesite** directory is needed.  For this example, we'll assume have loaded that directory in **/var/www/libs/onesite**.

	<pre lang="php"><code>
	require_once("/var/www/libs/onesite/sdk.php");
	</code></pre>

2. Create a new instance of the SDK your development key.  For this example, we'll use "abc123" as your devkey.

	<pre lang="php"><code>
	$sdk = new onesite_sdk("abc123");
	</code></pre>

3. Load the session and user APIs

	<pre lang="php"><code>
	$userApi = $sdk->getUserApi();
	$sessionApi = $sdk->getSessionApi();
	</code></pre>

4.  Check the local cookie values against ONEsite to see if there is a valid logged in session.  

	<pre lang="php"><code>
	$session = $sdk->newSession();
	$session->coreU = "cookie1";
	$session->coreX = "cookie2";
	$sessionApi->check($session);
	</code></pre>

5.  Check the session object and store the user locally if logged in.

	<pre lang="php"><code>
	if ($session->isValid()) {
		$user = $session->user;
	} else {
    	echo "Invalid session. User is logged out or there was a cookie error."
	}
	</code></pre>

6.  Interact with the user object

	<pre lang="php"><code>
	echo "Welcome back {$user->firstName}";
	</code></pre>


### License
---
Except as otherwise noted, the ONESite PHP SDK is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
