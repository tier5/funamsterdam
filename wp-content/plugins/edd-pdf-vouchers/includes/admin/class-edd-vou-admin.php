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
class EDD_Vou_Admin{
	
	var $scripts,$model,$render;
	
	public function __construct(){
		
		global $edd_vou_scripts,$edd_vou_model,
				$edd_vou_render;
		
		$this->scripts = $edd_vou_scripts;
		$this->model = $edd_vou_model;
		$this->render = $edd_vou_render;
	}
	
	/**
	 * Adding Submenu Page
	 * 
	 * Handles to adding submenu page for 
	 * voucher extension
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_admin_submenu() {
		
		global $current_user;
		
		$main_menu_slug = EDD_VOU_MAIN_MENU_NAME;
		
		if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
			
			$main_menu_slug = 'edd-vou-codes';
			
			//add Downloads Page
			add_menu_page( __( 'Voucher Codes', 'eddvoucher' ),__( 'Downloads', 'eddvoucher' ), EDD_VOU_VENDOR_LEVEL, 'edd-vou-codes', array( $this, 'edd_vou_codes_page' ) );
		} else { //voucher codes page
			$voucher_page = add_submenu_page( $main_menu_slug , __( 'Voucher Codes', 'eddvoucher'), __( 'Voucher Codes', 'eddvoucher' ), EDD_VOU_VENDOR_LEVEL, 'edd-vou-codes', array($this,'edd_vou_codes_page')); 
		}
	}
	
	/**
	 * Add Voucher Codes Page
	 * 
	 * Handles to add voucher codes page
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_codes_page() {
		include_once( EDD_VOU_ADMIN . '/forms/edd-vou-codes-page.php' );
	}
	
	/**
	 * Import Codes From CSV
	 * 
	 * Handle to import voucher codes from CSV Files
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_import_codes() {
		
		//import csv file code for voucher code importing to textarea
		if( ( isset( $_FILES['edd_vou_csv_file']['tmp_name'] ) && !empty( $_FILES['edd_vou_csv_file']['tmp_name'] ) ) ) {
			
			$filename = $_FILES['edd_vou_csv_file']['tmp_name'];
			$deletecode = isset( $_POST['edd_vou_delete_code'] ) && !empty( $_POST['edd_vou_delete_code'] ) ? $_POST['edd_vou_delete_code'] : '';
			$existingcode = isset( $_POST['edd_vou_existing_code'] ) && !empty( $_POST['edd_vou_existing_code'] ) ? $_POST['edd_vou_existing_code'] : '';
			$csvseprator = isset( $_POST['edd_vou_csv_sep'] ) && !empty( $_POST['edd_vou_csv_sep'] ) ? $_POST['edd_vou_csv_sep'] : ',';
			$csvenclosure = isset( $_POST['edd_vou_csv_enc'] ) ? $_POST['edd_vou_csv_enc'] : '';
			
			$importcodes = '';
			
			$importcodes = '';
			$pattern_data = array();
			
			if( !empty($existingcode) && $deletecode != 'y' ) { // check existing code and existing code not remove
				$pattern_data = explode( ',', $existingcode );
				$pattern_data = array_map( 'trim', $pattern_data );
			}
				
			if ( !empty( $filename ) && ( $handle = fopen( $filename, "r") ) !== FALSE) {
				
				if( !empty($csvenclosure) ) {
				
					while (($data = fgetcsv($handle, 1000, $csvseprator, $csvenclosure)) !== FALSE) { // check all row of csv
					
						foreach ( $data as $key => $value ) { // check all column of particular row
							
							if( !empty($value) && !in_array( $value, $pattern_data) ) { // cell value is not empty and avoid duplicate code
								
								$pattern_data[] = str_replace( ',', '', $value);
							}
					    }
					}
				} else {
				
					while (($data = fgetcsv($handle, 1000, $csvseprator)) !== FALSE) { // check all row of csv
					
						foreach ( $data as $key => $value ) { // check all column of particular row
							
							if( !empty($value) && !in_array( $value, $pattern_data) ) { // cell value is not empty and avoid duplicate code
								
								$pattern_data[] = str_replace( ',', '', $value);
							}
					    }
					}
				}
				
			    fclose($handle);
			    unset($_FILES['edd_vou_csv_file']);
			}
			
		    $import_code = implode( ', ', $pattern_data ); // all pattern codes
		
			echo $import_code;
			exit;
		}
	}
		
	/**
	 * Import Random Code using AJAX
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_import_code() {
		
		$noofvoucher 	= !empty($_POST['noofvoucher']) ? $_POST['noofvoucher'] : 0;
		$codeprefix 	= !empty($_POST['codeprefix']) ? $_POST['codeprefix'] : '';
		$codeseperator 	= !empty($_POST['codeseperator']) ? $_POST['codeseperator'] : '';
		$pattern 		= !empty($_POST['codepattern']) ? $_POST['codepattern'] : '';
		$existingcode	= !empty($_POST['existingcode']) ? $_POST['existingcode'] : '';
		$deletecode		= !empty($_POST['deletecode']) ? $_POST['deletecode'] : '';
		
		$pattern_prefix = $codeprefix . $codeseperator; // merge prefix with seperator
		
		$pattern_data = array();
		if( !empty($existingcode) && $deletecode != 'y' ) { // check existing code and existing code not remove
			$pattern_data = explode( ',', $existingcode );
			$pattern_data = array_map( 'trim', $pattern_data );
		}
		
		for ( $j = 0; $j < $noofvoucher; $j++ ) { // no of codes are generate
			
			$pattern_string = $pattern_prefix . $this->model->edd_vou_get_pattern_string( $pattern );
			
			while ( in_array( $pattern_string, $pattern_data) ) { // avoid duplicate pattern code
				$pattern_string = $pattern_prefix . $this->model->edd_vou_get_pattern_string( $pattern );
			}
			
			$pattern_data[] = str_replace( ',', '', $pattern_string);
		}
		$import_code = implode( ', ', $pattern_data ); // all pattern codes
		
		echo $import_code;
		exit;
		
	}
	
	/**
	 * Add Popup For import Voucher Code 
	 * 
	 * Handels to show import voucher code popup
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_import_footer() {
		
		global $post;
		
		//Check download post type page 
		if( isset( $post->post_type ) && $post->post_type == EDD_VOU_MAIN_POST_TYPE ) {
			
			include_once( EDD_VOU_ADMIN . '/forms/edd-vou-import-code-popup.php' );
		}
	}
		
	/**
	 * Add Custom meta boxs  for voucher templates post tpye
	 * 
	 * Handles to add custom meta boxs in voucher templates
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_editor_meta_box() {

		global $wp_meta_boxes;
		
		// add metabox for edtior
		add_meta_box( 'edd_vou_page_voucher' ,__( 'Voucher', 'eddvoucher' ), array( $this, 'edd_vou_editor_control' ), EDD_VOU_POST_TYPE, 'normal', 'high', 1 );
		
		// add metabox for style options 
		add_meta_box( 'edd_vou_pdf_options' ,__( 'Voucher Options', 'eddvoucher' ), array( $this, 'edd_vou_pdf_options_page' ), EDD_VOU_POST_TYPE, 'normal', 'high' );
		
	}
	
	/**
	 * Add Custom Editor
	 * 
	 * Handles to add custom editor
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_editor_control() {
		
		include( EDD_VOU_ADMIN . '/forms/edd-vou-editor.php');
	}
	
	/**
	 * Add Style Options
	 * 
	 * Handles to add Style Options
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_pdf_options_page() {
		
		include( EDD_VOU_ADMIN . '/forms/edd-vou-meta-options.php');
	}
	
	/**
	 * Save Voucher Meta Content
	 * 
	 * Handles to saving voucher meta on update voucher template post type
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_save_metadata( $post_id ) {
	
		global $post_type;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$post_type_object = get_post_type_object( $post_type );
		
		// Check for which post type we need to add the meta box
		$pages = array( EDD_VOU_POST_TYPE );

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                // Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )        // Check Revision
		|| ( ! in_array( $post_type, $pages ) )              // Check if current post type is supported.
		|| ( ! check_admin_referer( EDD_VOU_PLUGIN_BASENAME, 'at_edd_vou_meta_box_nonce') )      // Check nonce - Security
		|| ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) )       // Check permission
		{
		  return $post_id;
		}
		
		$metacontent = isset( $_POST['edd_vou_meta_content'] ) ? $_POST['edd_vou_meta_content'] : '';
		$metacontent = trim( $metacontent );
		update_post_meta( $post_id, $prefix . 'meta_content', $metacontent ); // updating the content of page builder editor
		
		//Update Editor Status
		if( isset( $_POST[ $prefix . 'editor_status' ] ) ) {			
			
			update_post_meta( $post_id, $prefix . 'editor_status', $_POST[ $prefix . 'editor_status' ] );
		}
		
		//Update Background Style
		if( isset( $_POST[ $prefix . 'pdf_bg_style' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_bg_style', $_POST[ $prefix . 'pdf_bg_style' ] );
		}
		//Update Background Pattern
		if( isset( $_POST[ $prefix . 'pdf_bg_pattern' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_bg_pattern', $_POST[ $prefix . 'pdf_bg_pattern' ] );
		}
		//Update Background Image
		if( isset( $_POST[ $prefix . 'pdf_bg_img' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_bg_img', $_POST[ $prefix . 'pdf_bg_img' ] );
		}
		//Update Background Color
		if( isset( $_POST[ $prefix . 'pdf_bg_color' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_bg_color', $_POST[ $prefix . 'pdf_bg_color' ] );
		}
		//Update PDF View
		if( isset( $_POST[ $prefix . 'pdf_view' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_view', $_POST[ $prefix . 'pdf_view' ] );
		}
		
		//Update PDF Size
		if( isset( $_POST[ $prefix . 'pdf_size' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_size', $_POST[ $prefix . 'pdf_size' ] );
			
		}
		
		//Update Margin Top
		if( isset( $_POST[ $prefix . 'pdf_margin_top' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_margin_top', $_POST[ $prefix . 'pdf_margin_top' ] );
		}
		//Update Margin Bottom
		if( isset( $_POST[ $prefix . 'pdf_margin_bottom' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_margin_bottom', $_POST[ $prefix . 'pdf_margin_bottom' ] );
		}
		//Update Margin Left
		if( isset( $_POST[ $prefix . 'pdf_margin_left' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_margin_left', $_POST[ $prefix . 'pdf_margin_left' ] );
		}
		//Update Margin Right
		if( isset( $_POST[ $prefix . 'pdf_margin_right' ] ) ) {
			
			update_post_meta( $post_id, $prefix . 'pdf_margin_right', $_POST[ $prefix . 'pdf_margin_right' ] );
		}
	}
	
	/**
	 * Custom column
	 *
	 * Handles the custom columns to voucher listing page
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_manage_custom_column( $column_name, $post_id ) {
		
		global $wpdb,$post;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		switch ($column_name) {
				
			case 'voucher_preview' :
										$preview_url = $this->edd_vou_get_preview_link( $post_id );
										echo '<a href="' . $preview_url . '" class="edd-vou-pdf-preview">' . __( 'View Preview', 'eddvoucher' ) . '</a>';
										break;
								
		}
	}
	
	/**
	 * Add New Column to voucher listing page
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_add_new_columns($new_columns) {
 		
 		unset($new_columns['date']);
 		
 		$new_columns['voucher_preview'] = __( 'View Preview', 'eddvoucher' );
		$new_columns['date']			= _x( 'Date', 'column name', 'eddvoucher' );
		
		return $new_columns;
	}
	
	/**
	 * Get Preview Link
	 *
	 * Handles to get preview link
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_get_preview_link( $postid ) {
		
		$preview_url = add_query_arg( array( 'post_type' => EDD_VOU_POST_TYPE, 'edd_vou_pdf_action' => 'preview', 'voucher_id' => $postid ), admin_url( 'edit.php' ) );
		
		return $preview_url;
	}
	
	/**
	 * Add New Action For Create Duplicate
	 *
	 * Handles to add new action for 
	 * Create Duplicate link of that voucher
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_dupd_action_new_link_add( $actions, $post ) {
		
		//check current user can have administrator rights
		//post type must have vouchers post type
		if ( ! current_user_can( 'manage_options' ) || $post->post_type != EDD_VOU_POST_TYPE ) 
			return $actions;
			
		// add new action for create duplicate
		$args = array( 'action'	=>	'edd_vou_duplicate_vou', 'edd_vou_dupd_vou_id' => $post->ID );
		$dupdurl = add_query_arg( $args, admin_url( 'edit.php' ) );
		$actions['edd_vou_duplicate_vou'] = '<a href="' . wp_nonce_url( $dupdurl, 'duplicate-vou_' . $post->ID ) . '" title="' . __( 'Make a duplicate from this voucher', 'eddvoucher' )
										. '" rel="permalink">' .  __( 'Duplicate', 'eddvoucher' ) . '</a>';
		
		// return all actions
		return $actions ;
		
	}
	
	/**
	 * Add Preview Button
	 *
	 * Handles to add preview button within
	 * Publish meta box
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_add_preview_button() {
		
		global $typenow, $post;
		
		if ( ! current_user_can( 'manage_options' )
			|| ! is_object( $post )
			|| $post->post_type != EDD_VOU_POST_TYPE ) {
				return;
		}
		
		if ( isset( $_GET['post'] ) ) {
			
			$args = array( 'action'	=>	'edd_vou_duplicate_vou', 'edd_vou_dupd_vou_id' => absint( $_GET['post'] ) );
			$dupdurl = add_query_arg( $args, admin_url( 'edit.php' ) );
			$notifyUrl = wp_nonce_url( $dupdurl, 'duplicate-vou_' . $_GET['post'] );
			?>
			<div id="duplicate-action"><a class="submitduplicate duplication" href="<?php echo esc_url( $notifyUrl ); ?>"><?php _e( 'Copy to a new draft', 'eddvoucher' ); ?></a></div>
			<?php
		}
		
		$preview_url = $this->edd_vou_get_preview_link( $post->ID );
		echo '<a href="' . $preview_url . '" class="button button-secondary button-large edd-vou-pdf-preview-button" >' . __( 'Preview', 'eddvoucher' ) . '</a>';
	}
	
	/**
	 * Duplicate Voucher
	 * 
	 * Handles to creating duplicate voucher
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function edd_vou_duplicate_process() {
		
		//check the duplicate create action is set or not and order id is not empty
		if( isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'edd_vou_duplicate_vou'
			&& isset( $_GET['edd_vou_dupd_vou_id'] ) && !empty($_GET['edd_vou_dupd_vou_id'])) {
			
			// get the vou id
			$vou_id = $_GET['edd_vou_dupd_vou_id'];
			
			//check admin referer	
			check_admin_referer( 'duplicate-vou_' . $vou_id );
			
			// create duplicate voucher
			$this->model->edd_vou_dupd_create_duplicate_vou( $vou_id );
		}
	}

	/**
	 * Vouchers Lists display based on menu order with ascending order
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_edit_posts_orderby( $orderby_statement ) {
		
		global $wpdb;
		
		 //Check post type is eddvouchers & sorting not applied by user
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == EDD_VOU_POST_TYPE && !isset( $_GET['orderby'] ) ) {
			
			$orderby_statement =  "{$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_date DESC";
		}
		return $orderby_statement;
	}
	
	/**
	 * Check Voucher Code
	 * 
	 * Handles to check voucher code
	 * is valid or invalid via ajax
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_check_voucher_code() {
		
		global $current_user;
		
		$prefix = EDD_VOU_META_PREFIX;
			
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
																)
													)
							);
			
			if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
				$args['author'] = $current_user->ID;
			}
			
			$voucodedata = $this->model->edd_vou_get_voucher_details( $args );
			
			// Check voucher code ids are not empty
			if( !empty( $voucodedata ) && is_array( $voucodedata ) ) {
				
				$args = array(
									'fields' 	=> 'ids',
									'post__in' 	=> $voucodedata,
									'meta_query'=> array(
															array(
																		'key' 		=> $prefix . 'used_codes',
																		'value' 	=> $voucode
																	)
														)
								);
								
				if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
					$args['author'] = $current_user->ID;
				}
				
				$voucodedata = $this->model->edd_vou_get_voucher_details( $args );
				
				//Check voucher code id is used  
				if( !empty( $voucodedata ) ) {
					
					$voucodeid = isset( $voucodedata[0] ) ? $voucodedata[0] : '';
					
					// get used code date
					$used_code_date = get_post_meta( $voucodeid, $prefix.'used_code_date', true );
					
					echo sprintf( __( 'Voucher code is invalid, was used on %s', 'eddvoucher' ), $this->model->edd_vou_get_date_format( $used_code_date, true ) );
					
				} else {
					
					echo 'success';
				}
				
			} else {
				echo 'error';
			}
			exit;
		}
	}

	/**
	 * Display Voucher Data within order meta
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_display_voucher_data( $payment_id ) {
		
		include( EDD_VOU_ADMIN . '/forms/edd-vou-meta-history.php');
	}
	
	/**
	 * Delete payment meta and all payment detail whene order delete.
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.2
	 */
	public function edd_vou_payment_delete( $payment_id = '' ){
		
		$prefix		= EDD_VOU_META_PREFIX;		
		
		if( !empty( $payment_id ) ) { // check if payment id is not empty
			
			$args = array(
						'post_type'		=> EDD_VOU_CODE_POST_TYPE,
						'post_status'	=> 'any',
						'meta_query' 	=> array(
												array(
													'key' 	=> $prefix.'order_id',
													'value' => $payment_id
												)
							)
			 );

			// get posts from payment id
			$posts = get_posts($args);
			
			if( !empty( $posts ) ){ // check if get any post
				
				foreach ( $posts as $post ){
					
					wp_delete_post( $post->ID, true );
				}
			}
		}
	}
	
	
	/**
	 * Download Pdf by admin
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 */
	public function edd_vou_admin_voucher_pdf_download() {
		
		global $current_user;
		
		if ( !empty( $_GET['eddfile'] ) && !empty( $_GET['ttl'] ) && !empty( $_GET['token'] ) && !empty($_GET['edd_vou_admin']) ) {
			
			$order_parts = explode( ':', rawurldecode( $_GET['eddfile'] ) );
			
			$_GET['expire']			= $_GET['ttl'];
			$_GET['download']		= $order_parts[1];
			$_GET['download_id']	= $order_parts[1];
			$_GET['payment']		= $order_parts[0];
			$_GET['file_key']		= $order_parts[2];
			$_GET['price_id']		= $order_parts[3];
			$_GET['email']			= get_post_meta( $order_parts[0], '_edd_payment_user_email', true );
			$_GET['key']			= get_post_meta( $order_parts[0], '_edd_payment_purchase_key', true );
			$_GET['download_key']	= get_post_meta( $order_parts[0], '_edd_payment_purchase_key', true );
			$_GET['has_access']		= true;
		}
		
		if( !empty( $_GET['download_id'] ) && !empty( $_GET['file'] ) 
			&& !empty( $_GET['edd_vou_admin'] ) && !empty( $_GET['edd_vou_payment_id'] ) ) {
			
				if ( current_user_can( 'manage_options' ) ) {
					
					$download_id	= (int) $_GET['download_id'];
					$email			= sanitize_email( str_replace( ' ', '+', $_GET['email'] ) );
					$download_file	= isset( $_GET['file'] ) ? preg_replace( '/\s+/', ' ', $_GET['file'] ) : '';
					$payment_id		= $_GET['edd_vou_payment_id'];
					$price_id		= $_GET['price_id'];
					
					//Generate PDF
					$this->model->edd_vou_generate_pdf_voucher( $email, $download_id, $download_file, $payment_id, $price_id );
					
				} else {
					
					wp_die( '<p>'.__( 'You are not allowed to access this URL.', 'eddvoucher' ).'</p>' );
				}
				
				exit;
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		if(edd_vou_is_edit_page()) {
				
			//add content for import voucher codes in footer
			add_action( 'admin_footer', array($this, 'edd_vou_import_footer') );
		}
		
		//add action to import csv file for codes with Ajaxform
		add_action ( 'init',  array( $this, 'edd_vou_import_codes' ) );
		
		//add submenu page
		add_action( 'admin_menu', array( $this, 'edd_vou_admin_submenu' ) );
		
		//AJAX action for import code
		add_action( 'wp_ajax_edd_vou_import_code', array( $this, 'edd_vou_import_code') );
		add_action( 'wp_ajax_nopriv_edd_vou_import_code', array( $this, 'edd_vou_import_code') );
		
		//add new field to voucher listing page
		add_action( 'manage_'.EDD_VOU_POST_TYPE.'_posts_custom_column', array( $this, 'edd_vou_manage_custom_column' ), 10, 2 );
		add_filter( 'manage_edit-'.EDD_VOU_POST_TYPE.'_columns', array( $this, 'edd_vou_add_new_columns' ) );
		
		//add action to add custom metaboxes on voucher template post type
		add_action( 'add_meta_boxes', array( $this, 'edd_vou_editor_meta_box' ) );	
		
		//saving voucher meta on update or publish voucher template post type
		add_action( 'save_post', array( $this, 'edd_vou_save_metadata' ) );
		
		//ajax call to edit all controls
		add_action( 'wp_ajax_edd_vou_page_builder', array( $this->render, 'edd_vou_page_builder') );
		add_action( 'wp_ajax_nopriv_edd_vou_page_builder', array( $this->render, 'edd_vou_page_builder' ) );
		
		//add filter to add new action "duplicate" on admin vouchers page
		add_filter( 'post_row_actions', array( $this , 'edd_vou_dupd_action_new_link_add' ), 10, 2 );
		
		//add action to add preview button after update button
		add_action( 'post_submitbox_start', array( $this, 'edd_vou_add_preview_button' ) ); 
		
		//add action to create duplicate voucher
		add_action( 'admin_init', array( $this, 'edd_vou_duplicate_process' ) );
		
		//add filter to display vouchers by menu order with ascending order
		add_filter( 'posts_orderby', array( $this, 'edd_vou_edit_posts_orderby' ) );
		
		//ajax call to edit all controls
		add_action( 'wp_ajax_edd_vou_check_voucher_code', array( $this, 'edd_vou_check_voucher_code') );
		add_action( 'wp_ajax_nopriv_edd_vou_check_voucher_code', array( $this, 'edd_vou_check_voucher_code' ) );
		
		//add action to display voucher data
		add_action( 'edd_view_order_details_files_after', array( $this, 'edd_vou_display_voucher_data' ) );
		
		//add action to delete payment meta when EDD Payment delete
		add_action( 'edd_payment_delete', array( $this, 'edd_vou_payment_delete' ) );
		
		//File download access to admin
		add_action( 'init', array( $this, 'edd_vou_admin_voucher_pdf_download' ), 9 );
	}
}