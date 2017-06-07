<?php

class Marketify_EDD_Wish_Lists extends Marketify_Integration {

    public function __construct() {
        parent::__construct( dirname( __FILE__ ) );
    }

    public function setup_actions() {
        add_filter( 'facetwp_is_main_query', array( $this, 'facetwp_is_main_query' ), 10, 2 );
        add_action( 'marketify_product_details_after', array( $this, 'add_button' ), 20 );
    }

    public function add_button() {
?>
<div class="widget-detail">
    <?php echo edd_wl_load_wish_list_link(); ?>
</div>
<?php
    }

    public function facetwp_is_main_query( $is_main_query, $query ) {
        if ( isset( $query->query_vars['post_type'] ) ) {
            if ( 'edd_wish_list' == $query->query_vars['post_type'] ) {
                $is_main_query = false;
            }
        }

        return $is_main_query;
    }

}
