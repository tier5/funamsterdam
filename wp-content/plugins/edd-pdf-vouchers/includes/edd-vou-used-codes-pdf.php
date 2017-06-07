<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Generate PDF for Voucher
 * 
 * Handles to Generate PDF on run time when 
 * user will execute the url which is sent to
 * user email with purchase receipt
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */

function edd_vou_code_pdf() {
	
	global $edd_options;
	
	$prefix = EDD_VOU_META_PREFIX;
	
	// Getting voucher character support
	$voucher_char_support	= !empty( $edd_options['vou_char_support'] ) ? 1 : 0;
	
	// Taking pdf fonts
	$pdf_font 	= ( !empty( $voucher_char_support ) ) ? 'freeserif' : 'helvetica';
	
	if( isset( $_GET['edd-vou-used-gen-pdf'] ) && !empty( $_GET['edd-vou-used-gen-pdf'] )
		&& $_GET['edd-vou-used-gen-pdf'] == '1' 
		&& isset($_GET['download_id']) && !empty($_GET['download_id']) ) {
		
		global $current_user,$edd_vou_model, $post;
		
		//model class
		$model = $edd_vou_model;
	
		$postid = $_GET['download_id']; 
		
		if ( ! class_exists('TCPDF') ) { // chek if class not exists
			
			 // include tcpdf library
			require_once EDD_VOU_DIR . '/includes/tcpdf/tcpdf.php';
		}
		
		// Check action is used codes
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) {
			
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_used_codes_by_download_id( $postid );
		 	
		 	$voucher_heading 	= __( 'Used Voucher Codes','eddvoucher' );
		 	$voucher_empty_msg	= __( 'No voucher codes used yet.', 'eddvoucher' );
		 	
		 	
			$vou_file_name = 'edd-used-voucher-codes-{current_date}';
			
		} else {
			
		 	//Get Voucher Details by post id
		 	$voucodes = $model->edd_vou_get_purchased_codes_by_download_id( $postid );
		 	
		 	$voucher_heading 	= __( 'Purchased Voucher Codes','eddvoucher' );
		 	$voucher_empty_msg	= __( 'No voucher codes purchased yet.', 'eddvoucher' );
		 	
			$vou_pdf_name = get_option( 'vou_pdf_name' );
			$vou_file_name = !empty( $vou_pdf_name )? $vou_pdf_name : 'edd-purchased-voucher-codes-{current_date}';
		}
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
		
		// remove default footer
		$pdf->setPrintFooter(false);
	
		$pdf->AddPage( 'L', 'A4' );
		
		// Auther name and Creater name
		$pdf->SetTitle( utf8_decode(__('Easy Digital Downloads Voucher','eddvoucher')) );
		$pdf->SetAuthor( utf8_decode( __('Easy Digital Downloads','eddvoucher') ) );
		$pdf->SetCreator( utf8_decode( __('Easy Digital Downloads','eddvoucher') ) );
		
		// Set margine of pdf (float left, float top , float right)
		$pdf->SetMargins( 8, 8, 8 );
		$pdf->SetX( 8 );
		
		
		// Font size set
		$pdf->SetFont( $pdf_font, '', 18 );
		$pdf->SetTextColor( 50, 50, 50 );
		
		$pdf->Cell( 270, 5, utf8_decode( $voucher_heading ), 0, 2, 'C', false );
		$pdf->Ln(5);
		$pdf->SetFont( $pdf_font, '', 12 );
		$pdf->SetFillColor( 238, 238, 238 );
		
		//voucher logo
		if( !empty( $voulogo ) ) {
			$pdf->Image( $voulogo, 95, 25, 20, 20 );
			$pdf->Ln(35);
		}
		
		$columns = array(
							array('name' => __('Voucher Code', 'eddvoucher') 	, 'width' => 70),
							array('name' => __('Buyer\'s Name', 'eddvoucher') 	, 'width' => 70),
							array('name' => __('Payment Date', 'eddvoucher') 	, 'width' => 50)							
						);
						
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) { // if generate pdf for used code add and extra column
			
			$new_columns[]	= array('name' => __('Payment ID', 'eddvoucher') 	, 'width' => 35);
			$new_columns[]	= array('name' => __('Redeem By', 'eddvoucher') 	, 'width' => 50);
			$columns 		= array_merge ( $columns , $new_columns );	
		} else {
			$new_columns[]	= array('name' => __('Payment ID', 'eddvoucher') 	, 'width' => 70);
			$columns 		= array_merge ( $columns , $new_columns );	
		}
		
		// Table head Code
		foreach ($columns as $column) {
			// parameter : (height, width, string, border[0 - no border, 1 - frame], )

			$pdf->Cell( $column['width'], 8, utf8_decode($column['name']), 1, 0, 'L', true );
		}
		$pdf->Ln();	
	
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

				$pdf->Cell(70, 8, utf8_decode( $voucode ), 1, 0, 'L', false );
				$pdf->Cell(70, 8, utf8_decode( $buyername ), 1, 0, 'L', false );
				$pdf->Cell(50, 8, utf8_decode( $orderdate ), 1, 0, 'L', false );				
				
				if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) { // if generate pdf for used code add and extra column
					
					$user_id 	 	= $voucodes_data['redeem_by'];
					$user_detail 	= get_userdata( $user_id );
					$redeem_by 		= isset( $user_detail->display_name ) ? $user_detail->display_name : 'N/A';
					
					$pdf->Cell(35, 8, utf8_decode( $orderid ), 1, 0, 'L', false );
					$pdf->Cell(50, 8, utf8_decode( $redeem_by ), 1, 1, 'L', false );
					
				} else {
					
					$pdf->Cell(70, 8, utf8_decode( $orderid ), 1, 1, 'L', false );
				}
					
			}
			
		} else { 
			
			$title = utf8_decode( $voucher_empty_msg );
			$pdf->Cell(280, 8, utf8_decode($title), 1, 1, 'L', false );
			
		}
		
		//voucher code
		$pdf->SetFont( $pdf_font, 'B', 14 );
		
		$vou_file_name = str_replace( '{current_date}', date('d-m-Y'), $vou_file_name );
		$pdf->Output( $vou_file_name . '.pdf', 'D' );
		exit;
		
	}
	
	// generate pdf for voucher code
	if( isset( $_GET['edd-vou-voucher-gen-pdf'] ) && !empty( $_GET['edd-vou-voucher-gen-pdf'] )
		&& $_GET['edd-vou-voucher-gen-pdf'] == '1' ) {
		
		$prefix = EDD_VOU_META_PREFIX;
		
		global $current_user,$edd_vou_model, $post;
		
		//model class
		$model = $edd_vou_model;
	
		//$postid = $_GET['download_id']; 
		
		// include tcpdf library
		require_once EDD_VOU_DIR . '/includes/tcpdf/tcpdf.php';
		
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
		 	
		 	$voucher_heading 	= __( 'Used Voucher Codes','eddvoucher' );
		 	$voucher_empty_msg	= __( 'No voucher codes used yet.', 'eddvoucher' );
		 	
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
		 	
		 	$voucher_heading 	= __( 'Used Voucher Codes','eddvoucher' );
		 	$voucher_empty_msg	= __( 'No voucher codes used yet.', 'eddvoucher' );
		 	
		 	$vou_file_name = 'edd-used-voucher-codes-{current_date}';
			
		} else {
			
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
		 	
		 	$voucher_heading 	= __( 'Purchased Voucher Codes','eddvoucher' );
		 	$voucher_empty_msg	= __( 'No voucher codes purchased yet.', 'eddvoucher' );
		 	
		 	$vou_pdf_name = get_option( 'vou_pdf_name' );
			$vou_file_name = !empty( $vou_pdf_name )? $vou_pdf_name : 'edd-purchased-voucher-codes-{current_date}';
		}
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
		
		// remove default footer
		$pdf->setPrintFooter(false);
	
		$pdf->AddPage( 'L', 'A4' );
		
		// Auther name and Creater name
		$pdf->SetTitle( utf8_decode(__('Easy Digital Downloads Voucher','eddvoucher')) );
		$pdf->SetAuthor( utf8_decode( __('Easy Digital Downloads','eddvoucher') ) );
		$pdf->SetCreator( utf8_decode( __('Easy Digital Downloads','eddvoucher') ) );
		
		// Set margine of pdf (float left, float top , float right)
		$pdf->SetMargins( 8, 8, 8 );
		$pdf->SetX( 8 );
		
		
		// Font size set
		$pdf->SetFont( $pdf_font, '', 18 );
		$pdf->SetTextColor( 50, 50, 50 );
		
		$pdf->Cell( 270, 5, utf8_decode( $voucher_heading ), 0, 2, 'C', false );
		$pdf->Ln(5);
		$pdf->SetFont( $pdf_font, '', 12 );
		$pdf->SetFillColor( 238, 238, 238 );
		
		//voucher logo
		if( !empty( $voulogo ) ) {
			$pdf->Image( $voulogo, 95, 25, 20, 20 );
			$pdf->Ln(35);
		}
		
		if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) { // if generate pdf for used code add and extra column
			/*$columns =  array(
								'vou_code' 		=> array('name' => __('Voucher Code', 'eddvoucher'), 'width' => 12),
								'download_title' => array('name' => __('Download Title', 'eddvoucher'), 'width' => 23),
								'buyer_name' 	=> array('name' => __('Buyer\'s Name', 'eddvoucher'), 'width' => 26),
								'payment_date' 	=> array('name' => __('Payment Date', 'eddvoucher'), 'width' => 24),								
								'payment_id' 	=> array('name' => __('Payment ID', 'eddvoucher'), 'width' => 15),
								'redeem_by' 	=> array('name' => __('Redeem By', 'eddvoucher'), 'width' => 15)
						);*/
			
			$columns =  array(
								'vou_code' 		=> array('name' => __('Voucher Code', 'eddvoucher'), 'width' => 12),
								'download_info' => array('name' => __('Download Information', 'eddvoucher'), 'width' => 24),
								'buyer_info' 	=> array('name' => __('Buyer\'s Information', 'eddvoucher'), 'width' => 24),
								'payment_info' 	=> array('name' => __('Payment Information', 'eddvoucher'), 'width' => 25),
								'redeem_info' 	=> array('name' => __('Redeem Info', 'eddvoucher'), 'width' => 15)
						);
			
		} else {
			$columns =  array(
								'vou_code' 		=> array('name' => __('Voucher Code', 'eddvoucher'), 'width' => 20),
								'download_info' => array('name' => __('Download Information', 'eddvoucher'), 'width' => 25),
								'buyer_info' 	=> array('name' => __('Buyer\'s Information', 'eddvoucher'), 'width' => 30),
								'payment_info' 	=> array('name' => __('Payment Information', 'eddvoucher'), 'width' => 25),
								
						);
		}
		
		$html = '';
		$html .= '<table style="line-height:1.5;" border="1"><thead><tr style="line-height:2;font-weight:bold;background-color:#EEEEEE;">';
		
		// Table head Code
		foreach ($columns as $column) {
			// parameter : (height, width, string, border[0 - no border, 1 - frame], )
			
			$html .= '<th style="margin:10px;"> '.$column['name'].' </th>';
		}
		$html .= '</tr></thead>';
		$html .= '<tbody>';
	
		if( count( $voucodes ) > 0 ) { 
												
			foreach ( $voucodes as $key => $voucodes_data ) { 
				
				$html .= '<tr>';
				
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
				
				$buyerinfo	   = $model->edd_vou_display_buyer_info_html( $orderid, $voucode );
				
				//get user
				$user_id 	 	= get_post_meta( $voucodes_data['ID'], $prefix.'redeem_by', true );
				$user_detail 	= get_userdata( $user_id );
				$redeem_by 		= isset( $user_detail->display_name ) ? $user_detail->display_name : 'N/A';
				
				$download_title = get_the_title( $voucodes_data['post_parent'] );
				$voucodes_data['download_title'] = $download_title;
				
				$download_desc = $model->edd_vou_display_download_info_html( $orderid, $voucode, $voucodes_data, 'html',false );
				$order_desc    = $model->edd_vou_display_payment_info_html( $orderid, $voucode, '', 'html', false );
				
				
				$html .= '<td> '.$voucode.' </td>';
				$html .= '<td> '.$download_desc.' </td>';
				$html .= '<td> '.$buyerinfo.' </td>';
				$html .= '<td> '.$order_desc.' </td>';
				
				if( isset( $_GET['edd_vou_action'] ) && $_GET['edd_vou_action'] == 'used' ) { // if generate pdf for used code add and extra column
					
					$redeem_info   = $model->edd_vou_display_redeem_info_html( $voucodes_data['ID'], $orderid, 'html' );
					$html .= '<td> '.$redeem_info.' </td>';
				}
				
				$html .= '</tr>';	
			}
			
		} else { 
			
			$title = utf8_decode( $voucher_empty_msg );
			//$pdf->Cell(280, 8, utf8_decode($title), 1, 1, 'L', false );
			
			$html .= '<tr><td colspan="'.$colspan.'"> '.$title.' </td></tr>';
		}
		
		$html .= '</tbody>';
		$html .= '</table>';

		// output the HTML content
		$pdf->writeHTML( $html, true, 0, true, 0 );

		// reset pointer to the last page
		$pdf->lastPage();
		
		//voucher code
		$pdf->SetFont( $pdf_font, 'B', 14 );
		
		$vou_file_name = str_replace( '{current_date}', date('d-m-Y'), $vou_file_name );
		$pdf->Output( $vou_file_name . '.pdf', 'D' );
		exit;
		
	} 
}
add_action( 'admin_init', 'edd_vou_code_pdf' );
?>