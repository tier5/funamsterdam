<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

	global $post;
	
	$prefix = EDD_VOU_META_PREFIX;
		
	$edd_vou_status = get_post_meta( $post->ID, $prefix . 'editor_status', true ); //getting metabox value for setting editor status
	$edd_vou_metacontent = get_post_meta( $post->ID, $prefix . 'meta_content',true );//getting the default metabox content
	
	if( $edd_vou_status == "" || !isset( $edd_vou_status ) ) { // setting editor's value if not set
	    $edd_vou_status = 'true'; // set default true when create new voucher
	}
	$metastring = '';
	$metastring .= '<input type="hidden" id="edd_vou_editor_status" name="'.$prefix.'editor_status" value="'.$edd_vou_status.'">
					<div id="edd_vou_main_editor" class="edd_vou_main_editor" style="display:block;">
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_site_logo_btn" name="edd_vou_site_logo_btn"><div class="edd_vou_site_logo_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Site Logo From Settings', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_logo_btn" name="edd_vou_logo_btn"><div class="edd_vou_vendor_logo_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Vendor\'s Logo', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_text_btn" name="edd_vou_text_btn"><div class="edd_vou_text_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Voucher Code', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_expire_btn" name="edd_vou_expire_btn"><div class="edd_vou_expire_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Expiration Date', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_venaddr_btn" name="edd_vou_venaddr_btn"><div class="edd_vou_venaddr_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Vendor\'s Address', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_siteurl_btn" name="edd_vou_siteurl_btn"><div class="edd_vou_siteurl_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Website URL', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_message_btn" name="edd_vou_message_btn"><div class="edd_vou_message_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Redeem Instructions', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_loc_btn" name="edd_vou_loc_btn"><div class="edd_vou_loc_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Voucher Locations', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_blank_btn" name="edd_vou_blank_btn"><div class="edd_vou_blank_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Blank Block', 'eddvoucher' ) . '</span></span>
						<span class="edd_vou_tooltip"><a href="javascript:void(0);" class="edd_vou_main_buttons" id="edd_vou_custom_btn" name="edd_vou_custom_btn"><div class="edd_vou_custom_btn"></div></a><span class="edd_vou_classic">' . __( 'Click to add a Custom Block', 'eddvoucher' ) . '</span></span>';
	$metastring .=	'</div><!--main editor-->
					<div class="clear"></div>';
	
	if( empty( $edd_vou_metacontent ) ) {
		$metastring .= '<div class="edd_vou_builder_area">' . __( 'Voucher Builder Area', 'eddvoucher' ).'</div><div id="columns"><div class="edd_vou_controls" id="edd_vou_controls"></div></div>';
	} else {
		$metastring .= '<div class="edd_vou_builder_area" style="display:none;">' . __( 'Voucher Builder Area', 'eddvoucher' ).'</div><div id="columns"><div class="edd_vou_controls" id="edd_vou_controls">' . $edd_vou_metacontent . '</div></div>';
	}		
		
		$metastring .=	'<div style="display:none;"><textarea name="edd_vou_meta_content" id="edd_vou_meta_content" cols="60" rows="14">' . $edd_vou_metacontent . '</textarea></div><!--update meta content-->';
		$metastring .= '<div class="edd_vou_editor" id="edd_vou_edit_form" style="display:none;">
						</div>';
	echo $metastring;
?>