# Shipping Rates for HK Post #
**Contributors:** excelerus, rangatia  
**Tags:** woocommerce, shipping, hongkong-post, hong-kong  
**Requires at least:** 5.0  
**Tested up to:** 5.7  
**Stable tag:** 1.2.3.1  
**Requires PHP:** 7.0  
**License:** GPLv3 or later  
**License URI:** https://www.gnu.org/licenses/gpl-3.0.html  

Hongkong Post postage calculator.

## Description ##

Shipping rates calculated from [open data](https://www.hongkongpost.hk/opendata/DataDictionary/en/DataDictionary_PostageRate.pdf) postage rates published by Hongkong Post.

Supports following delivery services :-

- Local [Ordinary Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/local/ordinary/index.html)  
- Local [Registered Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/local/registered/index.html)  
- Local [Parcel](https://www.hongkongpost.hk/en/sending_mail/local/parcel/index.html)  
- Local [CourierPost](https://www.hongkongpost.hk/en/sending_mail/local/lcp/index.html)  
- Local [Smart Post (Mail Delivery)](https://www.hongkongpost.hk/en/sending_mail/local/smartpost/index.html)  
- International [Surface Registered Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/international/surface/registered/index.html)  
- International [Air Registered Mail (Packet)](https://www.hongkongpost.hk/en/sending_mail/international/air/registered/index.html)  
- International [Surface Parcel](https://www.hongkongpost.hk/en/sending_mail/international/surface/parcel/index.html)  
- International [Air Parcel](https://www.hongkongpost.hk/en/sending_mail/international/air/parcel/index.html)  
- International [Speedpost (Standard Service)](https://www.hongkongpost.hk/en/sending_mail/international/speedpost/index.html)  
- International [e-Express](https://www.hongkongpost.hk/en/sending_mail/international/air/eexpress/index.html)  

**Delivery Services by Shipping Zone**

Store Managers can enable delivery service for each Shipping Zone giving more control over shipping options offered to customers in different geographies.

You can view the [Demo](https://demo.excelerus.dev/hkpost-postage-calculator/) here.

### Contribute and translate

Help localize by adding your locale – visit [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/shipping-rates-for-hk-post/).

## Frequently Asked Questions ##

### Are these official Hongkong Post rates? ###
Yes.  
The plugin uses official opendata json based data [published on Hongkong Post website](https://www.hongkongpost.hk/opendata/DataDictionary/en/DataDictionary_PostageRate.pdf).

### Why are shipping rates not displayed? ###

Please check following configuration

- Your store address (WooCommerce > Settings > General > Store Address) is set to Hong Kong.
- Shipping calculator (WooCommerce > Shipping > Shipping Options > Calculations) is enabled.
- Item(s) in the shopping cart have weights ( Product > Shipping > Weight ).
- Delivery service for shipping zone ( WooCommerce > Setting > Shipping > edit Shiping Zone > edit Hongkong Post > delivery services) has been enabled.

## Installation ##

### Minimum Requirements

* PHP 7.2 or greater is recommended
* MySQL 5.6 or greater is recommended

### Automatic installation

Automatic installation is the easiest option -- WordPress will handles the file transfer, and you won’t need to leave your web browser. To do an automatic install, log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”
 
In the search field type "Shipping Rates for HK Post” then click “Search Plugins.” Once you’ve found us,  you can view details about it such as the point release, rating, and description. Most importantly of course, you can install it by! Click “Install Now,” and WordPress will take it from there.

### Manual installation

Manual installation method requires downloading the plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

### Configuration

Go to plugin settings from 'Settings' link under the plugin on the Plugins page OR from WooCommerce > Settings > Shipping > Hongkong Post. Change the Shipping Method title and enable/disable debug mode if required.

Add Hongkong Post shipping method for a Shipping Zone by 'Add Shipping Method' and selecting 'Hongkong Post' from the dropdown. 

Enable delivery service(s) by clicking on the 'edit' link under the 'Hongkong Post' shipping method to enable respective delivery service for the shipping zone.

## Screenshots ##

### 1. Shipping Method settings. ###
![Shipping Method settings.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-1.png)

### 2. Add Hongkong Post shipping method to shipping zone. ###
![Add Hongkong Post shipping method to shipping zone.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-2.png)

### 3. Enable delivery services. ###
![Enable delivery services.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-3.png)

### 4. Shipping rates calculated for local destination. ###
![Shipping rates calculated for local destination.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-4.png)

### 5. Shipping rates calculated for international destination. ###
![Shipping rates calculated for international destination.](http://ps.w.org/shipping-rates-for-hk-post/assets/screenshot-5.png)


## Changelog ##

### 1.2.0  - 2021-03-DD  
* Revised rates
* Tested for WordPress 5.7
* I18n

### 1.1.0  
* Additional delivery services
* Instance settings for enabling delivery service for shipping zone

### 1.0.0  
* Major rewrite to replace SOAP based API integration with JSON data.

### 0.1.0  
* Inital Release

## Upgrade Notice

### 1.2.0

Postage rates have been revised. Upgrade immediately.