<?php
/**
 * Plugin Name: Easy Digital Downloads - Sales Recovery
 * Plugin URI: https://store.axelerant.com/downloads/sales-recovery-easy-digital-downloads/
 * Description: Increase cash flow by following up on users with abandoned shopping carts via automated reminders and discounts for Easy Digital Downloads transactions.
 * Version: 1.4.0
 * Author: Axelerant
 * Author URI: https://axelerant.com/
 * License: GPLv2 or later
 * Text Domain: edd-sales-recovery
 * Domain Path: /languages
 */


/**
 * Copyright 2016 Axelerant
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

define( 'EDD_SR_AIHR_VERSION', '1.2.9' );
define( 'EDD_SR_BASE', plugin_basename( __FILE__ ) );
define( 'EDD_SR_DIR', plugin_dir_path( __FILE__ ) );
define( 'EDD_SR_DIR_INC', EDD_SR_DIR . 'includes/' );
define( 'EDD_SR_DIR_LIB', EDD_SR_DIR_INC . 'libraries/' );
define( 'EDD_SR_NAME', 'EDD Sales Recovery' );
define( 'EDD_SR_REQ_CLASS', 'Easy_Digital_Downloads' );
define( 'EDD_SR_REQ_NAME', 'Easy Digital Downloads' );
define( 'EDD_SR_REQ_SLUG', 'easy-digital-downloads' );
define( 'EDD_SR_REQ_VERSION', '2.7.7' );
define( 'EDD_SR_VERSION', '1.4.0' );

require_once EDD_SR_DIR_INC . 'requirements.php';

require_once EDD_SR_DIR_INC . 'class-edd-sales-recovery.php';


register_activation_hook( __FILE__, array( 'EDD_Sales_Recovery', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'EDD_Sales_Recovery', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'EDD_Sales_Recovery', 'uninstall' ) );


if ( ! class_exists( 'EDD_License' ) ) {
	require_once EDD_SR_DIR_LIB . 'EDD_License_Handler.php';
}

$eddsr_license = new EDD_License( __FILE__, EDD_SR_NAME, EDD_SR_VERSION, 'Axelerant' );


add_action( 'plugins_loaded', 'edd_sales_recovery_plugin_init' );


function edd_sales_recovery_plugin_init() {
	if ( ! eddsr_requirements_check() ) {
		return;
	}

	if ( EDD_Sales_Recovery::version_check() ) {
		global $EDD_Sales_Recovery;
		if ( is_null( $EDD_Sales_Recovery ) ) {
			$EDD_Sales_Recovery = new EDD_Sales_Recovery();
		}
	}

	if ( false && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		echo '<h1>';
		echo '<a href="';
		echo wp_nonce_url( add_query_arg( array( 'edd_action' => 'test_recovery' ) ), 'edd-test-recovery' );
		echo '">';
		_e( 'EDD SR Test Recovery', 'edd-sales-recovery' );
		echo '</a>';
		echo '</h1>';
		echo '<h1>';
		echo '<a href="';
		echo wp_nonce_url( add_query_arg( array( 'edd_action' => 'test_purge' ) ), 'edd-test-purge' );
		echo '">';
		_e( 'EDD SR Test Purge', 'edd-sales-recovery' );
		echo '</a>';
		echo '</h1>';
	}
}


add_action( 'edd_send_test_final_email', 'edd_send_test_final_email' );
add_action( 'edd_send_test_initial_email', 'edd_send_test_initial_email' );
add_action( 'edd_send_test_interim_email', 'edd_send_test_interim_email' );


/**
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function edd_send_test_initial_email( $data ) {
	if ( ! wp_verify_nonce( $data['_wpnonce'], 'edd-test-initial-email' ) ) {
		return;
	}

	EDD_Sales_Recovery::email_test_sales_recovery( 'initial' );
	EDD_Sales_Recovery::redirect();
}


/**
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function edd_send_test_interim_email( $data ) {
	if ( ! wp_verify_nonce( $data['_wpnonce'], 'edd-test-interim-email' ) ) {
		return;
	}

	EDD_Sales_Recovery::email_test_sales_recovery( 'interim' );
	EDD_Sales_Recovery::redirect();
}


/**
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function edd_send_test_final_email( $data ) {
	if ( ! wp_verify_nonce( $data['_wpnonce'], 'edd-test-final-email' ) ) {
		return;
	}

	EDD_Sales_Recovery::email_test_sales_recovery( 'final' );
	EDD_Sales_Recovery::redirect();
}


if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	add_action( 'edd_test_recovery', 'edd_test_recovery' );
	add_action( 'edd_test_purge', 'edd_test_purge' );
}

function edd_test_recovery( $data ) {
	if ( ! wp_verify_nonce( $data['_wpnonce'], 'edd-test-recovery' ) ) {
		return;
	}

	$payment_posts = EDD_Sales_Recovery::get_recover_sales_ids();
	error_log( serialize( $payment_posts, true ) . ':' . __LINE__ . ':' . basename( __FILE__ ) );
}

function edd_test_purge( $data ) {
	if ( ! wp_verify_nonce( $data['_wpnonce'], 'edd-test-purge' ) ) {
		return;
	}

	EDD_Sales_Recovery::edd_sales_recovery_cron_purge();
}

?>