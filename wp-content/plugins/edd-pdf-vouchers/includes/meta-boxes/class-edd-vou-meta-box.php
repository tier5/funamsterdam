<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Handles generic Admin functionality and AJAX requests.
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
class EDD_Vou_Meta_Box {
	
	public $model, $scripts, $price;
	
	public function __construct() {		
	
		global $edd_vou_model,$edd_vou_scripts,$edd_vou_price;
				
		$this->model = $edd_vou_model;
		$this->scripts = $edd_vou_scripts;
		$this->price = $edd_vou_price;
		
	}
	
	/*
	 * Add metaboxes
	 *
	 * @package Easy Digital Downloads - Voucher Extension 
	 * @since 1.0.0
	 */
	function edd_vou_add_meta_box() {
		
		// Check for which post type we need to add the meta box
		$pages = edd_vou_get_meta_pages();
		
		// Loop through array	
		foreach ( $pages as $page ) {
			add_meta_box( 'edd_vou_meta', __( 'Easy Digital Downloads - PDF Vouchers', 'eddvoucher' ),  array( $this, 'edd_vou_meta_box_show' ), $page, 'normal', 'default' );
		}
		
	}
	
	/**
	 * Display Meta Box
	 * 
	 * Handles to display meta box
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_meta_box_show() {
		
		global $edd_options, $post;
		
		// get currency symbol from EDD
		$currencysymbol = edd_currency_filter( '' );
		
		$prefix = EDD_VOU_META_PREFIX;
		
		//var_dump($this->_fields);
		wp_nonce_field( EDD_VOU_PLUGIN_BASENAME, 'at_edd_vou_meta_box_nonce' );
		//adding tab in meta box via addTabs Function
			
		$voucher_options = array( '' => __( 'Please Select', 'eddvoucher' ) );
		$voucher_data = $this->model->edd_vou_get_vouchers();
		foreach ( $voucher_data as $voucher ) {
			if( isset( $voucher['ID'] ) && !empty( $voucher['ID'] ) ) { // Check voucher id is not empty
				$voucher_options[$voucher['ID']] = $voucher['post_title'];
			}
		}
		
		$vendors_options = array( '' => __( 'Please Select', 'eddvoucher' ) );
		$vendors_data = get_users( array( 'role' => EDD_VOU_VENDOR_ROLE ) );
		if( !empty( $vendors_data ) ) { // Check vendor users are not empty
			foreach ( $vendors_data as $vendors ) {
				$vendors_options[$vendors->ID] = $vendors->display_name . ' (#' . $vendors->ID . ' &ndash; ' . sanitize_email( $vendors->user_email ) . ')';
			}
		}
		
		// Get usability
		$using_type = get_post_meta( $post->ID, $prefix . 'using_type', true );
		
		// Get voucher codes
		$voucher_codes = get_post_meta( $post->ID, $prefix . 'codes', true );
		
		$total_avail_desc_class = empty( $using_type ) ? ' edd-vou-display-none ' : '';
		
		$based_on_purchase_opt = array(
										'7' 		=> '7 Days',
										'15' 		=> '15 Days',
										'30' 		=> '1 Month (30 Days)',
										'90' 		=> '3 Months (90 Days)',
										'180' 		=> '6 Months (180 Days)',
										'365' 		=> '1 Year (365 Days)',
										'cust'		=> 'Custom',
									);
		
		// Voucher Code Error
		$vou_codes_error_class 	= ' edd-vou-display-none ';
		$codes_error_msg 		= '<br/><span id="edd_vou_codes_error" class="edd-vou-codes-error ' . $vou_codes_error_class . '">' . __( 'Please enter atleast 1 voucher code.', 'eddvoucher' ) . '</span>';
		$days_error_msg	 		= '<span id="edd_vou_days_error" class="edd-vou-days-error edd-vou-error ' . $vou_codes_error_class . '">' . __( ' Please enter valid days.', 'eddvoucher' ) . '</span>';
		$website_url_error_msg 	= '<br/><span id="edd_vou_website_url_error" class="edd-vou-website-url-error edd-vou-error ' . $vou_codes_error_class . '">' . __( ' Please enter valid url.', 'eddvoucher' ) . '</span>';
		
	?>
		<div class="edd-wpsd-metabox-tabs-div">
		
			<?php
			
				edd_vou_content_begin();
	
					//Enable Voucher Code
					edd_vou_add_checkbox( array( 'id' => $prefix . 'enable', 'name'=> __( 'Enable Voucher Codes:', 'eddvoucher' ), 'desc' => __( 'To enable the Voucher for this Download check the "Enable Voucher Codes" check box.', 'eddvoucher' ) ) );
					
					//Enable Recipient Name
					edd_vou_add_checkbox( array( 'id' => $prefix . 'enable_recipient_name', 'name'=> __( 'Enable Recipient Name:', 'eddvoucher' ), 'desc' => __( 'To enable the Recipient Name on product page.', 'eddvoucher' ) ) );
					
					echo "<tr><td></td><td>";
					$this->edd_vou_add_cust_text( array( 'id' => $prefix . 'recipient_name_label', 'class' => 'edd_vou_recipient_name', 'name' => __( 'Label:', 'eddvoucher' ), 'description' => __( '', 'eddvoucher' ) . $days_error_msg ) );
					$this->edd_vou_add_cust_text( array( 'id' => $prefix . 'recipient_name_max_length', 'class' => 'edd_vou_recipient_name', 'name' => __( 'Max Length:', 'eddvoucher' ), 'description' => __( '', 'eddvoucher' ) . $days_error_msg ) );
					$this->edd_vou_add_cust_checkbox( array( 'id' => $prefix . 'recipient_name_is_required', 'class' => 'edd_vou_recipient_name', 'name' => __('Required:', 'eddvoucher' ), 'description' => __( 'Make this field required in order to add a voucher product to the cart', 'eddvoucher' ) ) );
					echo "</td></tr>";
					
					//Enable Recipient Email
					edd_vou_add_checkbox( array( 'id' => $prefix . 'enable_recipient_email', 'name'=> __( 'Enable Recipient Email:', 'eddvoucher' ), 'desc' => __( 'To enable the Recipient Email on product page.', 'eddvoucher' ) ) );
					
					echo "<tr><td></td><td>";
					$this->edd_vou_add_cust_text( array( 'id' => $prefix . 'recipient_email_label', 'class' => 'edd_vou_recipient_email', 'name' => __( 'Label:', 'eddvoucher' ), 'description' => __( '', 'eddvoucher' ) . $days_error_msg ) );					
					$this->edd_vou_add_cust_checkbox( array( 'id' => $prefix . 'recipient_email_is_required', 'class' => 'edd_vou_recipient_email', 'name' => __('Required:', 'eddvoucher' ), 'description' => __( 'Make this field required in order to add a voucher product to the cart', 'eddvoucher' ) ) );
					echo "</td></tr>";
						
					//Enable Recipient Message
					edd_vou_add_checkbox( array( 'id' => $prefix . 'enable_recipient_message', 'name'=> __( 'Enable Recipient Message:', 'eddvoucher' ), 'desc' => __( 'To enable the Recipient Message on product page.', 'eddvoucher' ) ) );

					echo "<tr><td></td><td>";
					$this->edd_vou_add_cust_text( array( 'id' => $prefix . 'recipient_message_label', 'class' => 'edd_vou_recipient_message', 'name' => __( 'Label:', 'eddvoucher' ), 'description' => __( '', 'eddvoucher' ) . $days_error_msg ) );
					$this->edd_vou_add_cust_text( array( 'id' => $prefix . 'recipient_message_max_length', 'class' => 'edd_vou_recipient_message', 'name' => __( 'Max Length:', 'eddvoucher' ), 'description' => __( '', 'eddvoucher' ) . $days_error_msg ) );
					$this->edd_vou_add_cust_checkbox( array( 'id' => $prefix . 'recipient_message_is_required', 'class' => 'edd_vou_recipient_message', 'name' => __('Required:', 'eddvoucher' ), 'description' => __( 'Make this field required in order to add a voucher product to the cart', 'eddvoucher' ) ) );
					echo "</td></tr>";
					
					//PDF Template
					edd_vou_add_select( array( 'id' => $prefix . 'pdf_template', 'options' => $voucher_options, 'name'=> __( 'PDF Template:', 'eddvoucher' ), 'std' => array( '' ), 'desc' => __( 'Select a PDF template. Leave it empty to use the template from the settings page.', 'eddvoucher' ) ) );
					
					//Vendor User
   					edd_vou_add_select( array( 'id' => $prefix . 'vendor_user', 'class' => 'chosen_select', 'options' => $vendors_options, 'name'=> __( 'Vendor User:', 'eddvoucher' ), 'desc' => __( 'Please select the vendor user.', 'eddvoucher' ) ) );
					
					//add the image voucher logo
					edd_vou_add_image( array( 'id' => $prefix . 'logo', 'name'=> __( 'Vendor\'s Logo:', 'eddvoucher' ), 'std' => array(''), 'desc' => __( 'Allows you to upload a logo of the vendor for which this Voucher is valid. The logo will also be displayed on the PDF document.', 'eddvoucher' ) ) );
					
					//voucher's type to use it
					edd_vou_add_radio( array( 'id' => $prefix . 'using_type', 'class' => 'edd-vou-using-type', 'options' => array( '0' => __( 'One time only', 'eddvoucher' ), '1' => __( 'Unlimited', 'eddvoucher' ) ), 'name'=> __( 'Usage Limits:', 'eddvoucher' ), 'std'=> array( 'no' ), 'desc' => sprintf( __( 'Choose how you wanted to use vouchers codes. %sif you set usability "%sone time only%s" then it will automatically set download quantity equal to number of voucher codes entered and it will automatically decrease quanity  by 1 when it get purchased.
if you set usability "%sunlimited%s" then plugin will automatically generate unique voucher codes when download purchased.', 'eddvoucher' ), '<br />', '<b>', '</b>', '<b>', '</b>' ) ) );
					
					//available voucher codes
					edd_vou_add_text( array( 'id' => $prefix . 'avail_total', 'name'=> __( 'Available Voucher Codes:', 'eddvoucher' ), 'desc' => __( 'Enter the amount of available voucher codes.', 'eddvoucher' ) . '<span class="edd-vou-avail-code-desc ' . $total_avail_desc_class . '">' . __( ' Leave it empty for unlimited purchases.', 'eddvoucher' ) . '</span>' ) );
					
					//voucher's code comma seprated
					edd_vou_add_textarea( array( 'id' => $prefix . 'codes', 'name'=> __( 'Voucher Codes:', 'eddvoucher' ) . '<span class="edd-vou-codes-error"> *</span>', 'desc' => __( 'If you have a list of Voucher Codes you can copy and paste them in to this option. Make sure, that they are comma separated.' . $codes_error_msg, 'eddvoucher' ) ) );
					
					//used voucher codes field
					edd_vou_add_importcsv( array( 'id' => $prefix . 'import_csv', 'btntext' => __( 'Generate / Import Codes', 'eddvoucher' ), 'name' => __( 'Generate / Import Codes:', 'eddvoucher' ), 'desc' => __( 'Here you can import a csv file with voucher vodes or you can enter the prefix, pattern and extension will automatically create the voucher codes.', 'eddvoucher' ) ) );
					
					//purchased voucher codes field
					edd_vou_add_purchasedcodes( array( 'id' => $prefix . 'purchased_codes', 'btntext' => __( 'Purchased Voucher Codes', 'eddvoucher' ), 'name' => __( 'Purchased Voucher Code:', 'eddvoucher' ), 'desc' => __( 'Click on the button to see a list of all purchased voucher vodes.', 'eddvoucher' ) ) );
					
					//used voucher codes field
					edd_vou_add_usedvoucodes( array( 'id' => $prefix . 'used_codes', 'btntext' => __( 'Used Voucher Codes', 'eddvoucher' ), 'name' => __( 'Used Voucher Code:', 'eddvoucher' ), 'desc' => __( 'Click on the button to see a list of all Used voucher vodes.', 'eddvoucher' ) ) );
					
					//voucher expiration date type
					edd_vou_add_radio( array( 'id' => $prefix . 'exp_type', 'class' => 'edd-vou-using-type', 'options' => array( 'specific_date' => __( 'Specific Time', 'eddvoucher' ), 'based_on_purchase' => __( 'Based on purchase', 'eddvoucher' ) ), 'name'=> __( 'Expiration Date Type:', 'eddvoucher' ), 'default'=> array( 'specific_date' ), 'desc' => __( 'Please select Expiration Date Type either specific time or set date based on purchased voucher date like After 7 days, 30 days, 1 year etc.', 'eddvoucher' ) ) );
					
					edd_vou_add_custom_select( array( 'id' => $prefix . 'days_diff', 'class' => 'chosen_select _edd_vou_days_diff', 'options' => $based_on_purchase_opt, 'name'=> __( 'Expiration Days:', 'eddvoucher' ), 'desc' => __( '', 'eddvoucher' ), 'sign' => __( ' After purchase', 'eddvoucher' ) ) );
					
					edd_vou_add_custom_text( array( 'id' => $prefix . 'custom_days', 'class' => 'custom-days-text', 'name'=> __( 'Custom Days:', 'eddvoucher' ), 'sign' => __( ' Days after purchase', 'eddvoucher' ), 'desc' => __( ''.$days_error_msg, 'eddvoucher' ) ) );
					
					//voucher start date time
					edd_vou_add_datetime( array( 'id' => $prefix . 'start_date', 'name' => __('Start Date:', 'eddvoucher'),'std' => array(''),'desc' => __('If you want to make the Voucher Code(s) valid for a specific time only, you can enter an start date here. If the Voucher Code never expires, then leave that option blank.', 'eddvoucher'),'format'=>'dd-mm-yy' ) );
					
					//voucher expiration date time
					edd_vou_add_datetime( array( 'id' => $prefix . 'exp_date', 'name' => __('Expiration Date:', 'eddvoucher'),'std' => array(''),'desc' => __('If you want to make the Voucher Code(s) valid for a specific time only, you can enter an expiration date here. If the Voucher Code never expires, then leave that option blank.', 'eddvoucher'),'format'=>'dd-mm-yy' ) );
					
					//vendor's address
					edd_vou_add_textarea( array( 'id' => $prefix . 'address_phone', 'name'=> __( 'Vendor\'s Address:', 'eddvoucher' ), 'desc' => __( 'Here you can enter the complete Vendor\'s address. This will be displayed on the PDF document sent to the customers so that they know where to redeem this Voucher. Limited HTML is allowed.', 'eddvoucher' ) ) );
					
					//vendor's website
					edd_vou_add_text( array( 'id' => $prefix . 'website', 'name'=> __( 'Website URL:', 'eddvoucher' ), 'desc' => __( 'Enter the Vendor\'s website URL here. This will be displayed on the PDF document sent to the customer.'.$website_url_error_msg, 'eddvoucher' ) ) );
					
					//using instructions of voucher
					edd_vou_add_textarea( array( 'id' => $prefix . 'how_to_use', 'name'=> __( 'Redeem Instructions:', 'eddvoucher' ), 'desc' => __( 'Within this option you can enter instructions on how this Voucher can be redeemed. This instruction will then be displayed on the PDF document sent to the customer after successful purchase. Limited HTML is allowed.', 'eddvoucher' ) ) );
					
					//location fields
					$voucherlocations = array( 
												'0'	=>	array( 'id' => $prefix. 'locations',  'name'=> __( 'Location:', 'eddvoucher' ), 'desc' => __( 'Enter the address of the location where the Voucher Code can be redeemed. This will be displayed on the PDF document sent to the customer. Limited HTML is allowed.', 'eddvoucher' )),
												'1'	=>	array( 'id' => $prefix. 'map_link', 'name'=> __( 'Location Map Link:', 'eddvoucher' ), 'desc' => __( 'Enter a link to a Google Map for the location here. This will be displayed on the PDF document sent to the customer.', 'eddvoucher' ))
											);
					
					//locations for voucher block is available
					edd_vou_add_repeater_block( array( 'id' => $prefix. 'avail_locations', 'name' => __( 'Locations:', 'eddvoucher' ), 'desc' => __( 'If the Vendor of the Voucher has more than one location where the Voucher can be redeemed, then you can add all the locations within this option.', 'eddvoucher' ), 'fields' => $voucherlocations ) );
					
				edd_vou_content_end();
						
			?>
		
		</div>
		
	<?php
	
	}
	
	/*
	 * Save meta
	 *
	 * @package Easy Digital Downloads - Voucher Extension 
	 * @since 1.0.0
	 */
	function edd_vou_save_meta( $post_id ) {
		
		global $post_type;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$post_type_object = get_post_type_object( $post_type );
		
		// Check for which post type we need to add the meta box
		$pages = edd_vou_get_meta_pages();

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                // Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
		|| ( ! in_array( $post_type, $pages ) )              // Check if current post type is supported.
		|| ( ! check_admin_referer( EDD_VOU_PLUGIN_BASENAME, 'at_edd_vou_meta_box_nonce') )      // Check nonce - Security
		|| ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )       // Check permission
		{
		  return $post_id;
		}
		
		// Enable Voucher Codes
		$enable = isset( $_POST[ $prefix.'enable' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'enable', $this->model->edd_vou_escape_slashes_deep( $enable ) );
		
		//Enable Recipient Name
		$enable_recipient_name = isset( $_POST[ $prefix.'enable_recipient_name' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'enable_recipient_name', $this->model->edd_vou_escape_slashes_deep( $enable_recipient_name ) );
						
		$recipient_name_label = !empty( $_POST[ $prefix.'recipient_name_label' ] ) ? trim( $_POST[ $prefix.'recipient_name_label' ] ) : '';		
		update_post_meta( $post_id, $prefix.'recipient_name_label', $recipient_name_label );
		
		$recipient_name_max_length	= !empty( $_POST[ $prefix.'recipient_name_max_length' ] ) && is_numeric( $_POST[$prefix.'recipient_name_max_length'] ) ? trim(round ( $_POST[ $prefix.'recipient_name_max_length' ] ) ) : '';
		update_post_meta( $post_id, $prefix.'recipient_name_max_length', $recipient_name_max_length );
		
		$recipient_name_is_required = isset( $_POST[ $prefix.'recipient_name_is_required' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'recipient_name_is_required', $this->model->edd_vou_escape_slashes_deep( $recipient_name_is_required ) );
				
		//Enable Recipient Email
		$enable_recipient_email = isset( $_POST[ $prefix.'enable_recipient_email' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'enable_recipient_email', $this->model->edd_vou_escape_slashes_deep( $enable_recipient_email ) );
		
		$recipient_email_label = !empty( $_POST[ $prefix.'recipient_email_label' ] ) ? trim( $_POST[ $prefix.'recipient_email_label' ] ) : '';
		update_post_meta( $post_id, $prefix.'recipient_email_label', $recipient_email_label );		
		
		$recipient_email_is_required = isset( $_POST[ $prefix.'recipient_email_is_required' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'recipient_email_is_required', $this->model->edd_vou_escape_slashes_deep( $recipient_email_is_required ) );
		
		//Enable Recipient message
		$enable_recipient_message = isset( $_POST[ $prefix.'enable_recipient_message' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'enable_recipient_message', $this->model->edd_vou_escape_slashes_deep( $enable_recipient_message ) );
				
		$recipient_message_label = !empty( $_POST[ $prefix.'recipient_message_label' ] ) ? trim( $_POST[ $prefix.'recipient_message_label' ] ) : '';
		update_post_meta( $post_id, $prefix.'recipient_message_label', $recipient_message_label );
		
		$recipient_message_max_length = !empty( $_POST[ $prefix.'recipient_message_max_length' ] )  && is_numeric( $_POST[$prefix.'recipient_message_max_length'] ) ? trim(round ( $_POST[ $prefix.'recipient_message_max_length' ] ) ) : '';
		update_post_meta( $post_id, $prefix.'recipient_message_max_length', $recipient_message_max_length );
		
		$recipient_message_is_required = isset( $_POST[ $prefix.'recipient_message_is_required' ] ) ? 'on' : '';
		update_post_meta( $post_id, $prefix.'recipient_message_is_required', $this->model->edd_vou_escape_slashes_deep( $recipient_message_is_required ) );
		
		// PDF Template
		update_post_meta( $post_id, $prefix.'pdf_template', $_POST[$prefix.'pdf_template'] );
		
		// Vendor User
		update_post_meta( $post_id, $prefix.'vendor_user', $_POST[$prefix.'vendor_user'] );
		
		// Redeem Instructions
		update_post_meta( $post_id, $prefix.'how_to_use', $this->model->edd_vou_escape_slashes_deep( $_POST[$prefix.'how_to_use'], true, true ) );
		
		// Logo
		update_post_meta( $post_id, $prefix.'logo', $_POST[$prefix.'logo'] );
		
		$exp_type = isset( $_POST[$prefix.'exp_type'] ) ? $_POST[$prefix.'exp_type'] : 'specific_date';
		update_post_meta( $post_id, $prefix.'exp_type', $exp_type );
		
		update_post_meta( $post_id, $prefix.'days_diff', $_POST[$prefix.'days_diff'] );
		
		$custom_days	=  !empty( $_POST[$prefix.'custom_days']) && is_numeric( $_POST[$prefix.'custom_days'] ) ? trim(round ( $_POST[$prefix.'custom_days'] ) ) : '';
		update_post_meta( $post_id, $prefix.'custom_days', $custom_days );
		
		// Start Date
		$start_date = $_POST[$prefix.'start_date'];
		if(!empty($start_date)) {
			$start_date = strtotime( $this->model->edd_vou_escape_slashes_deep( $start_date ) );
			$start_date = date('Y-m-d H:i:s',$start_date);
		}
		update_post_meta( $post_id, $prefix.'start_date', $start_date );
		
		// Expiration Date
		$exp_date = $_POST[$prefix.'exp_date'];
		if(!empty($exp_date)) {
			$exp_date = strtotime( $this->model->edd_vou_escape_slashes_deep( $exp_date ) );
			$exp_date = date('Y-m-d H:i:s',$exp_date);
		}
		update_post_meta( $post_id, $prefix.'exp_date', $exp_date );
		
		// Voucher Codes
		$voucher_codes = isset( $_POST[$prefix.'codes'] ) ? $this->model->edd_vou_escape_slashes_deep( $_POST[$prefix.'codes'] ) : '';
		update_post_meta( $post_id, $prefix.'codes', $voucher_codes );
		
		// Usability
		$using_type = isset( $_POST[$prefix.'using_type'] ) ? $_POST[$prefix.'using_type'] : '0';
		update_post_meta( $post_id, $prefix.'using_type', $using_type );
		
		// Vendor's Address
		update_post_meta( $post_id, $prefix.'address_phone', $this->model->edd_vou_escape_slashes_deep( $_POST[$prefix.'address_phone'], true, true ) );
		
		// Website URL
		update_post_meta( $post_id, $prefix.'website', $this->model->edd_vou_escape_slashes_deep( $_POST[$prefix.'website'] ) );
		
		// Total Available codes
		update_post_meta( $post_id, $prefix.'avail_total', $this->model->edd_vou_escape_slashes_deep( $_POST[$prefix.'avail_total'] ) );
		
		// update available downloads count on bases of entered voucher codes
		if( isset( $_POST[$prefix.'codes'] ) ) {
			
			$voucount = '';
			$vouchercodes = trim( $_POST[$prefix.'codes'], ',' );
			if( !empty( $vouchercodes ) ) {
				$vouchercodes = explode( ',', $vouchercodes );
				$voucount = count( $vouchercodes );
			}
			if( isset( $_POST[$prefix.'using_type'] ) && empty( $_POST[$prefix.'using_type'] ) ) {// using type is only one time
			
				$avail_total = empty( $voucount ) ? '0' : $voucount;
				
				//update available count on bases of 
				update_post_meta( $post_id, $prefix.'avail_total', $avail_total );
			}
		}
		
		//update location and map links
		$availlocations = array();
		if( isset( $_POST[$prefix.'locations'] ) ) {
			
			$locations = $_POST[$prefix.'locations'];
			$maplinks = $_POST[$prefix.'map_link'];
			for ( $i = 0; $i < count( $locations ); $i++ ){
				if( !empty( $locations[$i] ) || !empty( $maplinks[$i])) { //if location or map link is not empty then
					$availlocations[$i][$prefix.'locations'] = $this->model->edd_vou_escape_slashes_deep( $locations[$i], true, true );
					$availlocations[$i][$prefix.'map_link'] = $this->model->edd_vou_escape_slashes_deep( $maplinks[$i] );
				}
			}
		}
		
		//update location and map links
		update_post_meta( $post_id, $prefix. 'avail_locations', $availlocations );
		
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		if(edd_vou_is_edit_page()) { // check metabox page
			
			add_action( 'add_meta_boxes', array( $this, 'edd_vou_add_meta_box' ) );
			
			add_action( 'save_post', array( $this, 'edd_vou_save_meta' ) );
				
		}
	}
	
	/**
	 * Show Field custom text box.
	 *
	 * @param string $field 
	 * @param string $meta 
	 * @since 1.0
	 * @access public
	 */
	function edd_vou_add_cust_text( $args, $echo = true ) {
		
		$html = '';
		$class = isset( $args['id'] ) ? $args['id'] : '';
		
		$new_field = array( 'type' => 'text', 'name' => 'Text Field', 'wrap_class' => $class, 'class' => '' );
		
		$field = array_merge( $new_field, $args );
		
		$meta = edd_vou_meta_value( $field );
		
		$html .= '<div class="form-field ' . $field['id'] . '_field" style="display: inline-block; "><label style="display: block; float: none; width: auto !important;" for="' . $field['id'] . '">' . $field['name'] . '</label>';
		
		$html .= "<input type='text' class='edd-vou-meta-text edd-meta-text-width {$field['class']}' name='{$field['id']}' id='{$field['id']}' value='{$meta}' />";
		
		if ( isset( $field['desc'] ) && $field['desc'] )
			$html .= '<span class="description"></span>';
			
		$html .= '</div>';
		
		if($echo) {
			echo $html;
		} else {
			return $html;
		}
	}
	
	/**
	 * Show Custom Field Checkbox.
	 *
	 * @param string $field 
	 * @param string $meta 
	 * @since 1.0
	 * @access public
	 */
	function edd_vou_add_cust_checkbox( $args, $echo = true ) {
		
		$html = '';
	
		$new_field = array( 'type' => 'checkbox', 'name' => 'Checkbox Field', 'class' => '' );
		$field = array_merge( $new_field, $args );
	
		$meta = edd_vou_meta_value( $field );
		
		$html .= '<div class="form-field ' . $field['id'] . '_field" style="display: inline-block; "><label style="display: block; float: none; width: auto !important;" for="' . $field['id'] . '">' . $field['name'] . '</label>';
		
		$html .= "<input type='checkbox' class='woo-vou-meta-checkbox' name='{$field['id']}' id='{$field['id']}'" . checked(!empty($meta), true, false) . " />";
		
		if ( isset( $field['desc'] ) && $field['desc'] )
			$html .= '<span class="description"></span>';
			
		$html .= '</div>';
		
		if($echo) {
			echo $html;
		} else {
			return $html;
		}
	}
}
?>