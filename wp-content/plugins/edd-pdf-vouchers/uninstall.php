<?php
/**
 * Uninstall
 *
 * Does delete the created tables and all the plugin options
 * when uninstalling the plugin
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */

// check if the plugin really gets uninstalled 
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();
		
global $wpdb, $edd_options;

// check remove data on uninstall is checked, if yes then delete plugin data
if( edd_get_option( 'uninstall_on_delete' ) ) {	

	//delete vouchers data
	$post_types = array( 'eddvouchers', 'eddvouchercodes' );
	
	foreach ( $post_types as $post_type ) {
		$args = array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => '-1' );
		$all_posts = get_posts( $args );
		foreach ( $all_posts as $post ) {
			wp_delete_post( $post->ID, true);
		}
	}
	
	//delete set option
	delete_option( 'edd_vou_set_option' );
	//delete_option( 'edd_vou_default_templates' );
	
	// Unset all option values from edd global array to delete it
	unset( $edd_options['vou_site_logo'] );
	unset( $edd_options['vou_pdf_name'] );
	unset( $edd_options['vou_csv_name'] );
	unset( $edd_options['order_pdf_name'] );
	unset( $edd_options['vou_pdf_template'] );
	unset( $edd_options['vou_sale_notification_disable'] );
	unset( $edd_options['vou_email_subject'] );
	unset( $edd_options['vou_email_body'] );
	unset( $edd_options['vou_gift_notification_disable'] );
	unset( $edd_options['vou_recipient_email_subject'] );
	unset( $edd_options['vou_recipient_email_body'] );
	
	// update edd_settings option
	update_option( 'edd_settings', $edd_options );
}
?>