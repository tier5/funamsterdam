<?php

class Marketify_Customizer_Controls_Footer_Copyright extends Marketify_Customizer_Controls {

    public $controls = array();

    public function __construct() {
        $this->section = 'copyright';
        $this->priority = new Marketify_Customizer_Priority(49, 1);

        parent::__construct();

        add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
        add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
    }

    public function add_controls( $wp_customize ) {
        $this->controls = array(
            'footer-copyright-display' => array(
                'label' => __( 'Display "Copyright" Section', 'marketify' ),
                'type' => 'checkbox'
            ),
            'footer-copyright-logo' => array(
                'label' => __( 'Logo', 'marketify' ),
                'type' => 'WP_Customize_Image_Control',
                'sanitize_callback' => 'esc_url'
            ),
            'footer-copyright-text' => array(
                'label' => __( 'Copyright', 'marketify' ),
                'type' => 'textarea',
                'sanitize_callback' => 'wp_kses_post'
            ),
        );

        return $this->controls;
    }

}

new Marketify_Customizer_Controls_Footer_Copyright();
