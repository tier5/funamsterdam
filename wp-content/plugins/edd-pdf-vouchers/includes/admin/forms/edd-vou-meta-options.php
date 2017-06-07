<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $edd_options, $edd_vou_model, $post;

$model = $edd_vou_model;

$prefix = EDD_VOU_META_PREFIX;
		
$bg_style = edd_vou_meta_value( array( 'id' => $prefix . 'pdf_bg_style', 'type' => 'radio' ) );
$bg_pattern_css = $bg_image_css = $pdf_size_css = 'edd-vou-meta-display-none';

if( $bg_style == 'color' ) { //Check background style is color
	
} else if( $bg_style == 'image' ) { //Check background style is image
	$bg_image_css = '';
} else { //Check background style is pattern
	$bg_pattern_css = '';
}

$pdf_size_css	= '';

//Get pdf sizes
$pdf_sizes	= edd_vou_get_pdf_sizes_select();

wp_nonce_field( EDD_VOU_PLUGIN_BASENAME, 'at_edd_vou_meta_box_nonce' );

	edd_vou_content_begin();
	
		// voucher background image option
		edd_vou_add_radio( array( 'id' => $prefix . 'pdf_bg_style', 'name'=> __( 'Background Style:', 'eddvoucher' ), 'default' => 'pattern', 'options' => array( 'pattern' => __( 'Background Pattern', 'eddvoucher' ), 'image' => __( 'Background Image', 'eddvoucher' ), 'color' => __( 'Background Color', 'eddvoucher' ) ), 'desc' => __( 'Choose the background style for the PDF.', 'eddvoucher' ) ) );
	
		// voucher background pattern
		edd_vou_add_bg_pattern( array( 'id' => $prefix . 'pdf_bg_pattern', 'wrap_class' => 'edd-vou-meta-bg-pattern-wrap ' . $bg_pattern_css, 'name'=> __( 'Background Pattern:', 'eddvoucher' ), 'default' => 'pattern1', 'options' => array( 'pattern1', 'pattern2', 'pattern3', 'pattern4', 'pattern5' ), 'desc' => __( 'Select background pattern for the PDF.', 'eddvoucher' ) ) );
	
		// voucher background image
		edd_vou_add_image( array( 'id' => $prefix . 'pdf_bg_img', 'wrap_class' => 'edd-vou-meta-bg-image-wrap ' . $bg_image_css, 'name'=> __( 'Background Image:', 'eddvoucher' ), 'desc' => __( 'Upload the background image for the PDF.', 'eddvoucher' ) ) );
	
		// voucher background color
		edd_vou_add_color( array( 'id' => $prefix . 'pdf_bg_color', 'name'=> __( 'Background Color:', 'eddvoucher' ), 'desc' => __( 'Select background color for the PDF.', 'eddvoucher' ) ) );
	
		// voucher lanscap or portrait view
		edd_vou_add_select( array( 'id' => $prefix . 'pdf_view', 'class' => 'regular-text', 'name'=> __( 'View:', 'eddvoucher' ), 'options' => array( 'land' => __( 'Landscape', 'eddvoucher' ), 'port' => __( 'Portrait', 'eddvoucher' ) ), 'desc' => __( 'Select voucher pdf view in landscape or portrait.', 'eddvoucher' ) ) );
		
		// voucher pdf size
		edd_vou_add_select( array( 'id' => $prefix . 'pdf_size', 'wrap_class' => 'edd-vou-meta-pdf-size-wrap ' . $pdf_size_css, 'default' => 'A4', 'style' => 'min-width:200px;float: left;', 'class' => 'regular-text wc-enhanced-select', 'name'=> __( 'Pdf Size:', 'eddvoucher' ), 'options' => $pdf_sizes, 'desc' => __( 'Select voucher pdf size.', 'eddvoucher' ) ) );
		
		// voucher margin top
		edd_vou_add_number( array( 'id' => $prefix . 'pdf_margin_top', 'class' => 'small-text', 'name'=> __( 'Margin Top:', 'eddvoucher' ), 'desc' => __( 'Enter the margin top for the PDF, please set margin in pixel.', 'eddvoucher' ) ) );
		
		// voucher margin top
		edd_vou_add_number( array( 'id' => $prefix . 'pdf_margin_bottom', 'class' => 'small-text', 'name'=> __( 'Margin Bottom:', 'eddvoucher' ), 'desc' => __( 'Enter the margin bottom for the PDF, please set margin in pixel.', 'eddvoucher' ) ) );
		
		// voucher margin left
		edd_vou_add_number( array( 'id' => $prefix . 'pdf_margin_left', 'class' => 'small-text', 'name'=> __( 'Margin Left:', 'eddvoucher' ), 'desc' => __( 'Enter the margin left for the PDF, please set margin in pixel.', 'eddvoucher' ) ) );
	
		// voucher margin right
		edd_vou_add_number( array( 'id' => $prefix . 'pdf_margin_right', 'class' => 'small-text', 'name'=> __( 'Margin Right:', 'eddvoucher' ), 'desc' => __( 'Enter the margin right for the PDF, please set margin in pixel.', 'eddvoucher' ) ) );
	
	edd_vou_content_end();	

?>