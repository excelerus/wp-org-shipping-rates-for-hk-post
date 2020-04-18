# Shipping Rates for HK Post #
**Contributors:** excelerus  
**Tags:** hong kong, hongkong post, woocommerce, shipping,  
**Requires at least:** 4.6  
**Requires PHP:** 7.0  
**Tested up to:** 5.4  
**Stable tag:** 1.0.0  
**License:** GPLv3 or later  
**License URI:** https://www.gnu.org/licenses/gpl-3.0.html  

Automatically calculate shipping rates for Hongkong Post domestic and international e-commerce delivery services.

## Description ##

This plugin calculates postage rates based on [data from Hongkong Post](https://www.hongkongpost.hk/opendata/DataDictionary/en/DataDictionary_PostageRate.pdf).

**Supported Delivery Services**
Following e-commerce delivery services provided by Hongkong Post are supported by this plugin:
- SmartPost (Mail Delivery) service for domestic
- iMail service for international

**Total Weight**
Hongkong Post postage rates for e-commerce services are based on weight and destination. The plugin considers total weight of all items in the order for applicable rates.
_Notes :_
- If your site uses 'shipping classes' to separate shipment for different products, you might need additional customization for 'per box' calcuation.
- The weight limit for iMail service is 2.0 kgs per package. Incase the total weight of products in the cart exceeds this weight limit, the plugin considers additional number of packages to caclulate accurate rates for total weight of items in cart.

**Configuration Notes**
- Shipping rates for Hongkong Post are calculated **only if your store location (ie. country) is set to Hong Kong**, as Hongkong Post services is only available for packages originating on Hong Kong.
- Remember to set weight (Product > Shipping > Weight) for all products.
- Shipping rates are calculated in Hong Kong Dollars. If you store has any base currency and/or uses multi-currencies, remember to convert shipping rates accordingly.

## Installation ##

### Using the WordPress Dashboard ###

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Shipping Rates for HK Post'
3. Click 'Install Now'
4. Activate the plugin on the plugin dashboard

### Uploading in WordPress Dashboard ###

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `shipping-rates-for-hk-post.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

## Setup ##

1. Go to WooCommerce > Settings > Shipping Zones and click 'Edit' for the shipping zone that you want to add Hongkong Post shipping method.
2. Click 'Add Shipping Method' and select 'Hongkong Post' from the dropdown.
3. That's all!

## Frequently Asked Questions ##

### Are these official Hongkong Post rates? ###
Yes.
The plugin uses official opendata json based data [published on Hongkong Post website](https://www.hongkongpost.hk/opendata/DataDictionary/en/DataDictionary_PostageRate.pdf).

## Screenshots ##

### 1. Add Hongkong Post shipping method to shipping zone. ###
![Add Hongkong Post shipping method to shipping zone.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-1.png)

### 2. Hongkong Post shipping method added. ###
![Hongkong Post shipping method added.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-2.png)

### 3. Shipping rate calculated for SmartPost Mail Delivery by Hongkong Post. ###
![Shipping rate calculated for SmartPost Mail Delivery by Hongkong Post.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-3.png)

### 4. Shipping rate calculated for iMail by Hongkong Post. ###
![Shipping rate calculated for iMail by Hongkong Post.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-4.png)


## Pro Version ##

An advanced version with following additional features will be released on WooCommerce marketplace shortly
- 'Box Packing' calcuation for optimized packing and accurate shipping costs.
- Support for shipment insurance.
- Tracking links in admin and customer view of order.

## Changelog ##

### 1.0.0 ###
* Major rewrite to replace SOAP based API integration with JSON data.

### 0.1.0 ###
* Inital Release

