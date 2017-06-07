<?php

class Marketify_Template_Entry {

    public function __construct() {
        apply_filters( 'marketify_entry_author_social', array( $this, 'author_social' ) );
    }

    /**
     * Any custom social profiles that are added should take a full URL.
     */
    public function social_profiles( $user_id = null ) {
        global $post;

        $methods = wp_get_user_contact_methods();
        $social  = array();

        if ( ! $user_id ) {
            $user_id = get_the_author_meta( 'ID', $post->post_author );
        }

        foreach ( $methods as $key => $method ) {
            $url = get_the_author_meta( $key, $user_id );

            if ( ! $url ) {
                continue;
            }

            if ( false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
                $url = apply_filters( 'marketify_contact_method_' . $key . '_url', '' );
            }

            if ( '' != $url ) {
                $social[ $key ] = sprintf( '<a href="%1$s" target="_blank"><i class="ion-social-%2$s"></i></a>', esc_url( $url ), esc_attr( $key ) );
            }
        }

        $social = implode( ' ', $social );
        return $social;
    }

}
