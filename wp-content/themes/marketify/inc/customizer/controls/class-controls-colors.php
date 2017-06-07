<?php

class Marketify_Customizer_Controls_Colors extends Marketify_Customizer_Controls {

    public $controls = array();

    public function __construct() {
        $this->section = 'colors';
        $this->priority = new Marketify_Customizer_Priority(49, 1);

        parent::__construct();

        add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
        add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
    }

    public function add_controls( $wp_customize ) {
        $this->controls = array(
            'color-page-header-background' => array(
                'label' => __( 'Page Header Background Color', 'marketify' ),
                'type' => 'WP_Customize_Color_Control',
                'sanitize' => 'sanitize_hex_color'
            ),
            'color-primary' => array(
                'label' => __( 'Primary Color', 'marketify' ),
                'type' => 'WP_Customize_Color_Control',
                'sanitize' => 'sanitize_hex_color'
            ),
            'color-accent' => array(
                'label' => __( 'Accent Color', 'marketify' ),
                'type' => 'WP_Customize_Color_Control',
                'sanitize' => 'sanitize_hex_color'
            ),
            'color-footer-dark-background' => array(
                'label' => __( 'Footer Background Color', 'marketify' ),
                'type' => 'WP_Customize_Color_Control',
                'sanitize' => 'sanitize_hex_color'
            ),
        );

        return $this->controls;
    }

}

new Marketify_Customizer_Controls_Colors();
