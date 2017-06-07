<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Setting page Class
 * 
 * Handles Settings page functionality of plugin
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.2.2
 */
class EDD_Vou_Settings {
	
	var $model;
	public function __construct(){
		
		global $edd_vou_model;
		
		$this->model = $edd_vou_model;
	}

	/**
 	 * Add plugin section in extension settings
 	 * 
 	 * @package Easy Digital Downloads - Voucher Extension
 	 * @since 1.5.2
 	 */
	public function edd_vou_settings_section( $sections ) {
		
		$sections['eddvoucher'] = __( 'PDF Vouchers', 'eddvoucher' );
		return $sections;
	}
	
	/**
 	 * Add plugin settings
 	 * 
 	 * Handles to add plugin settings
 	 * 
 	 * @package Easy Digital Downloads - Voucher Extension
 	 * @since 1.0.0
 	 */
	public function edd_vou_settings( $settings ) {
		
		$voucher_options	= array( '' => __( 'Please Select', 'eddvoucher' ) );
		$voucher_data		= $this->model->edd_vou_get_vouchers();
		
		foreach ( $voucher_data as $voucher ) {
			
			if( isset( $voucher['ID'] ) && !empty( $voucher['ID'] ) ) { // Check voucher id is not empty
				
				$voucher_options[$voucher['ID']] = $voucher['post_title'];
			}
		}
		
		$edd_vou_settings = array(
				array(
					'id'		=> 'vou_settings',
					'name'		=> '<strong>' . __( 'Voucher Options', 'eddvoucher' ) . '</strong>',
					'desc'		=> __( 'Configure Voucher Settings', 'eddvoucher' ),
					'type'		=> 'header'
				),
				array(
					'id'		=> 'vou_site_logo',
					'name'		=> __( 'Site Logo:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Here you can upload a logo of your site. This logo will then be displayed on the Voucher as the Site Logo.', 'eddvoucher' ).'</p>',
					'type'		=> 'upload',
					'size'		=> 'regular'
				),
				array(
					'id'		=> 'vou_pdf_name',
					'name'		=> __( 'Export PDF File Name:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Enter the PDF file name. This file name will be used when generate a PDF of purchased voucher codes. The available tags are:' , 'eddvoucher').'<br /><code>{current_date}</code> - '. __( 'displays the current date', 'eddvoucher' ).'</p>',
					'type'		=> 'filename',
					'size'		=> 'regular',
					'options'	=> '.pdf'
				),
				array(
					'id'		=> 'vou_csv_name',
					'name'		=> __( 'Export CSV File Name:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Enter the CSV file name. This file name will be used when generate a CSV of purchased voucher codes. The available tags are:' , 'eddvoucher').'<br /><code>{current_date}</code> -'. __('displays the current date', 'eddvoucher' ).'</p>',
					'type'		=> 'filename',
					'size'		=> 'regular',
					'options'	=> '.csv'
				),
				array(
					'id'		=> 'order_pdf_name',
					'name'		=> __( 'Download PDF File Name:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Enter the PDF file name. This file name will be used when users download a PDF of voucher codes on froentend. The available tags are:' , 'eddvoucher').'<br /><code>{current_date}</code> -'. __('displays the current date', 'eddvoucher' ).'</p>',
					'type'		=> 'filename',
					'size'		=> 'regular',
					'options'	=> '.pdf'
				),
				array(
					'id'		=> 'vou_pdf_template',
					'name'		=> __( 'PDF Template:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Select PDF Template.', 'eddvoucher' ).'</p>',
					'type'		=> 'select',
					'options'	=> $voucher_options
				),
				array(
					'id'		=> 'multiple_pdf',
					'name'		=> __( 'Multiple voucher:', 'eddvoucher' ),
					/*'options'	=> __( 'Enable 1 voucher per Pdf', 'eddvoucher' ),*/
					'type'		=> 'checkbox',
					'desc'		=> '<p class="description">'.__( 'Check this box if you want to generate 1 pdf for 1 voucher code instead of creating 1 combined pdf for all vouchers.', 'eddvoucher' ).'</p>'
				),
				array(
					'id'		=> 'vou_char_support',
					'name'		=> __( 'Characters not displaying correctly?', 'eddvoucher' ),
					'type'		=> 'checkbox',
					'desc'		=> '<p class="description">'.__( 'Check this box to enable the characters support. Only do this if you have characters which do not display correctly (e.g. Greek characters).', 'eddvoucher' ).'</p>'
				),
				array(
					'id'		=> 'vou_email_settings',
					'name'		=> __( 'Vendor Sale Notification Email Template', 'eddvoucher' ),
					'desc'		=> '',
					'type'		=> 'header'
				),
				array(
					'id'		=> 'vou_sale_notification_disable',
					'name'		=> __( 'Disable Notification:', 'eddvoucher' ),
					'type'		=> 'checkbox',
					'desc'		=> '<p class="description">'.__( 'Check this box to disable the vendor sale notification email.', 'eddvoucher' ).'</p>'
				),
				array(
					'id'		=> 'vou_email_subject',
					'name'		=> __( 'Email Subject:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Enter the subject line for the vendor sale notification email. Available template tags:', 'eddvoucher' ). '<br /><code>{site_name}</code> - ' .__('displays the site name', 'eddvoucher' ) . '<br /><code>{download_title}</code> - ' .__('displays the download title', 'eddvoucher' ). '<br /><code>{voucher_code}</code> - ' .__('displays the voucher code', 'eddvoucher' ).'</p>',
					'type'		=> 'text'
				),
				array(
					'id'		=> 'vou_email_body',
					'name'		=> __( 'Email Body:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Enter the vendor email that is sent after completion of a purchase. HTML is accepted. Available template tags:', 'eddvoucher' ).'<br /><code>{site_name}</code> - ' .__('displays the site name', 'eddvoucher' ) . '<br /><code>{download_title}</code> - ' .__('displays the download title', 'eddvoucher' ). '<br /><code>{voucher_code}</code> - ' .__('displays the voucher code', 'eddvoucher' ).'</p>',
					'type'		=> 'rich_editor'
				),
				array(
					'id'		=> 'vou_email_notification_settings',
					'name'		=> __( 'Gift Notification Email Template', 'eddvoucher' ),
					'desc'		=> '',
					'type'		=> 'header'
				),
				array(
					'id'		=> 'vou_gift_notification_disable',
					'name'		=> __( 'Disable Notification:', 'eddvoucher' ),
					'type'		=> 'checkbox',
					'desc'		=> '<p class="description">'.__( 'Check this box to disable the gift notification email.', 'eddvoucher' ).'</p>'
				),
				array(
					'id'		=> 'vou_recipient_email_subject',
					'name'		=> __( 'Email Subject:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'Enter the subject line for the gift notification email. Available template tags:', 'eddvoucher' ). '<br /><code>{first_name}</code> - ' .__('displays the first name of customer', 'eddvoucher' ) . '<br /><code>{last_name}</code> - ' .__('displays the last name of customer', 'eddvoucher' ). '<br /><code>{recipient_name}</code> - ' .__('displays the recipient name', 'eddvoucher' ).'</p>',
					'type'		=> 'text'
				),
				array(
					'id'		=> 'vou_recipient_email_body',
					'name'		=> __( 'Email Body:', 'eddvoucher' ),
					'desc'		=> '<p class="description">'.__( 'This is the body, main content of the email that will be sent gift recipient user. HTML is accepted. Available template tags:', 'eddvoucher' ).'<br /><code>{first_name}</code> - ' .__('displays the first name of customer', 'eddvoucher' ) . '<br /><code>{last_name}</code> - ' .__('displays the last name of customer', 'eddvoucher' ). '<br /><code>{recipient_name}</code> - ' .__('displays the recipient name', 'eddvoucher' ). '<br /><code>{recipient_message}</code> - ' .__('displays the recipient message', 'eddvoucher' ). '<br /><code>{voucher_link}</code> - ' .__('displays the voucher download link', 'eddvoucher' ).'</p>',
					'type'		=> 'rich_editor'
				)
			);

			// If EDD is at version 2.5 or later
			if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
				
				$edd_vou_settings = array( 'eddvoucher' => $edd_vou_settings );
			}

		return array_merge( $settings, $edd_vou_settings );
	}
	
	/**
	 * Validate Settings
	 *
	 * Handles to validate settings
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_settings_validate( $input ) {
		
		$input['vou_site_logo'] 				= $this->model->edd_vou_escape_slashes_deep( $input['vou_site_logo'] );
		$input['vou_pdf_name'] 					= $this->model->edd_vou_escape_slashes_deep( $input['vou_pdf_name'] );
		$input['vou_csv_name'] 					= $this->model->edd_vou_escape_slashes_deep( $input['vou_csv_name'] );
		$input['vou_email_subject'] 			= $this->model->edd_vou_escape_slashes_deep( $input['vou_email_subject'] );		
		$input['vou_recipient_email_subject'] 	= $this->model->edd_vou_escape_slashes_deep( $input['vou_recipient_email_subject'] );		
		
		return $input;
	}
	
	/**
	 * Adding Hooks
	 * 
	 * Adding proper hoocks for the shortcodes.
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
 	 * @since 1.2.2
	 */
	public function add_hooks() {
		
		//add filter to add settings section
		add_filter( 'edd_settings_sections_extensions', array( $this, 'edd_vou_settings_section' ) );

		//add filter to add settings
		add_filter( 'edd_settings_extensions', array( $this, 'edd_vou_settings' ) );			
		
		//add filter to extension settings field
		add_filter( 'edd_settings_extensions-eddvoucher_sanitize', array( $this, 'edd_vou_settings_validate') );
	}
}