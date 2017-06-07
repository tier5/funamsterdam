# SETUP - EDD Sales Recovery

![5. Email content with template markers](https://store.axelerant.com/wp-content/uploads/2013/07/screenshot-51-566x231.png)

_Increase cash flow by checking on users with abandoned shopping carts via automated reminders and discounts for Easy Digital Downloads transactions._

* * *

## Installation

1. Via WordPress Admin &gt; Plugins &gt; Add New, Upload the `edd-sales-recovery.zip` file
2. Alternately, unzip `edd-sales-recovery.zip` the file and then via FTP, upload `edd-sales-recovery` directory to the `/wp-content/plugins/` directory
3. Activate the 'EDD Sales Recovery' plugin after uploading or through WP Admin &gt; Plugins
4. Configure 'EDD Sales Recovery' via WP Admin &gt; Downloads &gt; Settings, Emails and Extensions tabs [![plugin - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/plugin-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/plugin-EDD-Sales-Recovery.png)

* * *

## Initial Setup

Once you've installed and activated EDD Sales Recovery, you'll need to enable and configure the system to your needs. To begin, we need to enable the sales recovery system through the Extension Settings at WP Admin &gt; Downloads &gt; Settings, **Extensions** tab, **Sales Recovery** section.

1. Set your contact and unsubscribe (user profile) link pages so that email template markers are working.
2. Decide to purge your sales recovery discount codes. Otherwise, they're set to inactive.
3. Enable the initial, interim, and final attempts for the sales recovery process.
	* The initial recovery process is started within terms of hours. A decimal hour, like "0.5" representing 30 minutes is allowed.
	* Interim and final recovery processes are measured in days.
	* Multiple interim recovery emails can be sent, list the days to send in comma separated value format like "1,3,7,14,21". This means on days 1, 3, 7… to send interim sales recovery emails.
4. If you want to offer an incentive discount at the various recovery stages, you may. Leave the fields blank to not enable a discount for that period.
5. Save your changes

All extension options are described further in the **Setting Descriptions** section below.
[![Extension Settings - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/Extension-Settings-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/Extension-Settings-EDD-Sales-Recovery.png)

After completing your Extension Settings, it's time to check your Email Settings. Visit WP Admin &gt; Downloads &gt; Settings, **Emails** tab, **Sales Recovery** section.
[![Email Settings top - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/Email-Settings-top-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/Email-Settings-top-EDD-Sales-Recovery.png)

While it's possible to use the defaults given by the EDD Sales Recovery plugin, it's recommended to fine tune the recovery email subject lines and content for your target audience. Further changes are needed depending upon how your offer discounts or not.
[![Initial - Email Settings - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/Initial-Email-Settings-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/Initial-Email-Settings-EDD-Sales-Recovery.png)

If you're going to offer discounts on the initial recovery attempts, you'll need to add those template markers to the initial recovery email content section. Likewise, for removing discounts from the interim or final recovery attempts, then you need to remove those template markers from the interim or final recovery email content sections.

Added sales recovery email template markers. These can be used in the email subject line and content.

* {admin_order_details_url} - Admin order details URL
* {admin_order_details} - Admin order details tag - Automatically prepended to admin notifications
* {cart_items} - Cart contents
* {checkout_url} - Checkout page URL
* {checkout} - Checkout page tag
* {contact_url} - Contact page URL
* {recovery_url} - Checkout page URL with product link
* {contact} - Contact page tag
* {date} - The date of the purchase
* {discount_code} - The discount code
* {discount_expiration} - The discount code expiration date
* {discount} - The discount percentage
* {download_list} - A list of download links for each download purchased
* {file_urls} - A plain-text list of download URLs for each download purchased
* {fullname} - The buyer's full name, first and last
* {name} - The buyer's first name
* {payment_id} - The unique ID number for this purchase
* {payment_method} - The method of payment used for this purchase
* {price} - The total price of the purchase
* {receipt_id} - The unique ID number for this purchase receipt
* {site_url} - Site URL
* {sitename} - Your site name
* {stage} - Sales recovery stage
* {store_url} - Store URL
* {subtotal} - The price of the purchase before taxes
* {tax} - The taxed amount of the purchase
* {unsubscribe_url} - Unsubscribe page URL
* {unsubscribe} - Unsubscribe page tag
* {user_email} - The buyer's email address
* {users_orders_url} - User's orders URL
* {users_orders} - User's orders tag - Automatically prepended to admin notifications
* {username} - The buyer's user name on the site, if they registered an account

**Note** – "admin_" prepended template markers are automatically added to the admin notifications and **shouldn't be placed** in any of the recovery email content sections.

All email options are described further in the **Setting Descriptions** section below.

## Sales Recovery Process

An hourly automatic check for abandoned, failed, or pending transactions to attempt sales recovery on is made. If such a transaction is found, then the appropriate initial, interim, or final recovery stage is applied per the Extension and Email Settings.

For each recovery attempt, an email is sent to the shopping cart owner and site admin. Further, a unique one-time discount code is created if needed and embedded into the recovery email via template markers. The admin's email notification contains order detail and user's transaction links for a quick sales recovery overview.

Users can unsubscribe from sales recovery process via a single-click, if that template marker is included in their email. Further, via their user profile they can unsubscribe to current and future sales recovery processing. This is in place to prevent spam complaints.

If a sales recovery discount code is used, then that related sales recovery transaction is marked as successfully recovered. Otherwise, during the next sales recovery process attempt, the related recovery transaction is marked abandoned since a successful transaction has been completed.

Once a day, unused, expired discount codes are inactivated or purged. This helps keep the WP Admin &gt; Downloads &gt; Discount Codes section clean of extraneous entries.

### Manual Processing

Administrators can manually initiate, resend, or stop the sale recovery process via Payment History and Order Details screens.
[![Manually initiate - listing - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/Manually-initiate-listing-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/Manually-initiate-listing-EDD-Sales-Recovery.png) [![Manully stop - listing - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/Manully-stop-listing-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/Manully-stop-listing-EDD-Sales-Recovery.png) [![Manual recovery - details - EDD Sales Recovery](https://store.axelerant.com/wp-content/uploads/2013/09/Manual-recovery-details-EDD-Sales-Recovery.png)](https://store.axelerant.com/wp-content/uploads/2013/09/Manual-recovery-details-EDD-Sales-Recovery.png)

## Sales Recovery Video Overview

http://www.youtube.com/watch?v=xkpnPxvRYd0

[Video introduction](http://youtu.be/xkpnPxvRYd0)

## Setting Descriptions

### Email Settings

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

### Extension Settings

* Contact Page Link - This is a feedback page for users to contact you.
* Unsubscribe Page Link - This is the sales recovery unsubscribe or user profile page.
* Purge Expired Discounts - If enabled, a daily cron will delete unused, expired EDD Sales Recovery generated discount codes.
* Initial Attempt
	* Enabled? - Check this to enable initial sales recovery attempt.
	* Hours to Wait - Number of hours to wait before first sales recovery attempt.
	* Discount Percentage - Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.
* Interim Attempts
	* Enabled? - Check this to enable interim sales recovery attempts.
	* Days to Send - Age in days, of when to send sales recovery attempts. Format as CSV.
	* Discount Percentage - Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.
* Final Attempt
	* Enabled? - Check this to enable final sales recovery attempt.
	* Days to Wait - Number of days to wait before sending the final sales recovery attempt.
	* Discount Percentage - Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.
	* Discount Period - Number of days final discount offer is valid for.

* * *

## Support

For licensing and general support, use the [Sales Recovery Extension](https://easydigitaldownloads.com/support/forum/add-on-plugins/sales-recovery-extension/ "Sales Recovery Extension") forum. For viewing frequently asked questions, offering ideas, or getting paid support please use the [EDD Sales Recovery Knowledge Base](https://axelerant.atlassian.net/wiki/display/WPFAQ/).
