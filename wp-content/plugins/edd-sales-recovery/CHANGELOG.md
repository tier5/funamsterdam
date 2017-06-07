# CHANGELOG - EDD Sales Recovery

## master

## 1.4.0
* Add {recovery_url} template markers
* BUGFIX Set status complete, once customer recovered the product
* Removed extra white spaces after final php closing tag to avoid unexpected output generated code
* Update Axelerant FAQ links
* Update WordPress 4.7.3 compatibility

## 1.3.9
* BUGFIX Display blank date in email content when discount coupon is not generated 
* Generate unique discount coupon code for each sales recovery 
* Update WordPress 4.7 compatibility

## 1.3.8
* Removed BUGFIX Don't send user emails in WP_DEBUG mode

## 1.3.7
* BUGFIX Clear user attempts tracking when reseting recovery status
* BUGFIX Compare user attempts by date and hour plus contents
* BUGFIX Delete only unused, eddsr discount codes a month after expiration
* BUGFIX Directly ignore closed payments
* BUGFIX Don't send user emails in WP_DEBUG mode
* BUGFIX Grab all pending trans
* BUGFIX Ignore `failed` statuses for recovery attempts
* BUGFIX Plugin page mail & setting action links
* Convert discount codes to uppercase
* Enable Sales Recovery debug mode in WP_DEBUG mode
* Update Aihrus Framework 1.2.6
* Update Copyright 2016 Axelerant
* Update EDD required version
* Update settings in readme.txt

## 1.3.6
* BUGFIX Blank emails getting delivered
* BUGFIX EDD plugin requirement issue
* BUGFIX Manually created discount codes getting deleted
* BUGFIX Remove possible CONFIRM_KEY conflict
* Update Aihrus Framework 1.2.5
* Update WordPress 4.6.1 compatibility

## 1.3.5
* Fix admin notice issue
* Fix WordPress database error due to extra comma in query
* Override timespan checking for manual recoveries

## 1.3.4
* RESOLVE #20 Stop recovering pending payments made with the Checks payment gateway

## 1.3.3
* Add a date picker for `recovery_start_date` input field
* Change support address to `support@axelerant.com`
* RESOLVE #19 Register settings subsections with EDD 2.5
* RESOLVE Recovery process not starting automatically https://trello.com/c/RRiWBoni/345-edd-sales-recovery
* Update readme notes for recovery selection and prevention

## 1.3.2
* RESOLVE deprecation notice: `edd_apply_email_template` is deprecated since Easy Digital Downloads version 2.0
* RESOLVE #18 Add `recovery_start_date` to stop processing all the old cart attempts
* RESOLVE michael-cannon/aihrus-framework/issues#8 Remove activation helpers
* Tested with WordPress 4.4
* Update Aihrus Framework 1.2.3

## 1.3.1
* RESOLVE EDD active check update https://trello.com/c/RRiWBoni/345-edd-sales-recovery
* Update Aihrus Framework 1.2.2
* Update store branding

## 1.3.0
* Change branding from Aihrus to Axelerant
* Coding standards update
* Convert README.md to readme.txt
* Remove CDN links
* RESOLVE PHP Warning: md5() expects parameter 1 to be string, array
* Update Aihrus Framework 1.2.1
* Update copyright text
* Update file headers
* Update PHPCS to WordPress core
* Update WordPress 4.2 compatibility

## 1.2.0
* Add FAQ recovery emails not sent
* Add option Email Only To:?
* Add option Reset Recovery Status
* Baseline payment details during preview emails
* Convert readme.txt to README.md
* Dump the mail contents as a payment note on mail send failure
* RELATES #14 Are recovery emails set to be sent out before attempting recovery
* Remove filter `eddsr_get_email_headers`
* Remove unused ajax_recover_sale code
* Require Easy Digital Downloads 2.1.0
* RESOLVE #12 _edd_discount_expiration is not DATETIME format
* RESOLVE #13 Cron not working 
* RESOLVE #14 Initiate recovery action link in Payment History doesn't do anything
* RESOLVE Don't show email contents in payment notes on failures: http://screencloud.net/v/DH5r
* RESOLVE Notice: Array to string conversion http://screencloud.net/v/vhQJ
* Update Aihrus Framework 1.1.4
* Update copyright year
* Update EDD License libraries to 1.1
* Update name passed to EDD_License to "EDD Sales Recovery"
* Use EDD_Emails mail class
* Work with WordPress 4.0

## 1.1.0
* Add Screenshot 11. Preview Initial Sales Recovery
* Change `self::notice_error` to `aihr_notice_error`
* Change `self::notice_updated` to `aihr_notice_updated`
* Convert TODO to https://github.com/michael-cannon/edd-sales-recovery/issues
* Halt sales recovery on manual payment completion or abandoning
* Move ci to tests
* Move CSS to assets
* Move files to assets
* Move lib to includes/libraries
* Move main class to own class file
* Remove mass handler (was only for debugging purposes)
* RESOLVE #10 Rip out mass tool
* RESOLVE #4 Add Preview Recovery Email option
* RESOLVE #5 Add Send Test Recovery Email option
* RESOLVE #7 Error When Sending Purchase Receipt
* RESOLVE #8 Leave note when recovery attempt fails
* RESOLVE #9 Return to same URL after EDDSR action
* RESOLVE Warning: Missing argument 4 for EDD_Sales_Recovery::edd_email_template_tags() in /home/pippin/sites/wordpress/wp-content/plugins/edd-sales-recovery/includes/class-edd-sales-recovery.php on line 1601
* Revise preview and email sample code placement
* Revise readme structure
* Specify a “Text Domain” and “Domain Path”
* Use Aihrus Framework 1.0.3
* Use aihr_check_aihrus_framework
* Use YouTube https

## 1.0.7
* Revise create_link handling
* Update Aihrus framework

## 1.0.6
* BUGFIX Disable Recovery Notifications – Disabled but still receiving; Kudos Jeremy Wong for the find
* Check for PHP 5.3

## 1.0.5
* BUGFIX Settings not initialized
* BUGFIX Static property warnings
* Require EDD_VERSION 1.8.5
* Since abandoned sale text
* Use aihrus framework
* Use edd_get_settings than global

## 1.0.4
* BUGFIX Discount codes removed before expiration
* Revise base handling
* Update notification handling

## 1.0.3
* Add LICENSE
* Enable activation and version checking
* Remove unprocessed template markers
* Rename EDD_ID to EDD_PT
* Requires at least: 3.6
* Tested up to: 3.8.0
* Update license handlers

## 1.0.2
* BUGFIX #1 Check for EDD before setting
* Add user profile heading for unsubscribe

## 1.0.1
* Add INSTALL.md document
* Add `delete` option to method `set_recovery_start`
* Add failure reasons to admin notices
* Add order details to admin notices
* Allow no argument `get_order_link`
* BUGFIX end recovery process doesn't clean up postmeta
* BUGFIX recovery start time not set if `wp_mail` fails
* BUGFIX recovery start time not set to correct now on first iteration
* Centralize admin notice display handling
* Create `get_order_link` method returning complete HTML a tag to view order details
* Remove `$new_stage` `null` default
* Rename `get_order_link` to `get_order_url`
* Revise installation instructions
* State the recovery state in email sending
* Verbiage edits
* `final_period` default changed from 56 to 28
* Travis ignore WordPress.WhiteSpace.ControlStructureSpacing - false positives

## 1.0.0
* Add option Purge Expired Discounts
* Add screenshot 8. Example of interim follow-up email with discount code
* Add screenshot 9. Discount code set to inactive
* Add video
* Add {admin_order_details} and {admin_order_details_url} template markers
* Add {users_orders} template marker
* Consolidate payment_history_url handling
* Correct admin orders URL
* Correct product and support URLs
* Daily inactivation or purge of unused, expired discount codes
* Move settings to Extensions tabs
* Remove 1.x series version, revert to master, not ready for release
* Remove custom updater remnants
* Remove donate link
* Remove hyphen before Sales Recovery in naming
* Rename `edd_sales_recovery_cron` to `edd_sales_recovery_cron_recover`
* Replace get_option_* calls with get_edd_options
* Update screenshot 3 with full process
* Use EDD License Handler instead of own
* Verbiage updates

## 0.1.1
* Prevent resending recovery process emails on page reload
* Remove excess args from Payment History status selectors
* Use own nonce methods

## 0.1.0
* Add Contact Link option
* Add EDD general options getter
* Add In Recovery and Recovered payment statuses
* Add Recovery stopped by user request payment note
* Add WP_DEBUG options
* Add cron methods
* Add discount code template markers to email description
* Add final discount period option
* Add in Sales Recovery processing page for testing
* Add link in email to stop recovery process, mark as abandoned
* Add notices for manual sales recovery process start
* Add plugin action links for email and misc settings
* Add process to TODO
* Add recovery email subject options
* Add screenshot 7. Example of initial follow-up email
* Add screenshots
* Add settings link anchors
* Add several API filters and actions
* Add user sales recovery unsubscribe to profile screen
* Add {cart_items}, {discount_expiration}, {stage} template markers
* Add {checkout}, {checkout_url}, {contact}, {contact_url} template markers
* Add {site_url} and {store_url} template markers
* Add {unsubscribe}, {unsubscribe_url} template markers
* After unsuccessful recovery, mark as abandoned
* Change final days to wait from 56 to 28
* Change last interim days to send from 28 to 21
* Check for time period in sending recovery emails
* Clean up expiration date format
* Coding standards review
* Consolidate like user/cart items together as similar transactions
* Convert page link options to use select
* Correct missing $wpdb globals
* Correct unsubscribe link
* Create SLUG for easier options handling
* Create email sent methods
* Create get_option helpers
* Create option disable admin notification
* Create option sets of enabled, wait period, discount offer, and recovery text
* Create process mode methods
* Create simple recovery email content
* Created initial, interim, and final attempt option sets
* Decrease default discount percentages
* Don't reprocess sales recovery unsubs on user profile update
* Enable auto-updater
* Enable session handling
* Initiate and stop sale recovery process via Order Details
* Initiate and stop sale recovery process via Payment History
* Main and short description updates
* Message verbiage updates
* No longer mark completed transactions with a status tag
* Only operate Sales Recovery processing page when WP_DEUG is true
* Poll transactions to process
* Pretty print cart contents
* Recovery notes shown in Order Details
* Recovery status shows in Payment History
* Remove CSS file
* Remove call by reference for $this
* Remove empty API example links
* Remove error_log'ing
* Remove notes
* Remove test_remove_recovery_attempt()
* Remove unsubscribed users from processing
* Resend Recovery Email via Payment History and Order Details
* Revise default email templates
* Revise payments selection routines
* Revise textual content
* Send initial sales recovery email
* Set init priority to 9 - edd_actions are registered before EDD calls them
* Standardize sales recovery process note verbiage
* Stop sales recovery process on user unsubscribe
* Track recovery_start and email_sent to prevent loops in interim and compressing notifications
* Update API
* Update POT
* Update TODO
* Update readme and plugin headers
* Update readme content and features
* Use WP_SESSION via EDD->session
* Use discount code usage to determine recovered transaction via action edd_complete_purchase 
* Use intval vs. abs

## 0.0.1
* Initial code release 