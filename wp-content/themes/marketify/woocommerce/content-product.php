<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
	
<div itemscope="" itemtype="http://schema.org/Product"; class="edd_download content-grid-download 2885 post-2885 download type-download status-publish format-standard has-post-thumbnail hentry download_category-all-activities download_category-bachelor download_category-bachelor-daytime-activities download_category-bachelorette download_category-bachelorette-datime-activities download_category-corporate-daytime-activties download_category-team-building download_category-party-daytime-activities download_category-party-groups download_tag-5aside download_tag-amsterdam-bubble-football download_tag-bababalls download_tag-bbq-package download_tag-bubble-football download_tag-drinks-2 download_tag-food-3 download_tag-football download_tag-game download_tag-indoor download_tag-outdoor download_tag-zorb edd-download edd-download-cat-all-activities edd-download-cat-bachelor edd-download-cat-bachelor-daytime-activities edd-download-cat-bachelorette edd-download-cat-bachelorette-datime-activities edd-download-cat-corporate-daytime-activties edd-download-cat-team-building edd-download-cat-party-daytime-activities edd-download-cat-party-groups edd-download-tag-5aside edd-download-tag-amsterdam-bubble-football edd-download-tag-bababalls edd-download-tag-bbq-package edd-download-tag-bubble-football edd-download-tag-drinks-2 edd-download-tag-food-3 edd-download-tag-football edd-download-tag-game edd-download-tag-indoor edd-download-tag-outdoor edd-download-tag-zorb" id="edd_download_2885" style="">
<div class="edd_download_inner">


<div class="content-grid-download__entry-image">
<div class="content-grid-download__overlay">

<div class="gm-test content-grid-download__actions">

<?php do_action( 'woocommerce_after_shop_loop_item' );
?>


<a href="<?php echo get_permalink();?>" rel="bookmark" class="button button--color-white">Details</a>
<?php     
global $woocommerce, $product;
?>
<strong class="item-price"><span>Item Price: <span class="edd_price" id="edd_price_2885"><?php echo get_woocommerce_currency_symbol(); ?><?php echo $product->get_price(); ?></span></span></strong>

</div>
</div>

<?php do_action( 'woocommerce_before_shop_loop_item_title' );
?>

<!-- <img width="300" height="300" src="<?php //do_action( 'woocommerce_before_shop_loop_item_title' );
 ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt=""> -->

 </div>






<header class="content-grid-download__entry-header">
<h3 class="entry-title "><a href="<?php echo get_permalink();?>" rel="bookmark"><?php the_title(); ?></a></h3>


<div class="entry-meta"><span class="byline"> by <span class="author vcard"><a class="url fn n" href="/tom-donaldzgmail-com" title="View all Activities by Dowilo Concierge">Dowilo Concierge </a></span></span></div>
</header><!-- .entry-header -->
</div>
</div>