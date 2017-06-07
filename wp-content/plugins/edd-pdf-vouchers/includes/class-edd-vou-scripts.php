<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 * 
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
class EDD_Vou_Scripts {

	public function __construct() {
		
	}
	
	/**
	 * Enqueue Scrips
	 * 
	 * Handles to enqueue script on
	 * needed pages
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_popup_scripts( $hook_suffix ) {

		$edd_screen_id		= edd_vou_get_edd_screen_id();

		$pages_hook_suffix 	= array( 'post.php', 'post-new.php', $edd_screen_id.'_page_edd-vou-codes', 'toplevel_page_edd-vou-codes' );

		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {

			global $post, $wp_version;

			//Check vouchers & download post type
			if( $hook_suffix == $edd_screen_id.'_page_edd-vou-codes'
				|| $hook_suffix == 'toplevel_page_edd-vou-codes'
				|| ( isset( $post->post_type ) && $post->post_type == EDD_VOU_MAIN_POST_TYPE ) ) {

				//js directory url
				$js_dir = EDD_PLUGIN_URL . 'assets/js/';

				// Use minified libraries if SCRIPT_DEBUG is turned off
				$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

				wp_register_script( 'jquery-chosen', $js_dir . 'chosen.jquery.min.js', array( 'jquery' ), EDD_VERSION, true );
				wp_enqueue_script( 'jquery-chosen' );

				wp_register_script( 'edd-vou-script-metabox', EDD_VOU_URL.'includes/js/edd-vou-metabox.js', array( 'jquery', 'jquery-form' ), EDD_VOU_VERSION, true ); 
				wp_enqueue_script( 'edd-vou-script-metabox' );
				wp_localize_script( 'edd-vou-script-metabox', 'EddVouMeta', array(
																					'noofvouchererror' 		=> '<div>' . __( 'Please enter Number of Voucher Codes.', 'eddvoucher' ) . '</div>',
																					'patternemptyerror' 	=> '<div>' . __( 'Please enter Pattern to import voucher code(s).', 'eddvoucher' ) . '</div>',
																					'generateerror' 		=> '<div>' . __( 'Please enter Valid Pattern to import voucher code(s).', 'eddvoucher' ) . '</div>',
																					'filetypeerror'			=> '<div>' . __( 'Please upload csv file.', 'eddvoucher' ) . '</div>',
																					'fileerror'				=> '<div>' . __( 'File can not be empty, please upload valid file.', 'eddvoucher' ) . '</div>',
																					'check_code_error' 		=> __( 'Please enter voucher code.', 'eddvoucher' ),
																					'code_valid' 			=> __( 'Voucher code is valid. If you would like to submit voucher code as "Used", Please click on the submit button below:', 'eddvoucher' ),
																					'code_invalid' 			=> __( 'Voucher code doest not exist.', 'eddvoucher' ),
																					'code_used_success'		=> __( 'Thank you for your business, voucher code submitted successfully.', 'eddvoucher' )
																				) );
			}

			//Check vouchers post type
			if( isset( $post->post_type ) && $post->post_type == EDD_VOU_POST_TYPE ) {

				$newui = $wp_version >= '3.5' ? '1' : '0'; //check wp version for showing media uploader

				//If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.
			    if ( $wp_version >= 3.5 ) {
			        //Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
			        wp_enqueue_script( 'wp-color-picker' );
			    }
			    //If the WordPress version is less than 3.5 load the older farbtasic color picker.
			    else {
			        //As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
			        wp_enqueue_script( 'farbtastic' );
			    }

				wp_enqueue_script( array( 'jquery', 'jquery-ui-tabs', 'media-upload', 'thickbox', 'tinymce','jquery-ui-accordion' ) );

				wp_register_script( 'edd-vou-admin-script', EDD_VOU_URL . 'includes/js/edd-vou-admin-voucher.js', array(), EDD_VOU_VERSION, true );
				wp_enqueue_script( 'edd-vou-admin-script' );
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouAjax' , array( 'ajaxurl' => admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ) ) );
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouSettings' , array( 'new_media_ui' => $newui ) );
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouTranObj' , array(
																				'onbuttontxt' => __('Voucher Builder is On','eddvoucher'),
																				'offbuttontxt' => __('Voucher Builder is Off','eddvoucher'),
																				'switchanswer' => __('Default WordPress editor has some content, switching to the Voucher will remove it.','eddvoucher'),
																				'btnsave' => __('Save','eddvoucher'),
																				'btncancel' => __('Cancel','eddvoucher'),
																				'btndelete' => __('Delete','eddvoucher'),
																				'btnaddmore' => __('Add More','eddvoucher')
																			));
				/* this is used for text block section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouTextBlock' , array(
																					'textblocktitle' => __('Voucher Code','eddvoucher'),
																					'textblockdesc' => __('Voucher Code','eddvoucher'),
																					'textblockdesccodes' => '{codes}'
																				));
				/* this is used for message box section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouMsgBox' , array(
																					'msgboxtitle' => __('Redeem Instruction','eddvoucher'),
																					'msgboxdesc' => '<p>' . '{redeem}' . '</p>'
																				));
				/* this is used for logo box section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouSiteLogoBox' , array(
																						'sitelogoboxtitle' => __('Voucher Site Logo','eddvoucher'),
																						'sitelogoboxdesc'  => '{sitelogo}'
																					));
				/* this is used for logo box section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouLogoBox' , array(
																					'logoboxtitle' => __('Voucher Logo','eddvoucher'),
																					'logoboxdesc' => '{vendorlogo}'
																				));
				/* this is used for expire date block section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouExpireBlock' , array(
																						'expireblocktitle' => __('Expire Date','eddvoucher'),
																						'expireblockdesc' => __('Expires :','eddvoucher') . ' {expiredate}'
																					));
				/* this is used for vendor's address block section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouVenAddrBlock' , array(
																						'venaddrblocktitle' => __('Vendor\'s Address','eddvoucher'),
																						'venaddrblockdesc' => '{vendoraddress}'
																					));
				/* this is used for website URL block section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouSiteURLBlock' , array(
																						'siteurlblocktitle' => __('Website URL','eddvoucher'),
																						'siteurlblockdesc' => '{siteurl}'
																					));
				/* this is used for voucher location block section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouLocBlock' , array(
																					'locblocktitle' => __('Voucher Locations','eddvoucher'),
																					'locblockdesc' => '<p><span style="font-size: 9pt;">{location}</span></p>'
																				));
				/* this is used for blank box section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouBlankBox' , array(
																						'blankboxtitle' => __('Blank Block','eddvoucher'),
																						'blankboxdesc' => __('Blank Block','eddvoucher')
																					));
				/* this is used for custom box section */
				wp_localize_script( 'edd-vou-admin-script' , 'EddVouCustomBlock' , array( 
																						'customblocktitle' => __('Custom Block','eddvoucher'),
																						'customblockdesc' => __('Custom Block','eddvoucher')
																					));
			}
		}
	}

	/**
	 * Enqueue Styles
	 * 
	 * Handles to enqueue styles on
	 * needed pages
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_popup_styles( $hook_suffix ) {

		$edd_screen_id	= edd_vou_get_edd_screen_id();
		
		$edd_hook_suffix = array(
								$edd_screen_id.'_page_edd-payment-history',
								$edd_screen_id.'_page_edd-vou-check-voucher-code',
								'toplevel_page_edd-vou-codes',
								'downloads_page_edd-vou-used-voucher-codes',
								'downloads_page_edd-vou-check-voucher-code',
								'download_page_edd-vou-codes'
							);

		//$hook_suffix == $edd_screen_id.'_page_edd-payment-history' || $hook_suffix == $edd_screen_id.'_page_edd-vou-check-voucher-code' || $hook_suffix == 'toplevel_page_edd-vou-codes' || $hook_suffix == 'downloads_page_edd-vou-used-voucher-codes' || $hook_suffix == 'downloads_page_edd-vou-check-voucher-code' 							
							
		//Check download history page
		if( in_array( $hook_suffix, $edd_hook_suffix ) ) {
		
			wp_register_style( 'edd-vou-admin-style', EDD_VOU_URL.'includes/css/edd-vou-admin.css', array(), EDD_VOU_VERSION );
			wp_enqueue_style( 'edd-vou-admin-style' );
		}
		
		$pages_hook_suffix = array( 'post.php', 'post-new.php', $edd_screen_id.'_page_edd-vou-codes', 'toplevel_page_edd-vou-codes', 'downloads_page_edd-vou-used-voucher-codes' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
			
			global $post, $wp_version;
			
			//Check vouchers & download post type
			if( $hook_suffix == $edd_screen_id.'_page_edd-vou-codes'
				|| $hook_suffix == 'toplevel_page_edd-vou-codes'
				|| ( isset( $post->post_type ) && $post->post_type == EDD_VOU_MAIN_POST_TYPE ) ) {
				
				//css directory url
				$css_dir = EDD_PLUGIN_URL . 'assets/css/';
			
				// Use minified libraries if SCRIPT_DEBUG is turned off
				$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
				
				wp_register_style( 'jquery-chosen', $css_dir . 'chosen' . $suffix . '.css', array(), EDD_VERSION );
				wp_enqueue_style( 'jquery-chosen' );
				
				wp_register_style( 'edd-vou-style-metabox', EDD_VOU_URL.'includes/css/edd-vou-metabox.css', array(), EDD_VOU_VERSION );
				wp_enqueue_style( 'edd-vou-style-metabox' );
			}
			
			//Check vouchers post type
			if( isset( $post->post_type ) && $post->post_type == EDD_VOU_POST_TYPE ) {
				
				//for color picker
				
				//If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.
			    if ( $wp_version >= 3.5 ){
			        //Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
			        wp_enqueue_style( 'wp-color-picker' );
			    }
			    //If the WordPress version is less than 3.5 load the older farbtasic color picker.
			    else {
			        //As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
			        wp_enqueue_style( 'farbtastic' );
			    }
			    
				wp_register_style( 'edd-vou-admin-style',  EDD_VOU_URL . 'includes/css/edd-vou-admin-voucher.css', array(), EDD_VOU_VERSION );
				wp_enqueue_style( 'edd-vou-admin-style' );
			}
		}
	}
		
	/**
	 * Enqueue Scripts
	 * 
	 * Handles to enqueue scripts on 
	 * needed pages
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_admin_drag_drop_head() {
	
		global $post;
		
		//Check vouchers post type
		if( isset( $post->post_type ) && $post->post_type == EDD_VOU_POST_TYPE ) {
		
			echo '	<script type="text/javascript">			
						var settings 	= {};
						var options 	= { portal 			: "columns",
											editorEnabled 	: true};
						var data 		= {};

						var portal;

						Event.observe(window, "load", function() {
							portal = new Portal(settings, options, data);
						});
					</script>';
		}
	}
	
	/**
	 * Enqueue Scripts
	 * 
	 * Handles to enqueue scripts on 
	 * needed pages
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_admin_drag_drop_scripts( $hook_suffix ) {
		
		global $post;
			
		//Check vouchers post type
		if( isset( $post->post_type ) && $post->post_type == EDD_VOU_POST_TYPE ) {
			
			wp_register_script( 'edd-vou-drag-script', EDD_VOU_URL . 'includes/js/dragdrop/portal.js', array( 'scriptaculous' ), EDD_VOU_VERSION, true );
			wp_enqueue_script( 'edd-vou-drag-script' );
						
		}
	}
	
	/**
	 * Enqueue style for meta box page
	 * 
	 * Handles style which is enqueue in downloads meta box page
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_metabox_styles() {
		
		// Enqueue Meta Box Style
		wp_enqueue_style( 'edd-vou-meta-box', EDD_VOU_META_URL . '/css/meta-box.css', array(), EDD_VOU_VERSION );
  
		// Enqueue chosen library, use proper version.
		//wp_enqueue_style( 'edd-vou-multiselect-chosen-css', EDD_VOU_META_URL . '/css/chosen/chosen.css', array(), null );
		
		//css directory url
		$css_dir = EDD_PLUGIN_URL . 'assets/css/';
	
		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		
		wp_register_style( 'jquery-chosen', $css_dir . 'chosen' . $suffix . '.css', array(), EDD_VERSION );
		wp_enqueue_style( 'jquery-chosen' );
		
		// Enqueue for font awesome
		wp_enqueue_style( 'edd-vou-font-awesome', EDD_VOU_META_URL.'/css/font-awesome.css', array(), EDD_VOU_VERSION );
		
		// Enqueue for datepicker
		wp_enqueue_style( 'edd-vou-meta-jquery-ui-css', EDD_VOU_META_URL.'/css/datetimepicker/date-time-picker.css', array(), EDD_VOU_VERSION );
		
		// Enqueu built-in style for color picker.
		if( wp_style_is( 'wp-color-picker', 'registered' ) ) { //since WordPress 3.5
			wp_enqueue_style( 'wp-color-picker' );
		} else {
			wp_enqueue_style( 'farbtastic' );
		}
		
	}
	
	/**
	 * Enqueue script for meta box page
	 * 
	 * Handles script which is enqueue in downloads meta box page
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_metabox_scripts() {
		
		global $wp_version;
		
		// Enqueue Meta Box Scripts
		wp_enqueue_script( 'edd-vou-meta-box', EDD_VOU_META_URL . '/js/meta-box.js', array( 'jquery' ), EDD_VOU_VERSION, true );
		
		//localize script
		$newui = $wp_version >= '3.5' ? '1' : '0'; //check wp version for showing media uploader
		wp_localize_script( 'edd-vou-meta-box','EddVou',array(		'new_media_ui'	=>	$newui,
																	'one_file_min'	=>  __('You must have at least one file.','eddvoucher' )));

		// Enqueue for  image or file uploader
		wp_enqueue_script( 'media-upload' );
		add_thickbox();
		wp_enqueue_script( 'jquery-ui-sortable' );
						
		// Enqueue JQuery chosen library, use proper version.
		//wp_enqueue_script( 'edd-vou-multiselect-chosen-js', EDD_VOU_META_URL . '/js/chosen/chosen.js', array( 'jquery' ), false, true );
		
		//js directory url
		$js_dir = EDD_PLUGIN_URL . 'assets/js/';
		
		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		
		wp_register_script( 'jquery-chosen', $js_dir . 'chosen.jquery.min.js', array( 'jquery' ), EDD_VERSION, true );
		wp_enqueue_script( 'jquery-chosen' );
		
		// Enqueue for datepicker
		wp_enqueue_script(array('jquery','jquery-ui-core','jquery-ui-datepicker','jquery-ui-slider'));
		
		wp_deregister_script( 'datepicker-slider' );
		wp_register_script('datepicker-slider',EDD_VOU_META_URL.'/js/datetimepicker/jquery-ui-slider-Access.js', array(), EDD_VOU_VERSION, true );
		wp_enqueue_script('datepicker-slider');
		
		wp_deregister_script( 'timepicker-addon' );
		wp_register_script('timepicker-addon',EDD_VOU_META_URL.'/js/datetimepicker/jquery-date-timepicker-addon.js',array('datepicker-slider'),EDD_VOU_VERSION,true);
		wp_enqueue_script('timepicker-addon');
							
		// Enqueu built-in script for color picker.
		if( wp_style_is( 'wp-color-picker', 'registered' ) ) { //since WordPress 3.5
			wp_enqueue_script( 'wp-color-picker' );
		} else {
			wp_enqueue_script( 'farbtastic' );
		}
		
	}
	
	/**
	 * Adding Scripts
	 *
	 * Adding Scripts for check code public
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_check_code_public_scripts(){
		
		global $post;
		
		$post_content = isset($post->post_content) ? $post->post_content : '';
		
		// add css for check code in public
		if(  has_shortcode( $post_content, 'edd_vou_check_code' ) ) {
					
			wp_register_style( 'edd-vou-public-check-code-style', EDD_VOU_URL . 'includes/css/edd-vou-check-code.css', array(), EDD_VOU_VERSION );
			wp_enqueue_style( 'edd-vou-public-check-code-style' );						
		}
		
		// add js for check code in public
		wp_register_script( 'edd-vou-check-code-script', EDD_VOU_URL . 'includes/js/edd-vou-check-code.js', array(), EDD_VOU_VERSION, true );
		wp_enqueue_script( 'edd-vou-check-code-script' );
		
		wp_localize_script( 'edd-vou-check-code-script', 'EddVouCheck', array( 
																					'ajaxurl' 			=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
																					'check_code_error' 	=> __( 'Please enter voucher code.', 'eddvoucher' ),
																					'code_valid' 		=> __( 'Voucher code is valid. If you would like to redeem voucher code, Please click on the redeem button below:', 'eddvoucher' ),
																					'code_invalid' 		=> __( 'Voucher code doest not exist.', 'eddvoucher' ),
																					'code_used_success'	=> __( 'Thank you for your business, voucher code submitted successfully.', 'eddvoucher' ),
																					'recipient_required_error'  		=> __( 'Please Enter', 'eddvoucher' ),																					
																					'recipient_email_invalid_error' 	=> __( 'Please Enter Valid', 'eddvoucher' ),																					
																				) );
		
		wp_register_style( 'edd-vou-public-style', EDD_VOU_URL . 'includes/css/edd-vou-public.css', array(), EDD_VOU_VERSION );
		wp_enqueue_style( 'edd-vou-public-style' );				
	}
	
	/**
	 * Adding Scripts
	 *
	 * Adding Scripts for check code in admin
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_check_code_scripts( $hook_suffix ){
		
		$edd_screen_id	= edd_vou_get_edd_screen_id();

		if( $hook_suffix == $edd_screen_id.'_page_edd-vou-check-voucher-code' || $hook_suffix == 'downloads_page_edd-vou-check-voucher-code' ){
		
			// add css for check code in admin
			wp_register_style( 'edd-vou-check-code-style', EDD_VOU_URL . 'includes/css/edd-vou-check-code.css', array(), EDD_VOU_VERSION );
			wp_enqueue_style( 'edd-vou-check-code-style' );
			
			// add js for check code in admin
			wp_register_script( 'edd-vou-check-code-script', EDD_VOU_URL . 'includes/js/edd-vou-check-code.js', array(), EDD_VOU_VERSION, true );
			wp_enqueue_script( 'edd-vou-check-code-script' );
			
			wp_localize_script( 'edd-vou-check-code-script' , 'EddVouCheck' , array( 
																						'ajaxurl' 			=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
																						'check_code_error' 	=> __( 'Please enter voucher code.', 'eddvoucher' ),
																						'code_valid' 		=> __( 'Voucher code is valid. If you would like to redeem voucher code, Please click on the redeem button below:', 'eddvoucher' ),
																						'code_invalid' 		=> __( 'Voucher code doest not exist.', 'eddvoucher' ),
																						'code_used_success'	=> __( 'Thank you for your business, voucher code submitted successfully.', 'eddvoucher' )
																					) );
			
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hoocks for the scripts.
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add styles for new and edit post and purchased voucher code
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_vou_popup_styles' ) );
		
		//add script for new and edit post and purchased voucher code
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_vou_popup_scripts' ) );
		
		//add scripts for check code admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_vou_check_code_scripts' ) );
		
		//drag & drop scripts on admin head for new and edit post
		add_action( 'admin_head-post.php', array( $this, 'edd_vou_admin_drag_drop_head' ) );
		add_action( 'admin_head-post-new.php', array( $this, 'edd_vou_admin_drag_drop_head' ) );
		
		//drag & drop scripts for new and edit post
		add_action( 'admin_enqueue_scripts', array( $this, 'edd_vou_admin_drag_drop_scripts' ) );	

		if( edd_vou_is_edit_page() ) { // check metabox page
				
			//add styles for metaboxes
			add_action( 'admin_enqueue_scripts', array( $this, 'edd_vou_metabox_styles' ) );
			
			//add styles for metaboxes
			add_action( 'admin_enqueue_scripts', array( $this, 'edd_vou_metabox_scripts' ) );
			
		}
		
		//add scripts for check code front side
		add_action( 'wp_enqueue_scripts', array( $this, 'edd_vou_check_code_public_scripts' ) );
		
	}
}
?>