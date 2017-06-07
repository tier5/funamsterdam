<?php

class Marketify_TGMPA {

    public function __construct() {
        require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

        add_action( 'tgmpa_register', array( $this, 'register_plugins' ) );
    }

    public function register_plugins() {
        $plugins = array(
            array( 
                'name'      => 'Envato WordPress Toolkit',
                'slug'      => 'envato-wordpress-toolkit',
                'source'    => 'https://github.com/envato/envato-wordpress-toolkit/archive/master.zip',
                'external_url' => 'https://github.com/envato/envato-wordpress-toolkit',
                'required'  => false
            ),
            array(
                'name'      => 'Easy Digital Downloads',
                'slug'      => 'easy-digital-downloads',
                'required'  => true,
            ),
            array(
                'name'      => 'Easy Digital Downloads - Featured Downloads',
                'slug'      => 'edd-featured-downloads',
                'required'  => false,
            ),
            array(
                'name'      => 'Jetpack',
                'slug'      => 'jetpack',
                'required'  => false,
            ),
            array(
                'name'      => 'Features by WooThemes',
                'slug'      => 'features-by-woothemes',
                'required'  => false
            ),
            array(
                'name'      => 'Testimonials by WooThemes',
                'slug'      => 'testimonials-by-woothemes',
                'required'  => false
            ),
            array(
                'name'      => 'Multiple Post Thumbnails',
                'slug'      => 'multiple-post-thumbnails',
                'required'  => false
            ),
            array(
                'name'      => 'Nav Menu Roles',
                'slug'      => 'nav-menu-roles',
                'required'  => false,
            ),
            array(
                'name'      => 'WordPress Importer',
                'slug'      => 'wordpress-importer',
                'required'  => false 
            ),
            array(
                'name'      => 'Widget Importer & Exporter',
                'slug'      => 'widget-importer-exporter',
                'required'  => false,
            )
        );

        $config = array(
            'id'          => 'tgmpa-marketify-' . get_option( 'marketify_version', '2.0.0' ),
            'has_notices' => false
        );

        tgmpa( $plugins, $config );
    }

}
