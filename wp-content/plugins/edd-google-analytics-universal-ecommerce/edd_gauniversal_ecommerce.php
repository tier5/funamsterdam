<?php

/**
 * Plugin Name: EDD Google Analytics Universal Ecommerce
 * Plugin URI: http://danlester.com/eddgaue
 * Description: Ecommerce tracking for EDD using Google Universal Analytics - assumes you already create a ga object at the top of the page, and have enabled Ecommerce on your GA dashboard
 * Version: 1.2
 * Author: Dan Lester
 * Author URI: http://wp-glogin.com/
 * License: GPL3
 * 
 * Make sure you are already adding regular Universal Google Analytics code to the top of your page.
 * The Google object must be called "ga".
 * For example, use the plugin NK Google Analytics from http://www.marodok.com/nk-google-analytics/
 * with Tracking code location set to Head
 * 
 * You must also have enabled 'Ecommerce' on your Google Analytics dashboard
 * 
 * Tested with Easy Digital Downloads version 2.
 * 
 */

function edd_ga_ua_ecom_payment_receipt_after_table($payment, $edd_receipt_args) {
	
	if ( $edd_receipt_args['payment_id'] ) {
		// Use a meta value so we only send the beacon once.
		if (get_post_meta( $payment->ID, 'edd_ga_beacon_sent', true)) {
			return;
		}
		
		$grand_total = edd_get_payment_amount( $payment->ID );
		
		?>
		<script type="text/javascript">

		if (typeof ga != "undefined") {
			ga('require', 'ecommerce', 'ecommerce.js');
			
			ga('ecommerce:addTransaction', {
				  'id': '<?php echo esc_js(edd_get_payment_number( $payment->ID )); ?>', // Transaction ID. Required.
				  'affiliation': '<?php echo esc_js(get_bloginfo('name')); ?>', // Affiliation or store name.
				  'revenue': '<?php echo $grand_total ? esc_js($grand_total) : '0'; ?>', // Grand Total.
				  'shipping': '0', // Shipping.
				  'tax': '<?php echo edd_use_taxes() ? esc_js(edd_payment_tax( $payment->ID )) : '0'; ?>' // Tax.
				});
			
		<?php if ( $edd_receipt_args[ 'products' ] ) {
				$cart = edd_get_payment_meta_cart_details( $payment->ID, true );
				if ($cart) {
					foreach ( $cart as $key => $item ) {
						if( empty( $item['in_bundle'] ) ) {

							$price_id = edd_get_cart_item_price_id( $item );
							$itemname = $item['name'];
							if( ! is_null( $price_id ) ) {
								$itemname .= ' - '.edd_get_price_option_name( $item['id'], $price_id );
							}
				?>
					ga('ecommerce:addItem', {
						  'id': '<?php echo esc_js(edd_get_payment_number( $payment->ID )); ?>', // Transaction ID. Required.
						  'name': '<?php echo esc_js($itemname); ?>',  // Product name. Required.
						  'sku': '<?php echo esc_js(edd_use_skus() ? edd_get_download_sku( $item['id'] ) : $item['id']); ?>',                 // SKU/code.
						  //'category': 'Example',  - Category or variation.
						  'price': '<?php echo esc_js($item['item_price']); ?>', // Unit price.
						  'quantity': '<?php echo esc_js($item['quantity']); ?>' // Quantity.
						});

				<?php
						}
					}
				}
			  }
			
		?>
			ga('ecommerce:send');
		 }
		</script>
		<?php 
		
		update_post_meta( $payment->ID, 'edd_ga_beacon_sent', true );
		
	}
	
}

add_action('edd_payment_receipt_after_table', 'edd_ga_ua_ecom_payment_receipt_after_table', 10, 2);

?>