<?php
/**
 * Template Name: Page: Shop
 *
 * Load the [downloads] shortcode.
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div id="content" class="site-content container">

        <?php do_action( 'marketify_shop_before' ); ?>

        <?php get_template_part( 'content-grid-download', 'popular' ); ?>

        <div class="marketify-archive-download row">
            <div role="main" class="content-area col-xs-12 <?php echo is_active_sidebar( 'sidebar-download' ) ? 'col-md-8' : ''; ?>">

                <?php do_action( 'marketify_downloads_before' ); ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php if ( ! has_shortcode( get_the_content(), 'downloads' ) ) : ?>
                        <?php echo do_shortcode( sprintf( '[downloads number="%s"]', absint( marketify_theme_mod( 'downloads-archives-per-page' ) ) ) ); ?>
                    <?php else : ?>
                        <?php the_content(); ?>
                    <?php endif; ?>

                <?php endwhile; ?>

                <?php do_action( 'marketify_downloads_after' ); ?>

            </div #primary -->

            <?php get_sidebar( 'archive-download' ); ?>
        </div>

        <?php do_action( 'marketify_shop_after' ); ?>

    </div>

<?php get_footer(); ?>
