<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Pages Class
 *
 * Handles all the different features and functions
 * for the front end pages.
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
class EDD_Vou_Public {
	
	public $model;
	
	public function __construct() {
		
		global $edd_vou_model;
		
		$this->model = $edd_vou_model;
	}
	
	/**
	 * Handles to update voucher details in order data
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_process_voucher_codes( $payment_id, $payment_data ) {
		
		global $edd_options;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		// add code for add recipients fields to latest version of edd
		$this->model->edd_vou_save_recipient_field( $payment_id, $payment_data );
		
		$cart_details 	= edd_get_payment_meta_cart_details( $payment_id );
		$userdata    	= edd_get_payment_meta_user_info( $payment_id );
		
		$buyername 		= isset( $userdata['first_name'] ) ? trim( $userdata['first_name'] ) : '';
		$userlastname 	= isset( $userdata['last_name'] ) ? trim( $userdata['last_name'] ) : '';
		$useremail 		= isset( $userdata['email'] ) ? $userdata['email'] : '';
		
		//Get payment history data
		$postdata = get_post( $payment_id );
		
		$order_date = ( isset( $postdata->post_date ) && !empty( $postdata->post_date ) ) ? $postdata->post_date : '';
		
		$voucherdata = $vouchermetadata = array();
		
		//get voucher order details 
		$voucherdata = get_post_meta( $payment_id, $prefix.'order_details', true );
		
		// Check easy digital downloads order details & voucher data are not empty so code get executed once only
		if ( is_array( $cart_details ) && empty( $voucherdata ) ) {
			
			// Check cart details
			foreach ( $cart_details as $download_data ) {
				
				$maindownloadid = $download_data['id'];
				
				// "bundle" or "default"
				$download_type = edd_get_download_type( $maindownloadid );
				
				$all_downloads = array( $maindownloadid );
				if( $download_type == 'bundle' ) { // Check download type is bundle
					
					$all_downloads = edd_get_bundled_products( $maindownloadid );
				}
				
				$downloadqty = $download_data['quantity'];
				
				foreach ( $all_downloads as $downloadid ) {
				
					if( !empty( $downloadid ) && $downloadid > 0 ) {
						
						//Total voucher codes
						$avail_total_codes = get_post_meta( $downloadid, $prefix.'avail_total', true );
						
						//voucher codes
						$vou_codes = get_post_meta( $downloadid, $prefix . 'codes', true );
						
						//check enable voucher
						if( $this->model->edd_vou_check_enable_voucher( $downloadid ) && !empty( $vou_codes ) && $avail_total_codes != '0' ) {
							
							//usability
							$using_type = get_post_meta( $downloadid, $prefix.'using_type', true );
							
							
							if( $avail_total_codes > 0 ) {
								
								$avail_total_codes = $avail_total_codes - $downloadqty;
								
								//if voucher using type is only one time
								if( empty( $using_type ) ) {
									
									$avail_total_codes = ( $avail_total_codes < 0 ) ? 0 : $avail_total_codes;
									
								} else {
									
									$avail_total_codes = ( $avail_total_codes < 0 ) ? '' : $avail_total_codes;
								}
								
								//update total voucher codes
								update_post_meta( $downloadid, $prefix.'avail_total', $avail_total_codes );
								
							}
							
							// Get download items
							$download_items = isset( $download_data['item_number'] ) ? $download_data['item_number'] : array();
							
							// If product is variable then take its price id
							$price_id = isset( $download_items['options'] ) && isset( $download_items['options']['price_id'] ) ? $download_items['options']['price_id'] : null;
							
							//pdf template
							$pdf_template = get_post_meta( $downloadid, $prefix.'pdf_template', true );
							
							//vendor user
							$vendor_user = get_post_meta( $downloadid, $prefix.'vendor_user', true );
							
							//vendor logo
							$vendor_logo = get_post_meta( $downloadid, $prefix.'logo', true );
							
							//start date
							$start_date = get_post_meta( $downloadid, $prefix.'start_date', true );
							
							//expiry data
							$exp_date = get_post_meta( $downloadid, $prefix.'exp_date', true );
							
							$exp_type = get_post_meta( $downloadid, $prefix.'exp_type', true );
							$custom_days = '';
							
							if( $exp_type == 'based_on_purchase' ){
								
								$days_diff	= get_post_meta( $downloadid, $prefix.'days_diff', true );
								
									if( $days_diff == 'cust' ) {
										
										$custom_days	= get_post_meta( $downloadid, $prefix.'custom_days', true );
										$custom_days	= isset( $custom_days ) ? $custom_days : '';
										if( !empty( $custom_days ) ){
											$add_days 		= '+'.$custom_days.' days';										
											$exp_date 		= date( 'Y-m-d',strtotime( $order_date . $add_days ) );
										} else {
											$exp_date 		= date( 'Y-m-d' );
										}
										
									} else {
										
										$custom_days = $days_diff;
										
										$add_days 	= '+'.$custom_days.' days';
										
										$exp_date 	= date( 'Y-m-d',strtotime( $order_date . $add_days ) );
									}							
								
							}
							
							//vendor address
							$vendor_address = get_post_meta( $downloadid, $prefix.'address_phone', true );
							
							//website url
							$website_url = get_post_meta( $downloadid, $prefix.'website', true );
							
							//redeem instruction
							$redeem = get_post_meta( $downloadid, $prefix.'how_to_use', true );
							
							//locations
							$avail_locations = get_post_meta( $downloadid, $prefix.'avail_locations', true );
							
							//voucher code
							$vouchercodes = get_post_meta( $downloadid, $prefix.'codes', true );
							$vouchercodes = trim( $vouchercodes, ',' );
							
							//explode all voucher codes
							$salecode = explode( ',', $vouchercodes );
							
							// trim code
							foreach ( $salecode as $code_key => $code ) {
								$salecode[$code_key] = trim( $code );
							}
							
							$allcodes = '';
							
							
							//if voucher useing type is one time only 
							if( empty( $using_type ) ) { 
								
								for ( $i = 0; $i < $downloadqty; $i++ ) {
									
									//get first voucher code
									$voucode = $salecode[$i];
									
									//unset first voucher code to remove from all codes
									unset( $salecode[$i] );
									
									$allcodes .= $voucode.', ';
								}
								
								//after unsetting first code make one string for other codes
								$lessvoucodes = implode( ', ',$salecode );
								update_post_meta( $downloadid, $prefix.'codes', trim( $lessvoucodes ) );

								
							} else { //if voucher useing type is more than one time then generate voucher codes
								
								//if user buy more than 1 quantity of voucher
								if( isset( $downloadqty ) && $downloadqty > 1 ) {
									
									for ( $i = 1; $i <= $downloadqty; $i++ ) {
										
										$voucode = '';
										
										//make voucher code
										$randcode = array_rand( $salecode );
										
										if( !empty( $buyername ) ) {
											$voucode .= $buyername.'-';
										}
										if( !empty( $salecode[$randcode] ) && trim( $salecode[$randcode] ) != '' ) {
											$voucode .= trim( $salecode[$randcode] ).'-';
										}
										$voucode .= $payment_id.'-'.$downloadid;
										
										// If product is variable and uses type is multiple
										if( isset($price_id) ) {
											$voucode .= '-'.$price_id;
										}
										
										$voucode .= '-'.$i;
										$allcodes .= $voucode.', ';
									}
									
								} else {
									
									//make voucher code when user buy single quantity
									$randcode = array_rand( $salecode );
									
									$voucode = '';
									
									if( !empty( $buyername ) ) {
										$voucode .= $buyername.'-';
									}
									if( !empty( $salecode[$randcode] ) && trim( $salecode[$randcode] ) != '' ) {
										$voucode .= trim( $salecode[$randcode] ).'-';
									}
									$voucode .= $payment_id.'-'.$downloadid;
									
									// If product is variable and uses type is multiple
									if( isset($price_id) ) {
										$voucode .= '-'.$price_id;
									}
									
									$allcodes .= $voucode.', ';
								}
								
							}
							
							$voucher_key = $downloadid;
							
							// If product is variable then making unique voucher key by taking its price id
							if( !empty( $price_id ) ) {
								$voucher_key = $downloadid . '_' . $price_id;
							}
							
							$allcodes = trim( $allcodes, ', ' );
							$downloadvoudata = array(
														'download_id'	=> 	$downloadid,
														'enablevou'		=>	'1',
														'price_id'		=>	$price_id,
														'codes'			=>	$allcodes,
													);
							
							$voucherdata[$voucher_key] = $downloadvoudata;
							
							// Append for voucher meta data into order		
							$downloadvoumetadata = array(
															'user_email'		=>	$useremail,
															'pdf_template'		=>	$pdf_template,
															'vendor_logo'		=>	$vendor_logo,
															'start_date'		=>	$start_date,
															'exp_date'			=>	$exp_date,
															'using_type'		=>	$using_type,
															'vendor_address'	=>	$vendor_address,
															'website_url'		=>	$website_url,
															'redeem'			=>	$redeem,
															'avail_locations'	=>	$avail_locations,
														);
							
							$recipient_detail = array(); // initialize recipient array to store it details
							
							if( isset( $download_items[$prefix.'recipient_name'] ) )
								$recipient_detail['recipient_name']		= $download_items[$prefix.'recipient_name'];
							
							if( isset( $download_items[$prefix.'recipient_email'] ) )
								$recipient_detail['recipient_email']	= $download_items[$prefix.'recipient_email'];
							
							if( isset( $download_items[$prefix.'recipient_message'] ) )
								$recipient_detail['recipient_message']	= $download_items[$prefix.'recipient_message'];
							
							// merge recipient details to download voucher metadata
							$downloadvoumetadata = array_merge( $downloadvoumetadata, $recipient_detail );						
							
							$vouchermetadata[$voucher_key] = $downloadvoumetadata;						
							
							$all_vou_codes = explode( ', ', trim( $allcodes ) );
							
							foreach ( $all_vou_codes as $vou_code ) {
								
								$vou_code = trim( $vou_code, ',' );
								$vou_code = trim( $vou_code );
								
								//Insert voucher details into custom post type with seperate voucher code
								$vou_codes_args = array(
															'post_title'		=>	$payment_id,
															'post_content'		=>	'',
															'post_status'		=>	'pending',
															'post_type'			=>	EDD_VOU_CODE_POST_TYPE,
															'post_parent'		=>	$downloadid
														);
								if( !empty( $vendor_user ) ) { // Check vendor user is not empty
									
									$vou_codes_args['post_author'] = $vendor_user;
								}
								
								$vou_codes_id = wp_insert_post( $vou_codes_args );
								
								if( $vou_codes_id ) { // Check voucher codes id is not empty
								
									// update buyer first name
									update_post_meta( $vou_codes_id, $prefix.'first_name', $buyername );
									// update buyer last name
									update_post_meta( $vou_codes_id, $prefix.'last_name', $userlastname );
									// update order id
									update_post_meta( $vou_codes_id, $prefix.'order_id', $payment_id );
									// update order date
									update_post_meta( $vou_codes_id, $prefix.'order_date', $order_date );
									// update start date
									update_post_meta( $vou_codes_id, $prefix.'start_date', $start_date );
									// update expires date
									update_post_meta( $vou_codes_id, $prefix.'exp_date', $exp_date );
									// update purchased codes
									update_post_meta( $vou_codes_id, $prefix.'purchased_codes', $vou_code );
									// update price id if product is variable
									update_post_meta( $vou_codes_id, $prefix.'price_id', $price_id );
								}
							}
							
							if( !empty( $vendor_user ) ) { // Check vendor user is not empty
								
								$download_title	= '';
								
								$download_title = $this->model->edd_vou_get_product_name( $payment_id, $downloadid ); // Taking product name
								
								if( !empty( $price_id ) ) {//For Variation
									$download_title .= ' - ' . edd_get_price_option_name( $downloadid, $price_id, $payment_id );
								}
								
								if( empty( $download_title ) ) {
									$download_title		= get_the_title( $downloadid );
								}
								
								$site_name			= get_bloginfo( 'name' );
								$vendor_user_data	= get_user_by( 'id', $vendor_user );
								$vendor_email		= isset( $vendor_user_data->user_email ) ? $vendor_user_data->user_email : '';
													
								$vou_shortcodes		= array( "{site_name}", "{download_title}", "{voucher_code}" );
								$vou_replacecodes	= array( $site_name, $download_title, trim( $allcodes, ',' ) );
								
								$subject	= isset( $edd_options['vou_email_subject'] ) ? $edd_options['vou_email_subject'] : '';
								$subject	= str_replace( $vou_shortcodes, $vou_replacecodes, $subject );
								
								$message	= isset( $edd_options['vou_email_body'] ) ? $edd_options['vou_email_body'] : '';
								$message	= str_replace( $vou_shortcodes, $vou_replacecodes, nl2br( $message ) );
								
								//Get vendor sale enabled option
					 			 
								$vou_sale_notification_enabled	= isset( $edd_options['vou_sale_notification_disable'] ) ? $edd_options['vou_sale_notification_disable'] : '';
					 
								if( $vou_sale_notification_enabled != 1 ) {
								
									// send email to user
									$this->model->edd_vou_send_email( $vendor_email, $subject, $message );	
								
								}
							}
						}
					}
				}
			}
			
			$edd_settings = get_option( 'edd_settings', true );
			$multiple_pdf = isset( $edd_settings['multiple_pdf'] ) ? $edd_settings['multiple_pdf'] : '';
			
			//update multipdf option in ordermeta
			update_post_meta( $payment_id, $prefix . 'multiple_pdf', $multiple_pdf );
			
			if( !empty( $voucherdata ) ) { // Check voucher data are not empty
			
				//update voucher order details
				update_post_meta( $payment_id, $prefix.'order_details', $voucherdata );
			}
			
			if( !empty( $vouchermetadata ) ) { // Check voucher meta data are not empty
			
				//update voucher order details with all meta data ( not in use for now)
				update_post_meta( $payment_id, $prefix.'meta_order_details', $vouchermetadata );
			}
		}
	}
	
	/**
	 * Display Download Voucher Link
	 * 
	 * Handles to display download voucher link for user
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_order_download_files( $downloadfiles, $downloadid, $price_id ) {
		
		global $edd_receipt_args, $edd_payment_id;
		
		//get prefix
		$prefix 	= EDD_VOU_META_PREFIX;
		
		//initilize payment array
		$payment	= array();
		
		if( !empty( $edd_receipt_args['id'] ) ) {
			$payment	= get_post( $edd_receipt_args['id'] );
		}
		
		$order_id	= isset( $payment->ID ) ? $payment->ID : '';
		
		if( is_admin() && empty( $edd_payment_id ) ) {
			return  $downloadfiles;
		}
		
		if( empty( $order_id ) ) { //if order_id is empty thent get it from global payment id
			
			$order_id = $edd_payment_id;
		}
		
		//if( !is_admin() ) { // Check front side
			
			//Get mutiple pdf option from options
			$multiple_pdf = get_post_meta( $order_id, $prefix . 'multiple_pdf', true );
			
			if( ( $this->model->edd_vou_check_enable_voucher( $downloadid ) ) ) {
				
				//check enable voucher
				if( $multiple_pdf == '1' ) { //If multiple pdf is set
					
					$vouchercodes	= $this->model->edd_vou_get_multi_voucher_key( $order_id, $downloadid, $price_id );
					
					foreach ( $vouchercodes as $codes ) {
						
						$downloadfiles[$codes] = array(
																'name' => __( 'Download Voucher', 'eddvoucher' ),
																'file' => get_permalink( $downloadid )
															);
					}
				} else {
					
					$downloadfiles['edd_vou_pdf'] = array(
																'name' => __( 'Download Voucher', 'eddvoucher' ),
													            'file' => get_permalink( $downloadid )
															);
				}
			}
		//}
		
		return $downloadfiles;
	}
	
	/**
	 * Display Download Voucher Link
	 * 
	 * Handles to display download voucher link for user
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_order_download_file_url_args( $params ) {
		
		global $edd_receipt_args, $edd_payment_id;
		
		$payment = array();
		if( !empty( $edd_receipt_args['id'] ) ) {
			$payment	= get_post( $edd_receipt_args['id'] );
		}
		
		$order_id	= isset( $payment->ID ) ? $payment->ID : '';		
		
		if( empty( $order_id ) ) {
			
			$order_id = $edd_payment_id;
		}
		$downloadid 	= isset( $params['download_id'] ) ? $params['download_id'] : '';
		
		// If product is variable then take variavle option id
		$price_id = !empty($params['price_id']) ? $params['price_id'] : '';
		
		// If product is variable then making product key
		if( !empty($price_id) ) {
			$product_data_id = $downloadid . '_' . $price_id;
		} else {
			$product_data_id = $downloadid;
		}
		
		if( $this->model->edd_vou_check_enable_voucher( $downloadid ) ) { //check enable voucher
			
			$vou_codes_key	= array();
			$vou_codes_key	= $this->model->edd_vou_get_multi_voucher_key( $order_id, $params['download_id'], $price_id );
			
			if( isset( $params['download_key'] ) && isset( $params['file'] ) && !empty( $params['file'] )
				&& ( $params['file'] == 'edd_vou_pdf' || in_array( $params['file'], $vou_codes_key ) ) ) { // Check front side & edd voucher pdf
				
				$voucher_key = $product_data_id;
				$meta_query = array(
									array( 
												'key' 	=> '_edd_payment_purchase_key',
												'value' => $params['download_key']
											)
								);
				$payment_data  	= edd_get_payments( array( 'meta_query' => $meta_query ) );
				$payment  		= isset( $payment_data['0'] ) ? $payment_data['0'] : array();
				
				//get voucher order details
				$ordervoudata = $this->model->edd_vou_get_post_meta_ordered( $payment->ID );
				$downloadvoudata = isset( $ordervoudata[$voucher_key] ) ? $ordervoudata[$voucher_key] : array();
				
				if( !empty( $downloadvoudata ) ) { // Check voucher download data
					
					$params['edd_voucher'] = '1';
				}
			}
		} // end enaable check
		
		return $params;
	}
	
	/**
	 * Add products summery page
	 *
	 * Adding products summery page
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	/*public function edd_vou_expired_message() {
		
		echo '<p class="out-of-stock"><span><strong>' . __( 'Out Of Stock', 'eddvoucher' ) . '</strong></span></p>';
	}*/
	
	/**
	 * Page Loaded
	 *
	 * Handles to page loaded
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	/*public function edd_vou_loaded() {
		
		if( !is_admin() ) { // check this is not admin 
		
			global $post;
			
			$downloadid	= isset( $post->ID ) ? $post->ID : '';
			$post_type	= isset( $post->post_type ) ? $post->post_type : '';
			$prefix		= EDD_VOU_META_PREFIX;
			
			if( !empty( $downloadid ) && $post_type == EDD_VOU_MAIN_POST_TYPE ) {
				
				//"bundle" or "default"
				$download_type = edd_get_download_type( $downloadid );								
				
				// Check download type is not bundle & voucher is enable & check available voucher codes is 0
				if( $download_type != 'bundle' && $this->model->edd_vou_check_out_of_stock( $downloadid ) ) {
					
					remove_action( 'edd_after_download_content', 'edd_append_purchase_link' );
					add_action( 'edd_after_download_content', array( $this , 'edd_vou_expired_message' ) );
				}
			}
		}
	}*/
	
	/**
	 * Download Process
	 *
	 * Handles to download process
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	//public function edd_vou_download_process( $download ) {
	//for edd version 2.0 greater or equal
	public function edd_vou_download_process( $requested_file, $download, $email, $order_id ) {
		
		// If product is variable
		$price_id = isset($_GET['price_id']) ? $_GET['price_id'] : '';		
		
		//Generate PDF
		$this->model->edd_vou_generate_pdf_voucher( $email, $download, $_GET['file'], $order_id, $price_id );
	}
	
	/**
	 * Add Error For Purchase Download
	 * 
	 * Handles to show to error when user purchase download
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 **/
	public function edd_vou_download_error() {
		
		$prefix = EDD_VOU_META_PREFIX;
			
		//get cart data
		$cartdata = edd_get_cart_contents();
		
		if( is_array( $cartdata ) ) { // Check cart data
		
			foreach ( $cartdata as $downloaddata ) {
				
				$downloadid = isset( $downloaddata['id'] ) ? $downloaddata['id'] : '';
				
				//get voucher enabled
				$voucher_enabled = $this->model->edd_vou_check_enable_voucher( $downloadid );
				
				//get total voucher codes
				$avail_total_codes = get_post_meta( $downloadid, $prefix.'avail_total', true );
				
				//get usability
				$using_type = get_post_meta( $downloadid, $prefix.'using_type', true );
				
				// "bundle" or "default"
				$download_type = edd_get_download_type( $downloadid );
				
				// check download type is not bundle & voucher is enable
				// buy quanity is greater then available copy
				if( $voucher_enabled && $download_type != 'bundle' && empty( $using_type ) && isset( $downloaddata['quantity'] ) && $downloaddata['quantity'] > $avail_total_codes ) {
				
					$error_message = sprintf( __( '%s item have not enough quantity.', 'eddvoucher' ), get_the_title( $downloadid ) );
					
					//set error to show to user
					edd_set_error( 'edd_vou_download_' . $downloadid, $error_message );
				}
			}
		}
	}
	
	/**
	 * Get Detail From Order ID
	 * 
	 * Handles to get product detail
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since  
	 */
	
	public function edd_vou_get_product_detail($order_id, $voucode, $voucodeid = '' ) {
	 
		ob_start();
		 
		require( EDD_VOU_DIR . '/includes/edd-vou-check-code-product-info.php' );
		$html = ob_get_clean();
		
		return apply_filters( 'edd_vou_get_product_detail', $html, $order_id, $voucode, $voucodeid );
	}
	/**
	 * Check Voucher Code
	 * 
	 * Handles to check voucher code
	 * is valid or invalid via ajax
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.1.0
	 */
	public function edd_vou_check_voucher_code() {
		
		global $current_user;
		
		$prefix                 = EDD_VOU_META_PREFIX;
		$download_title         = '';
		$expiry_Date		    = '';
		$response['upcoming']	= false;
		$response['expire']	    = false;
			
		// Check voucher code is not empty
		if( !empty( $_POST['voucode'] ) ) {
			
			//Voucher Code
			$voucode = $_POST['voucode'];
			
			$args = array(
								'fields' 	=> 'ids',
								'meta_query'=> array(
														array(
																	'key' 		=> $prefix . 'purchased_codes',
																	'value' 	=> $voucode
																),
														array(
																	'key'     	=> $prefix . 'used_codes',
																	'compare' 	=> 'NOT EXISTS'
														)
													)
							);
			
			//Get User roles
			$user_roles	= isset( $current_user->roles ) ? $current_user->roles : array();
			$user_role	= array_shift( $user_roles );
			
			// voucher admin roles
			$vou_admins	= edd_vou_get_voucher_admins();
			
			if( !in_array( $user_role, $vou_admins ) ) { // Check vendor user role
				$args['author'] = $current_user->ID;
			}
			
			$voucodedata = $this->model->edd_vou_get_voucher_details( $args );
			 
			$used_args = array(
									'fields' 	=> 'ids',
									'meta_query'=> array(
															array(
																		'key' 		=> $prefix . 'used_codes',
																		'value' 	=> $voucode
																	)
														)
								);
			
			if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
				$used_args['author'] = $current_user->ID;
			}
			
			$used_voucode_data = $this->model->edd_vou_get_voucher_details( $used_args ); // Getting used voucher code data
			 
			// Check voucher code ids are not empty
			if( !empty( $voucodedata ) && is_array( $voucodedata ) ) {
				
				$voucodeid = isset( $voucodedata[0] ) ? $voucodedata[0] : '';
				$download_title	= '';
				if( !empty( $voucodeid ) ) {
					
					$order_id	  = get_post_meta( $voucodeid , $prefix.'order_id' , true );
					$cart_details = edd_get_payment_meta_cart_details( $order_id );
					$orderdata 	  = $this->model->edd_vou_get_post_meta_ordered( $order_id );
					
					foreach ($cart_details as $download_data){
						
						$download_pdt_ids = array();
						$download_id      = $download_data['id'];
						$download_title	  =	get_the_title( $download_id );
						
						$download_items   = isset( $download_data['item_number'] ) ? $download_data['item_number'] : array();
						// Get variable option id
						$price_id         = isset( $download_items['options'] ) && isset( $download_items['options']['price_id'] ) ? $download_items['options']['price_id'] : null;
						
						// If product is variable
						$download_pdt_key = !empty( $price_id ) ? $download_id . '_' . $price_id : $download_id;
						$download_pdt_ids[$download_pdt_key] = $download_id;
						
						foreach ( $download_pdt_ids as $download_key => $download_id ) {
							
							//vouchers data
							$voucherdata 	= isset( $orderdata[$download_key] ) ? $orderdata[$download_key] : array();
							if( $voucherdata['codes'] == $voucode ) {
								$download_title	=	get_the_title( $download_id );
							}
						}
						
					}
				
				}
				
				$response['success']=sprintf( __( 'Voucher code is valid and this voucher code has been bought for %s. ' . "\n" . 'If you would like to redeem voucher code, Please click on the redeem button below:', 'eddvoucher' ), $download_title );
				
				//voucher start date
				$start_date = get_post_meta( $voucodeid , $prefix .'start_date' ,true );
				
				if( isset( $start_date ) && !empty( $start_date ) ) {

					if( $start_date > $this->model->edd_vou_current_date() ) {
						$response['upcoming'] = true;						
						$response['success'] = sprintf( __( 'This voucher code can redeem after %s for %s.' . "\n" ,'eddvoucher'), $this->model->edd_vou_get_date_format( $start_date , true ) ,$download_title );		
					}
				}
				
				//voucher expired date
				$expiry_Date = get_post_meta( $voucodeid , $prefix .'exp_date' ,true );
				
				if( isset( $expiry_Date ) && !empty( $expiry_Date ) ) {

					if( $expiry_Date < $this->model->edd_vou_current_date() ) {
						$response['expire'] = true;						
						$response['success'] = sprintf( __( 'Voucher code was expired on %s for %s. ' . "\n" ,'eddvoucher'), $this->model->edd_vou_get_date_format( $expiry_Date , true ) ,$download_title );		
					}
				}
				
				$response['product_detail'] = $this->edd_vou_get_product_detail( $order_id, $voucode, $voucodeid );
				
			} else if (!empty( $used_voucode_data ) && is_array( $used_voucode_data ) ) { // Check voucher code is used or not
				
				//Check voucher code id is used
				$voucodeid = isset( $used_voucode_data[0] ) ? $used_voucode_data[0] : '';
				
				// get used code date
				$used_code_date = get_post_meta( $voucodeid, $prefix.'used_code_date', true );
				
				//echo sprintf( __( 'Voucher code is invalid, was used on %s', 'eddvoucher' ), $this->model->edd_vou_get_date_format( $used_code_date, true ) );
				$response['used'] = sprintf( __( 'Voucher code is invalid, was used on %s', 'eddvoucher' ), $this->model->edd_vou_get_date_format( $used_code_date, true ) );
						
				
			} else {
				//echo 'error';
				$response['error'] = __( 'Voucher code doest not exist.', 'eddvoucher' );
			}
			//exit;
			
			if( isset( $_POST['ajax'] ) && $_POST['ajax'] == true ) {  // if request through ajax
				echo json_encode( $response );
				exit;	
			} else {
				return $response;
			}
			
		}
	}
	
	/**
	 * Save Voucher Code
	 * 
	 * Handles to save voucher code
	 * via ajax
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.1.0
	 */
	public function edd_vou_save_voucher_code() {
		
		$prefix = EDD_VOU_META_PREFIX;
			
		// Check voucher code is not empty
		if( !empty( $_POST['voucode'] ) ) {
			
			global $current_user;
			
			// Taking current user data
			$vou_user_id 	= isset($current_user->ID) 		? $current_user->ID 	: '';
			$vou_user_roles = isset($current_user->roles) 	? $current_user->roles 	: array();
			
			//Voucher Code
			$voucode = $_POST['voucode'];
			
			$args = array(
								'fields' 	=> 'ids',
								'meta_query'=> array(
														array(
																	'key' 		=> $prefix . 'purchased_codes',
																	'value' 	=> $voucode
																)
													)
							);
			if( in_array( EDD_VOU_VENDOR_ROLE, $vou_user_roles ) ) { // Check vendor user role
				$args['author'] = $vou_user_id;
			}
			$voucodedata = $this->model->edd_vou_get_voucher_details( $args );
			
			// Check voucher code ids are not empty
			if( !empty( $voucodedata ) && is_array( $voucodedata ) ) {
				
				//current date
				$today = $this->model->edd_vou_current_date();
				
				foreach ( $voucodedata as $voucodeid ) {
					
					// update used codes
					update_post_meta( $voucodeid, $prefix.'used_codes', $voucode );
					
					// update redeem by
					update_post_meta( $voucodeid, $prefix.'redeem_by', $vou_user_id );
					
					// update used code date
					update_post_meta( $voucodeid, $prefix.'used_code_date', $today );
					
					// break is neccessary so if 2 code found then only 1 get marked as completed.
					break;
				}
			}
			echo 'success';
			exit;
		}
	}
	
	/**
	 * Display Check Code Html
	 * 
	 * Handles to display check code html for user and admin
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_check_code_content() { ?>
		
		<table class="form-table edd-vou-check-code">
			<tr>
				<th>
					<label for="edd_vou_voucher_code"><?php _e( 'Enter Voucher Code', 'eddvoucher' ) ?></label>
				</th>
				<td>
					<input type="text" id="edd_vou_voucher_code" name="edd_vou_voucher_code" value="" />
					<input type="button" id="edd_vou_check_voucher_code" name="edd_vou_check_voucher_code" class="button-primary" value="<?php _e( 'Check It', 'eddvoucher' ) ?>" />
					<div class="edd-vou-loader edd-vou-check-voucher-code-loader"><img src="<?php echo EDD_VOU_IMG_URL;?>/ajax-loader.gif"/></div>
					<div class="edd-vou-voucher-code-msg"></div>
				</td>
			</tr>
			<tr class="edd-vou-voucher-code-submit-wrap">
				<th>
				</th>
				<td>
					<input type="button" id="edd_vou_voucher_code_submit" name="edd_vou_voucher_code_submit" class="button-primary" value="<?php _e( 'Redeem', 'eddvoucher' ) ?>" />
					<div class="edd-vou-loader edd-vou-voucher-code-submit-loader"><img src="<?php echo EDD_VOU_IMG_URL;?>/ajax-loader.gif"/></div>
				</td>
			</tr>
		</table> <?php
	}
	
	/**
	 * Payment Is Completed
	 * 
	 * Make a payment id as a global
	 * variable and change status from
	 * pending to completed to get voucher code
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_complete_purchase( $payment_id = '' ) {
		
		$prefix	= EDD_VOU_META_PREFIX;
		
		global $edd_payment_id;
		
		//Set payment id as global
		$edd_payment_id	= $payment_id;
		
		// Status update from pending to publish when voucher is get completed or processing
		$args	= array( 
						'post_status'	=> array( 'pending' ),
						'meta_query'	=> array(
												array(
													'key'	=> $prefix . 'order_id',
													'value'	=> $payment_id,
												)
											)
					);
		
		// Get vouchers code of this order
		$purchased_vochers	= $this->model->edd_vou_get_voucher_details( $args );
		
		if( !empty( $purchased_vochers ) ) { // If not empty voucher codes
			
			//For all possible vouchers
			foreach ( $purchased_vochers as $vocher ) {
				
				// Get voucher data
				$current_post = get_post( $vocher['ID'], 'ARRAY_A' );
				
				//Change voucher status
				$current_post['post_status'] = 'publish';
				
				//Update voucher post
				wp_update_post( $current_post );
			}
		}
	}
	
	/**
	 * Restore Voucher Code
	 * 
	 * Handles to restore voucher codes
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_restore_voucher_codes( $payment_id, $new_status, $old_status ) {
		
		//Get prefix
		$prefix	= EDD_VOU_META_PREFIX;
		
		//Get model
		$model	= $this->model;
		
		if( $new_status == 'abandoned' || $new_status == 'failed' ) { //If status abandoned, failed
			
			$args	= array( 
							'post_status'	=> array( 'pending' ), //if order is completed once then there will be no record in pending status
							'meta_query'	=> array(
													array(
														'key'	=> $prefix . 'order_id',
														'value'	=> $payment_id,
													)
												)
						);
			
			//Get vouchers code of this order
			$vochers	= $this->model->edd_vou_get_voucher_details( $args );
			
			if( !empty( $vochers ) ) {//If empty voucher codes
				
				//get order meta
				$meta_order_details	= get_post_meta( $payment_id, $prefix.'meta_order_details', true );
				
				foreach ( $vochers as $vocher ) {
					
					//Initilize voucher codes array
					$salecode	= array();
					
					//Get voucher code ID
					$vou_codes_id	= isset( $vocher['ID'] ) ? $vocher['ID'] : '';
					
					//Get download ID
					$downloadid		= isset( $vocher['post_parent'] ) ? $vocher['post_parent'] : '';
					
					//Get voucher codes
					$voucher_codes			= get_post_meta( $vou_codes_id, $prefix . 'purchased_codes', true );
					
					//meta detail of specific download
					$download_meta_detail	= isset( $meta_order_details[$downloadid] ) ? $meta_order_details[$downloadid] : array();
					
					//Voucher uses types
					$voucher_uses_type		= isset( $download_meta_detail['using_type'] ) ? $download_meta_detail['using_type'] : '';
					
					if( !empty( $voucher_codes ) && empty( $voucher_uses_type ) ) {//If voucher codes available and type is not unlimited
						
						//voucher codes
						$download_vou_codes = get_post_meta( $downloadid, $prefix . 'codes', true );
						
						//explode all voucher codes
						$salecode	= explode( ',', $download_vou_codes );
						
						//append sales code array
						$salecode[]	= $voucher_codes;
						
						//trim code
						foreach ( $salecode as $code_key => $code ) {
							
							$salecode[$code_key] = trim( $code );
						}
						
						//Total avialable voucher code
						$avail_total_codes	= count( $salecode );
						
						//update total voucher codes
						update_post_meta( $downloadid, $prefix.'avail_total', $avail_total_codes );
						
						//after restore code in array update in code meta
						$lessvoucodes = implode( ', ',$salecode );
						update_post_meta( $downloadid, $prefix.'codes', trim( $lessvoucodes ) );
						
						//delete voucher post
						wp_delete_post( $vou_codes_id, true );
					}
				}
				
				//delete voucher order details
				delete_post_meta( $payment_id, $prefix.'order_details' );
				//delete voucher order details with all meta data ( not in use for now)
				delete_post_meta( $payment_id, $prefix.'meta_order_details' );
			}
			
		} else if( $new_status == 'refunded' ) { // when refunct process executed
			
			$args	= array( 
							'post_status'	=> array( 'pending', 'publish' ), //if order is completed once then there will be no record in pending status
							'meta_query'	=> array(
													array(
														'key'	=> $prefix . 'order_id',
														'value'	=> $payment_id,
													)
												)
						);
			
			//Get vouchers code of this order
			$vochers	= $this->model->edd_vou_get_voucher_details( $args );
			
			if( !empty( $vochers ) ) {//If empty voucher codes
				foreach ( $vochers as $vocher ) {
					$vou_codes_id	= isset( $vocher['ID'] ) ? $vocher['ID'] : '';
					if( !empty( $vou_codes_id ) ) {
						$update_refund	= array(
												'ID'			=> $vou_codes_id,
												'post_status'	=> EDD_VOU_REFUND_STATUS
											);
						//set status refunded of voucher post
						wp_update_post( $update_refund );
					}
				}
			}
		}
	}
	
	/**
	 * Display Recipient HTML
	 * 
	 * Handles to display the Recipient HTML for user
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 */
	public function edd_vou_before_add_to_cart_button( $downloadid ) {				
		
		if( is_single() ) {//Recipient field only show on single page
			
			//Initilize downloads
			$downloads = array();
			
			$downloads[] = $downloadid;
			
			foreach( $downloads as $download ) {
				
				//Get prefix
				$prefix			= EDD_VOU_META_PREFIX;
				
				//Get download ID
				$download_id	= $downloadid;
				
				//Get variation ID
				$variation_id	= $download;
				
				//voucher enable or not
				$voucher_enable	= $this->model->edd_vou_check_enable_voucher( $download_id );
				
				if( $voucher_enable ) {
					
					//Get download recipient meta setting
					$recipient_data	= $this->model->edd_vou_get_download_recipient_meta( $download_id );
					
					//Recipient name fields
					$enable_recipient_name			= $recipient_data['enable_recipient_name'];
					$recipient_name_label			= $recipient_data['recipient_name_label'];
					$recipient_name_max_length		= $recipient_data['recipient_name_max_length'];
					$recipient_name_is_required		= $recipient_data['recipient_name_is_required'];
					
					//Recipient email fields
					$enable_recipient_email			= $recipient_data['enable_recipient_email'];
					$recipient_email_label			= $recipient_data['recipient_email_label'];
					$recipient_email_is_required	= $recipient_data['recipient_email_is_required'];
					
					//Recipient message fields
					$enable_recipient_message		= $recipient_data['enable_recipient_message'];
					$recipient_message_label		= $recipient_data['recipient_message_label'];
					$recipient_message_max_length	= $recipient_data['recipient_message_max_length'];
					$recipient_message_is_required	= $recipient_data['recipient_message_is_required'];
					
					// check if enable Recipient Detail
					if( $enable_recipient_email == 'on' || $enable_recipient_name == 'on' || $enable_recipient_message == 'on' ) {
						
						$recipient_name		= isset( $_POST[$prefix.'recipient_name'][$variation_id] ) ? $this->model->edd_vou_escape_attr( $_POST[$prefix.'recipient_name'][$variation_id] ) : '';
						$recipient_email	= isset( $_POST[$prefix.'recipient_email'][$variation_id] ) ? $this->model->edd_vou_escape_attr( $_POST[$prefix.'recipient_email'][$variation_id] ) : '';
						$recipient_message	= isset( $_POST[$prefix.'recipient_message'][$variation_id] ) ? $this->model->edd_vou_escape_attr( $_POST[$prefix.'recipient_message'][$variation_id] ) : '';
						?>
						<div class="edd-vou-fields-wrapper" id="edd-vou-fields-wrapper-<?php echo $variation_id; ?>">
							<table cellspacing="0" class="edd-vou-recipient-fields">
							  <tbody><?php 
								
							  	if( $enable_recipient_name == 'on' ) {
							  		
							  		$recipient_name_label	= !empty( $recipient_name_label ) ? $recipient_name_label : __( 'Recipient Name' , 'eddvoucher' );
							  		$name_maxlength			= intval( $recipient_name_max_length );
							  		?>
									<tr>
										<td class="label">
											<label for="edd_vov_recipient_name"><?php echo $recipient_name_label; ?></label>
										</td>
										<td class="value">
											<input type="text" class="edd-vou-recipient-details" <?php if( !empty($name_maxlength) ) { echo 'maxlength="'.$name_maxlength.'"'; } ?> value="<?php echo $recipient_name; ?>" id="edd_vov_recipient_name" name="<?php echo $prefix; ?>recipient_name[<?php echo $variation_id; ?>]" data-required="<?php echo $recipient_name_is_required; ?>" data-label="<?php echo $recipient_name_label; ?>" <?php if( $recipient_name_is_required == "on" ) echo "required" ; ?> />
											<div class="edd-vou-error" style="color:#FF0000;"></div>
										</td>
									</tr><?php
							  	}
							  	if( $enable_recipient_email == 'on' ) {
							  		
							  		$recipient_email_label = !empty( $recipient_email_label ) ? $recipient_email_label : __( 'Recipient Email' , 'eddvoucher' );?>
									<tr>
										<td class="label">
											<label for="edd_vou_recipient_email"><?php echo $recipient_email_label; ?></label>
										</td>
										<td class="value">
											<input type="email" class="edd-vou-recipient-details" value="<?php echo $recipient_email; ?>" id="edd_vou_recipient_email" name="<?php echo $prefix; ?>recipient_email[<?php echo $variation_id; ?>]" data-required="<?php echo $recipient_email_is_required; ?>" data-label="<?php echo $recipient_email_label; ?>" <?php if( $recipient_email_is_required == "on" ) echo "required" ; ?> />
											<div class="edd-vou-error" style="color:#FF0000;"></div>
										</td>
									</tr><?php 
							  	}
							  	if( $enable_recipient_message == 'on' ) {
							  		
							  		$recipient_message_label	= !empty( $recipient_message_label ) ? $recipient_message_label : __( 'Message to Recipient' , 'eddvoucher' );
							  		$msg_maxlength				= intval( $recipient_message_max_length );
							  		?>
									<tr>
										<td class="label">
											<label for="edd_vou_recipient_message"><?php echo $recipient_message_label; ?></label>
										</td>
										<td class="value">
											<textarea <?php if( !empty($msg_maxlength) ) { echo 'maxlength="'.$msg_maxlength.'"'; } ?> class="edd-vou-recipient-details" id="edd_vou_recipient_message" name="<?php echo $prefix; ?>recipient_message[<?php echo $variation_id; ?>]" data-required="<?php echo $recipient_message_is_required; ?>" data-label="<?php echo $recipient_message_label; ?>" <?php if( $recipient_message_is_required == "on" ) echo "required" ; ?>><?php echo $recipient_message; ?></textarea>
											<div class="edd-vou-error" style="color:#FF0000;"></div>
										</td>
									</tr><?php
							  	}?>
							  </tbody>
							</table>
						</div><?php
					}
				}
			}
		}
	}
	
	/**
	 * Adds Recipient details
	 * 
	 * Handles to add Recipient details to cart
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 */
	public function edd_vou_add_to_cart_item( $item ) {
		
		//Get prefix
		$prefix	= EDD_VOU_META_PREFIX;
		
		if( isset( $_POST['post_data'] ) ) {
			// parse post data. required because post data is in serialize form
			parse_str( $_POST['post_data'], $post_data );	
		}
		
		$recipient_name		= isset( $post_data[$prefix.'recipient_name'][$item['id']] ) ? $post_data[$prefix.'recipient_name'][$item['id']] : '';
		$recipient_email	= isset( $post_data[$prefix.'recipient_email'][$item['id']] ) ? $post_data[$prefix.'recipient_email'][$item['id']] : '';
		$recipient_message	= isset( $post_data[$prefix.'recipient_message'][$item['id']] ) ? $post_data[$prefix.'recipient_message'][$item['id']] : '';
		
		$item[$prefix.'recipient_name']		= $this->model->edd_vou_escape_slashes_deep( $recipient_name );
		$item[$prefix.'recipient_email']	= $this->model->edd_vou_escape_slashes_deep( $recipient_email );
		$item[$prefix.'recipient_message']	= $this->model->edd_vou_escape_slashes_deep( $recipient_message );
		
		return $item;
	}
	
	/**
	 * Display Recipient details on checkout page
	 * 
	 * Handles to get recipient details & dispaly it
	 * on checkout page
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 */
	public function edd_vou_add_meta_data( $item ) {
		
		//Get prefix
		$prefix	= EDD_VOU_META_PREFIX;
		
		$item_recipient_html	= '';
		
		//Get Item ID
		$item_id	= isset( $item['id'] ) ? $item['id'] : '';
		
		//Get download recipient meta setting
		$recipient_data	= $this->model->edd_vou_get_download_recipient_meta( $item_id );
		
		if( !empty( $item[$prefix.'recipient_name'] ) ) {
			$item_recipient_html .= '<div><strong>'.$recipient_data['recipient_name_label'].':</strong> ';
			$item_recipient_html .= $item[$prefix.'recipient_name'].'</div>';
	 	}
		if( !empty( $item[$prefix.'recipient_email'] ) ) {
			$item_recipient_html .= '<div><strong>'.$recipient_data['recipient_email_label'].':</strong> ';
			$item_recipient_html .= $item[$prefix.'recipient_email'].'</div>';
		}
		if( !empty( $item[$prefix.'recipient_message'] ) ) {
			$item_recipient_html .= '<div><strong>'.$recipient_data['recipient_message_label'].':</strong> ';
			$item_recipient_html .= $item[$prefix.'recipient_message'].'</div>';
		}
		
		echo $item_recipient_html;
	}
	
	/**
	 * This is used to send an email after order completed to recipient user
	 * 
	 * Handles to send an email after order completed
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 */
	public function edd_vou_send_recipient_email( $payment_id ) {
		
		global $edd_options, $edd_payment_id;
		
		//Create global variable for this
		$edd_payment_id	= $payment_id;
		
		//Get prefix
		$prefix	= EDD_VOU_META_PREFIX;
		
		// Get payment data
		$payment_data = edd_get_payment_meta( $payment_id );
		
		// Cart details
		$cart_details = $payment_data['cart_details'];
		
		// User details
		$first_name = isset( $payment_data['user_info']['first_name'] ) ? $payment_data['user_info']['first_name'] : '';
		$last_name 	= isset( $payment_data['user_info']['last_name'] ) ? $payment_data['user_info']['last_name'] : '';
		
		foreach ($cart_details as $cart_key => $cart_detail ) {
			
			$enable_voucher = $this->model->edd_vou_check_enable_voucher( $cart_detail['id'] );
			
			if( $enable_voucher ) { // if voucher is enable
				
				$price_id = isset( $cart_detail['item_number']['options']['price_id'] ) ? $cart_detail['item_number']['options']['price_id'] : false;
				
				$download		= new EDD_Download( $cart_detail['id'] );
				$download_files	= $download->get_files( $price_id );
				
				$recipient_voucher_links	= '';
				
				if( !empty( $download_files ) ) {//If download files are not empty
					
					foreach ( $download_files as $download_key => $download_file ) {
						
						$check_key		= strpos( $download_key, 'edd_vou_pdf' );
						
						if( $check_key !== false ) {
							$recipient_voucher_url = edd_get_download_file_url( $payment_data['key'], $payment_data['email'], $download_key, $cart_detail['id'], $price_id );
							$recipient_voucher_url = '<br /><small><a href="' . esc_url( $recipient_voucher_url ) . '">'.__('Download Voucher', 'eddvoucher').'</a></small>';
							
							$recipient_voucher_links .= $recipient_voucher_url;
						}
					}
				}
				
				if( isset( $cart_detail['item_number'][$prefix.'recipient_email'] ) && !empty( $cart_detail['item_number'][$prefix.'recipient_email'] ) ) {
					
					$recipient_name		= $cart_detail['item_number'][$prefix.'recipient_name'];
					$recipient_email	= $cart_detail['item_number'][$prefix.'recipient_email'];
					$recipient_message	= isset( $cart_detail['item_number'][$prefix.'recipient_message'] ) ? '"'.nl2br( $cart_detail['item_number'][$prefix.'recipient_message'] ).'"' : '';
					$recipient_voucher	= $recipient_voucher_links;
					$subject			= $edd_options['vou_recipient_email_subject'];
					$message			= $edd_options['vou_recipient_email_body'];
					$vou_shortcodes		= array( "{recipient_name}", "{first_name}", "{last_name}", "{recipient_message}", "{voucher_link}" );
					$vou_replacecodes	= array( $recipient_name, $first_name, $last_name, $recipient_message, $recipient_voucher );
					$message			= str_replace( $vou_shortcodes, $vou_replacecodes, nl2br( $message ) );
					$subject			= str_replace( $vou_shortcodes, $vou_replacecodes, $subject );
					
					//Get vendor sale enabled option
					$vou_gift_notification_enabled	= isset( $edd_options['vou_gift_notification_disable'] ) ? $edd_options['vou_gift_notification_disable'] : '';
		 			 
					if( $vou_gift_notification_enabled != 1 ) {
						
						//Send mail to recipient
						$this->model->edd_vou_send_email( $recipient_email, $subject, $message );
					}
				}
			}
		}
	}
	
	/**
	 * Check for out of stock
	 *
	 * Handles to check for product is out of stock. 
	 * if yes then out of stock message will return
	 * otherwise it will work as it is
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.4.3
	 */
	function edd_vou_check_download_is_out_of_stock( $purchase_form, $atts ) {
		
		if( !is_admin() ) { // check this is not admin 
		
			global $post;
			
			$downloadid	= isset( $atts['download_id'] ) ? $atts['download_id'] : '';			
			
			if( !empty( $downloadid )) {
				
				//"bundle" or "default"
				$download_type = edd_get_download_type( $downloadid );								
				
				// Check download type is not bundle & voucher is enable & check available voucher codes is 0
				if( $download_type != 'bundle' && $this->model->edd_vou_check_out_of_stock( $downloadid ) ) {
										
				 	return '<p class="out-of-stock"><span><strong>' . __( 'Out Of Stock', 'eddvoucher' ) . '</strong></span></p>';									 	
				}
			}
		}
		return $purchase_form;
	}
	
	/**
	 * Set Global Payment ID
	 * 
	 * Handle to set global payment id for download history page
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.4.5
	 */
	public function edd_vou_download_history_global_payment( $cart_details, $payment_id ) {
		
		global $edd_payment_id;
		
		//set global payment id
		$edd_payment_id	= $payment_id;
		
		return $cart_details;
	}

	/**
	 * Add Filter For EDD 2.5.7 Compatibility
	 * 
	 * Add filter for edd 2.5.7 compatibility
	 * for allow edd arguments in download link
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.3
	 */
	public function edd_vou_edd_register_voucher_arg( $allowed ) { 
		
		$allowed[]	= 'edd_voucher';
		return $allowed;
	}
	
	/**
	 * Set Payment id
	 * 
	 * When purchase receipt resend to user
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.6
	 */
	public function edd_vou_resend_purchase_receipt( $data ){
		
		global $edd_payment_id;
		$purchase_id = absint( $data['purchase_id'] );

		if( !empty( $purchase_id ) ) {
			$edd_payment_id = $purchase_id;			
		}		
	}

	/**
	 * Adding Hooks
	 * 
	 * Adding proper hoocks for the discount codes
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add action to handle out of stock message
		//add_action( 'template_redirect', array( $this , 'edd_vou_loaded' ) );
		add_filter('edd_purchase_download_form', array( $this, 'edd_vou_check_download_is_out_of_stock' ), 10, 2 );
		
		//add action for order is completed
		add_action( 'edd_complete_purchase', array( $this, 'edd_vou_complete_purchase' ), 10, 1 );
		
		//add action to save voucher in order
		add_action( 'edd_insert_payment', array( $this, 'edd_vou_process_voucher_codes'), 10, 2 );
		
		//add filter to merge voucher pdf with download files
		add_filter( 'edd_download_files', array( $this, 'edd_vou_order_download_files' ),10, 3 );
		
		//add filter to display url for voucher pdf
		add_filter( 'edd_download_file_url_args', array( $this, 'edd_vou_order_download_file_url_args' ) );
		
		//add action to download process
		//add_action( 'edd_process_verified_download', array( $this, 'edd_vou_download_process' ) );
		//for edd version 2.0 greater or equal
		add_action( 'edd_process_download_headers', array( $this, 'edd_vou_download_process' ), 10, 4 );
		
		//add action to add error on checkout page when user purchase download
		add_action( 'edd_checkout_error_checks', array( $this, 'edd_vou_download_error' ) );
		
		//ajax call to edit all controls
		add_action( 'wp_ajax_edd_vou_check_voucher_code', array( $this, 'edd_vou_check_voucher_code') );
		add_action( 'wp_ajax_nopriv_edd_vou_check_voucher_code', array( $this, 'edd_vou_check_voucher_code' ) );
		
		//ajax call to save voucher code
		add_action( 'wp_ajax_edd_vou_save_voucher_code', array( $this, 'edd_vou_save_voucher_code') );
		add_action( 'wp_ajax_nopriv_edd_vou_save_voucher_code', array( $this, 'edd_vou_save_voucher_code' ) );
		
		// add action to add html for check voucher code
		add_action( 'edd_vou_check_code_content', array( $this, 'edd_vou_check_code_content' ) );
		
		//edd_update_payment_status
		add_action( 'edd_update_payment_status', array( $this, 'edd_vou_restore_voucher_codes' ), 10, 3 );
		
		//add custom html to single download page before add to cart button
		add_action( 'edd_purchase_link_top', array( $this, 'edd_vou_before_add_to_cart_button' ) );
		
		// add filter to add receipient details to cart
		add_filter( 'edd_add_to_cart_item', array( $this, 'edd_vou_add_to_cart_item' ) );
		
		//add filter to display receipient details on checkout page
		add_action( 'edd_checkout_cart_item_title_after', array( $this, 'edd_vou_add_meta_data' ) );
		
		//add action when order is completed to email recipient details
		add_action( 'edd_complete_purchase', array( $this, 'edd_vou_send_recipient_email' ), 15, 1 );
		
		add_filter( 'edd_payment_meta_cart_details', array( $this, 'edd_vou_download_history_global_payment' ), 10, 2 );
		
		// Add filter for edd 2.5.7 compatibility for allow edd arguments in download link
		add_filter( 'edd_url_token_allowed_params', array( $this, 'edd_vou_edd_register_voucher_arg' ) );
		
		//Set Payment id when purchase receipt resend
		add_action( 'edd_email_links', array( $this,'edd_vou_resend_purchase_receipt'), 1, 1 );
	}
}