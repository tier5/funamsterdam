<?php

class Marketify_Customizer_Controls_Download_Single_Video extends Marketify_Customizer_Controls {

    public $controls = array();

    public function __construct() {
        $this->section = 'download-single-video';
        $this->priority = new Marketify_Customizer_Priority(0, 10);

        parent::__construct();

        add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
        add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
    }

    public function add_controls( $wp_customize ) {
        $this->controls = array(
            'download-video-feature-area' => array(
                'label' => __( 'Video Location', 'marketify' ),
                'type'    => 'select',
                'choices' => array(
                    'top' => __( 'Page Header', 'marketify' ),
                    'inline' => __( 'Page Content', 'marketify' )
                ),
                'sanitize_callback' => 'esc_attr'
            ),
            'download-video-feature-image' => array(
                'label' => __( 'Featured Image Display', 'marketify' ),
                'type'    => 'select',
                'choices' => array(
                    'background' => __( 'Header Background', 'marketify' ),
                    'inline' => __( 'Below Video Player', 'marketify' ),
                    'none' => __( 'None', 'marketify' )
                ),
                'sanitize_callback' => 'esc_attr'
            )
        );

        return $this->controls;
    }

}

new Marketify_Customizer_Controls_Download_Single_Video();
