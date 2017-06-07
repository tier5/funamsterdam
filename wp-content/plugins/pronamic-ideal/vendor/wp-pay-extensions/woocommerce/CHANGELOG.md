# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]
-

## [1.2.6] - 2017-04-18
- Improved support for WooCommerce 3.0.

## [1.2.5] - 2017-03-15
- Don't set subscriptions 'on hold' due to delay in direct debit status update.
- Removed gateway description about valid mandate, as these mandates are no longer in use.

## [1.2.4] - 2017-01-25
- Fixed Composer requirement.

## [1.2.3] - 2017-01-25
- Added KBC/CBC Payment Button gateway.
- Added Belfius Direct Net gateway.
- Added filter for payment source description and URL.

## [1.2.2] - 2016-11-16
- Added Maestro gateway.
- Filter gateway description to show mandate notice also when description is empty.

## [1.2.1] - 2016-10-20
- Added experimental support for WooCommerce Subscriptions / recurring payments.
- Restore compatibility with WooCommerce versions < 2.2.0.
- Switched to new Bancontact logo.
- Added Bitcoin gateway.

## [1.2.0] - 2016-06-08
- Added PayPal gateway.

## [1.1.9] - 2016-05-16
- Use `get_woocommerce_currency` function so the `woocommerce_currency` filter is applied.

## [1.1.8] - 2016-04-12
- Check existence of WC_Order::has_status() to support older versions of WooCommerce.
- No longer use camelCase for payment data.
- Add clarification to Pronamic gateway with difference compared to regular payment method specific gateways.
- Fix adding 'Awaiting payment' order note if order status is already pending.

## [1.1.7] - 2016-03-23
- Redirect to payment options instead of 'Order received' if payment is not yet completed.
- Implemented new payment redirect URL filter.
- Use the global default config as the WooCommerce default config.

## [1.1.6] - 2016-02-02
- Add support for WooCommerce Deposits plugin

## [1.1.5] - 2015-10-21
- Removed status code 303 from redirect.

## [1.1.4] - 2015-10-15
- Updated WordPress pay core library to version 1.2.2.

## [1.1.3] - 2015-10-14
- Order note "iDEAL payment [status]" now includes the gateway title, instead of "iDEAL".
- Add DirectDebitGateway.
- Add bank transfer gateway.

## [1.1.2] - 2015-04-08
- Added general Pronamic gateway so the iDEAL gateway can be used for iDEAL only.

## [1.1.1] - 2015-03-03
- Changed WordPress pay core library requirment from ~1.0.1 to >=1.0.1.

## [1.1.0] - 2015-02-16
- Added SOFORT Banking gateway.
- Removed the word 'iDEAL' from the a few strings.

## [1.0.3] - 2015-01-20
- Require WordPress pay core library version 1.0.0.

## [1.0.2] - 2015-01-16
- Fix - Fixed in issue with WooCommerce cancel order URL and HTML entities.

## [1.0.1] - 2014-12-19
- Tweak - No longer set gateways enabled to yes by default.

## 1.0.0 - 2014-12-19
- First release.

[unreleased]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.6...HEAD
[1.2.6]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.5...1.2.6
[1.2.5]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/wp-pay-extensions/woocommerce/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.9...1.2.0
[1.1.9]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.8...1.1.9
[1.1.8]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.7...1.1.8
[1.1.7]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.6...1.1.7
[1.1.6]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.5...1.1.6
[1.1.5]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.4...1.1.5
[1.1.4]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.3...1.1.4
[1.1.3]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/wp-pay-extensions/woocommerce/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/wp-pay-extensions/woocommerce/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/wp-pay-extensions/woocommerce/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/wp-pay-extensions/woocommerce/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/wp-pay-extensions/woocommerce/compare/1.0.0...1.0.1
