<?php
/**
 * Plugin Name: Top Bar PRO
 * Plugin URI: http://wpdarko.com/top-bar-pro/
 * Description: Simply the easiest way to add a topbar to your website. This plugin adds a simple and clean notification bar at the top your website, allowing you to display a nice message to your visitors. Find support and information on the <a href="http://wpdarko.com/top-bar-pro/">plugin's page</a>.
 * Version: 1.2
 * Author: WP Darko
 * Author URI: http://wpdarko.com
 * Text Domain: top-bar
 * Domain Path: /lang/
 * License: GPL2
 */


 // Loading text domain
 add_action( 'plugins_loaded', 'tpbrp_load_plugin_textdomain' );
 function tpbrp_load_plugin_textdomain() {
   load_plugin_textdomain( 'top-bar', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
 }


//Checking for updates
define( 'tpbr_STORE_URL', 'https://wpdarko.com' );
define( 'tpbr_ITEM_NAME', 'Top Bar' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/inc/darko_updater/darko_updater.php' );
}

function tpbr_sl_plugin_updater() {

	$license_key = trim( get_option( 'tpbr_license_key' ) );

	$tpbr_updater = new EDD_SL_Plugin_Updater( tpbr_STORE_URL, __FILE__, array(
			'version' 	=> '1.2',
			'license' 	=> $license_key,
			'item_name' => tpbr_ITEM_NAME,
			'author' 	=> 'WP Darko'
		)
	);

}
add_action( 'admin_init', 'tpbr_sl_plugin_updater', 0 );

//Adding license menu
function tpbr_license_menu() {
    add_submenu_page( 'plugins.php', 'Top Bar PRO', __( 'Top Bar license', 'top-bar' ), 'manage_options', 'topbar-license', 'tpbr_license_page' );
}
add_action('admin_menu', 'tpbr_license_menu');

function tpbr_license_page() {
	$license 	= get_option( 'tpbr_license_key' );
	$status 	= get_option( 'tpbr_license_status' );
	?>
	<div class="wrap">
        <div style="background:white; padding:0px 20px; margin-top:20px; padding-top:10px;">
        <h2>Top Bar</h2>
        <h3 style="color:lightgrey;"><span style="color:lightgrey;" class="dashicons dashicons-admin-network"></span>&nbsp; <?php echo __( 'License activation', 'top-bar' );?> </h2>
        <p style="color:grey;"><?php echo __( 'Activating your license allows you to receive automatic updates for a year.<br/>This is <strong>NOT</strong> required for the plugin to work correctly.', 'top-bar' );?></p>
		<form method="post" action="options.php">

			<?php settings_fields('tpbr_license'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php echo __( 'License key', 'top-bar' );?>
						</th>
						<td>
							<input id="tpbr_license_key" name="tpbr_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<label class="description" for="tpbr_license_key"></label>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php echo __( 'Activate license', 'top-bar' );?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green; line-height:29px; margin-right:20px;"><span style="line-height:30px;" class="dashicons dashicons-yes"></span> <?php echo __( 'Activated', 'top-bar' );?></span>
									<?php wp_nonce_field( 'tpbr_nonce', 'tpbr_nonce' ); ?>
									<input type="submit" class="button-secondary" name="tpbr_license_deactivate" value="<?php echo __( 'Deactivate license', 'top-bar' );?>"/>
								<?php } else {
									wp_nonce_field( 'tpbr_nonce', 'tpbr_nonce' ); ?>
									<input type="submit" class="button-secondary" name="tpbr_license_activate" value="<?php echo __( 'Activate license', 'top-bar' );?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
        </div>
	<?php
}

function tpbr_register_option() {
	register_setting('tpbr_license', 'tpbr_license_key', 'tpbr_sanitize_license' );
}
add_action('admin_init', 'tpbr_register_option');

function tpbr_sanitize_license( $new ) {
	$old = get_option( 'tpbr_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'tpbr_license_status' );
	}
	return $new;
}

function tpbr_activate_license() {

	if( isset( $_POST['tpbr_license_activate'] ) ) {

	 	if( ! check_admin_referer( 'tpbr_nonce', 'tpbr_nonce' ) )
			return;

		$license = trim( get_option( 'tpbr_license_key' ) );

		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( tpbr_ITEM_NAME ),
			'url'       => home_url()
		);

		$response = wp_remote_get( add_query_arg( $api_params, tpbr_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( 'tpbr_license_status', $license_data->license );
	}
}
add_action('admin_init', 'tpbr_activate_license');

function tpbr_deactivate_license() {

	if( isset( $_POST['tpbr_license_deactivate'] ) ) {

	 	if( ! check_admin_referer( 'tpbr_nonce', 'tpbr_nonce' ) )
			return;

		$license = trim( get_option( 'tpbr_license_key' ) );

		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( tpbr_ITEM_NAME ),
			'url'       => home_url()
		);

		$response = wp_remote_get( add_query_arg( $api_params, tpbr_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if( $license_data->license == 'deactivated' )
			delete_option( 'tpbr_license_status' );

	}
}
add_action('admin_init', 'tpbr_deactivate_license');

/* --- Enqueue plugin stylsheet --- */
add_action( 'wp_enqueue_scripts', 'add_topbarp_style' );

function add_topbarp_style() {
		wp_enqueue_style( 'topbar', plugins_url('css/topbar_style.css', __FILE__));
    wp_enqueue_script( 'topbar_cookiejs', plugins_url('js/jquery.cookie.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script( 'topbar_frontjs', plugins_url('js/tpbr_front.min.js', __FILE__), array( 'jquery' ));

    if ( is_admin_bar_showing() ) {
        $tpbr_is_admin_bar = 'yes';
    } else {
        $tpbr_is_admin_bar = 'no';
    }

    if ( is_user_logged_in() ) {
        $who_match = 'loggedin';
    } else {
        $who_match = 'notloggedin';
    }

    // getting the options
    $tpbr_close_url = plugins_url('images/close.png', __FILE__);
    $tpbr_initial_state = get_option('tpbr_initial_state');
    $tpbr_fontsize = get_option('tpbr_fontsize');
    $tpbr_guests_or_users = get_option('tpbr_guests_or_users');
    $tpbr_yn_close = get_option('tpbr_yn_close');
    $tpbr_fixed = get_option('tpbr_fixed');
    $tpbr_delay = get_option('tpbr_delay');
    $tpbr_message = get_option('tpbr_message');
    $tpbr_status = get_option('tpbr_status');
    $tpbr_yn_button = get_option('tpbr_yn_button');
    $tpbr_color = get_option('tpbr_color');
    $tpbr_button_text = get_option('tpbr_btn_text');
    $tpbr_button_url = get_option('tpbr_btn_url');
    $tpbr_yn_border = get_option('tpbr_yn_border');
    $tpbr_settings = array(
        'initial_state' => $tpbr_initial_state,
        'user_who' => $who_match,
        'fixed' => $tpbr_fixed,
        'guests_or_users' => $tpbr_guests_or_users,
        'yn_close' => $tpbr_yn_close,
        'fontsize' => $tpbr_fontsize,
        'delay' => $tpbr_delay,
        'border' => $tpbr_yn_border,
        'message' => $tpbr_message,
        'status' => $tpbr_status,
        'yn_button' => $tpbr_yn_button,
        'color' => $tpbr_color,
        'button_text' => $tpbr_button_text,
        'button_url' => $tpbr_button_url,
        'is_admin_bar' => $tpbr_is_admin_bar,
        'close_url' => $tpbr_close_url,
    );
    // sending the options to the js file
    wp_localize_script( 'topbar_frontjs', 'tpbr_settings', $tpbr_settings );
}

/* --- Enqueue plugin stylsheet --- */
add_action( 'admin_enqueue_scripts', 'add_admin_topbarp_style' );

function add_admin_topbarp_style() {
		$screen = get_current_screen();
		if ($screen->base == 'toplevel_page_topbar-pro/topbar_pro'){
				wp_enqueue_style( 'topbar', plugins_url('css/admin_topbar_style.min.css', __FILE__));
		    wp_enqueue_script( 'topbar_cpjs', plugins_url('js/tpbr.js', __FILE__), array( 'jquery' ));
		    wp_enqueue_script( 'topbar_cpljs', plugins_url('js/jquery.tinycolorpicker.min.js', __FILE__), array( 'jquery' ));
		}
}


// create custom plugin settings menu
add_action('admin_menu', 'tpbrp_create_menu');
function tpbrp_create_menu() {

	//create new top-level menu
	add_menu_page('Top Bar', 'Top Bar', 'administrator', __FILE__, 'tpbrp_settings_page', 'dashicons-admin-generic');

	//call register settings function
	add_action( 'admin_init', 'register_tpbrp_settings' );
}


function register_tpbrp_settings() {
	//register our settings
    register_setting( 'tpbr-settings-group', 'tpbr_fixed' );
    register_setting( 'tpbr-settings-group', 'tpbr_initial_state' );
    register_setting( 'tpbr-settings-group', 'tpbr_guests_or_users' );
    register_setting( 'tpbr-settings-group', 'tpbr_yn_close' );
    register_setting( 'tpbr-settings-group', 'tpbr_fontsize' );
    register_setting( 'tpbr-settings-group', 'tpbr_delay' );
	register_setting( 'tpbr-settings-group', 'tpbr_status' );
    register_setting( 'tpbr-settings-group', 'tpbr_yn_button' );
	register_setting( 'tpbr-settings-group', 'tpbr_color' );
	register_setting( 'tpbr-settings-group', 'tpbr_message' );
    register_setting( 'tpbr-settings-group', 'tpbr_btn_text' );
    register_setting( 'tpbr-settings-group', 'tpbr_btn_url' );
    register_setting( 'tpbr-settings-group', 'tpbr_yn_border' );
    register_setting( 'tpbr-settings-group', 'tpbr_close_url' );
}

function tpbrp_settings_page() {
?>

<div class="tpbr_wrap">
    <div class="tpbr_inner">
        <h2>Top Bar <span style='color:lightgrey;'>— <?php echo __( 'options', 'top-bar' );?></span></h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'tpbr-settings-group' ); ?>
            <?php do_settings_sections( 'tpbr-settings-group' ); ?>

            <h4><?php echo __( 'Top Bar status', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'You can activate/deactivate your topbar at anytime.', 'top-bar' );?></p>
            <?php $current_status = esc_attr( get_option('tpbr_status') ); ?>
            <select name="tpbr_status">
                <?php if ($current_status == 'active') { ?>
                    <option value="active" selected><?php echo __( 'Active', 'top-bar' );?></option>
                    <option value="inactive"><?php echo __( 'Inactive', 'top-bar' );?></option>
                <?php } else if ($current_status == 'inactive') { ?>
                    <option value="inactive" selected><?php echo __( 'Inactive', 'top-bar' );?></option>
                    <option value="active"><?php echo __( 'Active', 'top-bar' );?></option>
                <?php } else { ?>
                    <option value="inactive" selected><?php echo __( 'Inactive', 'top-bar' );?></option>
                    <option value="active"><?php echo __( 'Active', 'top-bar' );?></option>
                <?php } ?>
            </select>
            <?php if ($current_status == 'active') { ?>
                <div class='tpbr_led_green'></div>
            <?php } else if ($current_status == 'inactive') { ?>
                <div class='tpbr_led_red'></div>
            <?php } else { ?>
                <div class='tpbr_led_red'></div>
            <?php } ?>

            <h4><?php echo __( 'Always visible?', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'If this is set to \'Yes\' the top bar will stick to the top of the screen.', 'top-bar' );?></p>
            <?php $current_fixed = esc_attr( get_option('tpbr_fixed') ); ?>
            <select class="tpbr_fixed" name="tpbr_fixed">
                    <?php if ($current_fixed == 'fixed') { ?>
                        <option value="fixed" selected><?php echo __( 'Yes', 'top-bar' );?></option>
                        <option value="notfixed"><?php echo __( 'No', 'top-bar' );?></option>
                    <?php } else if ($current_fixed == 'notfixed') { ?>
                        <option value="notfixed" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="fixed"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } else { ?>
                        <option value="notfixed" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="fixed"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } ?>
            </select>

            <h5>— <?php echo __( 'Message', 'top-bar' );?></h5>
            <input class='tpbr_tx_field' type="text" name="tpbr_message" placeHolder="<?php echo __( 'eg. Check out our new product right now!', 'top-bar' );?>" value="<?php echo esc_attr( get_option('tpbr_message') ); ?>" />
            <div class='tpbr_big_btnbox'>
                <h4><?php echo __( 'Button', 'top-bar' );?></h4>
                <?php $current_status = esc_attr( get_option('tpbr_yn_button') ); ?>
                <select class="tpbr_yn_button" name="tpbr_yn_button">
                    <?php if ($current_status == 'button') { ?>
                        <option value="button" selected><?php echo __( 'Yes', 'top-bar' );?></option>
                        <option value="nobutton"><?php echo __( 'No', 'top-bar' );?></option>
                    <?php } else if ($current_status == 'nobutton') { ?>
                        <option value="nobutton" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="button"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } else { ?>
                        <option value="nobutton" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="button"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } ?>
                </select>
                <div class='tpbr_button_box'>
                    <h5>— <?php echo __( 'Button text', 'top-bar' );?></h5>
                    <input class='tpbr_tx_field' type="text" name="tpbr_btn_text" placeHolder="<?php echo __( 'eg. See product', 'top-bar' );?>" value="<?php echo esc_attr( get_option('tpbr_btn_text') ); ?>" />

                    <h5>— <?php echo __( 'Button URL', 'top-bar' );?></h5>
                    <input class='tpbr_tx_field' type="text" name="tpbr_btn_url" placeHolder="<?php echo __( 'eg. http://wpdarko.com', 'top-bar' );?>" value="<?php echo esc_attr( get_option('tpbr_btn_url') ); ?>" />
                </div>
            </div>

            <h4><?php echo __( 'Add a bottom border', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'Set this to \'Yes\' to add a subtle border below the top bar.', 'top-bar' );?></p>
                <?php $current_status = esc_attr( get_option('tpbr_yn_border') ); ?>
                <select class="tpbr_yn_border" name="tpbr_yn_border">
                    <?php if ($current_status == 'border') { ?>
                        <option value="border" selected><?php echo __( 'Yes', 'top-bar' );?></option>
                        <option value="noborder"><?php echo __( 'No', 'top-bar' );?></option>
                    <?php } else if ($current_status == 'noborder') { ?>
                        <option value="noborder" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="border"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } else { ?>
                        <option value="noborder" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="border"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } ?>
            </select>

            <h4><?php echo __( 'Time before showing', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'Time in ms before the topbar appears (default is 0). 1000 would be 1 second.', 'top-bar' );?></p>
            <input class='tpbr_tx_field' type="text" name="tpbr_delay" placeHolder="<?php echo __( 'eg. 1000', 'top-bar' );?>" value="<?php echo esc_attr( get_option('tpbr_delay', '0') ); ?>" />

            <h4><?php echo __( 'Font-size', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'Font-size in pixels, recommended between 13 and 18 (default is 15).', 'top-bar' );?></p>
            <input class='tpbr_tx_field' type="text" name="tpbr_fontsize" placeHolder="<?php echo __( 'eg. 15', 'top-bar' );?>" value="<?php echo esc_attr( get_option('tpbr_fontsize', '15') ); ?>" />

            <h4><?php echo __( 'Allow closing top bar', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'Set this to \'Yes\' to allow your visitors to close the top bar. This creates a cookie and will disable the top bar for the user until cookies/cache are cleared.', 'top-bar' );?></p>
                <?php $current_status = esc_attr( get_option('tpbr_yn_close') ); ?>
                <select class="tpbr_yn_close" name="tpbr_yn_close">
                    <?php if ($current_status == 'close') { ?>
                        <option value="close" selected><?php echo __( 'Yes', 'top-bar' );?></option>
                        <option value="notclose"><?php echo __( 'No', 'top-bar' );?></option>
                    <?php } else if ($current_status == 'notclose') { ?>
                        <option value="notclose" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="close"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } else { ?>
                        <option value="notclose" selected><?php echo __( 'No', 'top-bar' );?></option>
                        <option value="close"><?php echo __( 'Yes', 'top-bar' );?></option>
                    <?php } ?>
            </select>

            <h4><?php echo __( 'Initial state', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'Should the top bar be displayed or hidden.', 'top-bar' );?></p>
                <?php $current_status = esc_attr( get_option('tpbr_initial_state') ); ?>
                <select class="tpbr_initial_state" name="tpbr_initial_state">
                    <?php if ($current_status == 'open') { ?>
                        <option value="open" selected><?php echo __( 'Open', 'top-bar' );?></option>
                        <option value="close"><?php echo __( 'Close', 'top-bar' );?></option>
                    <?php } else if ($current_status == 'close') { ?>
                        <option value="close" selected><?php echo __( 'Close', 'top-bar' );?></option>
                        <option value="open"><?php echo __( 'Open', 'top-bar' );?></option>
                    <?php } else { ?>
                        <option value="open" selected><?php echo __( 'Open', 'top-bar' );?></option>
                        <option value="close"><?php echo __( 'Close', 'top-bar' );?></option>
                    <?php } ?>
            </select>

            <h4><?php echo __( 'Who can see the top bar?', 'top-bar' );?></h4>
            <p style='color:lightgrey;'><?php echo __( 'Select an option for when the top bar should show. Default is \'All\'.', 'top-bar' );?></p>
                <?php $current_status = esc_attr( get_option('tpbr_guests_or_users') ); ?>
                <select class="tpbr_guests_or_users" name="tpbr_guests_or_users">
                    <?php if ($current_status == 'all') { ?>
                        <option value="all" selected><?php echo __( 'All', 'top-bar' );?></option>
                        <option value="guests"><?php echo __( 'Guests', 'top-bar' );?></option>
                        <option value="users"><?php echo __( 'Registered users', 'top-bar' );?></option>
                    <?php } else if ($current_status == 'guests') { ?>
                        <option value="all"><?php echo __( 'All', 'top-bar' );?></option>
                        <option value="guests" selected><?php echo __( 'Guests', 'top-bar' );?></option>
                        <option value="users"><?php echo __( 'Registered users', 'top-bar' );?></option>
                    <?php } else if ($current_status == 'users') { ?>
                        <option value="all" selected><?php echo __( 'All', 'top-bar' );?></option>
                        <option value="guests"><?php echo __( 'Guests', 'top-bar' );?></option>
                        <option value="users" selected><?php echo __( 'Registered users', 'top-bar' );?></option>
                    <?php } else { ?>
                        <option value="all" selected><?php echo __( 'All', 'top-bar' );?></option>
                        <option value="guests"><?php echo __( 'Guests', 'top-bar' );?></option>
                        <option value="users"><?php echo __( 'Registered users', 'top-bar' );?></option>
                    <?php } ?>
            </select>

            <?php $current_color = esc_attr( get_option('tpbr_color') ); ?>

            <?php if (empty($current_color)) {
                $current_color = '#333333';
            }?>

            <span id="tpbrcc" style='display:none;'><?php echo $current_color; ?></span>
            <br/>
            <h4 style='display:inline-block;'><?php echo __( 'Top Bar color', 'top-bar' );?></h4>
            <div style='position:relative; top:5px; left:14px;display:inline;' id="colorPicker">
                <a style='display:inline-block;' class="color"><div class="colorInner"></div></a>
                <div class="track"></div>
                <ul class="dropdown"><li></li></ul>
                <input name="tpbr_color" value="<?php echo $current_color; ?>" type="hidden" class="colorInput"/>
            </div>

            <?php submit_button(); ?>

        </form>
    </div>
</div>
<?php }

?>
