<?php
/**
 * Plugin Name: Easy Digital Downloads - PDF Vouchers
 * Plugin URI: https://easydigitaldownloads.com/extensions/pdf-vouchers/
 * Description: Customize and sell PDF vouchers with Easy Digital Download.
 * Version: 1.5.8
 * Author: WPWeb
 * Author URI: http://wpweb.co.in
 * Text Domain: eddvoucher
 * Domain Path: languages
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @category Core
 * @author WPWeb
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
if( !defined( 'EDD_VOU_VERSION' ) ) {
	define( 'EDD_VOU_VERSION', '1.5.8' ); // plugin dir
}
if( !defined( 'EDD_VOU_DIR' ) ) {
	define( 'EDD_VOU_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'EDD_VOU_URL' ) ) {
	define( 'EDD_VOU_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'EDD_VOU_ADMIN' ) ) {
	define( 'EDD_VOU_ADMIN', EDD_VOU_DIR . '/includes/admin' ); // plugin admin dir
}
if( !defined( 'EDD_VOU_IMG_URL' ) ) {
	define( 'EDD_VOU_IMG_URL', EDD_VOU_URL.'includes/images' ); // plugin admin dir
}
if( !defined( 'EDD_VOU_META_DIR' ) ) {
	define( 'EDD_VOU_META_DIR', EDD_VOU_DIR . '/includes/meta-boxes' ); // path to meta boxes
}
if( !defined( 'EDD_VOU_META_URL' ) ) {
	define( 'EDD_VOU_META_URL', EDD_VOU_URL . 'includes/meta-boxes' ); // path to meta boxes
}
if( !defined( 'EDD_VOU_META_PREFIX' ) ) {
	define( 'EDD_VOU_META_PREFIX', '_edd_vou_' ); // meta box prefix
}
if( !defined( 'EDD_VOU_POST_TYPE' ) ) {
	define( 'EDD_VOU_POST_TYPE', 'eddvouchers' ); // custom post type name
}
if( !defined( 'EDD_VOU_CODE_POST_TYPE' ) ) {
	define( 'EDD_VOU_CODE_POST_TYPE', 'eddvouchercodes' ); // custom post type voucher codes
}
if( !defined( 'EDD_VOU_MAIN_POST_TYPE' ) ) {
	define( 'EDD_VOU_MAIN_POST_TYPE', 'download' ); //edd post type
}
if( !defined( 'EDD_VOU_MAIN_MENU_NAME' ) ) {
	define( 'EDD_VOU_MAIN_MENU_NAME', 'edit.php?post_type='.EDD_VOU_MAIN_POST_TYPE ); //easy digital downloads main menu name
}
if( !defined( 'EDD_VOU_PLUGIN_BASENAME' ) ) {
	define( 'EDD_VOU_PLUGIN_BASENAME', basename( EDD_VOU_DIR ) ); //Plugin base name
}
if( !defined( 'EDD_VOU_PLUGIN_BASE_FILENAME' ) ) {
	define( 'EDD_VOU_PLUGIN_BASE_FILENAME', basename( __FILE__ ) ); //Plugin base file name
}
if( !defined( 'EDD_VOU_REFUND_STATUS' ) ) {
	define( 'EDD_VOU_REFUND_STATUS', 'refunded' ); //voucher refunded status
}

//Get Vendor Role name
$edd_vendor_role	= apply_filters( 'edd_vou_edit_vendor_role', 'edd_vou_vendors' );
if( !defined( 'EDD_VOU_VENDOR_ROLE' ) ) {
	define( 'EDD_VOU_VENDOR_ROLE', $edd_vendor_role ); //plugin vendor role
}
if( !defined( 'EDD_VOU_VENDOR_LEVEL' ) ) {
	define( 'EDD_VOU_VENDOR_LEVEL' , 'edd_vendor_options' ); //plugin vendor capability
}

/**
 * Admin notices
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
*/
function edd_vou_admin_notices() {

	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {

		echo '<div class="error">';
		echo "<p><strong>" . __( 'Easy Digital Downloads needs to be activated to be able to use the PDF Vouchers.', 'eddvoucher' ) . "</strong></p>";
		echo '</div>';
	}
}

/**
 * Check Easy Digital Downloads Plugin
 * 
 * Handles to check Easy Digital Downloads plugin
 * if not activated then deactivate our plugin
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
function edd_vou_check_activation() {

	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		// is this plugin active?
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			// deactivate the plugin
	 		deactivate_plugins( plugin_basename( __FILE__ ) );
	 		// unset activation notice
	 		unset( $_GET[ 'activate' ] );
	 		// display notice
	 		add_action( 'admin_notices', 'edd_vou_admin_notices' );
		}
	}
}
//Check Easy Digital Downloads plugin is Activated or not
add_action( 'admin_init', 'edd_vou_check_activation' );

// loads the Misc Functions file
require_once ( EDD_VOU_DIR . '/includes/edd-vou-misc-functions.php' );

//Post type to handle custom post type
require_once( EDD_VOU_DIR . '/includes/edd-vou-post-types.php' );

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'edd_vou_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
function edd_vou_install() {

	global $wpdb, $user_ID, $edd_options;

	//register post type
	edd_vou_register_post_types();

	//IMP Call of Function
	//Need to call when custom post type is being used in plugin
	flush_rewrite_rules();

	$udpopt = false;

	$default_template_page_id = '';

	//get option for when plugin is activating first time
	$edd_vou_set_option = get_option( 'edd_vou_set_option' );

	if( empty( $edd_vou_set_option ) ) { //check plugin version option

		// Create default templates
		$default_templates = edd_create_default_templates();

		// Get default template page id
		$default_template_page_id = isset( $default_templates['default_template'] ) ? $default_templates['default_template'] : '';

		//update default templates 
		//update_option( 'edd_vou_default_templates', $default_templates );

		//get vendor role
		$vendor_role = get_role( EDD_VOU_VENDOR_ROLE );
		if( empty( $vendor_role ) ) { //check vendor role

			$capabilities  = array(
										EDD_VOU_VENDOR_LEVEL	=> true,  // true allows add vendor level
										'read' 					=> true
									);
			add_role( EDD_VOU_VENDOR_ROLE,__( 'Vendor', 'eddvoucher' ), $capabilities );
		} else {

			$vendor_role->add_cap( EDD_VOU_VENDOR_LEVEL );
		}

		$role = get_role( 'administrator' );
		$role->add_cap( EDD_VOU_VENDOR_LEVEL );

		//update plugin version to option 
		update_option( 'edd_vou_set_option', '1.0' );
	}

	//check voucher site logo not set
	if( !isset( $edd_options['vou_site_logo'] ) ) {
		$edd_options['vou_site_logo'] = '';
		$udpopt = true;
	}//end if

	//check voucher pdf name not set
	if( !isset( $edd_options['vou_pdf_name'] ) ) {
		$edd_options['vou_pdf_name'] = __( 'edd-purchased-voucher-codes-{current_date}', 'eddvoucher' );
		$udpopt = true;
	}//end if

	//check voucher csv name not set
	if( !isset( $edd_options['vou_csv_name'] ) ) {
		$edd_options['vou_csv_name'] = __( 'edd-purchased-voucher-codes-{current_date}', 'eddvoucher' );
		$udpopt = true;
	}//end if

	//check voucher csv name not set
	if( !isset( $edd_options['order_pdf_name'] ) ) {
		$edd_options['order_pdf_name'] = __( 'edd-voucher-{current_date}', 'eddvoucher' );
		$udpopt = true;
	}//end if

	//check voucher pdf template not set
	if( !isset( $edd_options['vou_pdf_template'] ) ) {
		$edd_options['vou_pdf_template'] = $default_template_page_id;
		$udpopt = true;
	}//end if
	
	//check sale notification email subject not set
	if( !isset( $edd_options['vou_sale_notification_disable'] ) ) {
		$edd_options['vou_sale_notification_disable'] ='';
		$udpopt = true;
	}//end if
	
	//check voucher email subject not set
	if( !isset( $edd_options['vou_email_subject'] ) ) {
		$edd_options['vou_email_subject'] = __( 'New Sale', 'eddvoucher' );
		$udpopt = true;
	}//end if

	//check voucher email body not set
	if( !isset( $edd_options['vou_email_body'] ) ) {
		$edd_options['vou_email_body'] = __( 'Hello,', 'eddvoucher' ) . "\n\n" . __('A new sale on', 'eddvoucher').' {site_name}.'.
												"\n\n" . __('Download Title:', 'eddvoucher').' {download_title}'.
												"\n\n" . __('Voucher Code:', 'eddvoucher').' {voucher_code}'.
												"\n\n" . __('Thank you', 'eddvoucher');
		$udpopt = true;
	}//end if

	//check gift notification email subject not set
	if( !isset( $edd_options['vou_gift_notification_disable'] ) ) {
		$edd_options['vou_gift_notification_disable'] ='';
		$udpopt = true;
	}//end if
	
	//check gift notification email subject not set
	if( !isset( $edd_options['vou_recipient_email_subject'] ) ) {
		$edd_options['vou_recipient_email_subject'] = __( 'You have received a voucher from {first_name} {last_name}', 'eddvoucher' );
		$udpopt = true;
	}//end if

	//check gift notification email body not set
	if( !isset( $edd_options['vou_recipient_email_body'] ) ) {
		$edd_options['vou_recipient_email_body'] = __( 'Hello,', 'eddvoucher' ) . "\n\n" . __('Hi there. You\'ve been sent a voucher!', 'eddvoucher').
												"\n\n" . ' {recipient_message}'.
												"\n\n" . __('You can find your voucher:', 'eddvoucher').' {voucher_link}';
		$udpopt = true;
	}//end if

	//check need to update the defaults value to options
	if( $udpopt == true ) { // if any of the settings need to be updated
		update_option( 'edd_settings', $edd_options );
	}
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.4.4
 */
function edd_vou_load_text_domain() {

	// Set filter for plugin's languages directory
	$edd_vou_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$edd_vou_lang_dir	= apply_filters( 'edd_vou_languages_directory', $edd_vou_lang_dir );

	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'eddvoucher' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'eddvoucher', $locale );

	// Setup paths to current locale file
	$mofile_local	= $edd_vou_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . EDD_VOU_PLUGIN_BASENAME . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/edd-pdf-vouchers folder
		load_textdomain( 'eddvoucher', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/edd-pdf-vouchers/languages/ folder
		load_textdomain( 'eddvoucher', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'eddvoucher', false, $edd_vou_lang_dir );
	}
}

/**
 * Add plugin action links
 *
 * Adds a Settings, Docs link to the plugin list.
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.4.2
 */
function edd_vou_add_plugin_links( $links ) {
	$plugin_links = array(
		'<a href="edit.php?post_type=download&page=edd-settings&tab=extensions">' . __( 'Settings', 'eddvoucher' ) . '</a>',
		'<a href="http://wpweb.co.in/documents/edd-pdf-vouchers/">' . __( 'Docs', 'eddvoucher' ) . '</a>'		
	);

	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'edd_vou_add_plugin_links' );

//add action to load plugin
add_action( 'plugins_loaded', 'edd_vou_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded 
 * successfully
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 **/
function edd_vou_plugin_loaded() {

	if( class_exists( 'Easy_Digital_Downloads' ) ) {//check easy digital downloads is activated or not

		// load first text domain
		edd_vou_load_text_domain();

		//check EDD_License class is exist
		if( class_exists( 'EDD_License' ) ) {

			// Instantiate the licensing / updater. Must be placed in the main plugin file
			$license = new EDD_License( __FILE__, 'PDF Vouchers', EDD_VOU_VERSION, 'WPWeb' );
		}

		/**
		 * Deactivation Hook
		 * 
		 * Register plugin deactivation hook.
		 * 
		 * @package Easy Digital Downloads - Voucher Extension
		 * @since 1.0.0
		 */
		register_deactivation_hook( __FILE__, 'edd_vou_uninstall');

		/**
		 * Plugin Setup (On Deactivation)
		 * 
		 * Delete  plugin options.
		 * 
		 * @package Easy Digital Downloads - Voucher Extension
		 * @since 1.0.0
		 */
		function edd_vou_uninstall() {

			global $edd_options,$wpdb;

			//IMP Call of Function 
			//Need to call when custom post type is being used in plugin
			flush_rewrite_rules();
		}

		/**
		 * Check if current page is edit page.
		 * 
		 * @package Easy Digital Downloads - Voucher Extension
		 * @since 1.0.0
		 */
		function edd_vou_is_edit_page() {

			global $pagenow;
			return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
		}

		//global variables 
		global $edd_vou_scripts,$edd_vou_model,$edd_vou_render,
				$edd_vou_shortcode,$edd_vou_admin,$edd_vou_settings,$edd_vou_pubilc,
				$edd_vou_meta;

		//Model class handles most of functionalities of plugin
		include_once( EDD_VOU_DIR . '/includes/class-edd-vou-model.php' );
		$edd_vou_model = new EDD_Vou_Model();

		// Script Class to manage all scripts and styles
		include_once( EDD_VOU_DIR . '/includes/class-edd-vou-scripts.php' );
		$edd_vou_scripts = new EDD_Vou_Scripts();
		$edd_vou_scripts->add_hooks();

		//Render class to handles most of html design for plugin
		require_once( EDD_VOU_DIR . '/includes/class-edd-vou-renderer.php' );
		$edd_vou_render = new EDD_Vou_Renderer();

		//Shortcodes class for handling shortcodes
		require_once( EDD_VOU_DIR . '/includes/class-edd-vou-shortcodes.php' );
		$edd_vou_shortcode = new EDD_Vou_Shortcodes();
		$edd_vou_shortcode->add_hooks();

		//Public Class to handles most of functionalities of public side
		require_once( EDD_VOU_DIR . '/includes/class-edd-vou-public.php');
		$edd_vou_pubilc = new EDD_Vou_Public();
		$edd_vou_pubilc->add_hooks();

		//Admin Pages Class for admin side
		require_once( EDD_VOU_ADMIN . '/class-edd-vou-admin.php' );
		$edd_vou_admin = new EDD_Vou_Admin();
		$edd_vou_admin->add_hooks();

		//Settings Tab class for handling settings tab content
		require_once( EDD_VOU_ADMIN . '/class-edd-vou-admin-settings.php' );
		$edd_vou_settings = new EDD_Vou_Settings();
		$edd_vou_settings->add_hooks();

		if( edd_vou_is_edit_page() ) {

			//include the meta functions file for metabox
			require_once ( EDD_VOU_META_DIR . '/edd-vou-meta-box-functions.php' );

			//include the main class file for metabox
			require_once ( EDD_VOU_META_DIR . '/class-edd-vou-meta-box.php' );
			$edd_vou_meta = new EDD_Vou_Meta_Box();
			$edd_vou_meta->add_hooks();
		}

		//Export to CSV Process for used voucher codes
		require_once( EDD_VOU_DIR . '/includes/edd-vou-used-codes-export-csv.php' );

		//Generate PDF Process for voucher code and used voucher codes
		require_once( EDD_VOU_DIR . '/includes/edd-vou-used-codes-pdf.php' );
		require_once( EDD_VOU_DIR . '/includes/edd-vou-pdf-process.php' ); 

	}//end if to check class Easy_Digital_Downloads is exist or not

} //end if to check plugin loaded is called or not