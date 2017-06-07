<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Post Type Functions
 *
 * Handles all custom post types
 * functions
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0 
 */

/**
 * Register Post Type
 *
 * Handles to registers the Voucher 
 * post type
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0 
 */
function edd_vou_register_post_types() {
	
	//register easy digital downloads vouchers post type
	$voulabels = array(
					'name'					=> __( 'Voucher Templates', 'eddvoucher' ),
					'singular_name'			=> __( 'Voucher Template', 'eddvoucher' ),
					'add_new'				=> _x( 'Add New', EDD_VOU_POST_TYPE, 'eddvoucher' ),
					'add_new_item'			=> sprintf( __( 'Add New %s' , 'eddvoucher' ), __( 'Voucher Template' , 'eddvoucher' ) ),
					'edit_item'				=> sprintf( __( 'Edit %s' , 'eddvoucher' ), __( 'Voucher Template' , 'eddvoucher' ) ),
					'new_item'				=> sprintf( __( 'New %s' , 'eddvoucher' ), __( 'Voucher Template' , 'eddvoucher' ) ),
					'all_items'				=> sprintf( __( '%s' , 'eddvoucher' ), __( 'Voucher Templates' , 'eddvoucher' ) ),
					'view_item'				=> sprintf( __( 'View %s' , 'eddvoucher' ), __( 'Voucher Template' , 'eddvoucher' ) ),
					'search_items'			=> sprintf( __( 'Search %a' , 'eddvoucher' ), __( 'Voucher Templates' , 'eddvoucher' ) ),
					'not_found'				=> sprintf( __( 'No %s Found' , 'eddvoucher' ), __( 'Voucher Templates' , 'eddvoucher' ) ),
					'not_found_in_trash'	=> sprintf( __( 'No %s Found In Trash' , 'eddvoucher' ), __( 'Voucher Templates' , 'eddvoucher' ) ),
					'parent_item_colon'		=> '',
					'menu_name' 			=> __( 'Voucher Templates' , 'eddvoucher' )
				);

	$vouargs = array(
				'labels'				=> $voulabels,
				'public' 				=> false,
			    'exclude_from_search'	=> true,
			    'show_ui' 				=> true, 
			    'show_in_menu' 			=> 'edit.php?post_type='.EDD_VOU_MAIN_POST_TYPE,
			    'query_var' 			=> false,
			    'rewrite' 				=> true,
			    'capability_type' 		=> 'post',
			    'hierarchical' 			=> false,
			    'supports' 				=> array( 'title', 'editor' )
		  	);
	register_post_type( EDD_VOU_POST_TYPE, $vouargs );
	
	//register Edd voucher codes post type
	$voucodelabels = array(
					'name'					=> __( 'Voucher Codes', 'eddvoucher' ),
					'singular_name'			=> __( 'Voucher Code', 'eddvoucher' ),
					'add_new'				=> _x( 'Add New', EDD_VOU_CODE_POST_TYPE, 'eddvoucher' ),
					'add_new_item'			=> sprintf( __( 'Add New %s' , 'eddvoucher' ), __( 'Voucher Code' , 'eddvoucher' ) ),
					'edit_item'				=> sprintf( __( 'Edit %s' , 'eddvoucher' ), __( 'Voucher Code' , 'eddvoucher' ) ),
					'new_item'				=> sprintf( __( 'New %s' , 'eddvoucher' ), __( 'Voucher Code' , 'eddvoucher' ) ),
					'all_items'				=> sprintf( __( '%s' , 'eddvoucher' ), __( 'Voucher Codes' , 'eddvoucher' ) ),
					'view_item'				=> sprintf( __( 'View %s' , 'eddvoucher' ), __( 'Voucher Code' , 'eddvoucher' ) ),
					'search_items'			=> sprintf( __( 'Search %a' , 'eddvoucher' ), __( 'Voucher Codes' , 'eddvoucher' ) ),
					'not_found'				=> sprintf( __( 'No %s Found' , 'eddvoucher' ), __( 'Voucher Codes' , 'eddvoucher' ) ),
					'not_found_in_trash'	=> sprintf( __( 'No %s Found In Trash' , 'eddvoucher' ), __( 'Voucher Codes' , 'eddvoucher' ) ),
					'parent_item_colon'		=> '',
					'menu_name' 			=> __( 'Voucher Codes' , 'eddvoucher' )
				);

	$voucodeargs = array(
				'labels'				=> $voucodelabels,
				'public' 				=> false,
			    'exclude_from_search'	=> true,
			    'query_var' 			=> false,
			    'rewrite' 				=> false,
			    'capability_type' 		=> EDD_VOU_CODE_POST_TYPE,
			    'hierarchical' 			=> false,
			    'supports' 				=> array( 'title' ),
			);
	register_post_type( EDD_VOU_CODE_POST_TYPE, $voucodeargs );
}
//register custom post type

add_action( 'init', 'edd_vou_register_post_types', 1 ); 
?>