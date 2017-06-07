=== Plugin Name ===
Contributors: danlester
Tags: edd, easy digital downloads, google analytics, universal google analytics, ecommerce, ua
Requires at least: 3.3
Tested up to: 4.2
Stable tag: 1.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Basic Ecommerce tracking for Easy Digital Downloads using Google Universal Analytics

== Description ==

Basic plugin allowing you to add Google Analytics Ecommerce tracking to Easy Digital Downloads. 
Assumes you already create a ga object at the top of the page, and have enabled Ecommerce on your GA dashboard.

Make sure you are already adding regular Universal Google Analytics code to the top of your page somehow.

The Google object must be called "ga".

For example, use the plugin [NK Google Analytics](http://www.marodok.com/nk-google-analytics/) with Tracking code location set to Head.
This plugin is available on the WordPress plugin repository, so search for it by name from your admin panel.

You must also have enabled 'Ecommerce' on your Google Analytics dashboard.

Tested with Easy Digital Downloads version 2.


= Support =

Please help each other on the Support forum here on WordPress, and the author will also monitor the forums periodically.

== Frequently Asked Questions ==

= What are the system requirements? =

*  PHP 5.2.x or higher
*  Wordpress 3.3 or above
*  Easy Digital Downloads (recommended 2+)


== Installation ==

To set up the plugin, you will need access to a Google Apps domain as an administrator, or just a regular Gmail account.

Easiest way:

1. Go to your WordPress admin control panel's plugin page
1. Search for 'EDD Google Analytics Universal Ecommerce'
1. Click Install
1. Click Activate on the plugin

If you cannot install from the WordPress plugins directory for any reason, and need to install from ZIP file:

1. Upload `EDDGoogleAnalyticsUniversalEcommerce` directory and contents to the `/wp-content/plugins/` directory, or upload the ZIP file directly in
the Plugins section of your Wordpress admin


== Changelog ==

= 1.2 =

Prevents duplicate reporting, thanks to contribution from Jack Arturo. 

= 1.1 =

Total revenue was not being recording correctly, although individual product revenue was.
Fixed now.

= 1.0 =

First version.
