<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Export to CSV for Voucher
 * 
 * Handles to Export to CSV on run time when 
 * user will execute the url which is sent to
 * user email with purchase receipt
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */

function edd_vou_code_export_to_csv() {
	
	$prefix = EDD_VOU_META_PREFIX;
	
	if( isset( $_GET['edd-vou-used-exp-csv'] ) && !empty( $_GET['edd-vou-used-exp-csv'] ) 
		&& $_GET['edd-vou-used-exp-csv'] == '1'
		&& isset($_GET['download_id']) && !empty($_GET['download_id'] ) ) {
			
		global $current_user,$edd_vou_model, $post;
		
		//model class
		$model = $edd_vou_model;
	
		$postid = $_GET['download_id']; 
		
		$exports = '';
		
		// Check action is used codes
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
		
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_used_codes_by_download_id( $postid );
		 	
		 	$vou_file_name = 'edd-used-voucher-codes-{current_date}';
			
		} else{
			
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_purchased_codes_by_download_id( $postid );
		 	
			$vou_csv_name = get_option( 'vou_csv_name' );
			$vou_file_name = !empty( $vou_csv_name )? $vou_csv_name : 'edd-purchased-voucher-codes-{current_date}';
		}
		$columns = array(	
							__( 'Voucher Code', 'eddvoucher' ),
							__( 'Buyer\'s Name', 'eddvoucher' ),
							__( 'Payment Date', 'eddvoucher' ),
							__( 'Payment ID', 'eddvoucher' ),
					     );
					     
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
			
			$new_columns	= array( __('Redeem By', 'eddvoucher' ) );
			$columns 		= array_merge ( $columns , $new_columns );
			
		}			
        // Put the name of all fields
		foreach ($columns as $column) {
			
			$exports .= '"'.$column.'",';
		}
		$exports .="\n";
		
		if( !empty( $voucodes ) &&  count( $voucodes ) > 0 ) { 
												
			foreach ( $voucodes as $key => $voucodes_data ) { 
			
				//voucher order id
				$orderid 		= $voucodes_data['order_id'];
				
				//voucher order date
				$orderdate 		= $voucodes_data['order_date'];
				$orderdate 		= !empty( $orderdate ) ? $model->edd_vou_get_date_format( $orderdate ) : '';
				
				//buyer's name who has purchased/used voucher code				
				$buyername 		=  $voucodes_data['buyer_name'];
				
				//voucher code purchased/used
				$voucode 		= $voucodes_data['vou_codes'];

				//this line should be on start of loop
				$exports .= '"'.$voucode.'",';
				$exports .= '"'.$buyername.'",';
				$exports .= '"'.$orderdate.'",';
				$exports .= '"'.$orderid.'",';
				
				if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
					
					$user_id 	 	= $voucodes_data['redeem_by'];
					$user_detail 	= get_userdata( $user_id );
					$redeem_by 		= isset( $user_detail->display_name ) ? $user_detail->display_name : 'N/A';
					
					$exports .= '"'.$redeem_by.'",';
				}
				
				$exports .="\n";
			}
		} 
		
		$vou_file_name = str_replace( '{current_date}', date('d-m-Y'), $vou_file_name );
		
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=".$vou_file_name.".csv");
		echo $exports;
		exit;
		
	}
	
	// generate csv for voucher code
	if( isset( $_GET['edd-vou-voucher-exp-csv'] ) && !empty( $_GET['edd-vou-voucher-exp-csv'] ) 
		&& $_GET['edd-vou-voucher-exp-csv'] == '1' ) {
		
		global $current_user,$edd_vou_model, $post;
		
		//model class
		$model = $edd_vou_model;
	
		$exports = '';
		
		// Check action is used codes
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
		
			$args = array();
		
			$args['meta_query'] = array(
											array(
														'key'		=> $prefix.'used_codes',
														'value'		=> '',
														'compare'	=> '!=',
													)
										);
										
			if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
				$args['author'] = $current_user->ID;
			}
			
			if( isset( $_GET['edd_vou_post_id'] ) && !empty( $_GET['edd_vou_post_id'] ) ) {
				$args['post_parent'] = $_GET['edd_vou_post_id'];
			}
			
			if( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) {
				
				//$args['s'] = $_GET['s'];
				$args['meta_query'] = array(
												'relation'	=> 'OR',
												array(
															'key'		=> $prefix.'used_codes',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'first_name',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'last_name',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'order_id',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'order_date',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
											);
			}
			
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_voucher_details( $args );
		 	
		 	$vou_file_name = 'edd-used-voucher-codes-{current_date}';
			
		} else if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'expired' ) {
		
			$args = array();
		
			$args['meta_query'] = array(
											array(
														'key'		=> $prefix.'purchased_codes',
														'value'		=> '',
														'compare'	=> '!=',
												),
												array(
														'key'		=> $prefix .'exp_date',
														'compare'	=> '<=',
				                  						'value'		=> $model->edd_vou_current_date()
													),
												array(
														'key'		=> $prefix .'exp_date',
														'value'		=> '',
														'compare'	=> '!='
												)
										);
										
			if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
				$args['author'] = $current_user->ID;
			}
			
			if( isset( $_GET['edd_vou_post_id'] ) && !empty( $_GET['edd_vou_post_id'] ) ) {
				$args['post_parent'] = $_GET['edd_vou_post_id'];
			}
			
			if( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) {
				
				//$args['s'] = $_GET['s'];
				$args['meta_query'] = array(
												'relation'	=> 'OR',
												array(
															'key'		=> $prefix.'purchased_codes',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'first_name',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'last_name',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'order_id',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'order_date',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
											);
			}
			
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_voucher_details( $args );
		 	
		 	$vou_file_name = 'edd-used-voucher-codes-{current_date}';
			
		} else{
			
		 	$args = array();
		
			$args['meta_query'] = array(
											array(
														'key'		=> $prefix.'purchased_codes',
														'value'		=> '',
														'compare'	=> '!=',
													)
										);
										
			if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
				$args['author'] = $current_user->ID;
			}
			
			if( isset( $_GET['edd_vou_post_id'] ) && !empty( $_GET['edd_vou_post_id'] ) ) {
				$args['post_parent'] = $_GET['edd_vou_post_id'];
			}
			
			if( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) {
				
				//$args['s'] = $_GET['s'];
				$args['meta_query'] = array(
												'relation'	=> 'OR',
												array(
															'key'		=> $prefix.'purchased_codes',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'first_name',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'last_name',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'order_id',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
												array(
															'key'		=> $prefix.'order_date',
															'value'		=> $_GET['s'],
															'compare'	=> 'LIKE',
														),
											);
			}
			
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_voucher_details( $args );
		 	
		 	$vou_csv_name = get_option( 'vou_csv_name' );
			$vou_file_name = !empty( $vou_csv_name )? $vou_csv_name : 'edd-purchased-voucher-codes-{current_date}';
		}
		$columns = array(	
							__( 'Voucher Code', 'eddvoucher' ),
							__( 'Download Information', 'eddvoucher' ),
							__( 'Buyer\'s Information', 'eddvoucher' ),
							__( 'Payment Information', 'eddvoucher' ),
					     );
     	
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
			
			$new_columns	= array( __('Redeem By', 'eddvoucher' ) );
			$columns 		= array_merge ( $columns , $new_columns );
			
		}	
				
        // Put the name of all fields
		foreach ($columns as $column) {
			
			$exports .= '"'.$column.'",';
		}
		$exports .="\n";
		
		if( !empty( $voucodes ) &&  count( $voucodes ) > 0 ) { 
												
			foreach ( $voucodes as $key => $voucodes_data ) { 
			
				//voucher order id
				$orderid 		= get_post_meta( $voucodes_data['ID'], $prefix.'order_id', true );
				
				//voucher order date
				$orderdate 		= get_post_meta( $voucodes_data['ID'], $prefix.'order_date', true );
				$orderdate 		= !empty( $orderdate ) ? $model->edd_vou_get_date_format( $orderdate ) : '';
				
				//buyer's name who has purchased/used voucher code				
				$first_name = get_post_meta( $voucodes_data['ID'], $prefix.'first_name', true );
				$last_name 	= get_post_meta( $voucodes_data['ID'], $prefix.'last_name', true );
				$buyername  = $first_name . ' ' . $last_name;
				
				//voucher code purchased/used
				$voucode 		= get_post_meta( $voucodes_data['ID'], $prefix.'purchased_codes', true );

				$buyerinfo	    = $model->edd_vou_display_buyer_info_html( $orderid, $voucode, '', 'csv' );
				
				//get user
				$user_id 	 	= get_post_meta( $voucodes_data['ID'], $prefix.'redeem_by', true );
				$user_detail 	= get_userdata( $user_id );
				$redeem_by 		= isset( $user_detail->display_name ) ? $user_detail->display_name : 'N/A';
				
				$download_title = get_the_title( $voucodes_data['post_parent'] );
				$voucodes_data['download_title'] = $download_title;

				$download_desc = $model->edd_vou_display_download_info_html( $orderid, $voucode, $voucodes_data, 'csv',false );
				$order_desc    = $model->edd_vou_display_payment_info_html( $orderid, $voucode, '', 'csv', false );
				
				//this line should be on start of loop
				$exports .= '"'.$voucode.'",';
				$exports .= '"'.$download_desc.'",';
				$exports .= '"'.$buyerinfo.'",';
				$exports .= '"'.$order_desc.'",';
				//$exports .= '"'.$orderid.'",';
				
				if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
					
					$redeem_info = $model->edd_vou_display_redeem_info_html( $voucodes_data['ID'], $orderid, 'csv'  );
					$exports .= '"'.$redeem_info.'",';
				}
				
				$exports .="\n";
			}
		} 
		
		$vou_file_name = str_replace( '{current_date}', date('d-m-Y'), $vou_file_name );
		
		// Output to browser with appropriate mime type, you choose ;)
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=".$vou_file_name.".csv");
		echo $exports;
		exit;
		
	}
}
add_action( 'admin_init', 'edd_vou_code_export_to_csv' );
?>