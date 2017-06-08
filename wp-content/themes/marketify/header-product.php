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
<?php global $product;?>
<div id="page" class="hfeed site">

    <div <?php //echo apply_filters( 'marketify_page_header', array() ); ?>>
    <?php if ( has_post_thumbnail( $product->id ) ) {
                        $attachment_ids[0] = get_post_thumbnail_id( $product->id );
                         $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' ); ?>    
                        <img src="<?php echo $attachment[0] ; ?>" class="card-image"  />
                    <?php } ?>

                    <h2><?php echo get_the_title($product->id);?></h2>
                    <?php echo do_shortcode('[add_to_cart id="'.$product->id.'"]');?>
                    <?php //echo $currency = get_woocommerce_currency_symbol();?>
                    <?php  //echo $product->get_price();?>
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

                 
                    <a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i><?php echo WC()->cart->get_cart_contents_count();?></a>

                    <button class="js-toggle-nav-menu--primary nav-menu--primary-toggle"><span class="screen-reader-text"><?php _e( 'Menu', 'marketify' ); ?></span></button>

                    <?php
                        $args = array(
                            'theme_location' => 'primary'
                        );

                        if ( has_nav_menu( 'primary' ) ) {
                            $args[ 'container_class' ] = 'nav-menu nav-menu--primary';
                        } else {
                            $args[ 'menu_class' ] = 'nav-menu nav-menu--primary';
                        }

                        wp_nav_menu( $args );
                    ?>

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
