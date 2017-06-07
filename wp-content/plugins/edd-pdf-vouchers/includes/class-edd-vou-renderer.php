<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Renderer Class
 *
 * To handles some small HTML content for front end and backend
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
class EDD_Vou_Renderer {
	
	var $mainmodel, $model;
	
	public function __construct() {
		
		global $edd_vou_model;
		
		$this->model = $edd_vou_model;
		
	}
	
	/**
	 * Add Popup For Used 
	 * 
	 * Handels to show used voucher codes popup
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_purchased_codes_popup( $postid ) {
		
		ob_start();
		include_once( EDD_VOU_ADMIN . '/forms/edd-vou-purchased-codes-popup.php' ); // Including purchased voucher code file
		$html = ob_get_clean();
		
		return $html;
		
	}
	
	/**
	 * Add Popup For Used Codes
	 * 
	 * Handels to show used voucher codes popup
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_used_codes_popup( $postid ) {
		
		ob_start();
		include_once( EDD_VOU_ADMIN . '/forms/edd-vou-used-codes-popup.php' ); // Including used voucher code file
		$html = ob_get_clean();
		
		return $html;
		
	}
	
	/**
	 * Function For ajax edit of all controls
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_page_builder() {
		
		global $wp_version;
							
		$controltype = $_POST['type'];
		$bgcolor = isset( $_POST['bgcolor'] ) ? $_POST['bgcolor'] : '';
		$fontcolor = isset( $_POST['fontcolor'] ) ? $_POST['fontcolor'] : '';
		$fontsize = isset( $_POST['fontsize'] ) ? $_POST['fontsize'] : '';
		$textalign = isset( $_POST['textalign'] ) ? $_POST['textalign'] : '';
		$codetextalign = isset( $_POST['codetextalign'] ) ? $_POST['codetextalign'] : '';
		$codeborder = isset( $_POST['codeborder'] ) ? $_POST['codeborder'] : '';
		$codecolumn = isset( $_POST['codecolumn'] ) ? $_POST['codecolumn'] : '';
		$vouchercodes = isset( $_POST['vouchercodes'] ) ? $_POST['vouchercodes'] : '';
	
		$align_data = array(
								'left' 		=> __( 'Left', 'eddvoucher' ),
								'center'	=> __( 'Center', 'eddvoucher' ),
								'right' 	=> __( 'Right', 'eddvoucher' ),
							);
	
		$border_data = array( '1', '2', '3' );
		
		$column_data = array(
								'1' 	=> __( '1 Column', 'eddvoucher' ),
								'2'		=> __( '2 Column', 'eddvoucher' ),
								'3' 	=> __( '3 Column', 'eddvoucher' ),
							);
	
		if( $controltype == 'textblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
									
			/*echo '			<tr>
								<th scope="row">
									' . __( 'Title', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">
									<input type="text" name="' . $editorid . '" id="' . $editorid . '" value="" class="regular-text" />
									<br /><span class="description">' . __( 'Enter a voucher code title.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';*/
			
			echo '<tr>
								<th scope="row">
									' . __( 'Title', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';			
									$settings = array( 
															'textarea_name' => $editorid,
															'media_buttons'=> false,
															'quicktags'=> true,
															'teeny' => false,
															'editor_class' => 'content pbrtextareahtml'
														);
									wp_editor( '', $editorid, $settings );	
			echo '					<span class="description">' . sprintf( __( 'Enter a voucher code title.', 'eddvoucher' ), '<code>{codes}</code>' ) . '</span>
								</td>
							</tr>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Title Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
								
			/*echo '			<tr>
								<th scope="row">
									' . __( 'Title Font Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $fontcolor . '" id="edd_vou_edit_font_color" name="edd_vou_edit_font_color" class="edd_vou_font_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $fontcolor . '" id="edd_vou_edit_font_color" name="edd_vou_edit_font_color" class="edd_vou_edit_font_color" />
												<input type="button" class="edd_vou_font_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a font color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';*/
									
			echo '			<tr>
								<th scope="row">
									' . __( 'Title Font Size', 'eddvoucher' ) . '
								</th>
								<td>
									<input type="number" value="' . $fontsize . '" id="edd_vou_edit_font_size" name="edd_vou_edit_font_size" class="edd_vou_font_size_box small-text" maxlength="2" />
									' . __( 'pt', 'eddvoucher' ) . '<br /><span class="description">' . __( 'Enter a font size for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Title Alignment', 'eddvoucher' ) . '
								</th>
								<td>
									<select id="edd_vou_edit_text_align" name="edd_vou_edit_text_align" class="edd_vou_text_align_box">';
									foreach ( $align_data as $align_key => $align_value ) {
										echo '<option value="' . $align_key . '" ' . selected( $textalign, $align_key, false ) . '>' . $align_value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . __( 'Select text align for the voucher code title.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			/*echo '			<tr>
								<th scope="row">
									' . __( 'Voucher Code', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">
									<code>{code}</code>
									<br /><span class="description">' . __( 'Above code is replaced with their voucher code(s).', 'eddvoucher' ) . '</span>
								</td>
							</tr>';*/
					
			echo '<tr>
								<th scope="row">
									' . __( 'Voucher Code', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';			
									$settings = array( 
															'textarea_name' => $editorid . 'codes',
															'media_buttons'=> false,
															'quicktags'=> true,
															'teeny' => false,
															'editor_class' => 'content pbrtextareahtml'
														);
									wp_editor( '', $editorid . 'codes', $settings );	
			echo '					<span class="description">' . __( 'Enter your voucher codes content. The available tags are:' , 'eddvoucher').' <br /> <code>{codes}</code> - '.__( 'displays the voucher code(s)', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Voucher Code Border', 'eddvoucher' ) . '
								</th>
								<td>
									<select id="edd_vou_edit_code_border" name="edd_vou_edit_code_border" class="edd_vou_code_border_box">
										<option value="">' . __( 'Select', 'eddvoucher' ) . '</option>';
									foreach ( $border_data as $border ) {
										echo '<option value="' . $border . '" ' . selected( $codeborder, $border, false ) . '>' . $border . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . __( 'Select border for the voucher code.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
								
			echo '			<tr>
								<th scope="row">
									' . __( 'Voucher Code Alignment', 'eddvoucher' ) . '
								</th>
								<td>
									<select id="edd_vou_edit_code_text_align" name="edd_vou_edit_code_text_align" class="edd_vou_code_text_align_box">';
									foreach ( $align_data as $align_key => $align_value ) {
										echo '<option value="' . $align_key . '" ' . selected( $codetextalign, $align_key, false ) . '>' . $align_value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . __( 'Select text align for the voucher code.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
								
			/*echo '			<tr>
								<th scope="row">
									' . __( 'Voucher Code Column', 'eddvoucher' ) . '
								</th>
								<td>
									<select id="edd_vou_edit_code_column" name="edd_vou_edit_code_column" class="edd_vou_code_column_box">';
									foreach ( $column_data as $column_key => $column_value ) {
										echo '<option value="' . $column_key . '" ' . selected( $codecolumn, $column_key, false ) . '>' . $column_value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . __( 'Select column for the voucher code.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';*/
								
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if($controltype == 'message') {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Content', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';			
									$settings = array( 
															'textarea_name' => $editorid,
															'media_buttons'=> false,
															'quicktags'=> true,
															'teeny' => false,
															'editor_class' => 'content pbrtextareahtml'
														);
									wp_editor( '', $editorid, $settings );	
			echo '					<span class="description">' . __( 'Enter your content. The available tags are:' , 'eddvoucher' ). ' <br /><code>{redeem}</code> -'. __( 'displays the voucher redeem instruction', 'eddvoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
				
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'expireblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Content', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . __( 'Enter your content. The available tags are:' , 'eddvoucher').' <br /><code>{expiredate}</code> - '.__( 'displays the voucher expire date', 'eddvoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'venaddrblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Content', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . __( 'Enter your content. The available tags are:' , 'eddvoucher').' <br /> <code>{vendoraddress}</code> - '. __( 'displays the vendor\' address', 'eddvoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'siteurlblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Content', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . __( 'Enter your content. The available tags are:', 'eddvoucher').' <br /><code>{siteurl}</code> - '.__( 'displays the website url', 'eddvoucher' ). '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'locblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Content', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . __( 'Enter your content. The available tags are:' , 'eddvoucher').' <br /><code>{location}</code> - '.__( 'displays the voucher location', 'eddvoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'customblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . __( 'Background Color', 'eddvoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div style="position:relative;">
												<input type="text" value="' . $bgcolor . '" id="edd_vou_edit_bg_color" name="edd_vou_edit_bg_color" class="edd_vou_edit_bg_color" />
												<input type="button" class="edd_vou_color_box button-secondary" value="'.__('Select Color','eddvoucher').'">
												<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . __( 'Select a background color for the text box.', 'eddvoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . __( 'Content', 'eddvoucher' ) . '
								</th>
								<td class="edd_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . __( 'Enter your custom content. The available tags are:' , 'eddvoucher')
										 .'<br /><code>{redeem}</code> - '. __( 'displays the voucher redeem instruction' , 'eddvoucher')
										 .'<br /><code>{sitelogo}</code> - '.__( 'displays the voucher site logo' , 'eddvoucher')
										 .'<br /><code>{vendorlogo}</code> - '.__( 'displays the vendor logo' , 'eddvoucher')
										 .'<br /><code>{expiredate}</code> - '.__( 'displays the voucher expire date' , 'eddvoucher')
										 .'<br /><code>{vendoraddress}</code> - '.__( 'displays the vendor address' , 'eddvoucher')
										 .'<br /><code>{siteurl}</code> - '.__( 'displays the site url' , 'eddvoucher')
										 .'<br /><code>{location}</code> - '.__( 'displays the location(s)', 'eddvoucher' )
										 .'<br /><code>{buyername}</code> - '.__( 'displays the buyer name', 'eddvoucher' )
										 .'<br /><code>{buyeremail}</code> - '.__( 'displays the buyer email', 'eddvoucher' )
										 .'<br /><code>{orderid}</code> - '.__( 'displays the order id', 'eddvoucher' )
										 .'<br /><code>{orderdate}</code> - '.__( 'displays the order date', 'eddvoucher' )
										 .'<br /><code>{productname}</code> - '.__( 'displays the product name', 'eddvoucher' )
										 .'<br /><code>{productprice}</code> - '.__( 'displays the product price', 'eddvoucher' ) 
										 .'<br /><code>{codes}</code> - '.__( 'displays the voucher code(s)', 'eddvoucher' )
										 .'<br /><code>{recipientname}</code> - '.__( 'displays the recipient name for voucher code(s)', 'eddvoucher' )
										 .'<br /><code>{recipientemail}</code> - '.__( 'displays the recipient email for voucher code(s)', 'eddvoucher' )
										 .'<br /><code>{recipientmessage}</code> - '.__( 'displays the recipient message for voucher code(s)', 'eddvoucher' )
										 .'<br /><code>{payment_method}</code> - ' . __( 'displays the payment method of the order', 'eddvoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		}
		echo $html;
		exit;
	}
}
?>