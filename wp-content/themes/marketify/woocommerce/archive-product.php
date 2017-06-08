<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>
 
<div id="page" class="hfeed site">
<header class="woocommerce-products-header">

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>

		<?php
			/**
			 * woocommerce_archive_description hook.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			//do_action( 'woocommerce_archive_description' );
		?>

    </header>

<div id="content" class="site-content container">
  <div class="marketify-archive-download row">
    <div role="main" class="content-area col-xs-12 ">
      
            

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		//do_action( 'woocommerce_before_main_content' );
	?>

   


 <div data-columns="3" class="edd_downloads_list row download-grid-wrapper edd_download_columns_3">
		<?php
global $product;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array( 'post_type' => 'product', 'posts_per_page' => 12, 'paged' => $paged );

$loop = new WP_Query( $args );

while ( $loop->have_posts() ) : $loop->the_post();
global $product;
$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ));
if ($product->is_type( 'variable' )) 
{
#Step 1: Get product varations
$available_variations = $product->get_available_variations();

#Step 2: Get product variation id
$variation_id=$available_variations[0]['variation_id']; // Getting the variable id of just the 1st product. You can loop $available_variations to get info about each variation.

#Step 3: Create the variable product object
$variable_product1= new WC_Product_Variation( $variation_id );

#Step 4: You have the data. Have fun :)
$regular_price = $variable_product1 ->regular_price;
$sales_price = $variable_product1 ->sale_price;
}else{
	$regular_price = $product ->regular_price;
$sales_price = $product ->sale_price;
}
?>

<div itemscope="" itemtype="http://schema.org/Product"; class="edd_download content-grid-download 2885 post-2885 download type-download status-publish format-standard has-post-thumbnail hentry download_category-all-activities download_category-bachelor download_category-bachelor-daytime-activities download_category-bachelorette download_category-bachelorette-datime-activities download_category-corporate-daytime-activties download_category-team-building download_category-party-daytime-activities download_category-party-groups download_tag-5aside download_tag-amsterdam-bubble-football download_tag-bababalls download_tag-bbq-package download_tag-bubble-football download_tag-drinks-2 download_tag-food-3 download_tag-football download_tag-game download_tag-indoor download_tag-outdoor download_tag-zorb edd-download edd-download-cat-all-activities edd-download-cat-bachelor edd-download-cat-bachelor-daytime-activities edd-download-cat-bachelorette edd-download-cat-bachelorette-datime-activities edd-download-cat-corporate-daytime-activties edd-download-cat-team-building edd-download-cat-party-daytime-activities edd-download-cat-party-groups edd-download-tag-5aside edd-download-tag-amsterdam-bubble-football edd-download-tag-bababalls edd-download-tag-bbq-package edd-download-tag-bubble-football edd-download-tag-drinks-2 edd-download-tag-food-3 edd-download-tag-football edd-download-tag-game edd-download-tag-indoor edd-download-tag-outdoor edd-download-tag-zorb" id="edd_download_2885" style="">
<div class="edd_download_inner">


<div class="content-grid-download__entry-image">
<div class="content-grid-download__overlay">

<div class="gm-test content-grid-download__actions">
<a>
</a>
<?php echo do_shortcode('[add_to_cart id="'.get_the_ID().'"]');?>
<a href="<?php echo get_permalink();?>" rel="bookmark" class="button button--color-white">Details</a>

<strong class="item-price"><span>Item Price: <span class="edd_price" id="edd_price_2885">€<?php echo $regular_price;?></span></span></strong>

</div>
</div>

<img width="300" height="300" src="<?php echo $image[0]; ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt=""></div>






<header class="content-grid-download__entry-header">
<h3 class="entry-title "><a href="<?php echo get_permalink();?>" rel="bookmark"><?php echo get_the_title();?></a></h3>


<div class="entry-meta"><span class="byline"> by <span class="author vcard"><a class="url fn n" href="/tom-donaldzgmail-com" title="View all Activities by Dowilo Concierge">Dowilo Concierge </a></span></span></div>
</header><!-- .entry-header -->
</div>
</div>

<?php
endwhile;


wp_reset_query();

?>
</div>

<div id="edd_download_pagination" class="navigation">
				
<?php

				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>		
</div>




	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		//do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>
</div>
</div>
</div>
</div>

<?php get_footer(); ?>
