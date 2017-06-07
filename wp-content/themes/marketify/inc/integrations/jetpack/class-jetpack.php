<?php

class Marketify_Jetpack extends Marketify_Integration {

    public function __construct() {
        $this->includes = array(
            'class-jetpack-share.php'
        );

        parent::__construct( dirname( __FILE__ ) );
    }

    public function init() {
        $this->sharing = new Marketify_Jetpack_Share();
    }

}
