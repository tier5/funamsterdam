<?php

class Marketify_EDD_Product_Reviews extends Marketify_Integration {

    public function __construct() {
        $this->includes = array(
            'widgets/class-widget-download-review-details.php',
            'class-edd-product-reviews-widgets.php'
        );

        parent::__construct( dirname( __FILE__ ) );
    }

    public function setup_actions() {
    }

    public function init() {
        $this->widgets = new Marketify_EDD_Product_Reviews_Widgets();
    }

}
