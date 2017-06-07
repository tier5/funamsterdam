<?php

class Marketify_Easy_Digital_Downloads_Cross_Sell_UpSell {

	public function __construct() {
		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_filter( 'edd_csau_show_excerpt', '__return_false' );
		add_filter( 'edd_csau_show_price', '__return_false' );
	}

}
