<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Model Class
 *
 * Handles generic plugin functionality.
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
class EDD_Vou_Model {

	public function __construct() {
		
	}
	
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_escape_attr($data){
		return esc_attr(stripslashes($data));
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_escape_slashes_deep( $data = array(), $flag = false, $limited = false ){
			
		if( $flag != true ) {
			
			$data = $this->edd_vou_nohtml_kses($data);
			
		} else {
			
			if( $limited == true ) {
				$data = wp_kses_post( $data );
			}
			
		}
		$data = stripslashes_deep($data);
		return $data;
	}
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_nohtml_kses($data = array()) {
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'edd_vou_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}	
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_object_to_array($result) {
		
	    $array = array();
	    foreach ($result as $key=>$value)
	    {	
	        if (is_object($value))
	        {
	            $array[$key]=$this->edd_vou_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	    }
	    return $array;
	}
	
	/**
	 * Get Date Format
	 * 
	 * Handles to return formatted date which format is set in backend
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_date_format( $date, $time = false ) {
		
		$format = $time ? get_option( 'date_format' ).' '.get_option('time_format') : get_option('date_format');
		$date = date_i18n( $format, strtotime($date));
		return apply_filters('edd_vou_get_date_format',$date);
	}
	
	/**
	 * Get all voucher details
	 *
	 * Handles to return all voucher details
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_voucher_details( $args = array() ) {
		
		//Get post using post status
		$post_status	= isset( $args['post_status'] ) ? $args['post_status'] : 'publish';
		
		$vouargs = array( 'post_type' => EDD_VOU_CODE_POST_TYPE, 'post_status' => $post_status );
		
		$vouargs = wp_parse_args( $args, $vouargs );

		//return only id
		if(isset($args['fields']) && !empty($args['fields'])) {
			$vouargs['fields'] = $args['fields'];
		}
		
		//return by search parameter
		if(isset($args['s']) && !empty($args['s'])) {
			$vouargs['s'] = $args['s'];
		}
		
		//return based on post ids
		if(isset($args['post__in']) && !empty($args['post__in'])) {
			$vouargs['post__in'] = $args['post__in'];
		}
		
		//return based on author
		if(isset($args['author']) && !empty($args['author'])) {
			$vouargs['author'] = $args['author'];
		}
		
		//return based on meta query
		if(isset($args['meta_query']) && !empty($args['meta_query'])) {
			$vouargs['meta_query'] = $args['meta_query'];
		}
		
		//show how many per page records
		if(isset($args['posts_per_page']) && !empty($args['posts_per_page'])) {
			$vouargs['posts_per_page'] = $args['posts_per_page'];
		} else {
			$vouargs['posts_per_page'] = '-1';
		}
		
		//get by post parent records
		if(isset($args['post_parent']) && !empty($args['post_parent'])) {
			$vouargs['post_parent']	=	$args['post_parent'];
		}
		
		//show per page records
		if(isset($args['paged']) && !empty($args['paged'])) {
			$vouargs['paged']	=	$args['paged'];
		}

		//get order by records
		$vouargs['order'] = 'DESC';
		$vouargs['orderby'] = 'date';

		//show how many per page records
		if(isset($args['order']) && !empty($args['order'])) {
			$vouargs['order'] = $args['order'];
		}

		//show how many per page records
		if(isset($args['orderby']) && !empty($args['orderby'])) {
			$vouargs['orderby'] = $args['orderby'];
		}

		//fire query in to table for retriving data
		$result = new WP_Query( $vouargs );
		
		if(isset($args['getcount']) && $args['getcount'] == '1') {
			$postslist = $result->post_count;	
		}  else {
			//retrived data is in object format so assign that data to array for listing
			$postslist = $this->edd_vou_object_to_array($result->posts);
			
			// if get list for deal sales list then return data with data and total array
			if( isset($args['edd_vou_list']) && $args['edd_vou_list'] ) {

				$data_res	= array();
					
				$data_res['data'] 	= $postslist;

				//To get total count of post using "found_posts" and for users "total_users" parameter
				$data_res['total']	= isset($result->found_posts) ? $result->found_posts : '';

				return $data_res;
			}
		}
		
		return $postslist;
	}
	
	/**
	 * Get all products by vouchers
	 *
	 * Handles to return all products by vouchers
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_download_by_voucher( $args = array() ) {
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$args['fields'] = 'id=>parent';
									
		$voucodesdata = $this->edd_vou_get_voucher_details( $args );
		
		$product_ids =array();
		foreach ( $voucodesdata as $voucodes ) {
			
			if( !in_array( $voucodes['post_parent'], $product_ids ) ) {
				
				$product_ids[] = $voucodes['post_parent'];
			}
		}
		
		if( !empty( $product_ids ) ) { // Check products ids are not empty
			
			$vouargs = array( 'post_type' => EDD_VOU_MAIN_POST_TYPE, 'post_status' => 'publish', 'post__in' => $product_ids );
			
			//display based on per page
			if( isset( $args['posts_per_page'] ) && !empty( $args['posts_per_page'] ) ) {
				$vouargs['posts_per_page'] = $args['posts_per_page'];
			} else {
				$vouargs['posts_per_page'] = '-1';
			}
			
			//get order by records
			$vouargs['order'] = 'DESC';
			$vouargs['orderby'] = 'date';
			
			//fire query in to table for retriving data
			$result = new WP_Query( $vouargs );
			
			if( isset( $args['getcount'] ) && $args['getcount'] == '1' ) {
				$products = $result->post_count;	
			}  else {
				//retrived data is in object format so assign that data to array for listing
				$products = $this->edd_vou_object_to_array($result->posts);
			}
			return $products;
		} else {
			return array();
		}
	}
	
	/**
	 * Get purchased codes by product id
	 *
	 * Handles to get purchased codes by product id
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_purchased_codes_by_download_id( $download_id ) {
		
		//Check product id is empty
		if( empty( $download_id ) ) return array();
		
		global $current_user;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$args = array( 'post_parent' => $download_id, 'fields' => 'ids' );
		$args['meta_query'] = array(
										array(
													'key' 		=> $prefix . 'purchased_codes',
													'value' 	=> '',
													'compare' 	=> '!='
												),
										array(
													'key'     	=> $prefix.'used_codes',
													'compare' 	=> 'NOT EXISTS'
												)
									);
		
		if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
			$args['author'] = $current_user->ID;
		}
		
		//add filter to group by order id
		add_filter( 'posts_groupby', array( $this, 'edd_vou_groupby_order_id' ) );
		
		$voucodesdata = $this->edd_vou_get_voucher_details( $args );
		
		//remove filter to group by order id
		remove_filter( 'posts_groupby', array( $this, 'edd_vou_groupby_order_id' ) );

		$vou_code_details = array();
		if( !empty( $voucodesdata ) && is_array( $voucodesdata ) ) {
			
			foreach ( $voucodesdata as $vou_codes_id ) {
				
				// get order id
				$order_id = get_post_meta( $vou_codes_id, $prefix.'order_id', true );
				
				// get order date
				$order_date = get_post_meta( $vou_codes_id, $prefix.'order_date', true );

				//buyer's first name who has purchased voucher code
				$first_name = get_post_meta( $vou_codes_id, $prefix . 'first_name', true );
				
				//buyer's last name who has purchased voucher code
				$last_name = get_post_meta( $vou_codes_id, $prefix . 'last_name', true );
				
				//buyer's name who has purchased voucher code				
				$buyer_name =  $first_name. ' ' .$last_name;
				
				$args = array( 'post_parent' => $download_id, 'fields' => 'ids' );
				$args['meta_query'] = array(
												array(
															'key' 		=> $prefix . 'purchased_codes',
															'value' 	=> '',
															'compare' 	=> '!='
														),
												array(
															'key' 		=> $prefix . 'order_id',
															'value' 	=> $order_id
														),
												array(
															'key'     	=> $prefix.'used_codes',
															'compare' 	=> 'NOT EXISTS'
														)
											);
				$vouorderdata = $this->edd_vou_get_voucher_details( $args );
				
				$purchased_codes = array();
				if( !empty( $vouorderdata ) && is_array( $vouorderdata ) ) {
					
					foreach ( $vouorderdata as $order_vou_id ) {
						
						// get purchased codes
						$purchased_codes[] = get_post_meta( $order_vou_id, $prefix.'purchased_codes', true );
					}
				}
				
				// Check purchased codes are not empty
				if( !empty( $purchased_codes ) ) {
					
					$vou_code_details[] = array(
														'order_id'			=> $order_id,
														'order_date' 		=> $order_date,
														'first_name' 		=> $first_name,
														'last_name' 		=> $last_name,
														'buyer_name' 		=> $buyer_name,
														'vou_codes'			=> implode( ', ', $purchased_codes )
													);
				}
			}
		}
		return $vou_code_details;
	}
	
	/**
	 * Get used codes by product id
	 *
	 * Handles to get used codes by product id
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_used_codes_by_download_id( $download_id ) {
		
		//Check product id is empty
		if( empty( $download_id ) ) return array();
		
		global $current_user;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$args = array( 'post_parent' => $download_id, 'fields' => 'ids' );
		$args['meta_query'] = array(
										array(
													'key' 		=> $prefix . 'used_codes',
													'value' 	=> '',
													'compare' 	=> '!='
												)
									);
															
		if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
			$args['author'] = $current_user->ID;
		}
		
		//add filter to group by order id
		add_filter( 'posts_groupby', array( $this, 'edd_vou_groupby_order_id' ) );

		$voucodesdata = $this->edd_vou_get_voucher_details( $args );
		
		//remove filter to group by order id
		remove_filter( 'posts_groupby', array( $this, 'edd_vou_groupby_order_id' ) );

		$vou_code_details = array();
		if( !empty( $voucodesdata ) && is_array( $voucodesdata ) ) {
			
			foreach ( $voucodesdata as $vou_codes_id ) {
				
				// get order id
				$order_id = get_post_meta( $vou_codes_id, $prefix.'order_id', true );
				
				// get order date
				$order_date = get_post_meta( $vou_codes_id, $prefix.'order_date', true );

				//buyer's first name who has purchased voucher code
				$first_name = get_post_meta( $vou_codes_id, $prefix . 'first_name', true );
				
				//buyer's last name who has purchased voucher code
				$last_name = get_post_meta( $vou_codes_id, $prefix . 'last_name', true );
				
				//buyer's name who has purchased voucher code				
				$buyer_name =  $first_name. ' ' .$last_name;
				
				$args = array( 'post_parent' => $download_id, 'fields' => 'ids' );
				$args['meta_query'] = array(
												array(
															'key' 		=> $prefix . 'used_codes',
															'value' 	=> '',
															'compare' 	=> '!='
														),
												array(
															'key' 		=> $prefix . 'order_id',
															'value' 	=> $order_id
														)
											);
				$vouorderdata = $this->edd_vou_get_voucher_details( $args );
				
				$used_codes = $redeem_by = array();
				if( !empty( $vouorderdata ) && is_array( $vouorderdata ) ) {
					
					foreach ( $vouorderdata as $order_vou_id ) {
						
						// get purchased codes
						$used_codes[] = get_post_meta( $order_vou_id, $prefix.'used_codes', true );
						$redeem_by[]  = get_post_meta( $order_vou_id, $prefix.'redeem_by', true );
					}
				}
				
				// Check purchased codes are not empty
				if( !empty( $used_codes ) ) {
					
					$vou_code_details[] = array(
														'order_id'		=> $order_id,
														'order_date' 	=> $order_date,
														'first_name' 	=> $first_name,
														'last_name' 	=> $last_name,
														'buyer_name' 	=> $buyer_name,
														'vou_codes'		=> implode( ',', $used_codes ),
														'redeem_by'		=> implode( ',', $redeem_by )
													);
				}
			}
		}
		return $vou_code_details;
	}
	
	/**
	 * Group By Order ID
	 *
	 * Handles to group by order id
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_groupby_order_id( $groupby ) {
	    
		global $wpdb;
	    
	    $groupby = "{$wpdb->posts}.post_title"; // post_title is used for order id
	    
	    return $groupby;
	}
	
	/**
	 * Generate Random Letter
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_random_letter( $len = 1 ) {
	   
	    $alphachar = "abcdefghijklmnopqrstuvwxyz";
		$rand_string = substr(str_shuffle($alphachar), 0, $len);
		
	    return $rand_string;
	}
	
	/**
	 * Generate Random Number
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_random_number( $len = 1 ) {
	   
	    $alphanum = "0123456789";
		$rand_number = substr(str_shuffle($alphanum), 0, $len);
		
	    return $rand_number;
	}
	
	/**
	 * Generate Random Pattern Code
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_pattern_string( $pattern ) {
		
		$pattern_string = '';
		$pattern_length = strlen(trim($pattern, ' '));
		
		for ( $i = 0; $i < $pattern_length; $i++ ) {
			
			$pattern_code = substr($pattern, $i, 1);
			if( strtolower($pattern_code) == 'l' ) {
				$pattern_string .= $this->edd_vou_get_random_letter();
			} else if( strtolower($pattern_code) == 'd' ) {
				$pattern_string .= $this->edd_vou_get_random_number();
			}
		}
		return $pattern_string;
	}
	
	/**
	 * Get all vouchers templates
	 *
	 * Handles to return all vouchers templates
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_vouchers( $args = array() ) {
		
		$vouargs = array( 'post_type' => EDD_VOU_POST_TYPE, 'post_status' => 'publish' );
		
		//return only id
		if(isset($args['fields']) && !empty($args['fields'])) {
			$vouargs['fields'] = $args['fields'];
		}
		
		//return based on meta query
		if(isset($args['meta_query']) && !empty($args['meta_query'])) {
			$vouargs['meta_query'] = $args['meta_query'];
		}
		
		//show how many per page records
		if(isset($args['posts_per_page']) && !empty($args['posts_per_page'])) {
			$vouargs['posts_per_page'] = $args['posts_per_page'];
		} else {
			$vouargs['posts_per_page'] = '-1';
		}
		
		//show per page records
		if(isset($args['paged']) && !empty($args['paged'])) {
			$vouargs['paged']	=	$args['paged'];
		}
		
		//get order by records
		$vouargs['order'] = 'DESC';
		$vouargs['orderby'] = 'date';
		
		//fire query in to table for retriving data
		$result = new WP_Query( $vouargs );
		
		if(isset($args['getcount']) && $args['getcount'] == '1') {
			$postslist = $result->post_count;	
		}  else {
			//retrived data is in object format so assign that data to array for listing
			$postslist = $this->edd_vou_object_to_array($result->posts);
		}
		
		return $postslist;
	}
	
	
	/**
	 * Convert Color Hexa to RGB
	 *
	 * Handles to return RGB color from hexa color
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_hex_2_rgb( $hex ) {
		
		$rgb = array();
		if( !empty( $hex ) ) {
			
			$hex = str_replace("#", "", $hex);
			
			if(strlen($hex) == 3) {
				$r = hexdec(substr($hex,0,1).substr($hex,0,1));
				$g = hexdec(substr($hex,1,1).substr($hex,1,1));
				$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
				$r = hexdec(substr($hex,0,2));
				$g = hexdec(substr($hex,2,2));
				$b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);
		}
		return $rgb; // returns an array with the rgb values
	}
	
	/**
	 * Get voucher order details
	 * 
	 * Handles to return voucher order details
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_post_meta_ordered( $orderid ) {
	 
		$prefix = EDD_VOU_META_PREFIX;
		
		$data = get_post_meta( $orderid, $prefix.'order_details', true );
		return apply_filters( 'edd_vou_ordered_data', $data );
	}
	
	/**
	 * Get All voucher order details
	 * 
	 * Handles to return all voucher order details
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_get_all_ordered_data( $orderid ) {
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$data = get_post_meta( $orderid, $prefix.'meta_order_details', true );
		return apply_filters( 'edd_vou_all_ordered_data', $data );
	}
	
	/**
	 * Update Duplicate Post Metas
	 * 
	 * Handles to update all old vous meta to 
	 * duplicate meta
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_dupd_post_meta( $old_id, $new_id ) {
			
			// set prefix for meta fields 
			$prefix = EDD_VOU_META_PREFIX;
			
			// get all post meta for vou
			$meta_fields = get_post_meta( $old_id );
			
			// take array to store metakeys of old vou
			$meta_keys = array();
			
			foreach ( $meta_fields as $metakey => $matavalues ) {
				// meta keys store in a array
				$meta_keys[] = $metakey;
			}
			
			foreach ( $meta_keys as $metakey ) {
				
				// get metavalue from metakey
				$meta_value = get_post_meta( $old_id, $metakey, true );
				
				// update meta values to new duplicate vou meta
				update_post_meta( $new_id, $metakey, $meta_value );
			}
	}
	/**
	 * Create Duplicate Voucher
	 * 
	 * Handles to create duplicate voucher
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_dupd_create_duplicate_vou( $vou_id ) {
			
			// get the vou data
			$vou = get_post( $vou_id );
			
			$prefix = EDD_VOU_META_PREFIX;
			
			// start process to create a new vou
			$suffix = __( '(Copy)', 'eddvoucher');
			
			// get post table data
			$post_author   			= $vou->post_author;
			$post_date      		= current_time('mysql');
			$post_date_gmt 			= get_gmt_from_date($post_date);
			$post_type				= $vou->post_type;
			$post_parent			= $vou->post_parent;
			$post_content    		= str_replace("'", "''", $vou->post_content);
			$post_content_filtered 	= str_replace("'", "''", $vou->post_content_filtered);
			$post_excerpt    		= str_replace("'", "''", $vou->post_excerpt);
			$post_title      		= str_replace("'", "''", $vou->post_title).' '.$suffix;
			$post_name       		= str_replace("'", "''", $vou->post_name);
			$post_comment_status  	= str_replace("'", "''", $vou->comment_status);
			$post_ping_status     	= str_replace("'", "''", $vou->ping_status);
			
			// get the column keys
		    $post_data = array(
					            'post_author'			=>	$post_author,
					            'post_date'				=>	$post_date,
					            'post_date_gmt'			=>	$post_date_gmt,
					            'post_content'			=>	$post_content,
					            'post_title'			=>	$post_title,
					            'post_excerpt'			=>	$post_excerpt,
					            'post_status'			=>	'draft',
					            'post_type'				=>	EDD_VOU_POST_TYPE,
					            'post_content_filtered'	=>	$post_content_filtered,
					            'comment_status'		=>	$post_comment_status,
					            'ping_status'			=> 	$post_ping_status,
					            'post_password'			=>	$vou->post_password,
					            'to_ping'				=>	$vou->to_ping,
					            'pinged'				=>	$vou->pinged,
					            'post_modified'			=>	$post_date,
					            'post_modified_gmt'		=>	$post_date_gmt,
					            'post_parent'			=>	$post_parent,
					            'menu_order'			=>	$vou->menu_order,
					            'post_mime_type'		=>	$vou->post_mime_type
				       		);
			
			// returns the vou id if we successfully created that vou
			$post_id = wp_insert_post( $post_data );
			
			//update vous meta values
			$this->edd_vou_dupd_post_meta( $vou->ID, $post_id );
			
			// if successfully created vou than redirect to main page
			wp_redirect( add_query_arg( array( 'post_type' => EDD_VOU_POST_TYPE, 'action' => 'edit', 'post' => $post_id ), admin_url( 'post.php' ) ) );
			
			// to avoid junk
			exit;
	}
	
	/**
	 * Check Enable Voucher
	 * 
	 * Handles to check enable voucher using download id
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_check_enable_voucher( $downloadid ) {
		
		if( !empty( $downloadid ) ) { // Check download id is not empty
			
			$prefix = EDD_VOU_META_PREFIX;
			
			//enable voucher
			$enable_vou = get_post_meta( $downloadid, $prefix.'enable', true );
			
			//enable variable download
			//$is_variable = get_post_meta( $downloadid, '_variable_pricing', true );
			
			// Check enable voucher meta & product is not a variable product
			// Check Voucher codes are not empty 
			if( $enable_vou == 'on' ) { //&& empty( $is_variable )
				
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Check Voucher Inventory
	 * 
	 * Handles to check voucher inventory using download id
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_check_out_of_stock( $downloadid ) {
		
		if( !empty( $downloadid ) ) { // Check download id is not empty
			
			$prefix = EDD_VOU_META_PREFIX;
			
			//enable voucher
			$enable_vou = get_post_meta( $downloadid, $prefix.'enable', true );
			
			//voucher codes			
			$avail_total = get_post_meta( $downloadid, $prefix.'avail_total', true );
						
			//enable variable download
			//$is_variable = get_post_meta( $downloadid, '_variable_pricing', true );
			
			// Check enable voucher meta & product is not a variable product
			// Check Voucher codes are not empty 
			if( $enable_vou == 'on' && $avail_total == '0' ) { //&& empty( $is_variable )
				
				return true;
			}
		}
		return false;
	}	
	
	/**
	 * Sendt an email
	 * 
	 * Handles to send an email
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function edd_vou_send_email( $email, $subject, $message ) {
		
		global $edd_options;
		
		//Get blog name and admin email
		$blog_name 		= apply_filters( 'edd_vou_from_email_name', get_option( 'blogname' ) );
		$admin_email 	= get_option( 'admin_email' );
		
		$fromEmail  = $blog_name . ' <' . $admin_email . '>';
				
		$headers = 'From: '. $fromEmail . "\r\n";
		$headers .= "Reply-To: ". $fromEmail . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
		$site_logo	= $edd_options['vou_site_logo'];
		
		if( !empty( $site_logo ) ) {
			
			$site_logo	= '<img src="' . $site_logo . '" alt="'.__( 'Voucher Logo', 'eddvoucher' ).'" />';
		}
		
		$body	 = '<div style="background:#F6F6F6;padding:35px 0px;">
						<div style="width:100%">
							<div style="padding-top: 1px;text-align:center"><h1>' . $site_logo . '</h1></div>
							<div style="background:#FFFFFF;margin-left: 10%;width: 80%;border: 1px solid #e3e3e3;">
								<div style="font-size:18px;text-align:center"><h2>'.$subject.'</h2></div>
								<br/>
								<div style="padding: 0 50px 50px;"> '.$message.'
								</div>
							</div>
						</div>
						<div style="height:100px;"> &nbsp; </div>
					</div>';
		
		//Filter support for modify email template
		$body	= apply_filters( 'edd_vou_send_email_body', $body, $site_logo, $subject, $message );
				
		wp_mail( $email, $subject, $body, $headers );
	}
	
	/**
	 * Get the current date from timezone
	 * 
	 * Handles to get current date
	 * acording to timezone setting
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 **/
	public function edd_vou_current_date( $format = 'Y-m-d H:i:s' ) { 
		
		if( !empty($format) ) {
			
			$date_time = date( $format, current_time('timestamp') );
		} else {
			
			$date_time = date( 'Y-m-d H:i:s', current_time('timestamp') );
		}
		
		return $date_time;
	}
	
	/**
	 * Get Voucher Keys
	 * 
	 * Handles to get voucher keys
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.1.0
	 */
	public function edd_vou_get_multi_voucher_key( $order_id = '', $product_id = '', $price_id = '' ) {
		
		$voucher_keys	= array();
		$vouchers		= $this->edd_vou_get_multi_voucher( $order_id, $product_id, $price_id );
		
		if( !empty( $vouchers ) ) {
			
			$voucher_keys	= array_keys( $vouchers );
		}
		
		return $voucher_keys;
	}
	
	/**
	 * Get Vouchers
	 * 
	 * Handles to get vouchers
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.1.0
	 */
	public function edd_vou_get_multi_voucher( $order_id = '', $product_id = '', $price_id = '' ) {
		
		$vouchers		= array();
		$vou_ordered	= $this->edd_vou_get_post_meta_ordered( $order_id );
		
		// If product is variable
		if( !empty($price_id) ) {
			$product_id = $product_id . '_' . $price_id;
		}
		
		if(!empty($vou_ordered)) { 
			
			$codes			= isset( $vou_ordered[$product_id]['codes'] ) ? $vou_ordered[$product_id]['codes'] : '';
			$codes			= explode( ', ', $codes );
			
			if( !empty( $codes ) ) {
				
				$key	= 1;
				foreach ( $codes as $code ) {
					
					$vouchers['edd_vou_pdf_'.$key]	= $code;
					$key++;
				}
			}
		}
		
		return $vouchers;
	}
	
	/**
	 * Get the product name from order id
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 **/
	public function edd_vou_get_product_name( $orderid, $downloadid = false ) {
		
		// If order id is empty then return
		if( empty($orderid) ) return false;
		
		// Taking some defaults
		$result_item = '';
		
		$cart_details   = edd_get_payment_meta_cart_details( $orderid, true );
		
		if( !empty( $cart_details ) ) {// check if cart detail not empty
			
			foreach ( $cart_details as $item_key => $item_val ) {
				
				if( !empty( $item_val['id'] ) && $item_val['id'] == $downloadid ) { 
					
					$result_item = isset($item_val['name']) ? $item_val['name'] : '';
				}
			}
			
		} // End of if
		
		return $result_item;
	}
	
	/**
	 * Get the product price from order id, download id
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 **/
	public function edd_vou_get_product_price( $orderid, $downloadid = false, $price_id = '' ) {
		
		// If order id is empty then return
		if( empty($orderid) ) return false;
		
		// Taking some defaults
		$product_price = '';
		
		$cart_details   = edd_get_payment_meta_cart_details( $orderid, true );
		
		if( !empty( $cart_details ) ) {// check if cart detail not empty
			
			foreach ( $cart_details as $item_key => $item_val ) {
				
				if( !empty( $item_val['id'] ) && $item_val['id'] == $downloadid ) { // if download id match
										
					// check for price id if item is variable
					if( $price_id !== '' && $price_id == $item_val['item_number']['options']['price_id'] ) {						
						// get product price for variable download
						$product_price = isset( $item_val['item_price'] ) ? $item_val['item_price'] : '';
						break;
					} else if( $price_id !== '' && $price_id != $item_val['item_number']['options']['price_id'] ) {
						// if price is set but price is not match then need to check further
						continue;
					} else {	// if price id not set then its simple download. return item price					
						// get product price
						$product_price = isset( $item_val['item_price'] ) ? $item_val['item_price'] : '';
						break;
					}										
				}
			}
			
		} // End of if
		
		return $product_price;
	}
	
	/**
	 * Get download recipient meta setting
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 **/
	public function edd_vou_get_download_recipient_meta( $download_id = '' ) {
		
		//Prefix
		$prefix	= EDD_VOU_META_PREFIX;
		
		//default recipient data
		$recipient_data	= array(
								'enable_recipient_name'			=> '',
								'recipient_name_label'			=> '',
								'recipient_name_max_length'		=> '',
								'recipient_name_is_required'	=> '',
								
								'enable_recipient_email'		=> '',
								'recipient_email_label'			=> '',
								'recipient_email_is_required'	=> '',
								
								'enable_recipient_message'		=> '',
								'recipient_message_label'		=> '',
								'recipient_message_max_length'	=> '',
								'recipient_message_is_required'	=> ''
							);
		
		if( !empty( $download_id ) ) {
			
			//recipient name fields
			$recipient_data['enable_recipient_name']		= get_post_meta( $download_id, $prefix.'enable_recipient_name', true );
			
			$recipient_name_label	= get_post_meta( $download_id, $prefix.'recipient_name_label', true );
			$recipient_name_label	= !empty( $recipient_name_label ) ? $recipient_name_label : __( 'Recipient Name', 'eddvoucher' );
			
			$recipient_data['recipient_name_label']			= $recipient_name_label;
			$recipient_data['recipient_name_max_length']	= get_post_meta( $download_id, $prefix.'recipient_name_max_length', true );
			$recipient_data['recipient_name_is_required']	= get_post_meta( $download_id, $prefix.'recipient_name_is_required', true );
			
			//recipient email fields
			$recipient_data['enable_recipient_email']		= get_post_meta( $download_id, $prefix.'enable_recipient_email', true );
			
			$recipient_email_label	= get_post_meta( $download_id, $prefix.'recipient_email_label', true );
			$recipient_email_label	= !empty( $recipient_email_label ) ? $recipient_email_label : __( 'Recipient Email', 'eddvoucher' );
			
			$recipient_data['recipient_email_label']		= $recipient_email_label;
			$recipient_data['recipient_email_is_required']	= get_post_meta( $download_id, $prefix.'recipient_email_is_required', true );
			
			//recipient message fields
			$recipient_data['enable_recipient_message']		= get_post_meta( $download_id, $prefix.'enable_recipient_message', true );
			
			$recipient_message_label	= get_post_meta( $download_id, $prefix.'recipient_message_label', true );
			$recipient_message_label	= !empty( $recipient_message_label ) ? $recipient_message_label : __( 'Recipient Message', 'eddvoucher' );
			
			$recipient_data['recipient_message_label']		 = $recipient_message_label;
			$recipient_data['recipient_message_max_length']	 = get_post_meta( $download_id, $prefix.'recipient_message_max_length', true );
			$recipient_data['recipient_message_is_required'] = get_post_meta( $download_id, $prefix.'recipient_message_is_required', true );
		}
		
		return $recipient_data;
	}
	
	/**
	 * Check item is already exist in order
	 * 
	 * Handles to check the item is already exist in order or not
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.3.6
	 */
	public function edd_vou_generate_pdf_voucher( $email = '', $download_id = '', $download_file = '', $order_id = '', $price_id = '' ) {
		
		$prefix	= EDD_VOU_META_PREFIX;
		
		$vou_codes_key	= array();
		$vou_codes_key	= $this->edd_vou_get_multi_voucher_key( $order_id, $download_id, $price_id );
		
		//Get mutiple pdf option from order meta
		$multiple_pdf = empty( $order_id ) ? '' : get_post_meta( $order_id, $prefix . 'multiple_pdf', true );
		
		$orderdvoucodes = array();
		if( !empty( $multiple_pdf ) ) {
			
			$orderdvoucodes = $this->edd_vou_get_multi_voucher( $order_id , $download_id, $price_id );
		}
		
		if( in_array( $download_file, $vou_codes_key ) || $_GET['file'] == 'edd_vou_pdf' ) { // Check Voucher download
			
			if( isset( $_GET['edd_voucher'] ) && $_GET['edd_voucher'] == '1' ) {
				
				//download voucher pdf
				edd_vou_process_download_pdf( $download_id, $order_id, $orderdvoucodes, $price_id );
				
			} else {
				
				wp_die( apply_filters( 'edd_download_expire', __( 'Sorry, voucher is no longer available for download.', 'eddvoucher' ) ), __( 'Error', 'eddvoucher' ) );
			}
			exit;
		}		
	}
	
	/**
	 * Display product information
	 * 
	 * Handles to display deal information
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.0
	 */
	public function edd_vou_display_download_info_html( $order_id, $voucode = '', $item = array(), $type = 'html', $show_link = true ) {
		
		// return if order is deleted
		if( empty($order_id) ) return;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$deal_id		= !empty($item['post_parent']) ? $item['post_parent'] : '';
    	$download_title = !empty($item['download_title']) ? $item['download_title'] : '';
    	
    	$payment_meta   = edd_get_payment_meta( $order_id );
    	$meta_currency	= isset($payment_meta['currency']) ? $payment_meta['currency'] : '';
    	
    	// Get deal price
		if( edd_has_variable_prices($item['post_parent']) ) {
			
			$vou_price_id	= get_post_meta( $item['ID'], $prefix.'price_id', true );
			$prices			= get_post_meta( $item['post_parent'], 'edd_variable_prices', true );
			
			$variable_price	= isset($prices[$vou_price_id]) ? $prices[$vou_price_id] : '';
			$amount			= isset($variable_price['amount']) ? $variable_price['amount'] : 0;
			
			$download_price = edd_currency_symbol( $meta_currency ) . $amount;
		} else {
			$download_price = edd_currency_symbol( $meta_currency ) . edd_get_download_price( $item['post_parent'] );
		}
    	
    	$edit_deal_link = !empty($deal_id) ? admin_url( 'post.php?post=' . absint( $deal_id ) . '&action=edit' ) : '';
    	
    	$html_arr = array();
		
		$html_arr['name'] = $download_title;
		$html_arr['url'] = $edit_deal_link;
		$html_arr['price'] = $download_price;
		
		if( $type == 'html' ) {
    	
	    	$html = '';
			$html .= "<table>";
			
				$html .= "<tr>";
					$html .= "<td><strong>".__("Name: ", 'sdevoucher')."</strong></td>";
					if( $show_link ){
						$html .= "<td><a href='".$edit_deal_link."'>".$download_title."</a></td>";
					} else {
						$html .= "<td>".$download_title."</td>";
					}
				$html .= "</tr>";
				
				$html .= "<tr>";
					$html .= "<td><strong>".__("Price: ", 'sdevoucher')."</strong></td>";	
					$html .= "<td>".$download_price."</td>";
				$html .= "</tr>";
				
			$html .= "</table>";
			return $html;

		} else if( $type == 'csv' ){

			$html = '';
			$html .= __("Name: ", 'sdevoucher').$download_title."\n";
			$html .= __("Price: ", 'sdevoucher').$download_price;

			return $html;
		}
		return $html_arr;
	}
	
	/**
	 * Display buyer information
	 * 
	 * Handles to display buyer information
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.0
	 */
	public function edd_vou_display_buyer_info_html( $payment_id, $voucode = '', $item = '', $type = 'html' ) {
		
		//get user information
		$user_info = edd_get_payment_meta_user_info( $payment_id, true );
		
		// Get user data
		$firstname	= isset($user_info['first_name']) ? $user_info['first_name'] : '';
		$lastname	= isset($user_info['last_name']) ? $user_info['last_name'] : '';
		$useremail	= isset($user_info['email']) ? $user_info['email'] : '';
		
		$html_arr['name'] = $firstname.' '.$lastname;
		$html_arr['email'] = $useremail;
		
		$customer_address = array();
		if ( is_user_logged_in() ) {
			
			global $current_user;
			
			$user_id			= get_current_user_id();
			$first_name			= get_user_meta( $user_id, 'first_name', true );
			$last_name			= get_user_meta( $user_id, 'last_name', true );
			$display_name		= $current_user->display_name;
			$customer_address	= edd_get_customer_address( $user_id );
		}
		
		$billing_details = !empty( $user_info['address'] ) ? $user_info['address'] : $customer_address ;
		
		$html_arr['billing_addr'] = $billing_details;
		
		if( $type == 'html' ) {
			
			$html = '';
			$html .= "<table>";
			
				$html .= "<tr>";
					$html .= "<td><strong>".__("Name: ", 'sdevoucher')."</strong></td>";	
					$html .= "<td>".$html_arr['name']."</td>";
				$html .= "</tr>";
				
				$html .= "<tr>";
					$html .= "<td><strong>".__("Email: ", 'sdevoucher')."</strong></td>";	
					$html .= "<td>".$html_arr['email']."</td>";
				$html .= "</tr>";
				
				if( !empty($html_arr['billing_addr']) && !empty( $billing_details['line1'] ) ) {
					$html .= "<tr>";
						$html .= "<td><strong>".__("Address: ", 'sdevoucher')."</strong></td>";	
						$html .= "<td>";
						$html .= !empty($billing_details['line1']) ? $billing_details['line1']." " : '';
						$html .= !empty($billing_details['line2']) ? $billing_details['line2']."<br />" : '';
						$html .= !empty($billing_details['city']) ? $billing_details['city']." " : '';
						$html .= !empty($billing_details['zip']) ? $billing_details['zip']."<br />" : '';
						$html .= !empty($billing_details['state']) ? $billing_details['state']." " : '';
						$html .= !empty($billing_details['country']) ? $billing_details['country']."." : '';
						$html .= "</td>";
					$html .= "</tr>";
				}
				
				if( !empty($billing_details['phone']) ) {
					
					$html .= "<tr>";
						$html .= "<td><strong>".__("Phone: ", 'sdevoucher')."</strong></td>";	
						$html .= "<td>".$billing_details['phone']."</td>";
					$html .= "</tr>";
				}
				
			$html .= "</table>";
			
			return $html;

		} else if( $type == 'csv' ){

			$html = '';
			
			$html .= __("Name: ", 'sdevoucher').$html_arr['name']."\n";
			$html .= __("Email: ", 'sdevoucher').$html_arr['email']."\n";
			
			if( !empty($html_arr['billing_addr']) && !empty( $billing_details['line1'] ) ) {

				$html .= __("Address: ", 'sdevoucher').$billing_details['line1'].' '.$billing_details['line2']."\n";
				$html .= $billing_details['city'].' '.$billing_details['state'].' '.$billing_details['country'].' - '.$billing_details['zip']."\n";
			}
			
			if( !empty($billing_details['phone']) ) {
				
				$html .= __("Phone: ", 'sdevoucher').$billing_details['phone']."\n";
			}

			return $html;		
		}
		return $html_arr;
	}
	
	/**
	 * Display payment information
	 * 
	 * Handles to display payment information
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.0
	 */
	public function edd_vou_display_payment_info_html( $payment_id, $voucode = '', $item = '', $type = 'html', $show_link = true ) {
		
		$html_arr = array();
		
		// Get payment meta
		$payment_meta   = edd_get_payment_meta( $payment_id );
		$meta_currency	= isset($payment_meta['currency']) ? $payment_meta['currency'] : '';
		
		// Get payment date
		$item         	= get_post( $payment_id );
		$payment_date   = isset($item->post_date) ? strtotime( $item->post_date ) : '';
		
		// Get user information
		$get_way = edd_get_gateway_admin_label( edd_get_payment_gateway($payment_id) );
		
		// Get user information
		$user_info = edd_get_payment_meta_user_info( $payment_id, true );
		
		// Get informations
		$html_arr['id']			= $payment_id;
		$html_arr['url']		= admin_url( 'post.php?post=' . absint( $payment_id ) . '&action=edit' );
		$html_arr['getway']		= $get_way;
		$html_arr['total']		= edd_currency_symbol( $meta_currency ) . esc_attr( edd_format_amount( edd_get_payment_amount( $payment_id ) ) ); 
		$html_arr['date']		= isset($item->post_date) ? $this->edd_vou_get_date_format( $item->post_date ) : '';
		$html_arr['discount']	= isset( $user_info['discount'] ) && $user_info['discount'] !== 'none' ? $user_info['discount'] : '';
		
		// Create html
		if( $type == 'html' ) {
			
			$html = '';
			$html .= "<table>";
			
				$html .= "<tr>";
					$html .= "<td><strong>".__("ID: ", 'sdevoucher')."</strong></td>";
					if( $show_link ){
						$html .= "<td><a href='".$html_arr['url']."'>".$html_arr['id']."</a></td>";
					} else {
						$html .= "<td>".$html_arr['id']."</td>";
					}
				$html .= "</tr>";
				
				$html .= "<tr>";
					$html .= "<td><strong>".__("Order Date: ", 'sdevoucher')."</strong></td>";	
					$html .= "<td>".$html_arr['date']."</td>";
				$html .= "</tr>";
				
				$html .= "<tr>";
					$html .= "<td><strong>".__("Payment Method: ", 'sdevoucher')."</strong></td>";	
					$html .= "<td>".$html_arr['getway']."</td>";
				$html .= "</tr>";
				
				$html .= "<tr>";
					$html .= "<td><strong>".__("Order Total: ", 'sdevoucher')."</strong></td>";	
					$html .= "<td>".$html_arr['total']."</td>";
				$html .= "</tr>";
				
				if( !empty($html_arr['discount']) ) {
					$html .= "<tr>";
						$html .= "<td><strong>".__("Order Discount: ", 'sdevoucher')."</strong></td>";	
						$html .= "<td>".$html_arr['discount']."</td>";
					$html .= "</tr>";
				}
				
			$html .= "</table>";
			return $html;

		} else {

			$html = '';
			$html .= __("ID: ", 'sdevoucher').$html_arr['id']."\n";
			$html .= __("Order Date: ", 'sdevoucher').$html_arr['date']."\n";
			$html .= __("Payment Method: ", 'sdevoucher').$html_arr['getway']."\n";
			$html .= __("Order Total: ", 'sdevoucher').$html_arr['total']."\n";
			
			if( !empty($html_arr['discount']) ) {
				$html .= __("Order Discount: ", 'sdevoucher').$html_arr['discount']."\n";
			}
			return $html;
		}
		
		return $html_arr;
	}
	
	/**
	 * Add code for add recipients fields to latest version of edd
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.1
	 */
	public function edd_vou_save_recipient_field( $payment_id = '', $payment_data = array() ) {
		
		$prefix	= EDD_VOU_META_PREFIX;
		
		if( !empty( $payment_id ) && class_exists( 'EDD_Payment' ) ) {
			
			$payment			= new EDD_Payment( $payment_id );
			
			$_edd_payment_meta	= $payment->get_meta();
			
			$cart_detail			= isset( $_edd_payment_meta['cart_details'] ) ? $_edd_payment_meta['cart_details'] : array();
			$payment_cart_detail	= isset( $payment_data['cart_details'] ) ? $payment_data['cart_details'] : array();
			
			if( !empty( $cart_detail ) ) {
				foreach ( $cart_detail as $key => $item ) {
					
					$item_number	= isset( $payment_cart_detail[$key]['item_number'] ) ? $payment_cart_detail[$key]['item_number'] : array();
					
					if( !empty( $item_number ) ) {
						
						$recipient_name		= isset( $item_number[$prefix.'recipient_name'] ) ? $item_number[$prefix.'recipient_name'] : '';
						if( !empty( $recipient_name ) ) {
							$cart_detail[$key]['item_number'][$prefix.'recipient_name']	= $recipient_name;
						}
						
						$recipient_email	= isset( $item_number[$prefix.'recipient_email'] ) ? $item_number[$prefix.'recipient_email'] : '';
						if( !empty( $recipient_email ) ) {
							$cart_detail[$key]['item_number'][$prefix.'recipient_email']	= $recipient_email;
						}
						
						$recipient_message	= isset( $item_number[$prefix.'recipient_message'] ) ? $item_number[$prefix.'recipient_message'] : '';
						if( !empty( $recipient_message ) ) {
							$cart_detail[$key]['item_number'][$prefix.'recipient_message']	= $recipient_message;
						}
					}
				}
			}
			
			$_edd_payment_meta['cart_details']	= $cart_detail;
			$payment->update_meta( '_edd_payment_meta', $_edd_payment_meta );
		}
	}
	
	/**
	 * Display Reddem information
	 * Like Redeem by, Redeem date
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.5.1
	 */
	public function edd_vou_display_redeem_info_html( $vouchercodeid, $order_id, $type='html' ){
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$redeem_details_html	= '';
				
		$user_id 	  = get_post_meta( $vouchercodeid, $prefix.'redeem_by', true );
		$user_detail  = get_userdata( $user_id );
		$user_profile = add_query_arg( array('user_id' => $user_id), admin_url('user-edit.php') );
		$display_name = isset( $user_detail->display_name ) ? $user_detail->display_name : '';
		
		$display_name_html = $display_name;
		
		if( !empty( $display_name_html ) ) {
			$display_name_html = '<a href="'.$user_profile.'">'.$display_name.'</a>';
		} else {
			$display_name_html = __( 'N/A', 'woovoucher' );
		}
		
		$redeem_date = get_post_meta( $vouchercodeid, $prefix.'used_code_date', true );
		$redeem_date = !empty( $redeem_date ) ? $this->edd_vou_get_date_format( $redeem_date, true ) : '';
		
		if( $type == 'csv' ) {
			
			$redeem_details_html .= 'Redeem By: ' . $display_name . "\n";
			$redeem_details_html .= 'Redeem Time: ' . $redeem_date;			
		} else { // type is 'html'
			
			$redeem_details_html  .= '<table>';
			$redeem_details_html  .= '<tr><td style="font-weight:bold;">' . __( 'Redeem By:', 'woovoucher' ) . '</td><td>' . $display_name_html . '</td></tr>';
			$redeem_details_html  .= '<tr><td style="font-weight:bold;">' . __( 'Redeem Time:', 'woovoucher' ) . '</td><td>' . $redeem_date . '</td></tr>';			
			$redeem_details_html  .= '</table>';
		}
		
		return apply_filters( 'edd_vou_display_redeem_info_html', $redeem_details_html, $vouchercodeid, $order_id, $type );
	}
}