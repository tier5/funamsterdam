<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */

/**
 * Create Default Templates
 * 
 * Handle to create default templates
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
function edd_create_default_templates() {
	
	global $user_ID;
	
	$prefix = EDD_VOU_META_PREFIX;
	
	// Create Multi Color Template
	$template_muc_content = '<table class="edd_vou_pdf_table">
							<tbody>
								<tr>
								<td colspan="1"></td>
								<td colspan="2">[edd_vou_logo]{vendorlogo}
								
								[/edd_vou_logo]</td>
								<td colspan="1"></td>
								</tr>
								<tr>
								<td colspan="4"></td>
								</tr>
								<tr>
								<td colspan="2">[edd_vou_code_title color="#000000" fontsize="18" textalign="left"]Voucher Code
								
								[/edd_vou_code_title][edd_vou_code codetextalign="left"]{codes}
								
								[/edd_vou_code]</td>
								<td colspan="2">[edd_vou_vendor_address]
								<p style="text-align: right;">{vendoraddress}</p>
								
								[/edd_vou_vendor_address]</td>
								</tr>
								<tr>
								<td colspan="2">[edd_vou_expire_date]Expires : {expiredate}
								
								[/edd_vou_expire_date]</td>
								<td colspan="2">[edd_vou_siteurl]
								<p style="text-align: right;">{siteurl}</p>
								
								[/edd_vou_siteurl]</td>
								</tr>
								<tr>
								<td colspan="4"></td>
								</tr>
								<tr>
								<td colspan="4">[edd_vou_redeem]
								<p style="text-align: center;">{redeem}</p>
								
								[/edd_vou_redeem]</td>
								</tr>
								<tr>
								<td colspan="4"></td>
								</tr>
								<tr>
								<td colspan="4">[edd_vou_location]
								<h3 style="text-align: center;">AVAILABLE AT</h3>
								<p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p>
								
								[/edd_vou_location]</td>
								</tr>
							</tbody>
						</table>';
	$template_muc_meta_content = '<div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column logoblock draghandle one_half" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendorlogo}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_logo_width" name="edd_vou_text_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column textblock draghandle one_half" style="display: block; color: rgb(0, 0, 0); text-align: left; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcode" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text" style="font-size: 18pt;"><p>Voucher Code</p></div><div class="edd_vou_text_codes"><p>{codes}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_text_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_text_bg" name="edd_vou_text_bg" value="" type="hidden"><input class="edd_vou_text_font_color" id="edd_vou_text_font_color" name="edd_vou_text_font_color" value="#000000" type="hidden"><input class="edd_vou_text_font_size" id="edd_vou_text_font_size" name="edd_vou_text_font_size" value="18" type="hidden"><input class="edd_vou_text_text_align" id="edd_vou_text_text_align" name="edd_vou_text_text_align" value="left" type="hidden"><input class="edd_vou_text_code_text_align" id="edd_vou_text_code_text_align" name="edd_vou_text_code_text_align" value="left" type="hidden"><input class="edd_vou_text_code_border" id="edd_vou_text_code_border" name="edd_vou_text_code_border" value="" type="hidden"><input class="edd_vou_text_code_column" id="edd_vou_text_code_column" name="edd_vou_text_code_column" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column venaddrblock draghandle one_half" style="display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editvenaddr" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: right;">{vendoraddress}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_venaddr_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_venaddr_bg" name="edd_vou_venaddr_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column expireblock draghandle one_half" style="z-index: 0; left: 0px; top: 0px; display: block; background-color: rgb(255, 255, 255);"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editexpire" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Expires : {expiredate}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_expire_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_expire_bg" name="edd_vou_expire_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column siteurlblock draghandle one_half" style="z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editsiteurl" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: right;">{siteurl}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_siteurl_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_siteurl_bg" name="edd_vou_siteurl_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column messagebox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editredeem" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: center;">{redeem}</p></div><input value="" class="edd_vou_text_bg" id="edd_vou_msg_color" name="edd_vou_msg_color" type="hidden"><input id="edd_vou_messagebox_width" class="edd_vou_txtclass_width" name="edd_vou_text_width" value="full_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column locblock draghandle full_width" style="display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editloc" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><h3 style="text-align: center;">AVAILABLE AT</h3><p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_loc_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_loc_bg" name="edd_vou_loc_bg" value="" type="hidden"></div>';
	$template_muc_page = array(
								'post_type' 	=> EDD_VOU_POST_TYPE,
								'post_status' 	=> 'publish',
								'post_title' 	=> __( 'Multi Color Template', 'eddvoucher' ),
								'post_content' 	=> $template_muc_content,
								'post_author' 	=> $user_ID,
								'menu_order' 	=> 5,
								'comment_status' => 'closed'
							);

	$template_muc_page_id = wp_insert_post( $template_muc_page );
	
	if( $template_muc_page_id ) { //Check template id
		
		update_post_meta( $template_muc_page_id, $prefix . 'meta_content', $template_muc_meta_content );
		update_post_meta( $template_muc_page_id, $prefix . 'editor_status', 'true' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_bg_style', 'pattern' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_bg_pattern', 'pattern5' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_bg_img', '' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_bg_color', '#eaeaea' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_view', 'land' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_margin_top', '20' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_margin_bottom', '25' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_margin_left', '25' );
		update_post_meta( $template_muc_page_id, $prefix . 'pdf_margin_right', '25' );
	}
	
	// End code for Multi Color Template
	
	// Create Pink Template
	$template_pink_content = '<table class="edd_vou_pdf_table">
							<tbody>
								<tr>
									<td colspan="4">[edd_vou_logo]{vendorlogo}[/edd_vou_logo]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="2">[edd_vou_code_title color="#000000" fontsize="16" textalign="left"]Voucher Code[/edd_vou_code_title][edd_vou_code codetextalign="left"]{codes}[/edd_vou_code]</td>
									<td colspan="2">[edd_vou_vendor_address]{vendoraddress}[/edd_vou_vendor_address]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="2">[edd_vou_expire_date]Expires : {expiredate}[/edd_vou_expire_date]</td>
									<td colspan="2">[edd_vou_siteurl]{siteurl}[/edd_vou_siteurl]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="2">[edd_vou_redeem]{redeem}[/edd_vou_redeem]</td>
									<td colspan="2">[edd_vou_location]<h4>AVAILABLE AT</h4><span style="font-size: 9pt;">{location}</span>[/edd_vou_location]</td>
								</tr>
							</tbody>
						</table>';
	$template_pink_meta_content = '<div class="edd_vou_controls_editor text_column logoblock draghandle full_width" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendorlogo}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_logo_width" name="edd_vou_text_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column textblock draghandle one_half" style="display: block; color: rgb(0, 0, 0); text-align: left;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcode" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text" style="font-size: 16pt;"><p>Voucher Code</p></div><div class="edd_vou_text_codes"><p>{codes}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_text_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_text_bg" name="edd_vou_text_bg" value="" type="hidden"><input class="edd_vou_text_font_color" id="edd_vou_text_font_color" name="edd_vou_text_font_color" value="#000000" type="hidden"><input class="edd_vou_text_font_size" id="edd_vou_text_font_size" name="edd_vou_text_font_size" value="16" type="hidden"><input class="edd_vou_text_text_align" id="edd_vou_text_text_align" name="edd_vou_text_text_align" value="left" type="hidden"><input class="edd_vou_text_code_text_align" id="edd_vou_text_code_text_align" name="edd_vou_text_code_text_align" value="left" type="hidden"><input class="edd_vou_text_code_border" id="edd_vou_text_code_border" name="edd_vou_text_code_border" value="" type="hidden"><input class="edd_vou_text_code_column" id="edd_vou_text_code_column" name="edd_vou_text_code_column" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column venaddrblock draghandle one_half" style="background-color: rgb(255, 255, 255); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editvenaddr" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendoraddress}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_venaddr_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_venaddr_bg" name="edd_vou_venaddr_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column expireblock draghandle one_half" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editexpire" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Expires : {expiredate}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_expire_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_expire_bg" name="edd_vou_expire_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column siteurlblock draghandle one_half" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editsiteurl" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{siteurl}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_siteurl_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_siteurl_bg" name="edd_vou_siteurl_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column messagebox draghandle one_half" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editredeem" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{redeem}</p></div><input value="" class="edd_vou_text_bg" id="edd_vou_msg_color" name="edd_vou_msg_color" type="hidden"><input id="edd_vou_messagebox_width" class="edd_vou_txtclass_width" name="edd_vou_text_width" value="one_half" type="hidden"></div><div class="edd_vou_controls_editor text_column locblock draghandle one_half" style="display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editloc" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><h4>AVAILABLE AT</h4><p><span style="font-size: 9pt;">{location}</span></p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_loc_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_loc_bg" name="edd_vou_loc_bg" value="" type="hidden"></div>';
	$template_pink_page = array(
								'post_type' 	=> EDD_VOU_POST_TYPE,
								'post_status' 	=> 'publish',
								'post_title' 	=> __( 'Pink Template', 'eddvoucher' ),
								'post_content' 	=> $template_pink_content,
								'post_author' 	=> $user_ID,
								'menu_order' 	=> 4,
								'comment_status' => 'closed'
							);

	$template_pink_page_id = wp_insert_post( $template_pink_page );
	
	if( $template_pink_page_id ) { //Check template id
		
		update_post_meta( $template_pink_page_id, $prefix . 'meta_content', $template_pink_meta_content );
		update_post_meta( $template_pink_page_id, $prefix . 'editor_status', 'true' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_bg_style', 'pattern' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_bg_pattern', 'pattern4' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_bg_img', '' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_bg_color', '#cccccc' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_view', 'land' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_margin_top', '15' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_margin_bottom', '25' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_margin_left', '25' );
		update_post_meta( $template_pink_page_id, $prefix . 'pdf_margin_right', '25' );
	}
	
	// End code for Pink Template
	
	// Create Blue Template
	$template_blue_content = '<table class="edd_vou_pdf_table">
							<tbody>
								<tr>
									<td colspan="4">[edd_vou_site_logo]{sitelogo}[/edd_vou_site_logo]</td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_logo]{vendorlogo}[/edd_vou_logo]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_code_title fontsize="16" textalign="left"]<span style="color: #1e73be;">Voucher Code</span>[/edd_vou_code_title][edd_vou_code codetextalign="left"]{codes}[/edd_vou_code]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_vendor_address]{vendoraddress}[/edd_vou_vendor_address]</td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_siteurl]{siteurl}[/edd_vou_siteurl]</td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_expire_date]Expires : {expiredate}[/edd_vou_expire_date]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_redeem]{redeem}[/edd_vou_redeem]</td>
								</tr>
								<tr>
									<td colspan="4"></td>
								</tr>
								<tr>
									<td colspan="4">[edd_vou_location]<h3 style="text-align: center;"><span style="color: #1e73be;">AVAILABLE AT</span></h3><p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p>[/edd_vou_location]</td>
								</tr>
							</tbody>
						</table>';
	$template_blue_meta_content = '<div class="edd_vou_controls_editor text_column sitelogoblock draghandle full_width" style="background-color: rgb(255, 255, 255); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{sitelogo}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_site_logo_width" name="edd_vou_text_width" type="hidden"></div><div class="edd_vou_controls_editor text_column logoblock draghandle full_width" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendorlogo}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_logo_width" name="edd_vou_text_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column textblock full_width draghandle" style="display: block; color: rgb(30, 115, 190); text-align: left;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcode" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text" style="font-size: 16pt;"><p><span style="color: #1e73be;">Voucher Code</span></p></div><div class="edd_vou_text_codes"><p>{codes}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_text_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_text_bg" name="edd_vou_text_bg" value="" type="hidden"><input class="edd_vou_text_font_color" id="edd_vou_text_font_color" name="edd_vou_text_font_color" value="#1e73be" type="hidden"><input class="edd_vou_text_font_size" id="edd_vou_text_font_size" name="edd_vou_text_font_size" value="16" type="hidden"><input class="edd_vou_text_text_align" id="edd_vou_text_text_align" name="edd_vou_text_text_align" value="left" type="hidden"><input class="edd_vou_text_code_text_align" id="edd_vou_text_code_text_align" name="edd_vou_text_code_text_align" value="left" type="hidden"><input class="edd_vou_text_code_border" id="edd_vou_text_code_border" name="edd_vou_text_code_border" value="" type="hidden"><input class="edd_vou_text_code_column" id="edd_vou_text_code_column" name="edd_vou_text_code_column" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column venaddrblock full_width draghandle" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editvenaddr" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendoraddress}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_venaddr_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_venaddr_bg" name="edd_vou_venaddr_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column siteurlblock draghandle full_width" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editsiteurl" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{siteurl}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_siteurl_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_siteurl_bg" name="edd_vou_siteurl_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column expireblock draghandle full_width" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editexpire" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Expires : {expiredate}</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_expire_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_expire_bg" name="edd_vou_expire_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column messagebox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editredeem" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{redeem}</p></div><input value="" class="edd_vou_text_bg" id="edd_vou_msg_color" name="edd_vou_msg_color" type="hidden"><input id="edd_vou_messagebox_width" class="edd_vou_txtclass_width" name="edd_vou_text_width" value="full_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column locblock full_width draghandle" style="z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editloc" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><h3 style="text-align: center;"><span style="color: #1e73be;">AVAILABLE AT</span></h3><p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_loc_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_loc_bg" name="edd_vou_loc_bg" value="" type="hidden"></div>';
	$template_blue_page = array(
								'post_type' 	=> EDD_VOU_POST_TYPE,
								'post_status' 	=> 'publish',
								'post_title' 	=> __( 'Blue Template', 'eddvoucher' ),
								'post_content' 	=> $template_blue_content,
								'post_author' 	=> $user_ID,
								'menu_order' 	=> 3,
								'comment_status' => 'closed'
							);

	$template_blue_page_id = wp_insert_post( $template_blue_page );
	
	if( $template_blue_page_id ) { //Check template id
		
		update_post_meta( $template_blue_page_id, $prefix . 'meta_content', $template_blue_meta_content );
		update_post_meta( $template_blue_page_id, $prefix . 'editor_status', 'true' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_bg_style', 'pattern' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_bg_pattern', 'pattern3' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_bg_img', '' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_bg_color', '' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_view', 'port' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_margin_top', '20' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_margin_bottom', '25' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_margin_left', '20' );
		update_post_meta( $template_blue_page_id, $prefix . 'pdf_margin_right', '20' );
	}
	
	// End code for Blue Template
	
	// Create Green Template
	$template_green_content = '<table class="edd_vou_pdf_table">
							<tbody>
								<tr>
								<td colspan="1"></td>
								<td colspan="2">[edd_vou_logo]{vendorlogo}
								
								[/edd_vou_logo]</td>
								<td colspan="1"></td>
								</tr>
								<tr>
								<td colspan="4"></td>
								</tr>
								<tr>
								<td colspan="2">[edd_vou_vendor_address]{vendoraddress}
								
								[/edd_vou_vendor_address]</td>
								<td colspan="2">[edd_vou_code_title color="#000000" fontsize="18" textalign="right"]Voucher Code
								
								[/edd_vou_code_title][edd_vou_code codetextalign="right"]{codes}
								
								[/edd_vou_code]</td>
								</tr>
								<tr>
								<td colspan="2">[edd_vou_siteurl]{siteurl}
								
								[/edd_vou_siteurl]</td>
								<td colspan="2">[edd_vou_expire_date]
								<p style="text-align: right;">Expires : {expiredate}</p>
								
								[/edd_vou_expire_date]</td>
								</tr>
								<tr>
								<td colspan="4"></td>
								</tr>
								<tr>
								<td colspan="4">[edd_vou_redeem]{redeem}
								
								[/edd_vou_redeem]</td>
								</tr>
								<tr>
								<td colspan="4">[edd_vou_location]
								<h3 style="text-align: center;">AVAILABLE AT</h3>
								<p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p>
								
								[/edd_vou_location]</td>
								</tr>
							</tbody>
						</table>';
	$template_green_meta_content = '<div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column logoblock draghandle one_half" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendorlogo}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_logo_width" name="edd_vou_text_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle full_width" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column venaddrblock draghandle one_half" style="background-color: rgb(255, 255, 255); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editvenaddr" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendoraddress}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_venaddr_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_venaddr_bg" name="edd_vou_venaddr_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column textblock draghandle one_half" style="display: block; color: rgb(0, 0, 0); text-align: right; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcode" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text" style="font-size: 18pt;"><p>Voucher Code</p></div><div class="edd_vou_text_codes"><p>{codes}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_text_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_text_bg" name="edd_vou_text_bg" value="" type="hidden"><input class="edd_vou_text_font_color" id="edd_vou_text_font_color" name="edd_vou_text_font_color" value="#000000" type="hidden"><input class="edd_vou_text_font_size" id="edd_vou_text_font_size" name="edd_vou_text_font_size" value="18" type="hidden"><input class="edd_vou_text_text_align" id="edd_vou_text_text_align" name="edd_vou_text_text_align" value="right" type="hidden"><input class="edd_vou_text_code_text_align" id="edd_vou_text_code_text_align" name="edd_vou_text_code_text_align" value="right" type="hidden"><input class="edd_vou_text_code_border" id="edd_vou_text_code_border" name="edd_vou_text_code_border" value="" type="hidden"><input class="edd_vou_text_code_column" id="edd_vou_text_code_column" name="edd_vou_text_code_column" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column siteurlblock draghandle one_half" style="background-color: rgb(255, 255, 255); display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editsiteurl" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{siteurl}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_siteurl_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_siteurl_bg" name="edd_vou_siteurl_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column expireblock draghandle one_half" style="z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editexpire" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: right;">Expires : {expiredate}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_expire_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_expire_bg" name="edd_vou_expire_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column messagebox draghandle full_width" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editredeem" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{redeem}</p></div><input value="" class="edd_vou_text_bg" id="edd_vou_msg_color" name="edd_vou_msg_color" type="hidden"><input id="edd_vou_messagebox_width" class="edd_vou_txtclass_width" name="edd_vou_text_width" value="full_width" type="hidden"></div><div class="edd_vou_controls_editor text_column locblock full_width draghandle" style="display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editloc" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><h3 style="text-align: center;">AVAILABLE AT</h3><p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_loc_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_loc_bg" name="edd_vou_loc_bg" value="" type="hidden"></div>';
	$template_green_page = array(
									'post_type' 	=> EDD_VOU_POST_TYPE,
									'post_status' 	=> 'publish',
									'post_title' 	=> __( 'Green Template', 'eddvoucher' ),
									'post_content' 	=> $template_green_content,
									'post_author' 	=> $user_ID,
									'menu_order' 	=> 2,
									'comment_status' => 'closed'
								);

	$template_green_page_id = wp_insert_post( $template_green_page );
	
	if( $template_green_page_id ) { //Check template id
		
		update_post_meta( $template_green_page_id, $prefix . 'meta_content', $template_green_meta_content );
		update_post_meta( $template_green_page_id, $prefix . 'editor_status', 'true' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_bg_style', 'pattern' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_bg_pattern', 'pattern1' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_bg_img', '' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_bg_color', '#e0e0e0' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_view', 'land' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_margin_top', '22' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_margin_bottom', '25' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_margin_left', '22' );
		update_post_meta( $template_green_page_id, $prefix . 'pdf_margin_right', '22' );
	}
	
	// End code for Green Template
	
	// Create Default Template
	$default_template_content = '<table class="edd_vou_pdf_table">
									<tbody>
										<tr>
										<td colspan="1"></td>
										<td colspan="2">[edd_vou_logo]{vendorlogo}
										
										[/edd_vou_logo]</td>
										<td colspan="1"></td>
										</tr>
										<tr>
										<td colspan="2">[edd_vou_code_title color="#000000" fontsize="18" textalign="left"]Voucher Code
										
										[/edd_vou_code_title][edd_vou_code codetextalign="left"]{codes}
										
										[/edd_vou_code]</td>
										<td colspan="2">[edd_vou_vendor_address]
										<p style="text-align: right;">{vendoraddress}</p>
										
										[/edd_vou_vendor_address]</td>
										</tr>
										<tr>
										<td colspan="2"></td>
										<td colspan="2">[edd_vou_siteurl]
										<p style="text-align: right;">{siteurl}</p>
										
										[/edd_vou_siteurl]</td>
										</tr>
										<tr>
										<td colspan="2"></td>
										<td colspan="2">[edd_vou_expire_date]
										<p style="text-align: right;">Expires : {expiredate}</p>
										
										[/edd_vou_expire_date]</td>
										</tr>
										<tr>
										<td colspan="4"></td>
										</tr>
										<tr>
										<td colspan="4">[edd_vou_redeem]
										<p style="text-align: center;">{redeem}</p>
										
										[/edd_vou_redeem]</td>
										</tr>
										<tr>
										<td colspan="4"></td>
										</tr>
										<tr>
										<td colspan="1"></td>
										<td colspan="2">[edd_vou_location]
										<h3 style="text-align: center;">AVAILABLE AT</h3>
										<p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p>
										
										[/edd_vou_location]</td>
										<td colspan="1"></td>
										</tr>
									</tbody>
								</table>';
	$default_template_meta_content = '<div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column logoblock draghandle one_half" style="background-color: rgb(255, 255, 255); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>{vendorlogo}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_logo_width" name="edd_vou_text_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column textblock draghandle one_half" style="display: block; color: rgb(0, 0, 0); text-align: left; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcode" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text" style="font-size: 18pt;"><p>Voucher Code</p></div><div class="edd_vou_text_codes"><p>{codes}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_text_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_text_bg" name="edd_vou_text_bg" value="" type="hidden"><input class="edd_vou_text_font_color" id="edd_vou_text_font_color" name="edd_vou_text_font_color" value="#000000" type="hidden"><input class="edd_vou_text_font_size" id="edd_vou_text_font_size" name="edd_vou_text_font_size" value="18" type="hidden"><input class="edd_vou_text_text_align" id="edd_vou_text_text_align" name="edd_vou_text_text_align" value="left" type="hidden"><input class="edd_vou_text_code_text_align" id="edd_vou_text_code_text_align" name="edd_vou_text_code_text_align" value="left" type="hidden"><input class="edd_vou_text_code_border" id="edd_vou_text_code_border" name="edd_vou_text_code_border" value="" type="hidden"><input class="edd_vou_text_code_column" id="edd_vou_text_code_column" name="edd_vou_text_code_column" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column venaddrblock draghandle one_half" style="display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editvenaddr" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: right;">{vendoraddress}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_venaddr_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_venaddr_bg" name="edd_vou_venaddr_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_half" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column siteurlblock draghandle one_half" style="z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editsiteurl" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: right;">{siteurl}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_siteurl_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_siteurl_bg" name="edd_vou_siteurl_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_half" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column expireblock draghandle one_half" style="z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editexpire" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: right;">Expires : {expiredate}</p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_expire_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_expire_bg" name="edd_vou_expire_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column messagebox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block; z-index: 0; left: 0px; top: 0px;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editredeem" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p style="text-align: center;">{redeem}</p></div><input value="" class="edd_vou_text_bg" id="edd_vou_msg_color" name="edd_vou_msg_color" type="hidden"><input id="edd_vou_messagebox_width" class="edd_vou_txtclass_width" name="edd_vou_text_width" value="full_width" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column locblock draghandle one_half" style="display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/2</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editloc" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><h3 style="text-align: center;">AVAILABLE AT</h3><p style="text-align: center;"><span style="font-size: 9pt;">{location}</span></p></div><input value="one_half" class="edd_vou_txtclass_width" id="edd_vou_loc_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_text_bg" id="edd_vou_loc_bg" name="edd_vou_loc_bg" value="" type="hidden"></div><div class="edd_vou_controls_editor text_column blankbox draghandle one_fourth" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); z-index: 0; left: 0px; top: 0px; display: block;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/4</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>Blank Block</p></div><input value="one_fourth" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width" type="hidden"><input class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value="" type="hidden"></div>';
	$default_template_page = array(
								'post_type' 	=> EDD_VOU_POST_TYPE,
								'post_status' 	=> 'publish',
								'post_title' 	=> __( 'Default Template', 'eddvoucher' ),
								'post_content' 	=> $default_template_content,
								'post_author' 	=> $user_ID,
								'menu_order' 	=> 1,
								'comment_status' => 'closed'
							);

	$default_template_page_id = wp_insert_post( $default_template_page );
	
	if( $default_template_page_id ) { //Check template id
		
		update_post_meta( $default_template_page_id, $prefix . 'meta_content', $default_template_meta_content );
		update_post_meta( $default_template_page_id, $prefix . 'editor_status', 'true' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_bg_style', 'color' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_bg_pattern', 'pattern1' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_bg_img', '' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_bg_color', '#eaeaea' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_view', 'land' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_margin_top', '20' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_margin_bottom', '25' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_margin_left', '25' );
		update_post_meta( $default_template_page_id, $prefix . 'pdf_margin_right', '25' );
	}

	$default_templates = array(
									'default_template' 	=> $default_template_page_id,
									'green_template'	=> $template_green_page_id,
									'blue_template' 	=> $template_blue_page_id,
									'pink_template' 	=> $template_pink_page_id,
									'muc_template' 		=> $template_muc_page_id,
								);
		
	return $default_templates;
}

/**
 * Add Custom File Name settings
 * 
 * Handle to add custom file name settings
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
function edd_filename_callback( $args ) {
	
	global $edd_options;

	$value = '';
	if ( isset( $edd_options[ $args['id'] ] ) ) {
		$value = $edd_options[ $args['id'] ];
	} 
	$filetype = isset( $args['options'] ) ? $args['options'] : '';
	
	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';
	
	if( function_exists('edd_sanitize_key') ) {
		$html = '<input type="text" class="' . sanitize_html_class ( $args['size'] ) . '-text" id="edd_settings[' . edd_sanitize_key( $args['id'] ) . ']" name="edd_settings[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/> ' . $filetype;
		$html .= '<label for="edd_settings[' . edd_sanitize_key( $args['id'] ) . ']"> '  . wp_kses_post ( $args['desc'] ) . '</label>';
	} else {
		$html = '<input type="text" class="' . $args['size'] . '-text" id="edd_settings[' . $args['id'] . ']" name="edd_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/> ' . $filetype;
		$html .= '<label for="edd_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';		
	}	

	echo $html;
}

/**
 * Get Easy Digital Downloads Screen ID
 * 
 * Handles to get edd screen id
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.4.7
 */
function edd_vou_get_edd_screen_id() {
	
	//$edd_screen_id	= sanitize_title( __( 'Downloads', 'edd' ) );
	$edd_screen_id		= 'download';
	return apply_filters( 'edd_vou_get_edd_screen_id', $edd_screen_id );
}

/**
 * Get Easy Digital Downloads Screen ID
 * 
 * Handles to get edd screen id
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.4.8
 */
function edd_vou_get_voucher_admins() {
	
	return apply_filters( 'edd_vou_get_voucher_admins', array( 'administrator' ) );
}
/**
 * Different Pdf size Array
 * 
 * Handle to get different pdf sizes
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.5.1
 */
function edd_vou_get_pdf_sizes() {

	return apply_filters( 'edd_vou_get_pdf_sizes', array(
									'A0'	=> array(
													'width'		=> 841,
													'height'	=> 1189,
													'fontsize'	=> 50
												),
									'A1'	=> array(
													'width'		=> 594,
													'height'	=> 841,
													'fontsize'	=> 35
												),
									'A2'	=> array(
													'width'		=> 420,
													'height'	=> 594,
													'fontsize'	=> 25
												),
									'A3'	=> array(
													'width'		=> 297,
													'height'	=> 420,
													'fontsize'	=> 17
												),
									'A4'	=> array(
													'width'		=> 210,
													'height'	=> 297,
													'fontsize'	=> 12
												),
									'A5'	=> array(
													'width'		=> 148,
													'height'	=> 210,
													'fontsize'	=> 10
												),
									'A6'	=> array(
													'width'		=> 105,
													'height'	=> 148,
													'fontsize'	=> 9
												),
									'A7'	=> array(
													'width'		=> 74,
													'height'	=> 105,
													'fontsize'	=> 8
												),
									'A8'	=> array(
													'width'		=> 52,
													'height'	=> 74,
													'fontsize'	=> 7
												)
								)
				);
}

/**
 * Different Pdf size Array for select box
 * 
 * Handle to get Different Pdf size Array for select box
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.5.1
 */
function edd_vou_get_pdf_sizes_select() {

	$sizes	= edd_vou_get_pdf_sizes();
	$size_select_data	= array();

	if( !empty( $sizes ) ) {//if size is not empty

		foreach ( $sizes as $size => $values ) {

			$size_select_data[$size]	= $size;
		}
	}

	return apply_filters( 'edd_vou_get_pdf_sizes_select', $size_select_data );
}