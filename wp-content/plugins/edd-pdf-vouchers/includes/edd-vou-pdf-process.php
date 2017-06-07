<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function edd_vou_generate_pdf_by_html( $html = '', $pdf_args = array() ) {
	
	global $edd_options;
	
	if ( ! class_exists('TCPDF') ) { // chek if class not exists
		//include tcpdf file
		require_once EDD_VOU_DIR . '/includes/tcpdf/tcpdf.php';
	}
	
	$prefix = EDD_VOU_META_PREFIX;
	
	$pdf_margin_top 		= PDF_MARGIN_TOP;
	$pdf_margin_bottom		= PDF_MARGIN_BOTTOM;
	$pdf_margin_left 		= PDF_MARGIN_LEFT;
	$pdf_margin_right 		= PDF_MARGIN_RIGHT;
	$pdf_bg_image 			= '';
	$vou_template_pdf_view 	= '';
	$vou_template_size		= '';
	
	// Taking pdf fonts
	$pdf_font	= !empty($pdf_args['char_support']) ? 'freeserif' : 'helvetica';
	$font_size	= 12;
	
	if( isset( $pdf_args['vou_template_id'] ) && !empty( $pdf_args['vou_template_id'] ) ) {
		
		global $edd_vou_template_id;
		
		//Voucher PDF ID
		$edd_vou_template_id = $pdf_args['vou_template_id'];
		
		//Get pdf size meta
		$vou_template_size	= get_post_meta( $edd_vou_template_id, $prefix.'pdf_size', true );
		$vou_template_size	= !empty( $vou_template_size ) ? $vou_template_size : 'A4';
		
		//Get size array
		$edd_vou_allsize_array	= edd_vou_get_pdf_sizes();
		
		$edd_vou_size_array		= $edd_vou_allsize_array[$vou_template_size];
		
		$pdf_width	= isset( $edd_vou_size_array['width'] ) ? $edd_vou_size_array['width'] : '210';
		$pdf_height	= isset( $edd_vou_size_array['height'] ) ? $edd_vou_size_array['height'] : '297';
		$font_size	= isset( $edd_vou_size_array['fontsize'] ) ? $edd_vou_size_array['fontsize'] : '12';
		
		class VOUPDF extends TCPDF { // Extend the TCPDF class to create custom Header and Footer
			
			function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {

				// Call parent constructor
				parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
			}
			
			//Page header
			public function Header() {
				
				global $edd_vou_model, $edd_vou_template_id;
				
				//model class
				$model = $edd_vou_model;
				
				$prefix = EDD_VOU_META_PREFIX;
				
				$vou_template_bg_style 		= get_post_meta( $edd_vou_template_id, $prefix . 'pdf_bg_style', true );
				$vou_template_bg_pattern 	= get_post_meta( $edd_vou_template_id, $prefix . 'pdf_bg_pattern', true );
				$vou_template_bg_img 		= get_post_meta( $edd_vou_template_id, $prefix . 'pdf_bg_img', true );
				$vou_template_bg_color 		= get_post_meta( $edd_vou_template_id, $prefix . 'pdf_bg_color', true );
				$vou_template_pdf_view 		= get_post_meta( $edd_vou_template_id, $prefix . 'pdf_view', true );
				
				//Get pdf size meta
				$vou_template_size	= get_post_meta( $edd_vou_template_id, $prefix.'pdf_size', true );
				$vou_template_size	= !empty( $vou_template_size ) ? $vou_template_size : 'A4';
				
				//Get size array
				$vou_allsize_array	= edd_vou_get_pdf_sizes();
				
				$vou_size_array	= $vou_allsize_array[$vou_template_size];
				
				$pdf_width	= isset( $vou_size_array['width'] ) ? $vou_size_array['width'] : '210';
				$pdf_height	= isset( $vou_size_array['height'] ) ? $vou_size_array['height'] : '297';
				$font_size	= isset( $vou_size_array['fontsize'] ) ? $vou_size_array['fontsize'] : '12';
				
				//Voucher PDF Background Color
				if( !empty( $vou_template_bg_color ) ) {
					
					if( $vou_template_pdf_view == 'land' ) { // Check PDF View option is landscape
						// Background color
		    			$this->Rect( 0, 0, $pdf_height, $pdf_width, 'F', '', $fill_color = $model->edd_vou_hex_2_rgb( $vou_template_bg_color ) );
					} else {
						// Background color
		    			$this->Rect( 0, 0, $pdf_width, $pdf_height, 'F', '', $fill_color = $model->edd_vou_hex_2_rgb( $vou_template_bg_color ) );
					}
				}
				
				//Voucher PDF Background style is image & image is not empty
				if( !empty( $vou_template_bg_style ) && $vou_template_bg_style == 'image'
					&& isset( $vou_template_bg_img['src'] ) && !empty( $vou_template_bg_img['src'] ) ) {
					
					$img_file = $vou_template_bg_img['src'];
					
				} else if( !empty( $vou_template_bg_style ) && $vou_template_bg_style == 'pattern'
					&& !empty( $vou_template_bg_pattern ) ) {//Voucher PDF Background style is pattern & Background Pattern is not selected
					
					if( $vou_template_pdf_view == 'land' ) { // Check PDF View option is landscape
						
						// Background Pattern Image
		    			$img_file = EDD_VOU_IMG_URL . '/patterns/' . $vou_template_bg_pattern . '.png';
		    			
					} else {
						
						// Background Pattern Image      
		    			$img_file = EDD_VOU_IMG_URL . '/patterns/port_' . $vou_template_bg_pattern . '.png';
					}
				}
				
				if( !empty( $img_file ) ) { //Check image file
					
					// get the current page break margin
					$bMargin = $this->getBreakMargin();
					// get current auto-page-break mode
					$auto_page_break = $this->AutoPageBreak;
					// disable auto-page-break
					$this->SetAutoPageBreak(false, 0);
					
					if( $vou_template_pdf_view == 'land' ) { // Check PDF View option is landscape
						
						// Background image
						$this->Image($img_file, 0, 0, $pdf_height, $pdf_width, '', '', '', false, 300, '', false, false, 0);
						
					} else {
						
						// Background image
						$this->Image($img_file, 0, 0, $pdf_width, $pdf_height, '', '', '', false, 300, '', false, false, 0);
					}
					
					// restore auto-page-break status
					$this->SetAutoPageBreak( $auto_page_break, $bMargin );
					
					// set the starting point for the page content
					$this->setPageMark();
				}
			}
		}
		
		//Voucher PDF View
		$vou_template_pdf_view = get_post_meta( $edd_vou_template_id, $prefix . 'pdf_view', true );
		
		//Voucher PDF Margin Top
		$vou_template_margin_top = get_post_meta( $edd_vou_template_id, $prefix . 'pdf_margin_top', true );
		if( !empty( $vou_template_margin_top ) ) {
			$pdf_margin_top = $vou_template_margin_top;
		}
		
		//Voucher PDF Margin Bottom
		$vou_template_margin_bottom = get_post_meta( $edd_vou_template_id, $prefix . 'pdf_margin_bottom', true );
		if( !empty( $vou_template_margin_bottom ) ) {
			$pdf_margin_bottom = $vou_template_margin_bottom;
		}
		
		//Voucher PDF Margin Left
		$vou_template_margin_left = get_post_meta( $edd_vou_template_id, $prefix . 'pdf_margin_left', true );
		if( !empty( $vou_template_margin_left ) ) {
			$pdf_margin_left = $vou_template_margin_left;
		}
		
		//Voucher PDF Margin Right
		$vou_template_margin_right = get_post_meta( $edd_vou_template_id, $prefix . 'pdf_margin_right', true );
		if( !empty( $vou_template_margin_right ) ) {
			$pdf_margin_right = $vou_template_margin_right;
		}
		
		// create new PDF document
		$pdf = new VOUPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, $vou_template_size, true, 'UTF-8', false );
		
	} else {
		
		$vou_template_size = 'A4';
		
		// create new PDF document
		$pdf = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		
		// remove default header
		$pdf->setPrintHeader( false );
	}
	
	// remove default footer
	$pdf->setPrintFooter( false );
		
	// Auther name and Creater name 
	$pdf->SetCreator( utf8_decode( __('Easy Digital Downloads', 'eddvoucher' ) ) );
	$pdf->SetAuthor( utf8_decode( __('Easy Digital Downloads', 'eddvoucher' ) ) );
	$pdf->SetTitle( utf8_decode( __('Easy Digital Downloads Voucher', 'eddvoucher' ) ) );

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 021', PDF_HEADER_STRING);
	
	// set header and footer fonts
	$pdf->setHeaderFont( Array( PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
	$pdf->setFooterFont( Array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
	
	// set margins
	$pdf->SetMargins( $pdf_margin_left, $pdf_margin_top, $pdf_margin_right );
	$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
	$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
	
	// set auto page breaks
	$pdf->SetAutoPageBreak( TRUE, $pdf_margin_bottom );
	
	// set image scale factor
	$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
	
	// set default font subsetting mode
    $pdf->setFontSubsetting( true );
    
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont( apply_filters( 'edd_vou_pdf_generate_fonts', $pdf_font ), '', $font_size );
	
	// add a page
	if( $vou_template_pdf_view == 'land' ) { // Check PDF View option is landscape		
		$pdf->AddPage( 'L' );
	} else {
		$pdf->AddPage();
	}
	
	// set cell padding
	//$pdf->setCellPaddings(1, 1, 1, 1);
	
	// set cell margins
	$pdf->setCellMargins(0, 1, 0, 1);
	
	// set font color
	$pdf->SetTextColor( 50, 50, 50 );
	$pdf->SetFillColor( 238, 238, 238 );
	
	// output the HTML content
	$pdf->writeHTML($html, true, 0, true, 0);
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// ---------------------------------------------------------
	$order_pdf_name = isset( $edd_options['order_pdf_name'] ) ? $edd_options['order_pdf_name'] : '';
	
	if( !empty( $order_pdf_name ) ) {
		$pdf_file_name = str_replace( "{current_date}", date( 'd-m-Y' ), $order_pdf_name );
	} else {
		$pdf_file_name = 'edd-voucher-'. date( 'd-m-Y' );
	}
	
	//Get pdf name
	$pdf_name = isset( $pdf_args['pdf_name'] ) && !empty( $pdf_args['pdf_name'] ) ? $pdf_args['pdf_name'] : $pdf_file_name;
	
	// clean output just before generate voucher
	if ( ob_get_contents() || ob_get_length() ) ob_end_clean();
	
	//Close and output PDF document
	//Second Parameter I that means display direct and D that means ask download or open this file
	$pdf->Output( $pdf_name . '.pdf', 'D' );
	exit;
}

/**
 * View Preview for Voucher PDF
 * 
 * Handles to view preview for voucher pdf
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
function edd_vou_preview_pdf() {
	
	global $edd_options, $edd_vou_model;
	
	$model = $edd_vou_model;
	
	$pdf_args = array();
	
	if( isset( $_GET['voucher_id'] ) && !empty( $_GET['voucher_id'] )
		&& isset( $_GET['edd_vou_pdf_action'] ) && $_GET['edd_vou_pdf_action'] == 'preview' ) {
			
		$voucher_template_id = $_GET['voucher_id'];
			
		$pdf_args['vou_template_id'] = $voucher_template_id;
				
		//site logo
		$vousitelogohtml = '';
		if( !empty( $edd_options['vou_site_logo'] ) ) {
			$vou_site_url = $edd_options['vou_site_logo'];
			$vousitelogohtml = '<img src="' . $vou_site_url . '" alt="" />';
		}
			
		//vendor's logo
		$vou_url = EDD_VOU_IMG_URL . '/vendor-logo.png';
		$voulogohtml = '<img src="' . $vou_url . '" alt="" />';
		
		$vendor_address = __( 'Infiniti Mall Malad', 'eddvoucher' ) . "\n\r" . __( 'GF 9 & 10, Link Road, Mindspace, Malad West', 'eddvoucher' ) . "\n\r" . __( 'Mumbai, Maharashtra 400064', 'eddvoucher' );
		$vendor_address = nl2br( $vendor_address );
		
		$nextmonth = mktime(0, 0, 0, date("m")+1,   date("d"),   date("Y"));
		
		$redeem_instruction = __( 'Redeem instructions :', 'eddvoucher' );
		$redeem_instruction .= __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.', 'eddvoucher' );
		
		$locations = '<strong>' . __( 'DELHI:', 'eddvoucher' ) . '</strong> ' . __( 'Dlf Promenade Mall & Pacific Mall', 'eddvoucher' );
		$locations .= ' <strong>' . __( 'MUMBAI:', 'eddvoucher' ) . '</strong> ' . __( 'Infiniti Mall, Malad & Phoenix MarketCity', 'eddvoucher' );
		$locations .= ' <strong>' . __( 'BANGALORE:', 'eddvoucher' ) . '</strong> ' . __( 'Phoenix MarketCity Mall', 'eddvoucher' );
		$locations .= ' <strong>' . __( 'PUNE:', 'eddvoucher' ) . '</strong> ' . __( 'Phoenix MarketCity Mall', 'eddvoucher' );
		
		$buyer_name 	= __('WpWeb', 'eddvoucher');
		$buyer_email 	= 'wpweb101@gmail.com';
		$orderid		= '101';
		$orderdate		= date("Y-m-d");
		$productname	= __('Test Product', 'eddvoucher');
		$productprice	= '$'.number_format('10', 2);
		$codes 			= __( '[The voucher code will be inserted automatically here]', 'eddvoucher' );
		
		$voucher_rec_name		= 'Test Name';
		$voucher_rec_email		= 'recipient@example.com';
		$voucher_rec_message	= 'Test message';
		$payment_method			= 'Test Payment Method';
		if( !empty( $orderdate ) ) {
			$orderdate = $model->edd_vou_get_date_format( $orderdate );
		}
		$orderdate = isset( $orderdate ) ? $orderdate : '';
		
		$content_post = get_post( $voucher_template_id );
		$content = isset( $content_post->post_content ) ? $content_post->post_content : '';
		$post_title = isset( $content_post->post_title ) ? $content_post->post_title : '';
		$voucher_template_html = do_shortcode( $content );
		
		$voucher_template_html = str_replace( '{redeem}', $redeem_instruction, $voucher_template_html );
		$voucher_template_html = str_replace( '{vendorlogo}', $voulogohtml, $voucher_template_html );
		$voucher_template_html = str_replace( '{sitelogo}', $vousitelogohtml, $voucher_template_html );
		$voucher_template_html = str_replace( '{expiredate}', $model->edd_vou_get_date_format( date('d-m-Y', $nextmonth ) ), $voucher_template_html );
		$voucher_template_html = str_replace( '{vendoraddress}', $vendor_address, $voucher_template_html );
		$voucher_template_html = str_replace( '{siteurl}', 'www.bebe.com', $voucher_template_html );
		$voucher_template_html = str_replace( '{location}', $locations, $voucher_template_html );
		$voucher_template_html = str_replace( '{buyername}', $buyer_name, $voucher_template_html );
		$voucher_template_html = str_replace( '{buyeremail}', $buyer_email, $voucher_template_html );
		$voucher_template_html = str_replace( '{orderid}', $orderid, $voucher_template_html );
		$voucher_template_html = str_replace( '{orderdate}', $orderdate, $voucher_template_html );
		// will add support later
		$voucher_template_html = str_replace( '{productname}', $productname, $voucher_template_html );
		$voucher_template_html = str_replace( '{productprice}', $productprice, $voucher_template_html );
		$voucher_template_html = str_replace( '{codes}', $codes, $voucher_template_html );
		
		$voucher_template_html = str_replace( '{recipientname}', $voucher_rec_name, $voucher_template_html  );
		$voucher_template_html = str_replace( '{recipientemail}', $voucher_rec_email, $voucher_template_html );
		$voucher_template_html = str_replace( '{recipientmessage}', $voucher_rec_message, $voucher_template_html );
		$voucher_template_html = str_replace( '{payment_method}', $payment_method, $voucher_template_html );
		
		//Set pdf name
		$post_title = str_replace( ' ', '-', strtolower( $post_title ) );
		$pdf_args['pdf_name'] 		= $post_title . __( '-preview-', 'eddvoucher' ) . $voucher_template_id;
		$pdf_args['char_support']	= !empty($edd_options['vou_char_support']) ? 1 : 0;
		
		edd_vou_generate_pdf_by_html( $voucher_template_html, $pdf_args );
	}
}
add_action( 'init', 'edd_vou_preview_pdf', 9 );


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
//function edd_vou_process_download_pdf() {
//for edd version 2.0 greater or equal
function edd_vou_process_download_pdf( $downloadid, $orderid, $orderdvoucodes = array(), $price_id = false ) {
	
	$prefix = EDD_VOU_META_PREFIX;
	
	global $current_user, $edd_vou_model, $edd_options;
	
	//model class
	$model = $edd_vou_model;
	
	$pdf_args = array();
	
	if( !empty( $orderid ) ) { // Check order id is not empty
		
		//orderdata
		$orderdata 		= $model->edd_vou_get_post_meta_ordered( $orderid );
		//get all voucher details from order meta
		$allorderdata 	= $model->edd_vou_get_all_ordered_data( $orderid );
		
		// Getting buysers information
		$edd_buyer_info = edd_get_payment_meta_user_info( $orderid );
		 
		$buyer_email 	= ( !empty($edd_buyer_info['email']) )			? $edd_buyer_info['email']		: '';
		$buyer_fname 	= ( !empty($edd_buyer_info['first_name']) ) 	? $edd_buyer_info['first_name'] : '';
		$buyer_lname 	= ( !empty($edd_buyer_info['last_name']) ) 	? $edd_buyer_info['last_name'] 	: '';
		$buyer_fullname = $buyer_fname .' '. $buyer_lname;
		
		// get order meta
		$meta      = edd_get_payment_meta( $orderid );
		 		 
		$orderdate = isset( $meta['date'] ) ? $meta['date'] : '';
		
		if( !empty( $orderdate ) ) {
			$orderdate = $model->edd_vou_get_date_format( $orderdate );
		}
		
		$voucher_key = $downloadid;
		
		// variable product
		if( !empty( $price_id ) ) {
			$voucher_key = $downloadid . '_' . $price_id;
		}
		
		$productname = $model->edd_vou_get_product_name( $orderid, $downloadid ); // Taking product name
				
		// If price id is set then get product price of particular variation.		
		if( isset($price_id) && $price_id !== false && isset($orderdata[$voucher_key]) && isset($orderdata[$voucher_key]['price_id']) ) {
			// get product pruce
			$productprice = edd_currency_filter( edd_format_amount( $model->edd_vou_get_product_price( $orderid, $downloadid, $orderdata[$voucher_key]['price_id'] ) ) ); // Taking product price	
		} else {
			// get product pruce
			$productprice = edd_currency_filter( edd_format_amount( $model->edd_vou_get_product_price( $orderid, $downloadid ) ) ); // Taking product price	
		}
		
		// If price id is set then take its option name
		// Note: we are taking the price id from the order data because it default set the price id to 0 which creates wrong product name
		if( isset($price_id) && $price_id !== false && isset($orderdata[$voucher_key]) && isset($orderdata[$voucher_key]['price_id']) ) {
			$productname .= ' - ' . edd_get_price_option_name( $downloadid, $orderdata[$voucher_key]['price_id'], $orderid );
		}
		
		//vouchers data of pdf
		$voucherdata 	= isset( $orderdata[$voucher_key] ) ? $orderdata[$voucher_key] : array();
		//get all voucher details from order meta
		$allvoucherdata = isset( $allorderdata[$voucher_key] ) ? $allorderdata[$voucher_key] : array();
		
		//how to use the voucher details
		//$howtouse = get_post_meta( $downloadid, $prefix.'how_to_use', true );
		$howtouse = isset( $allvoucherdata['redeem'] ) ? $allvoucherdata['redeem'] : '';
	
		//expiry data
		//$exp_date = get_post_meta( $downloadid, $prefix.'exp_date', true );
		$exp_date = isset( $allvoucherdata['exp_date'] ) ? $allvoucherdata['exp_date'] : '';
		
		//vou logo
		//$voulogo = get_post_meta( $downloadid, $prefix.'logo', true );
		$voulogo = isset( $allvoucherdata['vendor_logo'] ) ? $allvoucherdata['vendor_logo'] : '';
		$voulogo = isset( $voulogo['src'] ) && !empty( $voulogo['src'] ) ? $voulogo['src'] : '';
		
		//website url 
		//$website = get_post_meta( $downloadid, $prefix.'website', true );
		$website = isset( $allvoucherdata['website_url'] ) ? $allvoucherdata['website_url'] : '';	
		
		//vendor address
		//$addressphone = get_post_meta( $downloadid, $prefix.'address_phone', true );
		$addressphone = isset( $allvoucherdata['vendor_address'] ) ? $allvoucherdata['vendor_address'] : '';
		
		//location where voucher is availble
		//$locations = get_post_meta( $downloadid, $prefix.'avail_locations', true );
		$locations = isset( $allvoucherdata['avail_locations'] ) ? $allvoucherdata['avail_locations'] : '';
		
		//Voucher template from meta
		$pdf_template_meta = get_post_meta( $downloadid, $prefix.'pdf_template', true );
		//$pdf_template_meta = isset( $allvoucherdata['pdf_template'] ) ? $allvoucherdata['pdf_template'] : '';
		
		//Get recipient details
		$recipientname		= isset( $allvoucherdata['recipient_name'] ) ? $allvoucherdata['recipient_name'] 	: '';
		$recipientemail		= isset( $allvoucherdata['recipient_email'] ) ? $allvoucherdata['recipient_email'] 	: '';
		$recipientmessage	= isset( $allvoucherdata['recipient_message'] ) ? $allvoucherdata['recipient_message'] 	: '';
		
		//get the payment gateway
		$gateway = edd_get_payment_gateway( $orderid );
		$payment_method = edd_get_gateway_admin_label( $gateway );
		 
		//vendor logo
		$voulogohtml = '';
		if( !empty( $voulogo ) ) {
			
			$voulogohtml = '<img src="' . $voulogo . '" alt="" />';
		}
		
		//site logo
		$vousitelogohtml = '';
		if( !empty( $edd_options['vou_site_logo'] ) ) {
			
			$vousitelogohtml = '<img src="' . $edd_options['vou_site_logo'] . '" alt="" />';
		}
		
		//expiration date
		if( !empty( $exp_date ) ) {
			$expiry_date = $model->edd_vou_get_date_format( $exp_date );
		} else {
			$expiry_date = __( 'No Expiration', 'eddvoucher' );
		}	
		
		//get voucher template id
		$voucher_template_id = isset( $edd_options['vou_pdf_template'] ) ? $edd_options['vou_pdf_template'] : '';
		
		//set voucher template id priority
		$voucher_template_id = !empty( $pdf_template_meta ) ? $pdf_template_meta : $voucher_template_id;
		
		//get template data for check its exist
		$voucher_template_data = get_post( $voucher_template_id );
		
		// Pdf arguments
		$pdf_args['char_support'] = !empty($edd_options['vou_char_support']) ? 1 : 0;
		
		//get voucher template html data
		$voucher_template_html = $voucher_template_css = '';
		if( !empty( $voucher_template_id ) && !empty( $voucher_template_data ) ) { // Check Template id and its exist or not
			
			$locations_html = $voucodes = '';
			
			$pdf_args['vou_template_id'] = $voucher_template_id;
			
			//locations for voucher use
			if( !empty( $locations ) ) {
				
				foreach ( $locations as $key => $value ) {
					
					if( isset( $value[$prefix.'locations'] ) && !empty( $value[$prefix.'locations'] ) ) {
					
						if( isset( $value[$prefix.'map_link'] ) && !empty( $value[$prefix.'map_link'] ) ) {
							$locations_html .= '<a style="text-decoration: none;" href="' . $value[$prefix.'map_link'] . '">' . $value[$prefix.'locations'] . '</a> ';
						} else {
							$locations_html .= $value[$prefix.'locations'] . ' ';
						}
					}
				}
			}
			
			$voucher_template_html = '<html>
										<head>
											<style>
												.edd_vou_textblock {
													text-align: justify;
												}
												.edd_vou_messagebox {
													text-align: justify;
												}
											</style>
										</head>
										<body>';
			
			$content_post = get_post( $voucher_template_id );
			$content = isset( $content_post->post_content ) ? $content_post->post_content : '';
			$voucher_template_inner_html = do_shortcode( $content );
			
			$multiple_pdf = empty( $orderid ) ? '' : get_post_meta( $orderid, $prefix . 'multiple_pdf', true );
		
			if( $multiple_pdf == '1' && !empty( $orderdvoucodes ) ) { //check is enable multiple pdf
			
					$key = isset( $_GET['file'] ) ? $_GET['file'] : '';
					$voucodes = isset( $orderdvoucodes[$key] ) ? $orderdvoucodes[$key] : '';
					
			} elseif ( !empty( $voucherdata['codes'] ) ) {
					
					$voucodes = trim( $voucherdata['codes'] );
			}
			
			$voucher_template_inner_html = str_replace( '{codes}', $voucodes, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{redeem}', nl2br( $howtouse ), $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{vendorlogo}', $voulogohtml, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{sitelogo}', $vousitelogohtml, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{expiredate}', $expiry_date, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{vendoraddress}', nl2br( $addressphone ), $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{siteurl}', $website, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{location}', $locations_html, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{buyername}', $buyer_fullname, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{buyeremail}', $buyer_email, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{orderid}', $orderid, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{orderdate}', $orderdate, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{productname}', $productname, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{productprice}', $productprice, $voucher_template_inner_html );
			
			$voucher_template_inner_html = str_replace( '{recipientname}', $recipientname, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{recipientemail}', $recipientemail, $voucher_template_inner_html );
			$voucher_template_inner_html = str_replace( '{recipientmessage}', nl2br( $recipientmessage ), $voucher_template_inner_html );
			
			$voucher_template_inner_html = str_replace( '{payment_method}', $payment_method, $voucher_template_inner_html );
	
			$voucher_template_html .= $voucher_template_inner_html;
			$voucher_template_html .= '</body>
									</html>';
			
		} else { // Default Template
			
			$voucher_template_html = '';
			
			$voucher_template_html .= '<table class="edd_vou_pdf_table">';
			
			//site logo
			if( !empty( $vousitelogohtml ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="2">' . $vousitelogohtml . '</td>
											<td colspan="2">&nbsp;</td>
										</tr>';
			}
		
			//voucher logo
			if( !empty( $voulogohtml ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="2">' . $voulogohtml . '</td>
											<td colspan="2">&nbsp;</td>
										</tr>';
			}
		
			//voucher website
			if( !empty( $website ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="2">' . $website . '</td>
											<td colspan="2">&nbsp;</td>
										</tr>';
			}
			
			//vendor's address & phone
			if( !empty( $addressphone ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="2">' . nl2br( $addressphone ) . '</td>
											<td colspan="2">&nbsp;</td>
										</tr>';
			}
			
			//Get mutiple pdf option from order meta
			$multiple_pdf = empty( $orderid ) ? '' : get_post_meta( $orderid, $prefix . 'multiple_pdf', true );
			
			if( $multiple_pdf == '1' && !empty( $orderdvoucodes ) ) {
				
				$key = isset( $_GET['file'] ) ? $_GET['file'] : '';
				
				$voucodes = $orderdvoucodes[$key];
				
				$voucher_template_html .= '<tr>
											<td colspan="4" style="text-align: center;">
												<table border="1">';
				$voucher_template_html .= '			<tr>
														<td><h3>' . __( 'Voucher Code(s)', 'eddvoucher' ) . '</h3></td>
													</tr>';					
				$voucher_template_html .= '			<tr>
														<td><h4>' . $voucodes . '</h4></td>
													</tr>';				
				$voucher_template_html .= '		</table>
											</td>
										</tr>';
				
				
			} elseif( !empty( $voucherdata['codes'] ) ) {
				
				$voucodes = trim( $voucherdata['codes'] );
				
				$voucher_template_html .= '<tr>
											<td colspan="4" style="text-align: center;">
												<table border="1">';
				$voucher_template_html .= '			<tr>
														<td><h3>' . __( 'Voucher Code(s)', 'eddvoucher' ) . '</h3></td>
													</tr>';
				$codes = explode( ', ', trim( $voucodes ) );
				foreach ( $codes as $code ) {
					
				$voucher_template_html .= '			<tr>
														<td><h4>' . $code . '</h4></td>
													</tr>';
				}
				$voucher_template_html .= '		</table>
											</td>
										</tr>';
			}
			
			//voucher use instruction
			if( !empty( $howtouse ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="4"><h4>' . __( 'How to redeem this Voucher', 'eddvoucher' ) . '</h4></td>
										</tr>';
				$voucher_template_html .= '<tr>
											<td colspan="4">' . strip_tags( $howtouse ) . '</td>
										</tr>';
			}
			
			//expiration date
			if( !empty( $expiry_date ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="4">' . sprintf( __( 'Valid Until: %s', 'eddvoucher' ), $expiry_date ) . '</td>
										</tr>';
			}
			
			//locations for voucher use
			if( !empty( $locations ) ) {
				
				$voucher_template_html .= '<tr>
											<td colspan="4"><h4>' . __( 'Locations where you can redeem the Voucher', 'eddvoucher' ) . '</h4></td>
										</tr>';
				
				foreach ( $locations as $key => $value ) {
					
					/*$key = $key + 1;
					$location = '(' . $key . ') ' . $value[$prefix.'locations'];
					
					if( !empty( $value[$prefix.'map_link'] ) ) { 
						$location .= ' - ' . $value[$prefix.'map_link'];
					}*/
					
					$location = '';
					if( isset( $value[$prefix.'locations'] ) && !empty( $value[$prefix.'locations'] ) ) {
					
						if( isset( $value[$prefix.'map_link'] ) && !empty( $value[$prefix.'map_link'] ) ) {
							$location .= '<a style="text-decoration: none;" href="' . $value[$prefix.'map_link'] . '">' . $value[$prefix.'locations'] . '</a> ';
						} else {
							$location .= $value[$prefix.'locations'] . ' ';
						}
					}
						
				$voucher_template_html .= '<tr>
											<td colspan="4">' . $location . '</td>
										</tr>';
				}
			}
			
			$voucher_template_html .= '<tr>
											<td colspan="4">
												<table>
													<tr>
														<td>'.__('Buyer Name :', 'eddvoucher').$buyer_fullname.'</td>
													</tr>
													<tr>
														<td>'.__('Buyer Email :', 'eddvoucher').$buyer_email.'</td>
													</tr>
													<tr>
														<td>'.__('Order Id :', 'eddvoucher').$orderid.'</td>
													</tr>
												</table>
											</td>
									</tr>';
			
			$voucher_template_html .= '</table>';
		}
		
		edd_vou_generate_pdf_by_html( $voucher_template_html, $pdf_args );
	}
}
?>