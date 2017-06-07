<?php

class Marketify_WooThemes_Features extends Marketify_Integration {

    public function __construct() {
        parent::__construct( dirname( __FILE__ ) );
    }


    public function setup_actions() {
        add_filter( 'woothemes_features_default_args', array( $this, 'default_args' ) );
        add_filter( 'woothemes_features_html', array( $this, 'add_columns' ) );
    }

    public function default_args( $args ) {
        $args[ 'link_title' ] = false;

        return $args;
    }

    public function add_columns( $html ) {
        $html = str_replace( '<div class="features', '<div data-columns class="features', $html );

        return $html;
    }

}
