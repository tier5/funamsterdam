<?php
/**
*Plugin Name: Woocommerce Quick View Lite
*Plugin URI: http://www.phoeniixx.com
*Description: Quick View is a plugin that allows the customers to have a brief overview of every product in a pop-up box.
*Author: phoeniixx
*Version: 1.3.6
*Author URI: http://www.phoeniixx.com
**/

if ( ! defined( 'ABSPATH' ) )
{
	exit;   
}

// Exit if accessed directly
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
	
	//global $woocommerce;
	add_action( 'woocommerce_after_shop_loop_item', 'ph_quick_view' , 14 );
	
	add_action('wp_head', 'phoen_wcqv_style'); 
	
	//products details hooks
	//image
	add_action( 'ph_wcqv_product_image', 'woocommerce_show_product_sale_flash', 10 );
	add_action( 'ph_wcqv_product_image', 'woocommerce_show_product_images', 20 );

	// Summary
	//add_action( 'ph_wcqv_product_summary', 'woocommerce_template_single_title', 5 );
	//add_action( 'ph_wcqv_product_summary', 'woocommerce_template_single_rating', 10 );
	//add_action( 'ph_wcqv_product_summary', 'woocommerce_template_single_price', 15 );
	//add_action( 'ph_wcqv_product_summary', 'woocommerce_template_single_excerpt', 20 );
	add_action( 'ph_wcqv_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
	add_action( 'ph_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );
	//AJAX
	add_action( 'wp_ajax_nopriv_ph_quick_ajax_submit', 'ph_quick_ajax_submit' );
	add_action( 'wp_ajax_ph_quick_ajax_submit', 'ph_quick_ajax_submit' );

	add_action('admin_head','phoen_quick_view_admin_assests');
	
	function phoen_quick_view_admin_assests(){
			
		wp_enqueue_script('wp-color-picker'); 

		wp_enqueue_style('wp-color-picker');

		wp_enqueue_media();	

	}
	
	
	function phoen_wcqv_style()
	{
		$plugin_dir_url =  esc_url( plugin_dir_url( __FILE__ ) );
		
		$plugins_url = plugins_url();
		
		wp_enqueue_style( 'style-quickview-request', $plugin_dir_url.'css/quick-view.css' );
		
		wp_enqueue_style( 'style-name', $plugins_url.'/woocommerce/assets/css/prettyPhoto.css' );
		
		wp_enqueue_style( 'style-namee', $plugin_dir_url.'/css/style.css' );
		
		$plugin_dir_url =  esc_url( plugin_dir_url( __FILE__ ) );
				
		wp_enqueue_script( 'wc-add-to-cart-variation');
		
		?>
			<script>
				var blog2 = '<?php echo $plugin_dir_url; ?>';
			</script>
			
		<?php
		//all js
		
		// embed the javascript file that makes the AJAX request

		wp_enqueue_script( 'script-quickview-request', plugin_dir_url( __FILE__ ) . '/js/qv_custom.js', array( 'jquery' ) );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( 'script-quickview-request', 'ph_Ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	}
	/************* Add quick view button in wc product loop Start*************/
	function ph_quick_view()
	{
		global $table_prefix, $wpdb, $product;
	
		$data= get_option('phoen_quick_view');
		
		$row=json_decode($data);
	
		$checkqv = esc_attr($row->status);
	
		$buttonlabel = esc_attr($row->button_label);
	
		if($buttonlabel == '')
		{
			$buttonlabel = "Quick View";
		}
		if($checkqv == 'enable')
		{

			echo '<a href="#" class="ajax button quick-btn" pro_id="'.esc_attr($product->id).'" action="ph_quick_ajax_submit" title="'.esc_attr($buttonlabel).'">' . esc_attr($buttonlabel) . '</a>';
		}
						
	}
	/************* Add quick view button in wc product loop end*************/
	
	register_activation_hook(__FILE__, 'ph_quick_view_regitration');

	//Quick view registration 
	function ph_quick_view_regitration()
	{
		$name = 'phoen_quick_view';
		
		if( !get_option( $name ) ) 
		{
			
			$option = 'phoen_quick_view';
			
			$data = array(
							'status'=>'enable',
							'button_label'=>'Quick View',
							'popup_bg'=>'#fff',
							'button_quick_view_color'=>'#edeaed',
							'close_popup_btn_color'=>'#333333',
							'close_popup_btn_hcolor'=>'#9e9e9e'
						);
			
			$value = json_encode($data);
			
			add_option($option, $value);
		}

	}


	
	function ph_quick_ajax_submit() 
	{
		$plugin_dir_url =  esc_url( plugin_dir_url( __FILE__ ) );
		
		$plugins_url = plugins_url();
		
		wp_enqueue_script( 'wc-add-to-cart-variation');
		?>
		<script>

			var wc_add_to_cart_variation_params = {"ajax_url":"\/wp-admin\/admin-ajax.php"};
			
			jQuery.getScript("<?php echo $plugins_url; ?>/woocommerce/assets/js/frontend/add-to-cart-variation.min.js");
		
		</script>
		<?php
		echo '<script src="'.$plugins_url.'/woocommerce/assets/js/prettyPhoto/jquery.prettyPhoto.min.js" type="text/javascript"></script>
		<script src="'.$plugins_url.'/woocommerce/assets/js/prettyPhoto/jquery.prettyPhoto.init.min.js" type="text/javascript"></script>';
			
		$product_id = sanitize_text_field($_REQUEST['pid']);
	
		if ( ! isset($product_id)) {
			die();
		}

		wp( 'p=' . $product_id . '&post_type=product' );

		
		ob_start();

		require_once(dirname(__FILE__).'/template.php');
			
		echo ob_get_clean();

		die();

	}
	
	


	/******** Add Custom Menu ************/

	add_action('admin_menu', 'ph_add_custom_view_page');
	
	function ph_add_custom_view_page() 
	{

		$plugin_dir_url =  plugin_dir_url( __FILE__ );
		
		add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, $plugin_dir_url.'/images/logo-wp.png', 57 );
        
		add_submenu_page( 'phoeniixx', 'Quick View', 'Quick View', 'manage_options', 'ph_wcqv_quick_view_setting', 'ph_wcqv_quick_view_setting' );	
	
	}

	/**************Quick View Setting***********************/
	function ph_wcqv_quick_view_setting(){
	
		require_once(dirname(__FILE__).'/admin_setting.php');
		
	}
	
	add_action('wp_head','ph_quick_view_hook_css');
    
    function ph_quick_view_hook_css()
	{
		global $table_prefix, $wpdb;

		$data = get_option('phoen_quick_view');
		
		$row = json_decode($data);
	
		$but_qk_color = esc_attr($row->button_quick_view_color);
	
		$col_pop_but_colr = esc_attr($row->close_popup_btn_color);
	
		$col_pop_but_h_colr = esc_attr($row->close_popup_btn_hcolor);
	
		$win_bag_color = esc_attr($row->popup_bg);
	
		$plugin_dir_url =  esc_url( plugin_dir_url( __FILE__ ) );
	
		?>
		<style>
			
			#wc-quick-view-popup .quick-wcqv-main{ background:<?php if($win_bag_color != ''){ echo esc_attr($win_bag_color).'!important'; } else { echo "#ffffff"; } ?>; }
		
			.ajax.button.quick-btn{ background:<?php if($but_qk_color != ''){ echo esc_attr($but_qk_color); } else { echo "#edeaed"; } ?>;  }
		
			.ajax.button.quick-btn:hover{ background:<?php if($but_qk_color != ''){ echo esc_attr($but_qk_color); } else { echo "#edeaed"; } ?>; }

			#wc-quick-view-close { color:<?php if($col_pop_but_colr != ''){ echo esc_attr($col_pop_but_colr); } else { echo "#000"; } ?>; }
		
			#wc-quick-view-close:hover { color:<?php if($col_pop_but_h_colr != ''){ echo esc_attr($col_pop_but_h_colr); } else { echo "#ccc"; } ?>; }
		
			
		</style>
		<?php
	
	}
		
}

?>