<?php

class Marketify_Template_Page_Header_Video {

    public function __construct() {
        add_action( 'marketify_register_meta', array( $this, 'register_meta' ) );

        add_action( 'marketify_entry_before', array( $this, 'page_title_video' ), 19 ); // right before closing outer div

        if ( ! is_admin() ) {
            return;
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
        add_action( 'admin_menu', array( $this, 'add_meta_box' ) );
        add_action( 'marketify_save_page_meta', array( $this, 'save_meta_box' ) );
    }

    public function admin_enqueue_styles () {
        global $pagenow, $post;

        if ( 
            ! ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) ) || 
            ( isset( $_GET[ 'post_type' ] ) && 'page' != $_GET[ 'post_type' ] ) ||
            'page' != $post->post_type
        ) {
            return;
        }

        wp_enqueue_script( 'marketify-page-header-video-admin', get_template_directory_uri() . '/js/page-header-video/page-header-video-admin.js', array( 'jquery' ) );
        wp_localize_script( 'marketify-page-header-video-admin', 'marketifyPageHeaderVideo',
            $this->get_localization()
        );
    }

    private function get_localization() {
        return array();
    }

    public function register_meta() {
        register_meta( 'post', 'video_url', 'esc_url' );
    }

    public function add_meta_box() {
        add_meta_box( 'marketify-settings', __( 'Video Header URL', 'marketify' ), array( $this, 'meta_box_settings' ), 'page', 'normal' );
    }

    public function save_meta_box( $post ) {
        $video_url = isset( $_POST[ 'video_url' ] ) ? esc_url( $_POST[ 'video_url' ] ) : '';

        update_post_meta( $post->ID, 'video_url', $video_url );
    }

    public function meta_box_settings() {
        $video_url = $this->get_video_url();
?>

<p>
    <input type="text" name="video_url" value="<?php echo esc_url( $video_url ); ?>" class="widefat" style="width: 100%;" />
</p>

<?php
    }

    public function page_title_video() {
        if ( ! is_singular( 'page' ) ) {
            return;
        }

        add_filter( 'wp_video_shortcode_library', '__return_false' );

        echo $this->get_video_shortcode();

        remove_filter( 'wp_video_shortcode_library', '__return_false' );
    }

    public function get_video_shortcode() {
        return wp_video_shortcode( apply_filters( 'marketify_page_header_video_shortcode', array(
            'src' => $this->get_video_url(),
            'controls' => false,
            'autoplay' => true,
            'loop' => true,
            'preload' => 'auto',
        ) ) );
    }

    public function get_video_url( $post = false ) {
        if ( ! $post ) {
            $post = get_post();
        }

        return $post->video_url;
    }

}
