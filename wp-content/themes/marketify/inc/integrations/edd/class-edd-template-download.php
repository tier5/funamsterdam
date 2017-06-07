<?php

class Marketify_EDD_Template_Download {

    public function __construct() {
        add_action( 'wp_head', array( $this, 'featured_area' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'marketify_entry_before', array( $this, 'download_title' ), 5 );
        add_action( 'marketify_entry_before', array( $this, 'featured_area_header_actions' ), 5 );

        add_action( 'marketify_download_info', array( $this, 'download_price' ), 5 );
        add_action( 'marketify_download_actions', array( $this, 'demo_link' ) );

        add_action( 'marketify_download_entry_meta_before_audio', array( $this, 'featured_audio' ) );

        add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );
        add_filter( 'body_class', array( $this, 'body_class' ) );
    }

    public function post_class( $classes, $class, $post_id ) {
        if( ! $post_id || get_post_type( $post_id ) !== 'download' || is_admin() ) {
            return $classes;
        }

        if ( 'on' == esc_attr( marketify_theme_mod( 'downloads-archives-truncate-title' ) ) ) {
            $classes[] = 'edd-download--truncated-title';
        }

        return $classes;
    }

    public function body_class( $classes ) {
        $format = $this->get_post_format();
        $setting = esc_attr( marketify_theme_mod( "download-{$format}-feature-area" ) );

        $classes[] = 'feature-location-' . $setting;

        return $classes;
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'marketify-download', get_template_directory_uri() . '/js/download/download.js', array( 'marketify' ) );
    }

    public function download_price() {
        global $post;
?>
<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    <span itemprop="price" class="edd_price">
        <?php edd_price( $post->ID ); ?>
    </span>
</span>
<?php
    }

    function demo_link( $download_id = null ) {
        global $post, $edd_options;

        if ( 'download' != get_post_type() ) {
            return;
        }

        if ( ! $download_id ) {
            $download_id = $post->ID;
        }

        $field = apply_filters( 'marketify_demo_field', 'demo' );
        $demo  = get_post_meta( $download_id, $field, true );

        if ( ! $demo ) {
            return;
        }

        $label = apply_filters( 'marketify_demo_button_label', __( 'Demo', 'marketify' ) );

        if ( $post->_edd_cp_custom_pricing ) {
            echo '<br /><br />';
        }

        $class = 'button';

        if ( ! did_action( 'marketify_single_download_content_before' ) ) {
            $class .= ' button--color-white';
        }

        echo apply_filters( 'marketify_demo_link', sprintf( '<a href="%s" class="%s" target="_blank">%s</a>', esc_url( $demo ), $class, $label ) );
    }

    public function get_featured_images() {
        global $post;

        $images  = array();
        $_images = get_post_meta( $post->ID, 'preview_images', true );

        if ( is_array( $_images ) && ! empty( $_images ) ) {
            foreach ( $_images as $image ) {
                $images[] = get_post( $image );
            }
        } else {
            $images = get_attached_media( 'image', $post->ID );
        }

        return apply_filters( 'marketify_download_get_featured_images', $images, $post );
    }

    public function featured_area() {
        global $post;

        if ( ! $post || ! is_singular( 'download' ) ) {
            return;
        }

        $format = get_post_format();

        if ( '' == $format ) {
            $format = 'standard';
        }

        if ( $this->is_format_location( 'top' ) ) {
            add_action( 'marketify_entry_before', array( $this, "featured_{$format}" ), 5 );

            if ( 'standard' != $format && $this->is_format_style( 'inline' ) ) {
                add_action( 'marketify_entry_before', array( $this, 'featured_standard' ), 6 );
            }
        } else {
            add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_' . $format ), 5 );

            if ( method_exists( $this, 'featured_' . $format . '_navigation' ) ) {
                add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_'. $format . '_navigation' ), 7 );
            }

            if ( 'standard' != $format && $this->is_format_style( 'inline' ) ) {
                add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_standard' ), 6 );
                add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_standard_navigation' ), 7 );
            }
        }
    }

    private function get_post_format() {
        global $post;

        if ( ! $post ) {
            return false;
        }

        $format = get_post_format();

        if ( '' == $format ) {
            $format = 'standard';
        }

        return $format;
    }

    public function is_format_location( $location ) {
        if ( ! is_array( $location ) ) {
            $location = array( $location );
        }

        $format = $this->get_post_format();
        $setting = esc_attr( marketify_theme_mod( "download-{$format}-feature-area" ) );

        if ( in_array( $setting, $location ) ) {
            return true;
        }

        return false;
    }

    public function is_format_style( $style ) {
        if ( ! is_array( $style ) ) {
            $style = array( $style );
        }

        $format = $this->get_post_format();
        $setting = esc_attr( marketify_theme_mod( "download-{$format}-feature-image" ) );

        if ( in_array( $setting, $style ) ) {
            return true;
        }

        return false;
    }

    public function download_title() {
        if ( ! is_singular( 'download' ) ) {
            return;
        }

        the_post();
    ?>
        <div class="page-header page-header--download download-header container">
            <h1 class="page-title"><?php the_title(); ?></h1>
    <?php
        rewind_posts();
    }

    public function featured_area_header_actions() {
        if ( ! is_singular( 'download' ) ) {
            return;
        }
    ?>
        <div class="download-header__info download-header__info--actions">
            <?php do_action( 'marketify_download_actions' ); ?>
        </div>

        <div class="download-header__info">
            <?php do_action( 'marketify_download_info' ); ?>
        </div>
    <?php
    }

   public function featured_standard() {
    /*    $images = $this->get_featured_images();
        $before = '<div class="download-gallery">';
        $after  = '</div>';

        $size = apply_filters( 'marketify_featured_standard_image_size', 'large' );

        echo $before;

        if ( empty( $images ) && has_post_thumbnail( get_the_ID() ) ) {
            echo get_the_post_thumbnail( get_the_ID(), $size );
            echo $after;
            return;
        } else {
    ?>
        <?php foreach ( $images as $image ) : ?>
            <div class="download-gallery__image"><a href="<?php echo esc_url( wp_get_attachment_url( $image->ID ) ); ?>"><?php echo wp_get_attachment_image( $image->ID, $size ); ?></a></div>
        <?php endforeach; ?>
    <?php
        }

        echo $after;*/
    }

    public function featured_standard_navigation() {
        $images = $this->get_featured_images();

        if ( empty( $images ) ) {
            return;
        }

        $before = '<div class="download-gallery-navigation ' . ( count ( $images ) > 6 ? 'has-dots' : '' ) . '">';
        $after  = '</div>';

        $size = apply_filters( 'marketify_featured_standard_image_size_navigation', 'thumbnail' );

        if ( count( $images ) == 1 || ( empty( $images ) && has_post_thumbnail( get_the_ID() ) ) ) {
            return;
        } 

        echo $before;

        foreach ( $images as $image ) {
    ?>
        <div class="download-gallery-navigation__image"><?php echo wp_get_attachment_image( $image->ID, $size ); ?></div>
    <?php
        }

        echo $after;
    }

    public function featured_audio() {
        global $post;

        $audio = $this->get_audio();

        // grid preview only needs one
        if ( ! is_singular( 'download' ) ) {
            $audio = array_splice( $audio, 0, 1 );
        }

        if ( empty( $audio ) ) {
            return;
        }

        echo wp_playlist_shortcode( array(
            'id' => $post->ID,
            'ids' => $audio,
            'images' => false,
            'tracklist' => is_singular( 'download' )
        ) );
    }

    private function get_audio() {
        global $post;

        $attachments = get_post_meta( $post->ID, 'preview_files', true );

        if ( ! $attachments ) {
            $attachments = get_attached_media( 'audio', $post->ID );

            if ( ! empty( $attachments ) ) {
                $attachments = wp_list_pluck( $attachments, 'ID' );
            }
        }

        return $attachments;
    }

    public function featured_video() {
        global $post;

        $field = apply_filters( 'marketify_video_field', 'video' );
        $video = get_post_meta( $post->ID, $field, true );

        if ( ! $video )
            return;

        if ( is_array( $video ) ) {
            $video = current( $video );
        }

        $info = wp_check_filetype( $video );

        if ( '' == $info[ 'ext' ] ) {
            global $wp_embed;

            $output = $wp_embed->run_shortcode( '[embed]' . $video . '[/embed]' );
        } else {
            $output = do_shortcode( sprintf( '[video %s="%s"]', $info[ 'ext' ], $video ) );
        }

        echo '<div class="download-video">' . $output . '</div>';
    }

}
