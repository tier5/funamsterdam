<?php
/**
 * Marketify
 *
 * Do not modify this file. Place all modifications in a child theme.
 */

if ( ! isset( $content_width ) ) {
    $content_width = 680;
}

class Marketify {

    private static $instance;

    public $helpers;

    public $customizer;

    public $activation;
    public $setup;

    public $integrations;
    public $widgets;

    public $template;

    public $page_settings;

    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Marketify ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {
        $this->base();
        $this->setup();
    }

    // Integration getter helper
    public function get( $integration ) {
        return $this->integrations->get( $integration );
    }

    private function base() {
        $this->files = array(
            'customizer/class-customizer.php',

            'activation/class-activation.php',

            'setup/class-setup.php',

            'class-helpers.php',

            'integrations/class-integration.php',
            'integrations/class-integrations.php',

            'widgets/class-widgets.php',
            'widgets/class-widget.php',

            'template/class-template.php',

            'pages/class-page-settings.php',

            'deprecated.php'
        );

        foreach ( $this->files as $file ) {
            require_once( get_template_directory() . '/inc/' . $file );
        }
    }

    private function setup() {
        $this->helpers = new Marketify_Helpers();

        $this->customizer = new Marketify_Customizer();

        $this->activation = new Marketify_Activation();
        $this->setup = new Marketify_Setup();

        $this->integrations = new Marketify_Integrations();
        $this->widgets = new Marketify_Widgets();

        $this->template = new Marketify_Template();

        // $this->page_settings = new Marketify_Page_Settings();

        add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
    }

    public function setup_theme() {
        $locale = apply_filters( 'plugin_locale', get_locale(), 'marketify' );
        load_textdomain( 'marketify', WP_LANG_DIR . "/marketify-$locale.mo" );
        load_theme_textdomain( 'marketify', get_template_directory() . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );

        add_editor_style( 'css/editor-style.css' );

        add_theme_support( 'custom-background', apply_filters( 'marketify_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        if ( apply_filters( 'marketify_hard_crop_images', true ) ) {
            add_image_size( 'medium', get_option( 'medium_size_w' ), get_option( 'medium_size_h' ), true );
            add_image_size( 'large', get_option( 'large_size_w' ), get_option( 'large_size_h' ), true );
        }
    }

}

function marketify() {
    return Marketify::instance();
}
wp_enqueue_style( 'quote-form', get_template_directory_uri() . '/quote-form.css' ); wp_enqueue_script( 'quote-form', get_template_directory_uri() . '/js/quote-form.js', array( 'jquery' ), '20150715', true );
wp_enqueue_style( 'custom', get_template_directory_uri() . '/css/custom.css' );
wp_dequeue_style( 'edd-software-specs' );

marketify();
define('EDD_SLUG', 'amsterdam');

include('gm-functions.php');
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

add_filter( 'woocommerce_product_add_to_cart_text' , 'custom_woocommerce_product_add_to_cart_text' );
/**
 * custom_woocommerce_template_loop_add_to_cart
*/
function custom_woocommerce_product_add_to_cart_text() {
    global $product;
    
    $product_type = $product->product_type;
    
    switch ( $product_type ) {
        case 'external':
            return __( 'ADD TO BOOKING', 'woocommerce' );
        break;
        case 'grouped':
            return __( 'ADD TO BOOKING', 'woocommerce' );
        break;
        case 'simple':
            return __( 'ADD TO BOOKING', 'woocommerce' );
        break;
        case 'variable':
            return __( 'ADD TO BOOKING', 'woocommerce' );
        break;
        default:
            return __( 'ADD TO BOOKING', 'woocommerce' );
    }
    
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
 
function woo_custom_cart_button_text() {
 
        return __( 'ADD TO BOOKING', 'woocommerce' );
 
}


remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');




add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
                    function woocommerce_header_add_to_cart_fragment( $fragments ) {
                        ob_start();
                        ?>
                        
                        <a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
                        <?php
                        
                        $fragments['a.cart-contents'] = ob_get_clean();
                        
                        return $fragments;
                    }



 

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
// Remove the sorting dropdown from Woocommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

function wp_nav_menu_no_ul()
{
    $options = array(
        'echo' => false,
        'container' => false,
        'theme_location' => 'primary',
        'fallback_cb'=> 'default_page_menu',
        'menu_class'=> false
    );

    $menu = wp_nav_menu($options);
    echo preg_replace(array(
        '#^<ul[^>]*>#',
        '#</ul>$#'
    ), '', $menu);

}

function default_page_menu() {
   wp_list_pages('title_li=');
} 


add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {
if(is_page('contact-us')){
$classes[] = 'contact-us';
}elseif(is_page('jobs-2')){
$classes[] = 'jobs';
}elseif(is_page('my-account')){
$classes[] = 'my-account';
}elseif(is_page('become-a-host')){
$classes[] = 'become-a-host';
}elseif(is_page('terms-conditions')){
$classes[] = 'terms-conditions';
}elseif(is_page('about-us')){
$classes[] = 'about-us';
}elseif(is_page('pay')){
$classes[] = 'pay';
}elseif(is_page('quick-amsterdam-quote')){
$classes[] = 'quick-amsterdam-quote';
}else{
$classes[] = "";
}


return $classes;

}

function my_custom_add_to_cart_redirect( $url ) {
$url = WC()->cart->get_checkout_url();
// $url = wc_get_checkout_url(); // since WC 2.5.0
return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );



add_action( 'wp_enqueue_scripts', 'basel_child_enqueue_styles', 1000 );

function basel_child_enqueue_styles() {
    
    
    
    wp_enqueue_script( 'basel-child', get_stylesheet_directory_uri() . '/js/child.js', array( 'jquery' ), '', true );
}


// Remove product tags and categories

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Add the "Check Availability" button

add_action( 'woocommerce_single_product_summary', function() {
    echo '<div class="check-button-wrapper"><a href="#" class="btn btn-color-primary btn-availability">' . __('Check Availability & Price', 'basel-child') . '</a>';
}, 8 );



?>