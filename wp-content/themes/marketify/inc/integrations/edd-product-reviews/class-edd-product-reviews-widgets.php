<?php

class Marketify_EDD_Product_Reviews_Widgets extends Marketify_Integration {

    public function __construct() {
        add_action( 'widgets_init', array( $this, 'register_widgets' ) );
    }

    public function register_widgets() {
        register_widget( 'Marketify_Widget_Download_Review_Details' );

        unregister_widget( 'EDD_Reviews_Widget_Reviews' );
        unregister_widget( 'EDD_Reviews_Widget_Featured_Review' );
        unregister_widget( 'EDD_Reviews_Per_Product_Reviews_Widget' );
    }

}
