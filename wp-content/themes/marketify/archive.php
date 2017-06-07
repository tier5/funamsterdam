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

            <div id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-1' ) ? '8' : '12'; ?>">
                <main id="main" class="site-main" role="main">

                <?php if ( have_posts() ) : ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'content', get_post_format() ); ?>

                    <?php endwhile; ?>

                    <?php do_action( 'marketify_loop_after' ); ?>

                <?php else : ?>

                    <?php get_template_part( 'no-results', 'index' ); ?>

                <?php endif; ?>

                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>

        </div>
    </div>

<?php get_footer(); ?>
