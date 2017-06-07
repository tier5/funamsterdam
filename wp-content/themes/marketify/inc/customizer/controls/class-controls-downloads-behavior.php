<?php

class Marketify_Customizer_Controls_Downloads_Behavior extends Marketify_Customizer_Controls {

    public $controls = array();

    public function __construct() {
        $this->section = 'downloads-behavior';
        $this->priority = new Marketify_Customizer_Priority(0, 10);

        parent::__construct();

        add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
        add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
    }

    public function add_controls( $wp_customize ) {
        $this->controls = array(
            'download-label-singular' => array(
                'label' => __( 'Singular Download Label', 'marketify' ),
                'type' => 'text',
                'sanitize_callback' => 'esc_attr'
            ),
            'download-label-plural' => array(
                'label' => __( 'Plural Download Label', 'marketify' ),
                'type' => 'text',
                'sanitize_callback' => 'esc_attr'
            ),
            'download-label-generate' => array(
                'label' => __( 'Generate Permalinks', 'marketify' ),
                'type' => 'checkbox',
                'description' => sprintf( __( 'Use these labels to create updated permalinks. Visit <a href="%s">Settings &rarr; Permalinks</a> once saved.', 'marketify' ), admin_url( 'options-permalink.php' ) )
            ),
        );

        return $this->controls;
    }

}

new Marketify_Customizer_Controls_Downloads_Behavior();
