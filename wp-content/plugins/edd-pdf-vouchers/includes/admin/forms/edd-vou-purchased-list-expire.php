<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Purchased Voucher Code List Page
 *
 * The html markup for the purchased voucher code list
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class EDD_Vou_List extends WP_List_Table {

	var $model, $public, $render, $edd_options, $per_page;
	
	function __construct(){
	
        global $edd_vou_model, $edd_vou_pubilc, $edd_vou_render;
                
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'usedvou',   //singular name of the listed records
							            'plural'    => 'usedvous',  //plural name of the listed records
							            'ajax'      => false        //does this table support ajax?
							        ) );   
		
		$this->model 	= $edd_vou_model;
		$this->render 	= $edd_vou_render;
		$this->public 	= $edd_vou_pubilc;

        $per_page 		= isset($edd_options['per_page']) && !empty($edd_options['per_page']) ? $edd_options['per_page'] : '10';
		$this->per_page	= apply_filters( 'edd_vou_purchase_posts_per_page', $per_page ); // Per page
		
    }
    
    /**
	 * Displaying Prodcuts
	 * 
	 * Does prepare the data for displaying the products in the table.
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function display_purchased_vouchers() {
 		
		global $wpdb, $current_user;
		
		$prefix = EDD_VOU_META_PREFIX;
		
		$args = $data = array();
		
		// Taking parameter
		$orderby 	= isset( $_GET['orderby'] )	? urldecode( $_GET['orderby'] )		: 'ID';
		$order		= isset( $_GET['order'] )	? $_GET['order']                	: 'DESC';
		$search 	= isset( $_GET['s'] ) 		? sanitize_text_field( trim($_GET['s']) )	: null;
		
		$args = array(
						'posts_per_page'	=> $this->per_page,
						'page'				=> isset( $_GET['paged'] ) ? $_GET['paged'] : null,
						'orderby'			=> $orderby,
						'order'				=> $order,
						'offset'  			=> ( $this->get_pagenum() - 1 ) * $this->per_page,
						'edd_vou_list'		=> true
					);
		
		$args['meta_query'] = array(
										array(
													'key'		=> $prefix.'purchased_codes',
													'value'		=> '',
													'compare'	=> '!=',
												),
												array(
													'key'     	=> $prefix.'used_codes',
													'compare' 	=> 'NOT EXISTS'
												),
												array(
													'key'		=> $prefix .'exp_date',
													'compare'	=> '<=',
	                  								'value'		=> $this->model->edd_vou_current_date()
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
		
		if( !empty( $search ) ) {
			
			/*//$args['s'] = $_GET['s'];
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
										);*/
			
			// meata query dont alow two relations parameters so we needed to make custom query
			$sql = "SELECT * FROM {$wpdb->posts} AS wps 
					INNER JOIN {$wpdb->postmeta} AS wpsm ON (wps.ID = wpsm.post_id) 
					LEFT JOIN {$wpdb->postmeta} AS wpsm1 ON (wps.ID = wpsm1.post_id AND wpsm1.meta_key = '{$prefix}used_codes' ) 
					WHERE 1=1  AND wps.post_type = '".EDD_VOU_CODE_POST_TYPE."' 
					AND (wps.post_status = 'publish') 
					AND ( (wpsm.meta_key = '{$prefix}purchased_codes' AND wpsm.meta_value LIKE '%{$_GET['s']}%')
					OR  (wpsm.meta_key = '{$prefix}first_name' AND wpsm.meta_value LIKE '%{$_GET['s']}%') 
					OR  (wpsm.meta_key = '{$prefix}last_name' AND wpsm.meta_value LIKE '%{$_GET['s']}%') 
					OR  (wpsm.meta_key = '{$prefix}order_id' AND wpsm.meta_value LIKE '%{$_GET['s']}%') 
					OR  (wpsm.meta_key = '{$prefix}order_date' AND wpsm.meta_value LIKE '%{$_GET['s']}%') ) 
					AND (wpsm1.post_id IS NULL)
					GROUP BY wps.ID ORDER BY wps.post_date DESC ";
			
			$voucher_data['data']  = $wpdb->get_results( $sql, ARRAY_A );
			
			//params for total count of purchase vouchers code
			$count_sql = "SELECT COUNT(*) FROM {$wpdb->posts} AS wps 
					INNER JOIN {$wpdb->postmeta} AS wpsm ON (wps.ID = wpsm.post_id) 
					LEFT JOIN {$wpdb->postmeta} AS wpsm1 ON (wps.ID = wpsm1.post_id AND wpsm1.meta_key = '{$prefix}used_codes' ) 
					WHERE 1=1  AND wps.post_type = '".EDD_VOU_CODE_POST_TYPE."' 
					AND (wps.post_status = 'publish') 
					AND ( (wpsm.meta_key = '{$prefix}purchased_codes') )";
			
			//Get Total count of Items
			$voucher_data['total'] = $wpdb->get_var( $count_sql );
			
		} else {
			
			// Get purchased voucher codes data from database
			$voucher_data = $this->model->edd_vou_get_voucher_details( $args );
		}
		
		if( !empty($voucher_data['data']) ) {
			
			foreach ( $voucher_data['data'] as $key => $value ) {
				
				$download_title 	= get_the_title( $value['post_parent'] );
				$download_order_id 	= get_post_meta( $value['ID'], $prefix.'order_id', true );
				
				// If product is variable then take price id for its variable option name
				$vou_price_id = get_post_meta( $value['ID'], $prefix.'price_id', true );
				if( edd_has_variable_prices($value['post_parent']) ) {
					$download_title .= ' - ' . edd_get_price_option_name( $value['post_parent'], $vou_price_id, $download_order_id );
				}
				
				$data[$key]['ID'] 				= $value['ID'];
				$data[$key]['post_parent'] 		= $value['post_parent'];
				$data[$key]['code'] 			= get_post_meta( $value['ID'], $prefix.'purchased_codes', true );
				$data[$key]['first_name'] 		= get_post_meta( $value['ID'], $prefix.'first_name', true );
				$data[$key]['last_name'] 		= get_post_meta( $value['ID'], $prefix.'last_name', true );
				$data[$key]['buyers_name']  	= $data[$key]['first_name'] . ' ' . $data[$key]['last_name'];
				$data[$key]['order_id'] 		= $download_order_id;
				$data[$key]['order_date'] 		= get_post_meta( $value['ID'], $prefix.'order_date', true );
				$data[$key]['download_title']	= $download_title;
			}
		}
		
		$result_arr['data']		= !empty($data) ? $data : array();
		$result_arr['total'] 	= isset( $voucher_data['total'] ) ? $voucher_data['total'] 	: ''; // Total no of data
		
		return $result_arr;
	}
	
	/**
	 * Mange column data
	 * 
	 * Default Column for listing table
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	function column_default( $item, $column_name ) {
		
		global $current_user;
		
        switch( $column_name ) {
            case 'code':
			case 'buyers_name' :
            	return $item[ $column_name ];
			
            case 'download_info':
				return $this->model->edd_vou_display_download_info_html( $item['order_id'], $item['code'], $item );
            	
            case 'buyer_info':
            	return $this->model->edd_vou_display_buyer_info_html( $item['order_id'], $item['code'], $item );
            	
            case 'payment_info' :
            	return $this->model->edd_vou_display_payment_info_html( $item['order_id'], $item['code'], $item );
            
            case 'download_title' :
            	$page_url = add_query_arg( array( 'edd_vou_post_id' => $item[ 'post_parent' ] ) );
            	return '<a href="' . $page_url . '">' . $item[ $column_name ] . '</a>';
			case 'order_id' :
				if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
					return $item[ $column_name ];
				} else {
					$page_url = add_query_arg( array( 'post_type' => EDD_VOU_MAIN_POST_TYPE, 'page' => 'edd-payment-history', 'view' => 'view-order-details', 'id' => $item[ $column_name ] ), admin_url( 'edit.php' ) );
	            	return '<a target="_blank" href="' . $page_url . '">' . $item[ $column_name ] . '</a>';
				}
			case 'order_date' :
				$datetime = $this->model->edd_vou_get_date_format($item[ $column_name ]);
            	return $datetime;
            	
            
            default:
				return $item[ $column_name ];
        }
    }
	
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
     */
	function get_columns() {
		
		$columns = array(
							'code'			=> __( 'Voucher Code', 'eddvoucher' ),
							'download_info'	=> __( 'Download Information', 'eddvoucher' ),
							'buyer_info'	=> __( 'Buyer\'s Information' ),
							'payment_info'	=> __( 'Payment Information' ),
							
							//'download_title'=>	__(	'Download Title', 'eddvoucher' ),
							//'buyers_name'	=>	__(	'Buyer\'s Name', 'eddvoucher' ),
							//'order_date'	=>	__(	'Payment Date', 'eddvoucher' ),
							//'order_id'		=>	__(	'Payment ID', 'eddvoucher' ),
						);
		
		return apply_filters('edd_vou_used_add_column',$columns);
	}
	
    /**
     * Sortable Columns
     *
     * Handles soratable columns of the table
     * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
     */
	function get_sortable_columns() {
		
		$sortable_columns = array(
									'code'			=>	array( 'code', true ),
									//'download_title'=>	array( 'download_title', true ),
									'buyers_name'	=>	array( 'buyers_name', true ),
									'order_date'	=>	array( 'order_date', true ),
									'order_id'		=>	array( 'order_id', true ),  
								);
		
		return apply_filters('edd_vou_used_add_sortable_column',$sortable_columns);
	}
	
	function no_items() {
		//message to show when no records in database table
		_e( 'No purchased voucher codes yet.', 'eddvoucher' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
     */
	function get_bulk_actions() {
		//bulk action combo box parameter
		//if you want to add some more value to bulk action parameter then push key value set in below array
		$actions = array();
		return $actions;
	}
	
	/**
	 * Add Filter for Sorting
	 * 
	 * Handles to add filter for sorting
	 * in listing
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 **/
	function extra_tablenav( $which ) {
		
		if( $which == 'top' ) {
			
			global $current_user;
			
			$prefix = EDD_VOU_META_PREFIX;
			
			$args = array();
			
			$args['meta_query'] = array(
											array(
														'key'		=> $prefix.'purchased_codes',
														'value'		=> '',
														'compare'	=> '!=',
													),
											array(
													'key'     	=> $prefix.'used_codes',
													'compare' 	=> 'NOT EXISTS'
												)
										);
			
			if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
				$args['author'] = $current_user->ID;
			}
			
			$downloads_data = $this->model->edd_vou_get_download_by_voucher( $args );
			
			echo '<div class="alignleft actions edd-vou-dropdown-wrapper">';
		?>
				<select id="edd_vou_post_id" name="edd_vou_post_id">
					<option value=""><?php _e( 'Show all downloads', 'eddvoucher' ); ?></option>
		<?php
					if( !empty( $downloads_data ) ) {
						
						foreach ( $downloads_data as $download_data ) {
							
							echo '<option value="' . $download_data['ID'] . '" ' . selected( isset( $_GET['edd_vou_post_id'] ) ? $_GET['edd_vou_post_id'] : '', $download_data['ID'], false ) . '>' . $download_data['post_title'] . '</option>';
						}
					}
		?>
				</select>
		<?php
    		submit_button( __( 'Apply', 'eddvoucher' ), 'button', false, false, array( 'id' => 'post-query-submit' ) );
			echo '</div>';
    	}
    }
    
	function prepare_items() {
        
		global $edd_options;
        
        // Get how many records per page to show
        $per_page	= $this->per_page;
        
        // Get All, Hidden, Sortable columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
		// Get final column header
        $this->_column_headers = array($columns, $hidden, $sortable);

		// Get Data of particular page
		$data_res 	= $this->display_purchased_vouchers();
		$data 		= $data_res['data'];		
   
		// Get current page number
        $current_page = $this->get_pagenum();
        
		// Get total count
        $total_items  = $data_res['total'];

        // Get page items
        $this->items = $data;
        
		// We also have to register our pagination options & calculations.
        $this->set_pagination_args( array(
									            'total_items' => $total_items,                  //WE have to calculate the total number of items
									            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
									            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
									        ) );
    }
    
}

global $current_user;

//Create an instance of our package class...
$EddUsedVouListTable = new EDD_Vou_List();
	
//Fetch, prepare, sort, and filter our data...
$EddUsedVouListTable->prepare_items();
		
?>

<div class="wrap">
    
    <?php 
    	//showing sorting links on the top of the list
    	$EddUsedVouListTable->views(); 
    ?>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="product-filter" method="get" action="">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <input type="hidden" name="vou-data" value="<?php echo isset( $_REQUEST['vou-data'] ) ? $_REQUEST['vou-data'] : 'purchased'; ?>" />
        						
		<?php if( !in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role ?>
		
			<input type="hidden" name="post_type" value="<?php echo EDD_VOU_MAIN_POST_TYPE; ?>" />
			
		<?php } ?>
		
        <!-- Search Title -->
        <?php $EddUsedVouListTable->search_box( __( 'Search', 'eddvoucher' ), 'eddvoucher' ); ?>
        
        <div class="alignright">
			<?php
				$generatpdfurl = add_query_arg( array( 
														'edd-vou-voucher-gen-pdf'	=>	'1',
														'edd_vou_action'			=> 'expired'
													));
				$exportcsvurl = add_query_arg( array( 
														'edd-vou-voucher-exp-csv'	=>	'1',
														'edd_vou_action'			=> 'expired'
													));
			?>

			<a href="<?php echo $exportcsvurl; ?>" id="edd-vou-export-csv-btn" class="button-secondary edd-gen-pdf" title="<?php echo __('Export CSV','eddvoucher'); ?>"><?php echo __("Export CSV",'eddvoucher'); ?></a>
			<a href="<?php echo $generatpdfurl; ?>" id="edd-vou-pdf-btn" class="button-secondary" title="<?php echo __('Generate PDF','eddvoucher'); ?>"><?php echo __("Generate PDF",'eddvoucher'); ?></a>		
		
		</div>
        
        <!-- Now we can render the completed list table -->
        <?php $EddUsedVouListTable->display(); ?>
        
    </form>
	        
</div>