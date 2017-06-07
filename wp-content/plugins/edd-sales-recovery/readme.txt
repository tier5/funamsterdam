=== Easy Digital Downloads - Sales Recovery ===

Contributors: comprock,saurabhd,subharanjan
Donate link: https://axelerant.com/about-axelerant/donate/
Purchase link: https://store.axelerant.com/downloads/sales-recovery-easy-digital-downloads/
Tags: easy digital downloads, edd, abandoned cart
Requires at least: 3.9.2
Tested up to: 4.7.4
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.htm

Increase cash flow by automatically checking on abandoned shopping carts via reminders and discounts for Easy Digital Downloads transactions.


== Description ==

This plugin makes it super easy to recoup sales revenue lost to abandoned shopping carts and failed Easy Digital Downloads transactions. At user defined times, emailed initial, multiple-interim, and final sales recovery attempts are made. These messages can be customized based upon the recovery stage and with a variety of template tags. Further, at each stage, a discount code can be created to encourage users to buy now.

**Introduction to Sales Recovery for Easy Digital Downloads**

https://www.youtube.com/watch?v=xkpnPxvRYd0

Users can opt out of the recovery process via completing a transaction or clicking an unsubscribe link. All discount codes are single-use, expiring, and unique to users. Further, discount code usage, helps determine successfully recovered sales.

At the end of an unsuccessful recovery process, the original payment record status is set to abandoned and no further recovery attempt emails are sent.

Admins can keep track of the recovery process via the Payment History report and Order Detail screens. Further, admins can manually initiate or stop the recovery process and resend the last recovery email. Lastly, when payments are manually completed or abandoned, the sales recovery process is halted.

= Sales Recovery Features =

* Adds {admin_order_details}, {admin_order_details_url}, {recovery_url}, {cart_items}, {discount_expiration}, {stage}, {checkout}, {checkout_url}, {contact}, {contact_url}, {site_url}, {store_url}, {unsubscribe}, {unsubscribe_url}, {users_orders}, {users_orders_url} template markers
* Admin notifications contain order detail and user's transaction links for sales recovery overview 
* API
* Attempts initial, multiple-interim, and final sales recovery sequence via email
* Automatically looks for abandoned, failed, or pending transactions to attempt sales recovery on
* Automatically runs via hourly cron
* Completed purchases are checked for discount code usage to mark related sales recovery transactions as successfully recovered
* Creates unique one-time discount code for recovery attempt stages
* Daily inactivation or purge of unused, expired discount codes
* Disable notification options
* Initial recovery is automatically attempted within hours of user abandoning cart
* Initiate or stop sale recovery process via Order Details and Payment History
* Interim and final recovery attempt in days
* Preview initial, interim, and final sales recovery email templates
* Recovery notes shown in Order Details
* Recovery status shows in Payment History and Order Details
* Resend Recovery Email via Payment History and Order Details
* Send initial, interim, and final sales recovery test emails
* User profile sales recovery unsubscribe stops current and future processing
* Users can unsubscribe from sales recovery emails via a single-click
* Works with Mandrill API/SMTP

= Sales Recovery Options =

= Email Settings =

* Email Only To:? - Check this box if you're integrated with Mandrill or other third party APIs or SMTPs that have mail sending issues.
* Disable Recovery Notifications - Check this box if you do not want to receive emails when sales recovery attempts are made.
* Initial Attempt
	* Recovery Subject
	* Recovery Content
	* Recovery Notification Subject
* Interim Attempts
	* Recovery Subject
	* Recovery Content
	* Recovery Notification Subject
* Final Attempt
	* Recovery Subject
	* Recovery Content
	* Recovery Notification Subject

= Extension Settings =

* Contact Page Link - This is a feedback page for users to contact you.
* Unsubscribe Page Link - This is the sales recovery unsubscribe or user profile page.
* Exclude processing of sales before - ( YYYY-MM-DD ) Sales which are older than inputed date will be excluded while processing sales recovery attempt.
* Purge Expired Discounts - If enabled, a daily cron will delete unused, expired EDD Sales Recovery generated discount codes.
* Reset Recovery Status - If enabled, checkouts with `ignored` recovery status will be reset for potential recovery actions.
* Initial Attempt
	* Enabled? - Check this to enable initial sales recovery attempt.
	* Hours to Wait - Number of hours to wait before first sales recovery attempt.
	* Discount Percentage - Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.
* Interim Attempts
	* Enabled? - Check this to enable interim sales recovery attempts.
	* Days to Send - Age in days, since abandoned sale, of when to send sales recovery attempts. Format as CSV.
	* Discount Percentage - Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.
* Final Attempt
	* Enabled? - Check this to enable final sales recovery attempt.
	* Days to Wait - Number of days to wait, since abandoned sale, before sending the final sales recovery attempt.
	* Discount Percentage - Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.
	* Discount Period - Number of days final discount offer is valid for.


== Installation ==

= Requirements =

* PHP 5.3+ [Read notice](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=12845151)
* Plugin "[Easy Digital Downloads](http://wordpress.org/plugins/easy-digital-downloads/)" version 2.3.4 or newer is required to be installed and activated prior to activating "Easy Digital Downloads - Sales Recovery".

= Install Methods =

* Download `edd-sales-recovery.zip` locally
	* Through WordPress Admin > Plugins > Add New
	* Click Upload
	* "Choose File" `edd-sales-recovery.zip`
	* Click "Install Now"
* Download and unzip `edd-sales-recovery.zip` locally
	* Using FTP, upload directory `edd-sales-recovery` to your website's `/wp-content/plugins/` directory

= Activation Options =

* Activate the "Easy Digital Downloads - Sales Recovery" plugin after uploading
* Activate the "Easy Digital Downloads - Sales Recovery" plugin through WordPress Admin > Plugins

= License Activation =

1. Set the license key through WordPress Admin > Products > Settings > Licenses tab, EDD - Sales Recovery License Key field
1. License key activation is automatic upon clicking "Save Changes"

= Usage =

1. Read "[Easy Digital Downloads Sales Recovery Setup](https://store.axelerant.com/edd-sales-recovery/setup/)"

= Upgrading =

* Through WordPress
	* Via WordPress Admin > Dashboard > Updates, click "Check Again"
	* Select plugins for update, click "Update Plugins"
* Using FTP
	* Download and unzip `edd-sales-recovery.zip` locally
	* Upload directory `edd-sales-recovery` to your website's `/wp-content/plugins/` directory
	* Be sure to overwrite your existing `edd-sales-recovery` folder contents


== Frequently Asked Questions ==

= Most Common Issues =

* Mandrill API/SMTP compatibility mode - Check "Email Only To:?" in Downloads > Settings > Emails
* [Easy Digital Downloads Sales Recovery Setup](https://store.axelerant.com/edd-sales-recovery/setup/)
* [Recovery emails aren't being sent](https://axelerant.atlassian.net/wiki/display/WPFAQ/Recovery+emails+aren%27t+being+sent)
* Got `Parse error: syntax error, unexpected T_STATIC, expecting ')'`? Read [Most Axelerant Plugins Require PHP 5.3+](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=12845151) for the fixes.
* [Debug common theme and plugin conflicts](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+to+Debug+common+issues)

= Still Stuck or Want Something Done? Get Support! =

1. [Knowledge Base](https://axelerant.atlassian.net/wiki/display/WPFAQ/) - read and comment upon frequently asked questions
1. [Open Issues](https://github.com/michael-cannon/edd-sales-recovery/issues) - review and submit bug reports and enhancement requests
1. [Support Forum](https://easydigitaldownloads.com/support/forum/add-on-plugins/sales-recovery-extension/) - review past questions and ask new ones
1. [Contribute Code](https://github.com/michael-cannon/edd-sales-recovery/blob/master/CONTRIBUTING.md) - [request access](https://axelerant.com/contact-axelerant/)
1. [Beta Testers Needed](http://store.axelerant.com/become-beta-tester/) - provide feedback and direction to plugin development

= Tutorials =

* [Easy Digital Downloads Sales Recovery Setup](https://store.axelerant.com/edd-sales-recovery/setup/)


== Screenshots ==

1. Payment History with "Initiate Sales Recovery"
2. Payment History with "Resend Recovery Email"
3. Order Detail of sales recovery attempt
4. Email settings
5. Email content with template markers
6. Extension Settings
7. Example of initial follow-up email
8. Example of interim follow-up email with discount code
9. Discount code set to inactive
10. User profile EDD Sales Recovery options
11. Preview Initial Sales Recovery


== Changelog ==

See [Changelog](https://store.axelerant.com/edd-sales-recovery/changelog/)


== Upgrade Notice ==

= 1.0.0 =

* Initial release


== Notes ==

The payment statuses looked at for a potential recovery attempt are defined in `self::$status_recover` of `includes/class-edd-sales-recovery.php`. They`re `abandoned`, `failed`, and `pending`. When EDD Sales Recovery finds a payment entry to attempt recovery, the payment status is changed to `recovery`. Then all further EDD Sales Recovery settings apply to this `recovery` status.

Regarding a payment moving from `pending` to `abandoned` after a week, there`s no new payment record created. Only a status change. As such, a recovery attempt is only done once automatically unless manually overridden. 

While EDD Sales Recovery does interrupt the normal `pending` to `abandoned` by converting it to `recovery` there`s no functionality loss. The payment record can still be converted to other statuses as normal, even during recovery.

At the end of the recovery attempt, the payment status is changed to `abandoned` or `recovered` per `end_recovery_process` of `includes/class-edd-sales-recovery.php`. The payment record also has a meta value of `ignore` via `self::STATUS_IGNORE` applied to prevent the payment from being recovered automatically again.


== API ==

* Read the [EDD Sales Recovery API](https://store.axelerant.com/edd-sales-recovery/api/).


== Deprecation Notices ==

* None at this time


== Localization ==

You can translate this plugin into your own language if it's not done so already. The localization file `edd-sales-recovery.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](https://axelerant.com/contact-axelerant/) for plugin inclusion.

**[How do I localize?](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+I+change+Testimonials+Widget+text+labels)**
