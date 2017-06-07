<?php

class Marketify_Customizer_Panels {

    public function __construct() {
        $this->priority = new Marketify_Customizer_Priority(0, 10);

        add_action( 'customize_register', array( $this, 'register_panels' ), 9 );
        add_action( 'customize_register', array( $this, 'organize_appearance' ), 11 );
    }

    public function panel_list() {
        $this->panels = array();

        $this->panels[ 'general' ] = array(
            'title' => __( 'General', 'marketify' ),
            'sections' => array(
            )
        );

        $this->panels[ 'appearance' ] = array(
            'title' => __( 'Appearance', 'marketify' ),
            'sections' => array(
                'colors' => array(
                    'title' => __( 'Colors', 'marketify' ),
                ),
                'footer' => array(
                    'title' => __( 'Footer', 'marketify' ),
                    'priority' => 100
                )
            )
        );

        if ( marketify()->get( 'edd' ) ) {
            $this->panels[ 'downloads' ] = array(
                'title' => edd_get_label_plural(),
                'sections' => array(
                    'downloads-behavior' => array(
                        'title' => __( 'Labels & Behavior', 'marketify' ),
                    ),
                    'download-archives' => array(
                        'title' => __( 'Shop', 'marketify' )
                    ),
                    'download-single-standard' => array(
                        'title' => sprintf( __( 'Standard %s', 'marketify' ), edd_get_label_singular() )
                    ),
                    'download-single-audio' => array(
                        'title' => sprintf( __( 'Audio %s', 'marketify' ), edd_get_label_singular() )
                    ),
                    'download-single-video' => array(
                        'title' => sprintf( __( 'Video %s', 'marketify' ), edd_get_label_singular() )
                    )
                )
            );
        }

        $this->panels[ 'footer' ] = array(
            'title' => __( 'Footer', 'marketify' ),
            'sections' => array(
                'contact-us' => array(
                    'title' => __( 'Contact Us', 'marketify' ),
                ),
                'copyright' => array(
                    'title' => __( 'Copyright', 'marketify' ),
                )
            )
        );

        return $this->panels;
    }

    public function register_panels( $wp_customize ) {
        $panels = $this->panel_list();

        foreach ( $panels as $key => $panel ) {
            $defaults = array(
                'priority' => $this->priority->next()
            );

            $panel = wp_parse_args( $defaults, $panel );

            $wp_customize->add_panel( $key, $panel );

            $sections = isset( $panel[ 'sections' ] ) ? $panel[ 'sections' ] : false;

            if ( $sections ) {
                $this->add_sections( $key, $sections, $wp_customize );
            }
        }
    }

    public function add_sections( $panel, $sections, $wp_customize ) {
        foreach ( $sections as $key => $section ) {
            $wp_customize->add_section( $key, array(
                'title' => $section[ 'title' ],
                'panel' => $panel,
                'priority' => isset( $section[ 'priority' ] ) ? $section[ 'priority' ] : $this->priority->next(),
                'description' => isset( $section[ 'description' ] ) ? $section[
                'description' ] : ''
            ) );

            include_once( dirname( __FILE__ ) . '/controls/class-controls-' . $key . '.php' );
        }
    }

    public function organize_appearance( $wp_customize ) {
        $wp_customize->get_section( 'colors' )->panel = 'appearance';
		$wp_customize->get_section( 'background_image' )->panel = 'appearance';
		$wp_customize->get_section( 'title_tagline' )->panel = 'appearance';
		$wp_customize->get_section( 'header_image' )->panel = 'appearance';

		$wp_customize->get_section( 'header_image' )->title = __( 'Site Logo', 'marketify' );
        $wp_customize->get_control( 'header_textcolor' )->label = __( 'Header & Navigation Text Color', 'marketify' );

        return $wp_customize;
    }

}
