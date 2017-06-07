<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Marketify
 */
?>

    <footer id="colophon" class="site-footer site-footer--<?php echo esc_attr( marketify_theme_mod( 'footer-style' ) ); ?>" role="contentinfo">
        <div class="container">
            <?php do_action( 'marketify_footer_above' ); ?>

            <div class="site-info row<?php echo is_active_sidebar( 'footer-1' ) ? ' has-widgets' : ''; ?>">
                <?php do_action( 'marketify_footer_site_info' ); ?>
            </div><!-- .site-info -->

        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
