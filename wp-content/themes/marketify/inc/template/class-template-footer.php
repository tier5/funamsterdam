<?php

class Marketify_Template_Footer {

    public function __construct() {
        add_action( 'marketify_footer_site_info', array( $this, 'social_menu' ), 10 );
        add_action( 'marketify_footer_site_info', array( $this, 'contact_address' ), 20 );
        add_action( 'marketify_footer_site_info', array( $this, 'site_info' ), 30 );

        add_action( 'marketify_footer_above', array( $this, 'footer_widget_areas' ) );
    }

    public function footer_widget_areas() {
		if ( ! ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3 ' ) ) ) {
			return;
		}
    ?>
        <div class="footer-widget-areas row">
        <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
            <div class="widget widget--site-footer col-xs-12 col-md-4">
                <?php dynamic_sidebar( 'footer-' . $i ); ?>
            </div>
        <?php endfor; ?>
        </div>
    <?php
    }

    private function has_social_menu() {
        return has_nav_menu( 'social' );
    }

    public function social_menu() {
        if ( ! $this->has_social_menu() ) {
            return;
        }
    ?>
        <div class="<?php echo $this->get_column_class(); ?>">
            <h3 class="widget-title widget-title--site-footer"><?php echo marketify()->template->navigation->get_theme_menu_name( 'social' ); ?></h3>
            <?php
                $social = wp_nav_menu( array(
                    'theme_location'  => 'social',
                    'container_class' => 'footer-social',
                    'items_wrap'      => '%3$s',
                    'depth'           => 1,
                    'echo'            => false,
                    'link_before'     => '<span class="screen-reader-text">',
                    'link_after'      => '</span>',
                ) );

                echo strip_tags( $social, '<a><div><span>' );
            ?>
        </div>
    <?php
    }

    private function has_contact_address() {
        return marketify_theme_mod( 'footer-contact-us-display' );
    }

    public function contact_address() {
        if ( ! $this->has_contact_address() ) {
            return;
        }
    ?>
        <div class="<?php echo $this->get_column_class(); ?>">
            <h3 class="widget-title widget-title--site-footer"><?php echo esc_attr( marketify_theme_mod( 'footer-contact-us-title' ) ); ?></h3>
                    <p>
                    Email: book@funamsterdam.com
                    </p>
                    <p>Whatsapp : +3165 8888 212</p>
                    <p>UK Hotline:
                    020 3868 4144</p>
                    <p>Germany Hotline:
                    0157 359 86 525</p>
                    <p></p>
                    <p>U.S Hotline:
                    +1-888-840-8060</p>
                    <p>
                    The Netherlands:
                    020 8100 285</p>
                    <p>
                    International:
                    +31 20 8100 285</p>
                    <p>
                    Visit us:
                    Spuistraat 74 Amsterdam</p>
            <?php //echo wp_kses_post( marketify_theme_mod( 'footer-contact-us-address' ) ); ?>
        </div>
    <?php
    }

    public function has_site_info() {
        return marketify_theme_mod( 'footer-copyright-display' );
    }

    public function site_info() {
    ?>

                

        <div class="<?php echo $this->get_column_class(); ?>">
            <h3 class="site-title site-title--footer"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php //if ( esc_attr( marketify_theme_mod( 'footer-copyright-logo' ) ) ) : ?>
                    <!--<img src="<?php //echo esc_attr( marketify_theme_mod( 'footer-copyright-logo' ) ); ?>" />-->
					<img src="<?php echo site_url()."/wp-content/uploads/edd/2016/01/cropped-fun-amsterdam-activities.png";?>" />
                <?php //else : ?>
                    <?php //bloginfo( 'name' ); ?>
                <?php //endif; ?>
            </a></h3>
            Made with <i class="fa fa-heart"></i>&nbsp; By Fun Amsterdam in Amsterdam.
<img src="<?php echo site_url();?>/wp-content/uploads/2016/11/payment-methods-small.png" alt="some_text">
            <?php //echo wp_kses_post( marketify_theme_mod( 'footer-copyright-text' ) ); ?>
        </div>
    <?php
    }

    private function get_column_span() {
        $columns = 3;

        if ( ! $this->has_social_menu() ) {
            $columns--;
        }

        if ( ! $this->has_contact_address() ) {
            $columns--;
        }

        if ( ! $this->has_site_info() ) {
            $columns--;
        }

        return floor( 12 / $columns );
    }

    private function get_column_class() {
        return sprintf( 'widget--site-footer col-xs-12 col-sm-6 col-md-%s', $this->get_column_span() );
    }

}
