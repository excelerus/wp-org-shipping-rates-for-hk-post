=== Shipping Rates for HK Post ===
Contributors: excelerus
Tags: hong-kong, hongkong-post, woocommerce, shipping
Requires at least: 5.0
Tested up to: 5.6
Stable tag: 1.1
Requires PHP: 7.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Calculate shipping rates for Hongkong Post domestic and international delivery services.

== Description ==

This plugin calculates postage rates using [rates published by Hongkong Post](https://www.hongkongpost.hk/opendata/DataDictionary/en/DataDictionary_PostageRate.pdf).

**Support for additional delivery services**
_New in version 1.1_

This plugin now supports following delivery services provided by Hongkong Post.  
* Local [Ordinary Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/local/ordinary/index.html)  
* Local [Registered Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/local/registered/index.html)  
* Local [Parcel](https://www.hongkongpost.hk/en/sending_mail/local/parcel/index.html)  
* [Local CourierPost](https://www.hongkongpost.hk/en/sending_mail/local/lcp/index.html)  
* Local [Smart Post (Mail Delivery)](https://www.hongkongpost.hk/en/sending_mail/local/smartpost/index.html)  
* International [Surface Registered Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/international/surface/registered/index.html)  
* International [Air Registered Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/international/air/registered/index.html)  
* International [Surface Parcel](https://www.hongkongpost.hk/en/sending_mail/international/surface/parcel/index.html)  
* International [Air Parcel](https://www.hongkongpost.hk/en/sending_mail/international/air/parcel/index.html)  
* International [Speedpost (Standard Service)](https://www.hongkongpost.hk/en/sending_mail/international/speedpost/index.html)  
* International [e-Express](https://www.hongkongpost.hk/en/sending_mail/international/air/eexpress/index.html)  

**Enable Delivery Service(s) for Shipping Zone**
_New in version 1.1_

Store Managers can now enable specific delivery services for Shipping Zone. For example, if your Japan customers usually demand faster shipping than you can enable 'SpeedPost' for 'Japan' shipping zone to offer them a faster (and expensive) shipping option.

== Installation ==

**Install & Activate**

Using the WordPress Dashboard

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Shipping Rates for HK Post'
3. Click 'Install Now'
4. Activate the plugin on the plugin dashboard

Or Uploading in WordPress Dashboard

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `shipping-rates-for-hk-post.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

**Configure**

1. Click 'Settings' link for the plugin on Plugins page -or- Go to WooCommerce > Settings > Shipping > Hongkong Post
2. Change the Shipping Method title and enable/disable debug mode if required. If debug mode is enabled, logs can be accessed at WooCommerce > Status > Logs > hkpost...
3. On WooCommerce > Settings > Shipping select or add a Shipping Zone for which you want to add Hongkong Post shipping method.
4. Click 'Add Shipping Method' and select 'Hongkong Post' from the dropdown.
5. Click on the 'edit' link under the 'Hongkong Post' shipping method to enable/disable delivery services for the shipping zone.

== Frequently Asked Questions ==

= Are these official Hongkong Post rates? =
Yes.
The plugin uses official opendata json based data [published on Hongkong Post website](https://www.hongkongpost.hk/opendata/DataDictionary/en/DataDictionary_PostageRate.pdf).
But. 
Users have reported discrepancies between published rates and rates / deliverability notices posted on Hongkong Post website. Discretion advised.

= Shipping Rates are not showing. What gives? =

[x] Check if your store address (WooCommerce > Settings > General > Store Address) is set to Hong Kong.
[x] Check if the items in the shopping cart have weights ( Product > Shipping > Weight ).
[x] Check if enabled delivery service for the shipping zone serves the destination.

== Screenshots ==

1. Shipping Method settings.
2. Add Hongkong Post shipping method to shipping zone.
3. Enable delivery services.
4. Shipping rates calculated for local destination.
5. Shipping rates calculated for international destination.

== Changelog ==

= 1.1.0 =
* Additional delivery services
* Instance settings for enabling delivery service for shipping zone

= 1.0.0 =
* Major rewrite to replace SOAP based API integration with JSON data.

= 0.1.0 =
* Inital Release
