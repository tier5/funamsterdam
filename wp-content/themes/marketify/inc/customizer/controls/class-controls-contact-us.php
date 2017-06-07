<?php

class Marketify_Customizer_Controls_Footer_Contact_Us extends Marketify_Customizer_Controls {

    public $controls = array();

    public function __construct() {
        $this->section = 'contact-us';
        $this->priority = new Marketify_Customizer_Priority(49, 1);

        parent::__construct();

        add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
        add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
    }

    public function add_controls( $wp_customize ) {
        $this->controls = array(
            'footer-contact-us-display' => array(
                'label' => __( 'Display "Contact Us" Section', 'marketify' ),
                'type' => 'checkbox',
                'sanitize_callback' => 'esc_attr'
            ),
            'footer-contact-us-title' => array(
                'label' => __( 'Title', 'marketify' ),
                'type' => 'text',
                'sanitize_callbackk' => 'esc_attr'
            ),
            'footer-contact-us-address' => array(
                'label' => __( 'Contact Address/Information', 'marketify' ),
                'type' => 'textarea',
                'sanitize_callback' => 'wp_kses_post'
            ),
        );

        return $this->controls;
    }

}

new Marketify_Customizer_Controls_Footer_Contact_Us();
