<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div class="container">
        <div id="content" class="site-content row">

            <div role="main" class="content-area <?php echo ! is_active_sidebar( 'sidebar-download-single' ) ? 'col-xs-12' : 'col-xs-12 col-md-8'; ?>">

                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content-single', 'download' ); ?>
                <?php endwhile; rewind_posts(); ?>

            </div>

            <?php get_sidebar( 'single-download' ); ?>

        </div><!-- #content -->

        <?php comments_template(); ?>

        <?php do_action( 'marketify_single_download_after' ); ?>
    </div>

<?php get_footer(); ?>
