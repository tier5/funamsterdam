# API - EDD Sales Recovery

The [EDD Sales Recovery plugin](https://store.axelerant.com/downloads/sales-recovery-easy-digital-downloads/) comes with its own set of actions and filters, as described below.

## Actions

* `eddsr_edd_recover_sale`

	Post manual recover sale attempt.

* `eddsr_edd_resend_recovery`

	Post manual resend sale recovery email.

* `eddsr_sales_recover_process`

	Post sales recover process. Called after discount created and emails sent.

* `eddsr_edd_resend_recovery`

	Post ending sales recover process.

* `eddsr_personal_options_update`

	Post personal options update.

## Filters

* `eddsr_sales_recovery_template_tags`
	
	Revise contents of sales recovery template tags text. [example]()

* `eddsr_set_edd_payment_statuses_ignore`
	
	Filter ignored payments IDs.

* `eddsr_set_edd_payment_statuses_users`
	
	Filter users of payments to attempt recovery efforts for. [example](https://axelerant.atlassian.net/wiki/display/WPFAQ/How+do+you+use+filter+eddsr_set_edd_payment_statuses_users)

* `eddsr_get_recover_sales_ids`
	
	Filter payment ID's to attempt recovery efforts for.

* `eddsr_sales_recover_process_attachments`
	
	Add attachments to outgoing sales recovery emails.

* `eddsr_get_email_to`
	
	Modify outgoing email to address contents [Example](https://axelerant.atlassian.net/wiki/pages/viewpage.action?pageId=14024817)

* `eddsr_get_email_body`
	
	Modify outgoing email body contents.

* `eddsr_create_discount_code`
	
	Modify discount code text. Useful to pretty print "51f385b441f74".

* `eddsr_create_discount`
	
	Modify meta parameters used to create store discount.

## Need More?

Further examples and more can be found by reading and searching the [EDD Sales Recovery Knowledge Base](https://axelerant.atlassian.net/wiki/display/WPFAQ/).
