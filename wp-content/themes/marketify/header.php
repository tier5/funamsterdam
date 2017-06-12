<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package Marketify
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php wp_head(); ?>
	<script>
	jQuery(document).ready( function(){
		/* alert("test"); */
		jQuery("li.icl-de a").attr("href", "https://funamsterdam.de/");
		/*jQuery("li.icl-nl a").attr("href", "http://topuitjes.com/");*/
		
		jQuery('.widget--site-footer p').each(function() {
			var text = jQuery(this).text();
			jQuery(this).text(text.replace('Spuistraat 54', 'Spuistraat 74')); 
		});
	});
	</script>
</head>
<body <?php body_class(); ?>>

<div id="page" class="hfeed site">

    <div <?php echo apply_filters( 'marketify_page_header', array() ); ?>>
    <div class="custom-header-serach-function">
        <div class="custom_language_select"><?php do_action('icl_language_selector');?></div>

        <header id="masthead" class="site-header" role="banner">
            <div class="container">

                <div class="site-header-inner">

                    <div class="site-branding">
                        <?php /*$header_image = get_header_image(); ?>
                        <?php if ( ! empty( $header_image ) ) : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="custom-header"><img src="<?php echo esc_url( $header_image ); ?>" alt=""></a>
                        <?php endif;*/ ?>
						<?php $header_image = get_header_image(); ?>
                        <?php //if ( ! empty( $header_image ) ) : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="custom-header"><img src="<?php echo site_url()."/wp-content/uploads/edd/2016/01/cropped-fun-amsterdam-activities.png"; ?>" alt=""></a>
							<?php /* echo site_url()."<<<>>>".home_url(); */?>
                        <?php //endif; ?>

                       <!--  <h1 class="site-title"><a href="<?php //echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php //bloginfo( 'name' ); ?></a></h1> -->
                        <h2 class="site-description screen-reader-text"><?php bloginfo( 'description' ); ?></h2>
                    </div>

                    


                    
<button class="js-toggle-nav-menu--primary nav-menu--primary-toggle"><span class="screen-reader-text"><?php _e( 'Menu', 'marketify' ); ?></span></button>
<div class="nav-menu nav-menu--primary">
<ul id="menu-main-menu-final" class="menu">
    

<li class="current-cart menu-item menu-item-has-children">
    <a href="<?php echo wc_get_checkout_url(); ?>"><span class="edd-cart-quantity"><?php echo WC()->cart->get_cart_contents_count();?></span></a>
   
<a class="cart-customlocation edd-checkout-link" href="<?php echo wc_get_checkout_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i><?php echo WC()->cart->get_cart_contents_count();?></a>

<ul class="sub-menu nav-menu">
<li class="widget">
<?php if( WC()->cart->get_cart_contents_count() > 0 ){?>

<p class="edd-cart-number-of-items">Number of items in cart: <span class="edd-cart-quantity"><?php echo WC()->cart->get_cart_contents_count();?></span></p>
<?php } ?>
<ul class="edd-cart">
<?php if( WC()->cart->get_cart_contents_count() == 0 ){?>
    <li class="cart_item empty" style=""><span class="edd_empty_cart">Your cart is empty.</span></li>
<?php }else{?>    
<li class="cart_item edd-cart-meta edd_total" style="">Total: <span class="cart-total"><?php echo WC()->cart->get_cart_total(); ?></span></li>
<li class="cart_item edd_checkout" style=""><a href="<?php echo wc_get_checkout_url(); ?>">Checkout</a></li>
<?php } ?>
</ul>
</li>
</ul>


</li>
                    <?php
                        $args = array(
                            'theme_location' => 'primary',
                            'container'=> false, 
                            'menu_class'=> false
                        );

                        if ( has_nav_menu( 'primary' ) ) {
                           // $args[ 'container_class' ] = 'nav-menu nav-menu--primary';
                        } else {
                           // $args[ 'menu_class' ] = 'nav-menu nav-menu--primary';
                        }

                        //wp_nav_menu( $args );
 wp_nav_menu_no_ul(); 


                    ?>
    </ul>

</div>



                </div>

            </div>
        </header><!-- #masthead -->

        <div class="search-form-overlay">
            <?php
                add_filter( 'get_search_form', array( marketify()->template->header, 'search_form' ) );
                get_search_form();
                remove_filter( 'get_search_form', array( marketify()->template->header, 'search_form' ) );
            ?>
        </div>
       <?php if(is_product_category()){ ?>                       

    <div class="woocommerce-products-header">

    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1> 
    </div>
<?php } ?>




    </div>    
