<?php

function marketify_get_theme_mod_defaults() {
    $mods = array(
        // Colors
        'color-page-header-background' => '#515a63',
        'color-footer-dark-background' => '#515a63',
        'color-primary' => '#515a63',
        'color-accent' => '#50ca8b',

        // Downloads
        'download-label-singular' => 'Download',
        'download-label-plural' => 'Downloads',
        'download-label-generate' => 'on',

        'downloads-archives-popular' => 'on',
        'downloads-archives-excerpt' => '',
        'downloads-archives-truncate-title' => 'on',
        'downloads-archives-meta' => 'auto',
        'downloads-archives-grid-height' => 520,
        'downloads-archives-per-page' => 12,
        'downloads-archives-columns' => 3,

        'download-standard-feature-area' => 'top',
        'download-standard-feature-image' => 'inline',

        'download-audio-feature-area' => 'top',
        'download-audio-feature-image' => 'background',

        'download-video-feature-area' => 'top',
        'download-video-feature-image' => 'background',

        // Footer
        'footer-style' => 'light',

        'footer-contact-us-display' => 'on',
        'footer-contact-us-title' => 'Contact Us',
        'footer-contact-us-address' => "393 Bay Street, 2nd Floor\nToronto, Ontario, Canada, L9T8S2",

        'footer-copyright-display' => 'on',
        'footer-copyright-logo' => '',
        'footer-copyright-text' => sprintf( 'Copyright &copy; %s %s', date( 'Y' ), get_bloginfo( 'name' ) )
    );

    return apply_filters( 'marketify_theme_mod_defaults', $mods );
}

function marketify_theme_mod( $key ) {
    $mods = marketify_get_theme_mod_defaults();

    $default = isset( $mods[ $key ] ) ? $mods[ $key ] : '';

    return get_theme_mod( $key, $default );
}
