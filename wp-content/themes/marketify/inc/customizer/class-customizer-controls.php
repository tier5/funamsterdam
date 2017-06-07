<?php

class Marketify_Customizer_Controls {

    public $section;

    public function __construct() {	
        if ( ! isset( $this->priority ) ) {
            $this->priority = new Marketify_Customizer_Priority();
        }
    }

    public function set_controls( $wp_customize ) {
        foreach ( $this->controls as $key => $control ) {
            $defaults = array(
                'priority' => $this->priority->next(),
                'type' => 'text',
                'section' => $this->section
            );

            $control = wp_parse_args( $control, $defaults );

            $wp_customize->add_setting( $key, array(
                'default' => marketify_theme_mod( $key ),
                'sanitize_callback' => isset( $control[ 'sanitize_callback' ] ) ? $control[ 'sanitize_callback' ] : null
            ) );

            if ( class_exists( $control[ 'type' ] ) ) { 
                $type = $control[ 'type' ];

                unset( $control[ 'type' ] );

                $wp_customize->add_control( new $type(
                    $wp_customize,
                    $key,
                    $control
                ) );
            } else {
                $wp_customize->add_control( $key, $control );
            }
        }
    }
}
