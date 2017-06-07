<?php
class Marketify_Setup {

    public function __construct() {
        if ( ! is_admin() ) {
            return;
        }

        add_action( 'after_setup_theme', array( $this, 'init' ), 0 );
    }

    public function init() {
        $menus = get_theme_mod( 'nav_menu_locations' );
        $this->theme = marketify()->activation->theme;
        $has_downloads = new WP_Query( array( 'post_type' => 'download', 'fields' => 'ids', 'posts_per_page' => 1 ) );

        $this->steps = array();

        $this->steps[ 'install-plugins' ] = array(
            'title' => __( 'Install Required &amp; Recommended Plugins', 'marketify' ),
            'completed' => class_exists( 'Easy_Digital_Downloads' ),
            'documentation' => array(
                'Easy Digital Downloads' => 'http://marketify.astoundify.com/article/713-easy-digital-downloads',
                'Easy Digital Downloads - Marketplace Bundle' => 'https://astoundify.com/go/marketplace-bundle/',
                'Bulk Install' => 'http://marketify.astoundify.com/article/712-bulk-install-required-and-recommended-plugins-recommended'
            )
        );

        $this->steps[ 'import-content' ] = array(
            'title' => __( 'Import Demo Content', 'marketify' ),
            'completed' => $has_downloads->have_posts(),
            'documentation' => array(
                'Install Demo Content' => 'http://marketify.astoundify.com/article/789-installing-demo-content',
                'Importing Content (Codex)' => 'http://codex.wordpress.org/Importing_Content',
                'WordPress Importer' => 'https://wordpress.org/plugins/wordpress-importer/'
            )
        );

        $this->steps[ 'import-widgets' ] = array(
            'title' => __( 'Import Widgets', 'marketify' ),
            'completed' => is_active_sidebar( 'home-1' ),
            'documentation' => array(
                'Widget Areas' => 'http://marketify.astoundify.com/category/692-widget-areas',
                'Widgets' => 'http://marketify.astoundify.com/category/585-widgets' 
            )
        );

        if ( marketify()->get( 'edd' ) ) { 
            $this->steps[ 'setup-edd' ] = array(
                'title' => 'Setup Easy Digital Downloads',
                'completed' => true == get_option( 'edd_settings' ),
                'documentation' => array(
                    'Documentation' => 'http://docs.easydigitaldownloads.com/',
                    'Shortcodes' => 'http://docs.easydigitaldownloads.com/article/218-short-codes-overview',
                    'FAQs' => 'http://docs.easydigitaldownloads.com/collection/171-faqs'
                )
            );
        }

        if ( marketify()->get( 'edd-fes' ) ) { 
            $this->steps[ 'setup-fes' ] = array(
                'title' => 'Setup Frontend Submissions',
                'completed' => 0 == EDD_FES()->helper->get_option( 'fes-use-css' ),
                'documentation' => array(
                    'Documentation' => 'http://docs.easydigitaldownloads.com/category/330-frontend-submissions',
                    'Setup' => 'http://docs.easydigitaldownloads.com/article/337-frontend-submissions-basic-setup',
                    'Shortcodes' => 'http://docs.easydigitaldownloads.com/article/333-frontend-submissions-short-codes',
                    'FAQs' => 'http://docs.easydigitaldownloads.com/article/331-frontend-submissions-frequently-asked-questions'
                )
            );
        }

        $this->steps[ 'setup-menus' ] = array(
            'title' => __( 'Setup Menus', 'marketify' ),
            'completed' => isset( $menus[ 'primary' ] ),
            'documentation' => array(
                'Primary Menu' => 'http://marketify.astoundify.com/article/700-manage-the-primary-menu',
                'Show/Hide Items' => 'http://marketify.astoundify.com/article/702-show-hide-links-depending-on-the-user',
            )
        );

        $this->steps[ 'setup-homepage' ] = array(
            'title' => __( 'Setup Static Homepage', 'marketify' ),
            'completed' => (bool) get_option( 'page_on_front', false ),
            'documentation' => array(
                'Create Your Homepage' => 'http://marketify.astoundify.com/article/581-creating-your-homepage',
                'Reading Settings (codex)' => 'http://codex.wordpress.org/Settings_Reading_Screen'
            )
        );

        $docs = array(
            'Widget Areas' => 'http://marketify.astoundify.com/category/692-widget-areas',
            'Widgets' => 'http://marketify.astoundify.com/category/585-widgets' 
        );

        if ( marketify()->get( 'woothemes-testimonials' ) ) {
            $docs[ 'Companies We&#39;ve Helped' ]  = 'http://marketify.astoundify.com/article/842-home-companies-weve-helped';
            $docs[ 'Individual Testimonials' ]  = 'http://marketify.astoundify.com/article/843-home-individual-testimonials';
        }

        $this->steps[ 'setup-widgets' ] = array(
            'title' => __( 'Setup Widgets', 'marketify' ),
            'completed' => is_active_sidebar( 'widget-area-front-page' ),
            'documentation' => $docs
        );

        $this->steps[ 'customize-theme' ] = array(
            'title' => __( 'Customize', 'marketify' ),
            'completed' => get_option( 'theme_mods_marketify' ),
            'documentation' => array(
                'Appearance' => 'http://marketify.astoundify.com/collection/463-customization',
                'Child Themes' => 'http://marketify.astoundify.com/category/719-child-themes',
                'Translations' => 'http://marketify.astoundify.com/category/720-translations'
            )
        );

        $this->steps[ 'support-us' ] = array(
            'title' => __( 'Get Involved', 'marketify' ),
            'completed' => 'na',
            'documentation' => array(
                'Leave a Positive Review' => 'https://astoundify.com/go/rate-theme/',
                'Contribute Your Translation' => 'https://astoundify.com/go/translate-marketify/'
            )
        );

        add_action( 'admin_menu', array( $this, 'add_page' ), 100 );
        add_action( 'admin_menu', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
    }

    public function add_page() {
        add_theme_page( __( 'Marketify Setup', 'marketify' ), __( 'Setup Guide', 'marketify' ), 'manage_options', 'marketify-setup', array( $this, 'output' ) );
    }

    public function admin_css() {
        $screen = get_current_screen();
        if ( 'appearance_page_marketify-setup' != $screen->id ) {
            return;
        }
        wp_enqueue_style( 'marketify-setup', get_template_directory_uri() . '/inc/setup/style.css' );
    }

    public function add_meta_boxes() {
        foreach ( $this->steps as $step => $info ) {
            $info = array_merge( array( 'step' => $step ), $info );
            add_meta_box( $step , $info[ 'title' ], array( $this, 'step_box' ), 'marketify_setup_steps', 'normal', 'high', $info );
        }
    }

    public function step_box( $object, $metabox ) {
        $args = $metabox[ 'args' ];
    ?>
        <?php if ( $args[ 'completed' ] === true ) { ?>
            <div class="is-completed"><?php _e( 'Completed!', 'marketify' ); ?></div>
        <?php } elseif ( $args[ 'completed' ] === false || $args[ 'completed' ] == '' ) { ?>
            <div class="not-completed"><?php _e( 'Incomplete', 'marketify' ); ?></div>
        <?php } ?>

        <?php include ( get_template_directory() . '/inc/setup/steps/' . $args[ 'step' ] . '.php' ); ?>

        <?php if ( 'Get Involved' != $args[ 'title' ] ) : ?> 
            <hr />
            <p><?php _e( 'You can read more and watch helpful video tutorials below:', 'marketify' ); ?></p>
        <?php endif; ?>

        <p>
            <?php foreach ( $args[ 'documentation' ] as $title => $url ) { ?>
            <a href="<?php echo esc_url( $url ); ?>" class="button button-secondary"><?php echo esc_attr( $title ); ?></a>&nbsp;
            <?php } ?>
        </p>
    <?php
    }

    public function output() {
    ?>
        <div class="wrap about-wrap marketify-setup">
            <?php $this->welcome(); ?>
            <?php $this->links(); ?>
        </div>

        <div id="poststuff" class="wrap marketify-steps" style="margin: 25px 40px 0 20px">
            <?php $this->steps(); ?>
        </div>
        <script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={modal: true, docs:{enabled:!0,baseUrl:"//astoundify-marketify.helpscoutdocs.com/"},contact:{enabled:!1,formId:"b68bfa79-83ce-11e5-8846-0e599dc12a51"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>
    <?php  
    }

    public function welcome() {
    ?>
        <h1><?php printf( __( 'Welcome to %s', 'marketify' ), esc_attr( $this->theme->Name . ' ' . $this->theme->Version ) ); ?></h1>
        <p class="about-text"><?php printf( __( 'Creating a digital marketplace has never been easier with Marketify&mdash;Use the steps below to start setting up your new website. If you have more questions please <a href="%s">review the documentation</a>.', 'marketify' ), 'http://marketify.astoundify.com' ); ?></p>
        <div class="marketify-badge"><img src="<?php echo get_template_directory_uri(); ?>/inc/setup/images/banner.jpg" width="140" alt="" /></div>
    <?php
    }

    public function links() {
    ?>
        <p class="helpful-links">
            <a href="http://marketify.astoundify.com" class="button button-primary js-trigger-documentation"><?php _e( 'Search Documentation', 'marketify' ); ?></a>&nbsp;
            <a href="http://support.astoundify.com" class="button button-secondary"><?php _e( 'Submit a Support Ticket', 'marketify' ); ?></a>&nbsp;
        </p>
        <script>
            jQuery(document).ready(function($) {
                $('.js-trigger-documentation').click(function(e) {
                    e.preventDefault();
                    HS.beacon.open();
                });
            });
        </script>
    <?php
    }

    public function steps() {
        do_accordion_sections( 'marketify_setup_steps', 'normal', null );
    }
}
