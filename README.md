# ONEsite Magento Plugin

Overview
--------

Magento is a popular open source ecommerce application used for online product catalogs and other advanced commerce sites. Magento is a very popular solution with a vibrant community.

The ONEsite Magento plugin allows you to utilize core ONEsite SSO and Social technology to extend the capabilities of your Magento store, easily engage with new customers, create a vibrant community around your products and increase customer conversions.

### Features
ONEsite offers many advanced Magento integration customizations but the primary integration is the Social Login SSO plugin.  Key plugins and features are as follows:

 * Single Sign On
 * Social Login
 * Account Linking
 * Review Widget
 * Comments Widget
 * ONEsite Live

### Requirements
The Magento plugin does not have any additional software or hardware requirements on your Magento servers.

The plugin does require a valid ONEsite devkey in order to link your Magento installation to a ONEsite account. See the installation instructions below for registering with ONEsite.

Plugin Setup
------------

### Setting up your ONEsite account
1. [Sign up for an account](http://www.onesite.com/node/ssoSignup) on ONEsite.com.
   You will receive a devkey and an email confirmation.
2. To enable social providers for authentication (Facebook, Twitter, etc…):
	1. You will need to set up your own 'application' on each of the
	   Social Networks you would like to integrate with on your site.
	2. Log in to the [ONEsite control panel](https://admin.onesite.com).
	3. Go to this page: "Plugins" > "Social Integration" > "Settings"
	4. Enable each of the providers you require. Make sure you enter any
	   required API keys or integration info for each provider.

### Installation
The Magento plugin may be installed using the automated system available in Magento or may be downloaded as an attachment and extracted into your Magento installation direction. 

### Download
The ONEsite Magento plugins are available from the Magento Connect plugin repository or directly from the anced Magento integration customizations but the primary integratiNEsite plugin please ask your account representatives or use the support boards.

 * Magento Connect plugin page
 * ONEsite Wiki Attachment
 * ONEsite public git account

### Install Manually

1. Download onesite_magento_v1.0.tgz
2. Copy onesite_magento_v1.0.tgz to your magento directory
3. Extract onesite_magento_v1.0.tgz
4. Verify that the plugin exists in app/code/local/Onesite/SSO

**Example Manual Installation**
<pre><code>
> # cd /var/www/magento
> # wget http://developers.onesite.com/files/plugins/magento/onesite_magento_v1.0.tgz
> # tar -xvf onesite_magento_v1.0.tgz
> # ls -al app/code/local/Onesite/SSO
</code></pre>

**Enabling social login widget**

Add or adjust your layout XML file for your current Magento them.
This is usually in a directory such as app/design/frontend/default/default/layout.
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


## Configuration
---

### Basic Configuration
After the Magento plugin has been installed you will need to utilize the Magento configuration section in the admin panel in order to finish the installation process.

1. Login to your Magento admin panel
2. Go to System -> Configuration
3. Click on ONEsite -> SSO in the left hand column
4. Enter in your ONEsite devkey (provided by your account representative)
5. Hit 'Save Config' (the other parameters will be autofilled)

At this point Magento will connect to the ONEsite servers to discover details about your account and will automatically adjust the other settings.

### Advanced Configuration
After setting up the basics of the ONEsite SSO plugin you will need to add in the ONEsite widgets where you want to use them within Magento.a

### Social Login Widget
We recommend you place the Social Login widget in your toolbar so that on any page load users may quickly login or create an account.

### Registration Forms
The registration forms may be switched over to ONEsite powered registration forms by checking the 'Use ONEsite Registration Forms' in the plugin configuration.

### Review Widget
In order to utilize the ONEsite review widget you must edit the page layout of your product pages and insert the ONEsite widget.  This is straightforward process if you have not heavily extended Magento.

1. Login to your Magento admin panel
2. Go to CMS -> Manage Pages
3. Select 'Product Pages'
4. Pick a spot to put the review widget and insert the cursor in the WYSIWYG editor
5. Hit the 'Insert Plugin' button (orange icon) in the top left of the WYSIWYG editor
6. Select the 'ONEsite Review Widget' plugin from the list that appears
7. Select which options you would like to use in the review widget

