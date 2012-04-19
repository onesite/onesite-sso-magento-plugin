# ONEsite Magento Plugin

## Overview

Magento is a highly trusted open source ecommerce application used for online product catalogs and other advanced commerce sites.  It is a very popular solution with a vibrant community.

The ONEsite Magento plugin allows you to utilize core ONEsite SSO and social technology to extend the capabilities of your Magento store, easily engage with new customers, and create your own vibrant community around your products.

### Features

ONEsite's primary integration offering for Magento is the Social Login SSO plugin.  Key plugins and features includes:

 * [Single Sign On] (http://developer.onesite.com/guide/single-sign-quick-start)
 * [Social Login] (http://developer.onesite.com/widget/social-login)
 * [Account Linking] (http://developer.onesite.com/widget/link-social-accounts)
 * [Review Widget] (http://developer.onesite.com/widget/reviews)

### Requirements

No additional software or hardware is required on your Magento servers.  However, the plugin does require a valid ONEsite devkey in order to link your Magento installation to a ONEsite account.

Request your free ONEsite devkey at: [http://onesite.com/node/ssoSignup]

## Plugin Setup

### Installation

Please ensure that your Magento installation is fully completed before attempting to add this or other plugins to your server.   Failure to do so may cause Magento to crash during installation.

### Download

The ONEsite Magento plugins are available from the following locations:

 * [Magento Connect plugin repository] (http://www.magentocommerce.com/magento-connect/)
 * [ONEsite Developer Portal] (http://developer.onesite.com/plugins)
 * [ONEsite @ GitHub] (https://github.com/onesite/onesite-sso-magento-plugin)

### Manual Installation

If you have chosen to download the plugin from ONEsite or GitHub, please adhere to the following steps:

**Direct Server Access**

 * Download and save the plugin file.
 * Copy the file to your Magento directory.
 * Extract the file.
 * Verify that the plugin exists in app/code/Onesite/SSO/

**Example Manual Installation**
<pre><code>
> # cd /var/www/magento
> # wget http://developers.onesite.com/files/plugins/magento/onesite_magento_v1.0.tgz
> # tar -xvf onesite_magento_v1.0.tgz
> # ls -al app/code/local/Onesite/SSO
</code></pre>

**FTP Server Access Only**

 1. Download and save the plugin file
 2. Unzip the files
 3. Upload the files to your server into the Magento base directory
 4. Verify that the plugin exists in app/code/Onesite/SSO/

**Automated Installation**

 1. Go to Magento Connect
 2. Search for ONEsite SSO Plugin
 3. Request an Extension Key and hit copy
 4. Return to your Magento Admin Panel
 5. Navigate to System > Magento Connect
 6. Paste the Extension Key into the Install New Extensions area and hit Install 
 
## Configuration

Once installation is complete, return to the Magento Admin Panel to configure the plugin.

**Please note:** Magento heavily caches both the admin interface and its frontend.  It is important to regularly return to System > Cache Management and flush the cache to ensure the your plugins display properly.

### Basic Configuration

 1. Log in to the Magento Admin Panel
 2. Navigate to System > Cache Management from the top navigation menu
 3. Flush the Magento Cache and Cache Storage
 4. Navigate to System > Configuration from the top navigation menu
 5. Click on ONESITE > SSO in the left rail navigation menu 
 6. Click on the down area to the right of Options to open the page
 7. Enter your ONEsite API Devkey and hit Save Config.  ONEsite will automatically update all other fields based upon your devkey.

### Advanced Configuration

After setting up the basics of the ONEsite SSO plugin, you will need to add in the ONEsite widgets to your Magento site where you want to use them.

### Social Login Widget

ONEsite recommends placing the Social Login widget within the toolbar area of your site so that on any page load the users can quickly login or create a new account.  The process to replace the built in login system in Magento is a bit more complex but not difficult.  It will require access to your site's FTP.

 1. Connect to the FTP for your site.
 2. Navigate to app/design/frontend/default/default.
 4. Within this folder you need to add a layout folder.
 5. Save the following template within this folder as local.xml.

<pre><code>&lt;?xml version="1.0"?&gt;
&lt;layout version="0.1.0"&gt;
        &lt;sso_main_login&gt;
                &lt;reference name="customer_form_login"&gt;
                        &lt;action method="setTemplate"&gt;
                                &lt;template&gt;onesite-main/login.phtml&lt;/template&gt;
                        &lt;/action&gt;
                &lt;/reference&gt;
        &lt;/sso_main_login&gt;
        &lt;sso_main_checkout&gt;
                &lt;reference name="checkout.onepage.login"&gt;
                        &lt;action method="setTemplate"&gt;
                                &lt;template&gt;onesite-main/checkout/login.phtml&lt;/template&gt;
                        &lt;/action&gt;
                &lt;/reference&gt;
        &lt;/sso_main_checkout&gt;
        &lt;customer_account_login&gt;
                &lt;update handle="sso_main_login"/&gt;
        &lt;/customer_account_login&gt;
        &lt;checkout_onepage_index&gt;
                &lt;update handle="sso_main_checkout"/&gt;
        &lt;/checkout_onepage_index&gt;
&lt;/layout&gt;
</code></pre>

### Reviews Widget

The Reviews Widget allows visitors to your site to post reviews of your products including a free text field and a 5 star rating system.  When working with a widget like the Review Widget that you want to display in multiple locations on the network it is necessary to add a Widget Block.

 1. Log in to the Magento Admin Panel
 2. Navigate to CMS > Widgets from the top navigation menu
 3. Click Add New Widget Instance
 4. Under Settings set:
	* Type - ONEsite Review Widget
	* Design Package/Theme - default/default.  Please note if you have created a custom theme for your site, you should select the appropriate theme location, for example default/myTheme.
 5. Under Widget Options:
	* Widget Instance Title - This title is for identification purposes only.  It does not display on the front end to your users.
 6. Click Add Layout Update
	* Set Display On to All Product Types
	* Set Block Reference to the location on the page you wish the Reviews Widget to display.  Our suggestion is "Main Content Area".  This adds the widget below the final item on the product page - tags.
 7. Save your Widget Instance

 

