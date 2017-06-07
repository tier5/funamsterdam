<?php
/**
 * Template Name: Account: Vendor Profile
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div class="container">
        <div id="content" class="site-content row">

            <section id="primary" class="content-area col-md-9 col-sm-7 col-xs-12">
                <main id="main" class="site-main" role="main">

                    <?php while ( have_posts() ) : the_post(); ?>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                    <?php endwhile; ?>

                </main><!-- #main -->
            </section><!-- #primary -->

            <div id="secondary" class="author-widget-area col-md-3 col-sm-5 col-xs-12" role="complementary">
                <div class="vendor-widget-area">
                    <?php 
                        if ( ! dynamic_sidebar( 'sidebar-vendor' ) ) :
                            $args = array(
                                'before_widget' => '<aside class="widget widget--vendor-profile widget-detail">',
                                'after_widget'  => '</aside>',
                                'before_title'  => '<h3 class="widget-title widget-title--vendor-profile">',
                                'after_title'   => '</h3>',
                            );

                            the_widget( 'Marketify_Widget_FES_Vendor', array( 'extras' => '' ), $args );
                            the_widget( 'Marketify_Widget_FES_Vendor_Description', array(), $args );
                            the_widget( 'Marketify_Widget_FES_Vendor_Contact', array(), wp_parse_args( array( 'before_widget' => '<aside class="widget widget--vendor-profile widget--vendor-profile-contact widget-detail">' ), $args ) );
                        endif;
                    ?>
                </div>
            </div><!-- #secondary -->

        </div><!-- #content -->
    </div>

<?php get_footer(); ?>
