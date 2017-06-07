<?php
/**
 * Copyright 2016 Axelerant
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

require_once AIHR_DIR_INC . 'class-aihrus-common.php';

if ( class_exists( 'EDD_Sales_Recovery' ) ) {
	return;
}

class EDD_Sales_Recovery extends Aihrus_Common {
	const BASE    = EDD_SR_BASE;
	const ID      = 'edd-sales-recovery';
	const SLUG    = 'eddsr_';
	const VERSION = EDD_SR_VERSION;

	const CONFIRM_KEY        = 'eddsr_cc';
	const EDD_PT             = 'download';
	const PAYMENT_POST_TYPE  = 'edd_payment';
	const STAGE_FINAL        = 'final';
	const STAGE_INITIAL      = 'initial';
	const STAGE_INTERIM      = 'interim';
	const STATUS_ABANDONED   = 'abandoned';
	const STATUS_IGNORE      = 'ignore';
	const STATUS_PROCESS     = 'process';
	const STATUS_RECOVERED   = 'recovered';
	const STATUS_RECOVERY    = 'recovery';
	const CHEQUE_GATEWAY_KEY = '_edd_payment_gateway';
	const CHEQUE_GATEWAY_VAL = 'checks';

	private static $discount_code;
	private static $discount_expiration;
	private static $discount_text;
	private static $edd;

	public static $class = __CLASS__;
	public static $discount_key;
	public static $email_sent_key;
	public static $error_reason;
	public static $notice_key;
	public static $payment_history_url;
	public static $payment_id;
	public static $payment_key;
	public static $plugin_assets;
	public static $process_page;
	public static $recovery_start_key;
	public static $settings_link;
	public static $settings_link_email;
	public static $slug_pt;
	public static $stage_key;
	public static $status_closed;
	public static $status_key;
	public static $status_recover;
	public static $unsubscribe_key;


	public function __construct() {
		parent::__construct();

		self::$plugin_assets = plugins_url( '/assets/', dirname( __FILE__ ) );
		self::$plugin_assets = self::strip_protocol( self::$plugin_assets );

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'edd_sales_recovery_cron_recover', array( __CLASS__, 'edd_sales_recovery_cron_recover' ) );
		add_action( 'edd_sales_recovery_cron_purge', array( __CLASS__, 'edd_sales_recovery_cron_purge' ) );
		add_action( 'init', array( __CLASS__, 'init' ), 9 );
	}


	public static function admin_menu() {
		global $edd_settings_page;
		add_action( 'admin_print_scripts-' . $edd_settings_page, array( __CLASS__, 'scripts_settings' ) );
	}


	public static function admin_init() {
		self::update();

		add_action( 'edd_eddsr_final_header', array( __CLASS__, 'edd_eddsr_final_header' ) );
		add_action( 'edd_eddsr_initial_header', array( __CLASS__, 'edd_eddsr_initial_header' ) );
		add_action( 'edd_eddsr_interim_header', array( __CLASS__, 'edd_eddsr_interim_header' ) );
		add_action( 'edd_view_order_details_totals_after', array( __CLASS__, 'edd_view_order_details_totals_after' ) );
		add_filter( 'edd_payment_row_actions', array( __CLASS__, 'edd_payment_row_actions' ), 10, 2 );
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );

		self::$settings_link       = '<a href="' . get_admin_url() . 'edit.php?post_type=' . self::EDD_PT . '&page=edd-settings&tab=extensions&section=eddsr_edd_settings_extensions">' . esc_html__( 'Settings', 'edd-sales-recovery' ) . '</a>';
		self::$settings_link_email = '<a href="' . get_admin_url() . 'edit.php?post_type=' . self::EDD_PT . '&page=edd-settings&tab=emails&section=eddsr_edd_settings_emails">' . esc_html__( 'Emails', 'edd-sales-recovery' ) . '</a>';
	}


	public static function init() {
		load_plugin_textdomain( self::ID, false, 'edd-sales-recovery/languages' );

		add_action( 'edd_complete_purchase', array( __CLASS__, 'edd_complete_purchase' ) );
		add_action( 'edd_end_recovery_process', array( __CLASS__, 'edd_end_recovery_process' ) );
		add_action( 'edd_recover_sale', array( __CLASS__, 'edd_recover_sale' ) );
		add_action( 'edd_resend_recovery', array( __CLASS__, 'edd_resend_recovery' ) );
		add_action( 'edd_update_payment_status', array( __CLASS__, 'edd_update_payment_status' ), 10, 3 );
		add_action( 'personal_options_update', array( __CLASS__, 'personal_options_update' ) );
		add_action( 'show_user_profile', array( __CLASS__, 'show_user_profile' ) );
		add_filter( 'edd_email_template_tags', array( __CLASS__, 'edd_email_template_tags' ), 10, 4 );
		add_filter( 'edd_payment_statuses', array( __CLASS__, 'edd_payment_statuses' ) );
		add_filter( 'edd_payments_table_views', array( __CLASS__, 'edd_payments_table_views' ) );
		add_filter( 'edd_settings_emails', array( __CLASS__, 'edd_settings_emails' ), 10, 1 );
		add_filter( 'edd_settings_extensions', array( __CLASS__, 'edd_settings_extensions' ), 10, 1 );
		add_filter( 'edd_settings_sections_emails', array( __CLASS__, 'eddsr_edd_settings_section_emails' ) );
		add_filter( 'edd_settings_sections_extensions', array( __CLASS__, 'eddsr_edd_settings_section_extensions' ) );
		add_filter( 'eddsr_set_edd_payment_statuses_users', array( __CLASS__, 'eddsr_set_edd_payment_statuses_users' ) );
		add_filter( 'edd_recoverable_payment_statuses', array( __CLASS__, 'edd_recoverable_payment_statuses' ) );

		self::$discount_key        = self::SLUG . 'discount_';
		self::$email_sent_key      = self::SLUG . 'email_sent';
		self::$error_reason        = self::SLUG . 'error_reason';
		self::$payment_history_url = admin_url( 'edit.php?post_type=download&page=edd-payment-history' );
		self::$payment_key         = self::SLUG . 'payment_id';
		self::$recovery_start_key  = self::SLUG . 'recovery_start';
		self::$slug_pt             = self::SLUG . 'payment_tracking';
		self::$stage_key           = self::SLUG . 'stage';
		self::$status_closed       = array( 'publish', 'refunded', 'revoked', 'edd_subscription', 'failed', self::STATUS_RECOVERED );
		self::$status_key          = self::SLUG . 'status';
		self::$status_recover      = array( self::STATUS_ABANDONED, 'pending', self::STATUS_RECOVERY );
		self::$unsubscribe_key     = self::SLUG . 'recovery_unsubscribe';

		self::register_post_status();
		self::set_edd();
	}


	/**
	 * Remove unsubscribed users
	 */
	public static function eddsr_set_edd_payment_statuses_users( $users ) {
		$temp_users  = array_flip( $users );
		$unsub_users = get_users( 'meta_key=' . self::$unsubscribe_key );
		foreach ( $unsub_users as $unsub ) {
			unset( $temp_users[ $unsub->ID ] );
		}

		$users = array_flip( $temp_users );

		return $users;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function edd_payments_table_views( $views ) {
		$current         = isset( $_GET['status'] ) ? $_GET['status'] : '';
		$payment_count   = edd_count_payments();
		$recovery_count  = '&nbsp;<span class="count">( ' . $payment_count->recovery . ' )</span>';
		$recovered_count = '&nbsp;<span class="count">( ' . $payment_count->recovered . ' )</span>';

		$link_args                      = array(
			'status' => self::STATUS_RECOVERY,
		);
		$views[ self::STATUS_RECOVERY ] = sprintf( '<a href="%s"%s>%s</a>', add_query_arg( $link_args, self::$payment_history_url ), self::STATUS_RECOVERY == $current ? ' class="current"' : '', __( 'In Recovery', 'edd-sales-recovery' ) . $recovery_count );

		$link_args                       = array(
			'status' => self::STATUS_RECOVERED,
		);
		$views[ self::STATUS_RECOVERED ] = sprintf( '<a href="%s"%s>%s</a>', add_query_arg( $link_args, self::$payment_history_url ), self::STATUS_RECOVERED == $current ? ' class="current"' : '', __( 'Recovered', 'edd-sales-recovery' ) . $recovered_count );

		return $views;
	}


	public static function register_post_status() {
		register_post_status(
			self::STATUS_RECOVERY,
			array(
				'label'                     => _x( 'In Recovery', 'In recovery payment status', 'edd-sales-recovery' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'In Recovery <span class="count">(%s)</span>', 'In Recovery <span class="count">(%s)</span>', 'edd-sales-recovery' ),
			)
		);

		register_post_status(
			self::STATUS_RECOVERED,
			array(
				'label'                     => _x( 'Recovered', 'Recovered payment status', 'edd-sales-recovery' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Recovered <span class="count">(%s)</span>', 'Recovered <span class="count">(%s)</span>', 'edd-sales-recovery' ),
			)
		);
	}


	public static function edd_payment_statuses( $payment_statuses ) {
		$payment_statuses[ self::STATUS_RECOVERY ]  = esc_html__( 'In Recovery', 'edd-sales-recovery' );
		$payment_statuses[ self::STATUS_RECOVERED ] = esc_html__( 'Recovered', 'edd-sales-recovery' );

		return $payment_statuses;
	}


	public static function get_edd_options( $key = null, $default = null ) {
		$edd_options = edd_get_settings();

		if ( is_null( $key ) ) {
			return $edd_options;
		} elseif ( isset( $edd_options[ self::SLUG . $key ] ) ) {

			if ( false !== strpos( $key, '_email' ) && empty( $edd_options[ self::SLUG . $key ] ) ) {
				return self::get_default_options( self::SLUG . $key );
			}

			if ( false !== strpos( $key, '_subject' ) && empty( $edd_options[ self::SLUG . $key ] ) ) {
				return self::get_default_options( self::SLUG . $key );
			}

			return $edd_options[ self::SLUG . $key ];
		} elseif ( isset( $edd_options[ $key ] ) ) {

			if ( false !== strpos( $key, '_email' ) && empty( $edd_options[ $key ] ) ) {
				return self::get_default_options( $key );
			}

			if ( false !== strpos( $key, '_subject' ) && empty( $edd_options[ $key ] ) ) {
				return self::get_default_options( $key );
			}

			return $edd_options[ $key ];
		} else {

			if ( false !== strpos( $key, '_email' ) && empty( $edd_options[ $key ] ) ) {
				return self::get_default_options( $key );
			}

			if ( false !== strpos( $key, '_subject' ) && empty( $edd_options[ $key ] ) ) {
				return self::get_default_options( $key );
			}

			return $default;
		}
	}


	public static function get_default_options( $key = null, $default = null ) {
		$default_options = array();

		$sections = array(
			'edd_settings_emails',
			'edd_settings_extensions',
		);

		foreach ( $sections as $section ) {
			$settings = self::$section( array() );

			// If EDD is at version 2.5 or later...
			if ( version_compare( EDD_VERSION, 2.5, '>=' ) && isset( $settings[ self::SLUG . $section ] ) ) {
				$settings = $settings[ self::SLUG . $section ];
			}

			foreach ( $settings as $setting ) {
				if ( ! isset( $setting['std'] ) ) {
					continue;
				}

				$id  = $setting['id'];
				$std = $setting['std'];

				if ( ! isset( $default_options[ $id ] ) ) {
					$default_options[ $id ] = $std;
				}
			}
		}

		if ( is_null( $key ) ) {
			return $default_options;
		} elseif ( isset( $default_options[ self::SLUG . $key ] ) ) {
			return $default_options[ self::SLUG . $key ];
		} elseif ( isset( $default_options[ $key ] ) ) {
			return $default_options[ $key ];
		} else {
			return $default;
		}
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::BASE == $file ) {
			array_unshift( $links, self::$settings_link_email );
			array_unshift( $links, self::$settings_link );
		}

		return $links;
	}


	public static function activation() {
		if ( ! EDD_Sales_Recovery::version_check( false ) ) {
			return;
		}

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		self::initialize_settings();
		self::set_recovery_start_date();
		self::activate_cron();
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		self::deactivate_cron();
	}


	public static function activate_cron() {
		if ( ! wp_next_scheduled( 'edd_sales_recovery_cron_recover' ) ) {
			wp_schedule_event( time(), 'hourly', 'edd_sales_recovery_cron_recover' );
		}

		if ( ! wp_next_scheduled( 'edd_sales_recovery_cron_purge' ) ) {
			wp_schedule_event( time(), 'daily', 'edd_sales_recovery_cron_purge' );
		}
	}


	public static function deactivate_cron() {
		if ( wp_next_scheduled( 'edd_sales_recovery_cron_recover' ) ) {
			wp_clear_scheduled_hook( 'edd_sales_recovery_cron_recover' );
		}

		if ( wp_next_scheduled( 'edd_sales_recovery_cron_purge' ) ) {
			wp_clear_scheduled_hook( 'edd_sales_recovery_cron_purge' );
		}
	}


	public static function set_recovery_start_date() {
		$recovery_start_date = self::get_edd_options( 'recovery_start_date' );
		if ( empty( $recovery_start_date ) ) {
			self::set_edd_options( 'recovery_start_date', date( 'Y-m-d' ) );
		}
	}


	// delete unused, eddsr discount codes after there expiration
	public static function edd_sales_recovery_cron_purge() {
		$purge_expired = self::get_edd_options( 'discount_purge_expired' );
		if ( empty( $purge_expired ) ) {
			return;
		}

		// grab post_id of discounts having meta_key self::$payment_key
		// and having _edd_discount_expiration that's older than a month
		// and having 0 _edd_discount_uses
		$current_time = current_time( 'timestamp' );
		$expired_time = $current_time + MONTH_IN_SECONDS;
		$expired_time = date( 'Y-m-d H:i:s', $expired_time );

		$args = array(
			'post_type' => 'edd_discount',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => self::$payment_key,
					'compare' => 'EXISTS',
				),
				array(
					'key' => '_edd_discount_uses',
					'compare' => '=',
					'value' => 0,
					'type' => 'NUMERIC',
				),
				array(
					'key' => '_edd_discount_expiration',
					'compare' => '<',
					'value' => $expired_time,
					'type' => 'NUMERIC',
				),
			),
			'fields' => 'ids',
			'posts_per_page' => -1,
		);

		$expired_discounts = new WP_Query( $args );
		// cycle through those discount_id and delete them
		while ( $expired_discounts->have_posts() ) {
			$expired_discounts->the_post();
			$discount_id = get_the_ID();

			$code       = get_post_meta( $discount_id, '_edd_discount_code', true );
			$payment_id = get_post_meta( $discount_id, self::$payment_key, true );

			$text = esc_html__( 'Deleted unused, expired discount code "%1$s"', 'edd-sales-recovery' );
			$text = sprintf( $text, $code );

			edd_insert_payment_note( $payment_id, $text );
			edd_remove_discount( $discount_id );
		}
	}


	public static function edd_sales_recovery_cron_recover() {
		$payment_posts = self::get_recover_sales_ids();

		if ( empty( $payment_posts ) ) {
			return;
		}

		foreach ( $payment_posts as $payment_id ) {
			self::recover_sale( $payment_id );
		}
	}


	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
	}


	public static function scripts_settings() {
		wp_enqueue_script( 'edd-sales-recovery', self::$plugin_assets . 'js/edd-sales-recovery.js', array( 'jquery' ), EDD_SR_VERSION );
	}


	public static function edd_payment_row_actions( $row_actions, $payment ) {
		$link_args = array(
			'edd-action' => 'resend_recovery',
			'purchase_id' => $payment->ID,
			self::CONFIRM_KEY => self::create_nonce( 'resend_recovery' ),
		);

		if ( self::STATUS_RECOVERY == $payment->post_status ) {
			$link = add_query_arg( $link_args, self::$payment_history_url );

			$row_actions['resend_recovery'] = '<a href="' . $link . '">' . esc_html__( 'Resend Recovery Email', 'edd-sales-recovery' ) . '</a>';

			$confirm   = self::get_confirm_code( $payment->ID );
			$link_args = array(
				'edd-action' => 'end_recovery_process',
				'purchase_id' => $payment->ID,
				self::CONFIRM_KEY => $confirm,
			);

			$link = add_query_arg( $link_args, self::$payment_history_url );

			$row_actions['stop_recovery'] = '<a href="' . $link . '">' . esc_html__( 'Stop Recovery', 'edd-sales-recovery' ) . '</a>';
		} elseif ( self::is_sale_recoverable( $payment->ID ) ) {
			$link_args['edd-action']        = 'recover_sale';
			$link_args[ self::CONFIRM_KEY ] = self::create_nonce( 'recover_sale' );
			$link                           = add_query_arg( $link_args, self::$payment_history_url );

			$row_actions['recover_sale'] = '<a href="' . $link . '">' . esc_html__( 'Initiate Sale Recovery', 'edd-sales-recovery' ) . '</a>';
		}

		return $row_actions;
	}


	public static function eddsr_edd_settings_section_extensions( $sections ) {
		$sections['eddsr_edd_settings_extensions'] = __( 'Sales Recovery', 'edd-sales-recovery' );

		return $sections;
	}


	public static function eddsr_edd_settings_section_emails( $sections ) {
		$sections['eddsr_edd_settings_emails'] = __( 'Sales Recovery', 'edd-sales-recovery' );

		return $sections;
	}


	public static function edd_settings_extensions( $edd_settings ) {
		$settings[] = array(
			'id' => self::SLUG . 'header',
			'name' => '<h3 id="EDD_Sales_Recovery">' . esc_html__( 'Sales Recovery', 'edd-sales-recovery' ) . '</h3>',
			'type' => 'header',
		);

		$pages         = get_pages();
		$pages_options = array( 0 => '' ); // Blank option
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}

		$settings[] = array(
			'id' => self::SLUG . 'contact_link',
			'name' => esc_html__( 'Contact Page Link', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'This is a feedback page for users to contact you.', 'edd-sales-recovery' ),
			'type' => 'select',
			'options' => $pages_options,
		);

		$settings[] = array(
			'id' => self::SLUG . 'unsubscribe_link',
			'name' => esc_html__( 'Unsubscribe Page Link', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'This is the sales recovery unsubscribe or user profile page.', 'edd-sales-recovery' ),
			'type' => 'select',
			'options' => $pages_options,
		);

		$settings[] = array(
			'id' => self::SLUG . 'recovery_start_date',
			'name' => esc_html__( 'Exclude processing of sales before', 'edd-sales-recovery' ),
			'desc' => esc_html__( '( YYYY-MM-DD ) Sales which are older than inputed date will be excluded while processing sales recovery attempt.', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => date( 'Y-m-d' ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'discount_purge_expired',
			'name' => esc_html__( 'Purge Expired Discounts', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'If enabled, a daily cron will delete unused, expired EDD Sales Recovery generated discount codes.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'reset_eddsr_status',
			'name' => esc_html__( 'Reset Recovery Status', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'If enabled, checkouts with `ignored` recovery status will be reset for potential recovery actions.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_header',
			'name' => '<strong>' . esc_html__( 'Initial Attempt', 'edd-sales-recovery' ) . '</strong>',
			'type' => 'header',
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_enable',
			'name' => esc_html__( 'Enabled?', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Check this to enable initial sales recovery attempt.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_period',
			'name' => esc_html__( 'Hours to Wait', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Number of hours to wait before first sales recovery attempt.', 'edd-sales-recovery' ),
			'type' => 'text',
			'size' => 'small',
			'std' => 2,
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_discount',
			'name' => esc_html__( 'Discount Percentage', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Incentive offer to complete transaction. Number is converted to percentage. Expires at beginning of first interim period. Ex: 10 becomes 10%. Leave blank for none.', 'edd-sales-recovery' ),
			'type' => 'text',
			'size' => 'small',
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_header',
			'name' => '<strong>' . esc_html__( 'Interim Attempts', 'edd-sales-recovery' ) . '</strong>',
			'type' => 'header',
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_enable',
			'name' => esc_html__( 'Enabled?', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Check this to enable interim sales recovery attempts.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_period',
			'name' => esc_html__( 'Days to Send', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Age in days, since abandoned sale, of when to send sales recovery attempts. Format as CSV.', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => '1,3,7,14,21',
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_discount',
			'name' => esc_html__( 'Discount Percentage', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Incentive offer to complete transaction. Number is converted to percentage. Expires at beginning of final offer. Ex: 10 becomes 10%. Leave blank for none.', 'edd-sales-recovery' ),
			'type' => 'text',
			'size' => 'small',
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_header',
			'name' => '<strong>' . esc_html__( 'Final Attempt', 'edd-sales-recovery' ) . '</strong>',
			'type' => 'header',
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_enable',
			'name' => esc_html__( 'Enabled?', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Check this to enable final sales recovery attempts.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_period',
			'name' => esc_html__( 'Days to Wait', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Number of days to wait, since abandoned sale, before sending the final sales recovery attempt.', 'edd-sales-recovery' ),
			'type' => 'text',
			'size' => 'small',
			'std' => 28,
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_discount',
			'name' => esc_html__( 'Discount Percentage', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Incentive offer to complete transaction. Number is converted to percentage. Ex: 10 becomes 10%. Leave blank for none.', 'edd-sales-recovery' ),
			'type' => 'text',
			'size' => 'small',
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_discount_period',
			'name' => esc_html__( 'Discount Period', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Number of days final discount offer is valid for.', 'edd-sales-recovery' ),
			'type' => 'text',
			'size' => 'small',
			'std' => 3,
		);

		// If EDD is at version 2.5 or later...
		if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
			// Use the previously noted array key as an array key again and next your settings
			$settings = array( 'eddsr_edd_settings_extensions' => $settings );
		}

		return array_merge( $settings, $edd_settings );
	}


	public static function edd_settings_emails( $edd_settings ) {
		$settings[] = array(
			'id' => self::SLUG . 'header',
			'name' => '<h3 id="EDD_Sales_Recovery">' . esc_html__( 'Sales Recovery', 'edd-sales-recovery' ) . '</h3>',
			'type' => 'header',
		);

		$settings[] = array(
			'id' => self::SLUG . 'simple_to',
			'name' => esc_html__( 'Email Only To:?', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Check this box if you\'re integrated with Mandrill or other third party APIs or SMTPs that have mail sending issues.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'disable_admin_notices',
			'name' => esc_html__( 'Disable Recovery Notifications', 'edd-sales-recovery' ),
			'desc' => esc_html__( 'Check this box if you do not want to receive emails when sales recovery attempts are made.', 'edd-sales-recovery' ),
			'type' => 'checkbox',
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_header',
			'name' => '<strong>' . esc_html__( 'Initial Attempt', 'edd-sales-recovery' ) . '</strong>',
			'type' => 'hook',
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_subject',
			'name' => esc_html__( 'Recovery Subject', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => esc_html__( '{sitename}: Did you have checkout trouble?', 'edd-sales-recovery' ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_email',
			'name' => esc_html__( 'Recovery Content', 'edd-sales-recovery' ),
			'desc' => self::sales_recovery_template_tags(),
			'type' => 'rich_editor',
			'std' => self::sales_recovery_template( false ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'initial_admin_subject',
			'name' => esc_html__( 'Recovery Notification Subject', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => esc_html__( '{sitename}: Sale Recovery Attempt: {stage}', 'edd-sales-recovery' ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_header',
			'name' => '<strong>' . esc_html__( 'Interim Attempts', 'edd-sales-recovery' ) . '</strong>',
			'type' => 'hook',
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_subject',
			'name' => esc_html__( 'Recovery Subject', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => esc_html__( '{sitename}: We want your business!', 'edd-sales-recovery' ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_email',
			'name' => esc_html__( 'Recovery Content', 'edd-sales-recovery' ),
			'desc' => self::sales_recovery_template_tags(),
			'type' => 'rich_editor',
			'std' => self::sales_recovery_template(),
		);

		$settings[] = array(
			'id' => self::SLUG . 'interim_admin_subject',
			'name' => esc_html__( 'Recovery Notification Subject', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => esc_html__( '{sitename}: Sale Recovery Attempt: {stage}', 'edd-sales-recovery' ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_header',
			'name' => '<strong>' . esc_html__( 'Final Attempt', 'edd-sales-recovery' ) . '</strong>',
			'type' => 'hook',
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_subject',
			'name' => esc_html__( 'Recovery Subject', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => esc_html__( '{sitename}: Final Offer!', 'edd-sales-recovery' ),
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_email',
			'name' => esc_html__( 'Recovery Content', 'edd-sales-recovery' ),
			'desc' => self::sales_recovery_template_tags(),
			'type' => 'rich_editor',
			'std' => self::sales_recovery_template(),
		);

		$settings[] = array(
			'id' => self::SLUG . 'final_admin_subject',
			'name' => esc_html__( 'Recovery Notification Subject', 'edd-sales-recovery' ),
			'type' => 'text',
			'std' => esc_html__( '{sitename}: Sale Recovery Attempt: {stage}', 'edd-sales-recovery' ),
		);

		// If EDD is at version 2.5 or later...
		if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
			// Use the previously noted array key as an array key again and next your settings
			$settings = array( 'eddsr_edd_settings_emails' => $settings );
		}

		return array_merge( $settings, $edd_settings );
	}


	public static function get_unsubscribe_link( $payment_id ) {
		$unsubscribe_link = self::get_edd_options( 'unsubscribe_link' );
		$links            = self::create_link( $unsubscribe_link, null, null, false );

		if ( empty( $links ) ) {
			return null;
		}

		$tag     = $links['tag'];
		$link    = $links['link'];
		$confirm = self::get_confirm_code( $payment_id );

		$link_args = array(
			'edd_action' => 'end_recovery_process',
			'purchase_id' => $payment_id,
			self::CONFIRM_KEY => $confirm,
		);

		$new_link = add_query_arg( $link_args, $link );
		$tag      = str_replace( $link, $new_link, $tag );

		return array(
			'link' => $new_link,
			'tag' => $tag,
		);
	}


	public static function sales_recovery_template( $show_discount = true ) {
		$template = __(
			'Hello {name},

We\'re following up with you, because we noticed that on {date} you attempted to purchase the following products via {payment_method} on {sitename}.

{cart_items}

If you had any purchase troubles, could you please {contact} to share them?

Otherwise, how about giving us another chance? Shop <a href="{store_url}">{sitename}</a>.',
			'edd-sales-recovery'
		);

		if ( $show_discount ) {
			$template .= __(
				'

As a thank you for coming back, here\'s a single-use coupon for {discount}, that expires {discount_expiration}. You can use discount code "{discount_code}" during {recovery_url}.',
				'edd-sales-recovery'
			);
		}

		$template .= __(
			'
<hr />
You may <a href="{unsubscribe_url}">unsubscribe</a> to stop receiving these emails.

<a href="{site_url}">{sitename}</a> appreciates your business.',
			'edd-sales-recovery'
		);

		return $template;
	}


	public static function sales_recovery_template_tags() {
		$tags   = array();
		$tags[] = esc_html__( 'Enter the email contents that is sent for sales recovery attempts. HTML is accepted. Additional EDD template tags:', 'edd-sales-recovery' );
		$tags[] = '{admin_order_details_url} - ' . esc_html__( 'Admin order details URL', 'edd-sales-recovery' );
		$tags[] = '{admin_order_details} - ' . esc_html__( 'Admin order details tag - Automatically prepended to admin notifications', 'edd-sales-recovery' );
		$tags[] = '{cart_items} - ' . esc_html__( 'Cart contents', 'edd-sales-recovery' );
		$tags[] = '{checkout_url} - ' . esc_html__( 'Checkout page URL', 'edd-sales-recovery' );
		$tags[] = '{checkout} - ' . esc_html__( 'Checkout page tag', 'edd-sales-recovery' );
		$tags[] = '{contact_url} - ' . esc_html__( 'Contact page URL', 'edd-sales-recovery' );
		$tags[] = '{contact} - ' . esc_html__( 'Contact page tag', 'edd-sales-recovery' );
		$tags[] = '{discount_code} - ' . esc_html__( 'The discount code', 'edd-sales-recovery' );
		$tags[] = '{discount_expiration} - ' . esc_html__( 'The discount code expiration date', 'edd-sales-recovery' );
		$tags[] = '{discount} - ' . esc_html__( 'The discount percentage', 'edd-sales-recovery' );
		$tags[] = '{recovery_url} - ' . esc_html__( 'Recovery URL', 'edd-sales-recovery' );
		$tags[] = '{site_url} - ' . esc_html__( 'Site URL', 'edd-sales-recovery' );
		$tags[] = '{stage} - ' . esc_html__( 'Sales recovery stage', 'edd-sales-recovery' );
		$tags[] = '{store_url} - ' . esc_html__( 'Store URL', 'edd-sales-recovery' );
		$tags[] = '{unsubscribe_url} - ' . esc_html__( 'Unsubscribe page URL', 'edd-sales-recovery' );
		$tags[] = '{unsubscribe} - ' . esc_html__( 'Unsubscribe page tag', 'edd-sales-recovery' );
		$tags[] = '{users_orders_url} - ' . esc_html__( 'User\'s orders URL', 'edd-sales-recovery' );
		$tags[] = '{users_orders} - ' . esc_html__( 'User\'s orders tag - Automatically prepended to admin notifications', 'edd-sales-recovery' );

		$tags = implode( '<br />', $tags );

		return apply_filters( 'eddsr_sales_recovery_template_tags', $tags );
	}


	public static function get_order_url( $payment_id ) {
		$link_base = self::$payment_history_url . '&view=view-order-details';
		$link      = add_query_arg( 'id', $payment_id, $link_base );

		return $link;
	}


	/**
	 * Attempts to process edd_payment records with ignore and process status
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function set_edd_payment_statuses() {
		global $wpdb;

		$status_closed  = "'" . implode( "', '", self::$status_closed ) . "'";
		$status_recover = "'" . implode( "', '", self::$status_recover ) . "'";
		$initial_period = self::get_edd_options( 'initial_period' );

		$reset_status = self::get_edd_options( 'reset_eddsr_status' );
		if ( empty( $reset_status ) ) {
			$query = "
				SELECT m.post_id
				FROM {$wpdb->postmeta} m
				WHERE 1 = 1
				AND m.meta_key = '" . self::$status_key . "'
				AND m.meta_value = '" . self::STATUS_IGNORE . "'
				";

			$ignore_payments = $wpdb->get_col( $query );
			$ignore_payments = array_unique( $ignore_payments, SORT_NUMERIC );
			$ignore_payments = apply_filters( 'eddsr_set_edd_payment_statuses_ignore', $ignore_payments );
			sort( $ignore_payments, SORT_NUMERIC );
			$ignore_payments = implode( ',', $ignore_payments );
			if ( empty( $ignore_payments ) ) {
				$ignore_payments = 0;
			}
		} else {
			$ignore_payments = 0;
			self::set_edd_options( 'reset_eddsr_status', 0 );
			self::$edd->session->set( self::$slug_pt, null );
		}

		// query to ignore payments of type `Checks`
		$query = "
			SELECT m.post_id
			FROM {$wpdb->postmeta} m
			WHERE 1 = 1
			AND m.meta_key = '" . self::CHEQUE_GATEWAY_KEY . "'
			AND m.meta_value = '" . self::CHEQUE_GATEWAY_VAL . "'
			";

		$ignore_cheque_payments = $wpdb->get_col( $query );
		$ignore_cheque_payments = array_unique( $ignore_cheque_payments, SORT_NUMERIC );
		sort( $ignore_cheque_payments, SORT_NUMERIC );
		if ( ! empty( $ignore_cheque_payments ) ) {
			$ignore_cheque_payments = implode( ',', $ignore_cheque_payments );
			if ( empty( $ignore_payments ) ) {
				$ignore_payments = $ignore_cheque_payments;
			} else {
				$ignore_payments .= ',' . $ignore_cheque_payments;
			}
		}

		$query_post = "
			SELECT p.ID
			FROM {$wpdb->posts} p
			WHERE 1 = 1
				AND p.post_parent = 0
				AND p.post_type = '" . self::PAYMENT_POST_TYPE . "'
		";

		if ( ! empty( $ignore_payments ) ) {
			$query_post .= ' AND p.ID NOT IN ( ' . $ignore_payments . ' ) ';
		}

		$recovery_start_date = self::get_edd_options( 'recovery_start_date' );
		if ( ! empty( $recovery_start_date ) ) {
			$query_post .= " AND p.post_date >= STR_TO_DATE('". $recovery_start_date ."', '%Y-%m-%d') ";
		}

		// grab user account ids
		$query = $query_post;
		$query = str_replace( 'SELECT p.ID', 'SELECT DISTINCT p.post_author', $query );

		$users = $wpdb->get_col( $query );
		$users = apply_filters( 'eddsr_set_edd_payment_statuses_users', $users );
		sort( $users, SORT_NUMERIC );
		foreach ( $users as $key => $user_id ) {
			// grab closed transactions by user account, not marked ignore|process
			$query  = $query_post;
			$query .= '
				AND p.post_author = ' . $user_id . '
				AND p.post_status IN ( ' . $status_closed . ' )
			';

			$closed_payments = $wpdb->get_col( $query );
			if ( empty( $closed_payments ) ) {
				continue;
			}

			// mark closed transactions as ignore
			foreach ( $closed_payments as $key => $closed_payment_id ) {
				self::set_process_mode( $closed_payment_id, self::STATUS_IGNORE );
			}
		}

		// for now remaining open transactions are lumped by email/cart contents with no worries to time frame
		// don't select newer than initial_period since those could be currently
		// active cart transactions

		reset( $users );
		foreach ( $users as $key => $user_id ) {
			$query  = $query_post;
			$query .= "
				AND p.post_author = {$user_id}
				AND p.post_status IN ( {$status_recover} )
				AND p.post_date_gmt < DATE_SUB( NOW(), INTERVAL {$initial_period} HOUR )
			";

			$recover_payments = $wpdb->get_col( $query );
			if ( empty( $recover_payments ) ) {
				continue;
			}

			rsort( $recover_payments, SORT_NUMERIC );
			foreach ( $recover_payments as $key => $recover_payment_id ) {
				if ( ! empty( $reset_status ) ) {
					delete_post_meta( $recover_payment_id, self::$email_sent_key );
					delete_post_meta( $recover_payment_id, self::$recovery_start_key );
					delete_post_meta( $recover_payment_id, self::$stage_key );
					delete_post_meta( $recover_payment_id, self::$status_key );
				}

				// mark first transactions as recover, reset ignore
				self::set_edd_payment_status( $recover_payment_id );
			}
		}
	}


	public static function get_recover_sales_ids() {
		global $wpdb;

		// this just determines what's to attempt recovery on, not at which step
		self::set_edd_payment_statuses();

		// now grab only the payments marked for recovery processing
		$query = "
			SELECT p.ID
			FROM {$wpdb->posts} p JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
			WHERE 1 = 1
				AND m.meta_key = '" . self::$status_key . "'
				AND m.meta_value = '" . self::STATUS_PROCESS . "'
		";


		$recovery_start_date = self::get_edd_options( 'recovery_start_date' );
		if ( ! empty( $recovery_start_date ) ) {
			$query .= " AND p.post_date >= STR_TO_DATE('". $recovery_start_date ."', '%Y-%m-%d') ";
		}

		$recover_sales = $wpdb->get_col( $query );
		$recover_sales = array_unique( $recover_sales, SORT_NUMERIC );
		$recover_sales = apply_filters( 'eddsr_get_recover_sales_ids', $recover_sales );
		rsort( $recover_sales, SORT_NUMERIC );

		return $recover_sales;
	}


	/**
	 * track similar payment attempts for transactions
	 * first instance is processed
	 * all other instances are marked as ignore in postmeta
	 */
	public static function set_edd_payment_status( $payment_id ) {
		self::set_edd();

		$payment_meta = edd_get_payment_meta( $payment_id );
		$date         = $payment_meta['date'];
		$email        = $payment_meta['email'];

		$payment_tracking = self::$edd->session->get( self::$slug_pt );
		if ( is_null( $payment_tracking ) ) {
			$payment_tracking = array();
		}

		if ( empty( $payment_tracking[ $email ] ) ) {
			$payment_tracking[ $email ] = array();
		}

		// date and hour reference
		$time_mark = substr( $date, 0, 13 );
		if ( empty( $payment_tracking[ $email ][ $time_mark ] ) ) {
			$payment_tracking[ $email ][ $time_mark ] = array();
		}

		$cart_details = ! empty( $payment_meta['cart_details'] ) ? $payment_meta['cart_details'] : '';
		if ( is_array( $cart_details ) ) {
			$cart_details = serialize( $cart_details );
		}

		$cart_hash = md5( $cart_details );
		if ( empty( $payment_tracking[ $email ][ $time_mark ][ $cart_hash ] ) ) {
			$payment_tracking[ $email ][ $time_mark ][ $cart_hash ] = 0;
		}

		// don't blindly grab same cart contents without checking timestamp
		// if paid already
		$payment_status = get_post_status( $payment_id );
		if ( ! in_array( $payment_status, self::$status_closed ) ) {
			// first instance of cart contents
			if ( empty( $payment_tracking[ $email ][ $time_mark ][ $cart_hash ] ) ) {
				self::set_process_mode( $payment_id, self::STATUS_PROCESS );
			} else {
				// subsequent instances of same cart contents by timestamp
				self::set_process_mode( $payment_id, self::STATUS_IGNORE );
			}
		} else {
			self::set_process_mode( $payment_id, self::STATUS_IGNORE );
		}

		$payment_tracking[ $email ][ $time_mark ][ $cart_hash ] += 1;
		self::$edd->session->set( self::$slug_pt, $payment_tracking );
	}


	public static function get_process_mode( $payment_id ) {
		return get_post_meta( $payment_id, self::$status_key, true );
	}


	public static function set_process_mode( $payment_id, $mode = null ) {
		$current_state = self::get_process_mode( $payment_id );
		if ( $current_state == $mode ) {
			return;
		}

		delete_post_meta( $payment_id, self::$status_key );
		add_post_meta( $payment_id, self::$status_key, $mode, true );

		$text = esc_html__( 'Sales recovery state changed to %1$s', 'edd-sales-recovery' );
		edd_insert_payment_note( $payment_id, sprintf( $text, $mode ) );
	}


	public static function is_sale_recoverable( $payment_id ) {
		// are emails set to be sent out, otherwise waste of cpus to go on
		$initial_enable = self::get_edd_options( 'initial_enable' );
		$interim_enable = self::get_edd_options( 'interim_enable' );
		$final_enable   = self::get_edd_options( 'final_enable' );
		if ( empty( $initial_enable ) && empty( $interim_enable ) && empty( $final_enable ) ) {
			self::set_notice( 'eddsr_notice_not_enabled' );

			return false;
		}

		$status = get_post_status( $payment_id );
		if ( in_array( $status, self::$status_recover ) ) {
			return true;
		}

		return false;
	}

	public static function edd_recoverable_payment_statuses( $status ) {
		array_push( $status, 'recovery' );

		return $status;
	}

	public static function edd_recover_sale( $args ) {
		$result = false;

		if ( empty( $args['purchase_id'] ) ) {
			set_transient( self::$error_reason, esc_html__( 'Empty purchase ID', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		} elseif ( empty( $args[ self::CONFIRM_KEY ] ) ) {
			set_transient( self::$error_reason, esc_html__( 'Empty confirmation key', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		} elseif ( ! self::verify_nonce( $args[ self::CONFIRM_KEY ], 'recover_sale' ) ) {
			set_transient( self::$error_reason, esc_html__( 'Confirmation key failed', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		} else {
			$payment_id = intval( $args['purchase_id'] );
			$result     = self::recover_sale( $payment_id );
		}

		// manually forced, so override possible ignore
		if ( $result ) {
			self::set_process_mode( $payment_id, self::STATUS_PROCESS );
			set_transient( self::$payment_key, $payment_id, HOUR_IN_SECONDS );
			add_action( 'admin_notices', array( __CLASS__, 'notice_recover_success' ) );
		} else {
			add_action( 'admin_notices', array( __CLASS__, 'notice_recover_failure' ) );
		}

		do_action( 'eddsr_edd_recover_sale', $args );
	}


	public static function edd_resend_recovery( $args ) {
		$result = false;

		if ( empty( $args['purchase_id'] ) ) {
			set_transient( self::$error_reason, esc_html__( 'Empty purchase ID', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		} elseif ( empty( $args[ self::CONFIRM_KEY ] ) ) {
			set_transient( self::$error_reason, esc_html__( 'Empty confirmation key', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		} elseif ( ! self::verify_nonce( $args[ self::CONFIRM_KEY ], 'resend_recovery' ) ) {
			set_transient( self::$error_reason, esc_html__( 'Confirmation key failed', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		} else {
			$payment_id = intval( $args['purchase_id'] );
			$result     = self::resend_recovery( $payment_id );
		}

		if ( $result ) {
			set_transient( self::$payment_key, $payment_id, HOUR_IN_SECONDS );
			add_action( 'admin_notices', array( __CLASS__, 'notice_email_sent' ) );
		} else {
			add_action( 'admin_notices', array( __CLASS__, 'notice_email_failed' ) );
		}

		do_action( 'eddsr_edd_resend_recovery', $args );
	}


	public static function edd_end_recovery_process( $args ) {
		if ( empty( $args[ self::CONFIRM_KEY ] ) || empty( $args['purchase_id'] ) ) {
			return;
		}

		$c_confirm  = esc_html( $args[ self::CONFIRM_KEY ] );
		$payment_id = intval( $args['purchase_id'] );
		$confirm    = self::get_confirm_code( $payment_id );

		if ( $c_confirm == $confirm ) {
			$text = esc_html__( 'Sales recovery manually stopped', 'edd-sales-recovery' );
			edd_insert_payment_note( $payment_id, $text );
			self::end_recovery_process( $payment_id );
			set_transient( self::$payment_key, $payment_id, HOUR_IN_SECONDS );
			add_action( 'admin_notices', array( __CLASS__, 'notice_recovery_stopped' ) );
			do_action( 'eddsr_end_recovery_process', $args );
		}
	}


	public static function notice_recovery_stopped() {
		$order_link = self::get_order_link();

		$text = esc_html__( 'Sales recovery process has been stopped. %1$s', 'edd-sales-recovery' );
		$text = sprintf( $text, $order_link );

		aihr_notice_updated( $text );
	}


	public static function get_confirm_code( $payment_id ) {
		$user_id      = edd_get_payment_user_id( $payment_id );
		$payment_meta = edd_get_payment_meta( $payment_id );
		$cart_details = ! empty( $payment_meta['cart_details'] ) ? $payment_meta['cart_details'] : '';
		if ( is_array( $cart_details ) ) {
			$cart_details  = serialize( $cart_details );
		}

		$code = md5( $user_id . $cart_details );

		return $code;
	}


	public static function resend_recovery( $payment_id ) {
		if ( ! self::is_sale_recoverable( $payment_id ) ) {
			set_transient( self::$error_reason, esc_html__( 'Sale is not recoverable', 'edd-sales-recovery' ), HOUR_IN_SECONDS );

			return false;
		}

		$now   = time();
		$stage = self::get_stage( $payment_id );
		if ( empty( $stage ) ) {
			$stage = self::STAGE_INITIAL;
		}

		return self::sales_recover_process( $payment_id, $stage, $now );
	}


	public static function notice_email_failed() {
		$reason = self::get_error_reason();

		$text = esc_html__( 'Latest recovery email failed to be resent because "%1$s".', 'edd-sales-recovery' );
		$text = sprintf( $text, $reason );

		aihr_notice_error( $text );
	}


	public static function get_order_link( $payment_id = null ) {
		if ( is_null( $payment_id ) ) {
			$payment_id = self::get_payment_key();
		}

		$order_link = __( 'View <a href="%1$s">order details</a>.', 'edd-sales-recovery' );
		$order_url  = self::get_order_url( $payment_id );
		$order_link = sprintf( $order_link, $order_url );

		return $order_link;
	}


	public static function notice_email_sent() {
		$order_link = self::get_order_link();

		$text = esc_html__( 'Latest recovery email was resent. %1$s', 'edd-sales-recovery' );
		$text = sprintf( $text, $order_link );

		aihr_notice_updated( $text );
	}


	public static function notice_recover_success() {
		$order_link = self::get_order_link();

		$text = esc_html__( 'Sales recovery process started. %1$s', 'edd-sales-recovery' );
		$text = sprintf( $text, $order_link );

		aihr_notice_updated( $text );
	}


	public static function get_error_reason() {
		$reason = get_transient( self::$error_reason );
		delete_transient( self::$error_reason );

		return $reason;
	}


	public static function get_payment_key() {
		$reason = get_transient( self::$payment_key );
		delete_transient( self::$payment_key );

		return $reason;
	}


	public static function notice_recover_failure() {
		$reason = self::get_error_reason();

		$text = esc_html__( 'Sales recovery process failed for "%1$s".', 'edd-sales-recovery' );
		$text = sprintf( $text, $reason );

		aihr_notice_error( $text );
	}


	public static function get_email_sent( $payment_id ) {
		return get_post_meta( $payment_id, self::$email_sent_key, true );
	}


	public static function set_email_sent( $payment_id, $now ) {
		delete_post_meta( $payment_id, self::$email_sent_key );
		add_post_meta( $payment_id, self::$email_sent_key, $now, true );
	}


	public static function get_recovery_start( $payment_id ) {
		return get_post_meta( $payment_id, self::$recovery_start_key, true );
	}


	public static function set_recovery_start( $payment_id, $now, $delete = false ) {
		if ( $delete ) {
			delete_post_meta( $payment_id, self::$recovery_start_key );
		}

		add_post_meta( $payment_id, self::$recovery_start_key, $now, true );
	}


	/**
	 * check recovery stage (initial, interim, final) and delegate
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function recover_sale( $payment_id ) {
		if ( ! self::is_sale_recoverable( $payment_id ) ) {

			return false;
		}

		$current_stage  = self::get_stage( $payment_id );
		$email_sent     = self::get_email_sent( $payment_id );
		$now            = time();
		$recovery_start = self::get_recovery_start( $payment_id );
		$manual_recovery = isset( $_GET['edd-action'] ) ? sanitize_key( $_GET['edd-action'] ) : '';

		$begin_time = $recovery_start;
		if ( empty( $begin_time ) ) {
			$begin_time = get_post_time( 'U', false, $payment_id );
			self::set_recovery_start( $payment_id, $now, true );
		}

		$initial_enable = self::get_edd_options( 'initial_enable' );
		if ( $initial_enable && empty( $current_stage ) && empty( $recovery_start ) ) {
			$period = self::get_edd_options( 'initial_period' );
			$term   = $begin_time + ( $period * HOUR_IN_SECONDS );
			if ( ( $term < $now ) || ( 'recover_sale' == $manual_recovery ) ) {
				return self::sales_recover_process( $payment_id, self::STAGE_INITIAL, $now );
			}
		}

		if ( self::STAGE_FINAL != $current_stage ) {
			$interim_enable = self::get_edd_options( 'interim_enable' );
			if ( $interim_enable ) {
				$period  = self::get_edd_options( 'interim_period' );
				$periods = explode( ',', $period );
				sort( $periods, SORT_NUMERIC );
				foreach ( $periods as $period ) {
					$term = $begin_time + ( $period * DAY_IN_SECONDS );
					if ( ( $term < $now && $term > $email_sent ) || ( 'recover_sale' == $manual_recovery ) ) {
						return self::sales_recover_process( $payment_id, self::STAGE_INTERIM, $now );
					}
				}
			}

			$final_enable = self::get_edd_options( 'final_enable' );
			if ( $final_enable ) {
				$period = self::get_edd_options( 'final_period' );
				$term   = $begin_time + ( $period * DAY_IN_SECONDS );
				if ( ( $term < $now ) || ( 'recover_sale' == $manual_recovery ) ) {
					return self::sales_recover_process( $payment_id, self::STAGE_FINAL, $now );
				}
			}
		}

		// recovery failed, stop recovery process entirely
		if ( self::STAGE_FINAL == $current_stage ) {
			$period          = self::get_edd_options( 'final_period' );
			$discount_period = self::get_edd_options( 'final_discount_period' );
			$term            = $begin_time + ( $period * DAY_IN_SECONDS ) + ( $discount_period * DAY_IN_SECONDS );
			if ( $term < $now ) {
				$text = esc_html__( 'Sales recovery ended automatically', 'edd-sales-recovery' );
				edd_insert_payment_note( $payment_id, $text );
				self::end_recovery_process( $payment_id );
			}
		}

		return true;
	}


	public static function end_recovery_process( $payment_id, $success = false ) {
		self::set_email_sent( $payment_id, null );
		self::set_process_mode( $payment_id, self::STATUS_IGNORE );
		self::set_recovery_start( $payment_id, null, true );
		self::set_stage( $payment_id, null );

		if ( ! $success ) {
			self::set_status( $payment_id, self::STATUS_ABANDONED );
		} else {
			self::set_status( $payment_id, self::STATUS_RECOVERED );
		}
	}


	public static function get_stage( $payment_id ) {
		return get_post_meta( $payment_id, self::$stage_key, true );
	}


	public static function set_stage( $payment_id, $new_stage ) {
		$current_stage = self::get_stage( $payment_id );
		if ( $current_stage == $new_stage ) {
			return;
		} elseif ( empty( $current_stage ) ) {
			$text = esc_html__( 'Sales recovery stage changed to %2$s', 'edd-sales-recovery' );
		} else {
			$text = esc_html__( 'Sales recovery stage changed from %1$s to %2$s', 'edd-sales-recovery' );
		}

		delete_post_meta( $payment_id, self::$stage_key );
		add_post_meta( $payment_id, self::$stage_key, $new_stage, true );

		if ( ! is_null( $new_stage ) ) {
			edd_insert_payment_note( $payment_id, sprintf( $text, $current_stage, $new_stage ) );
		}
	}


	public static function set_status( $payment_id, $new_status ) {
		$current_status = get_post_status( $payment_id );
		if ( $current_status == $new_status ) {
			return;
		}

		$data = array(
			'ID' => $payment_id,
			'post_status' => $new_status,
		);
		wp_update_post( $data );

		$text = esc_html__( 'Transaction status changed from %1$s to %2$s', 'edd-sales-recovery' );
		edd_insert_payment_note( $payment_id, sprintf( $text, $current_status, $new_status ) );
	}


	public static function sales_recover_process( $payment_id, $stage, $now ) {
		self::set_status( $payment_id, self::STATUS_RECOVERY );

		$admin_notice  = ! self::get_edd_options( 'disable_admin_notices' );
		$admin_subject = self::get_edd_options( $stage . '_admin_subject' );
		$discount      = self::get_edd_options( $stage . '_discount' );
		$email_text    = self::get_edd_options( $stage . '_email' );
		$payment_data  = edd_get_payment_meta( $payment_id );
		$subject       = self::get_edd_options( $stage . '_subject' );

		if ( $discount ) {
			self::create_discount( $payment_id, $discount, $stage );
		}

		// generate email
		$to            = self::get_email_to( $payment_id );
		$email_subject = edd_email_template_tags( $subject, $payment_data, $payment_id, false );
		$email_body    = self::get_email_body( $email_text, $payment_data, $payment_id );
		$attachments   = apply_filters( 'eddsr_sales_recover_process_attachments', array(), $payment_id, $payment_data );

		$success = EDD()->emails->send( $to, $email_subject, $email_body, $attachments );
		if ( $success ) {
			$text = esc_html__( 'Sales recovery %2$s email sent: "%1$s"', 'edd-sales-recovery' );
			edd_insert_payment_note( $payment_id, sprintf( $text, $email_subject, $stage ) );
			self::set_email_sent( $payment_id, $now );
			self::set_stage( $payment_id, $stage );

			if ( $admin_notice ) {
				$to            = edd_get_admin_notice_emails();
				$admin_subject = edd_email_template_tags( $admin_subject, $payment_data, $payment_id, true );
				$email_body    = self::get_email_body( $email_text, $payment_data, $payment_id, true );
				EDD()->emails->send( $to, $admin_subject, $email_body );
			}

			do_action( 'eddsr_sales_recover_process', $payment_id );
		} else {
			$text = esc_html__( 'Sales recovery %2$s email "%1$s" failed due to mail sending problems.', 'edd-sales-recovery' );
			edd_insert_payment_note( $payment_id, sprintf( $text, $email_subject, $stage ) );
			set_transient( self::$error_reason, esc_html__( '`EDD()->emails->send` failed to send', 'edd-sales-recovery' ), HOUR_IN_SECONDS );
		}

		return $success;
	}


	public static function get_full_name( $payment_id ) {
		$user_id   = edd_get_payment_user_id( $payment_id );
		$user_info = edd_get_payment_meta_user_info( $payment_id );
		$name      = '';

		if ( ! empty( $user_id ) && $user_id > 0 ) {
			$user_data = get_userdata( $user_id );
			$name      = $user_data->display_name;
		} elseif ( ! empty( $user_info['first_name'] ) && ! empty( $user_info['last_name'] ) ) {
			$name = $user_info['first_name'] . ' ' . $user_info['last_name'];
		} elseif ( ! empty( $user_info['first_name'] ) ) {
			$name = $user_info['first_name'];
		}

		return $name;
	}


	public static function get_email_to( $payment_id ) {
		$email     = edd_get_payment_user_email( $payment_id );
		$simple_to = self::get_edd_options( 'simple_to' );
		if ( ! $simple_to ) {
			$name = self::get_full_name( $payment_id );
			if ( $name ) {
				$to = $name . ' <' . $email . '>';
			} else {
				$to = $email;
			}
		} else {
			$to = $email;
		}

		$to = apply_filters( 'eddsr_get_email_to', $to, $payment_id );

		return $to;
	}


	public static function get_email_body( $email_text, $payment_data, $payment_id, $admin_notice = false ) {
		if ( $admin_notice ) {
			$email_text = '{admin_order_details} {users_orders}<hr />' . $email_text;
		}

		$email_body = edd_email_template_tags( $email_text, $payment_data, $payment_id, $admin_notice );
		$email_body = apply_filters( 'eddsr_get_email_body', $email_body, $email_text, $payment_data, $payment_id );
		$email_body = apply_filters( 'edd_purchase_receipt', $email_body, $payment_id, $payment_data );

		return $email_body;
	}


	public static function pretty_print_cart_items( $payment_id ) {
		$html       = '';
		$cart_items = edd_get_payment_meta_cart_details( $payment_id );
		if ( empty( $cart_items ) ) {
			return $html;
		}

		foreach ( $cart_items as $item ) {
			$link = self::create_link( $item['id'] );
			if ( empty( $link ) ) {
				continue;
			}

			$html .= '<li>';
			$html .= $link;
			$html .= '</li>';
		}

		if ( ! empty( $html ) ) {
			$html = '<ul>' . $html . '</ul>';
		}

		return $html;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function edd_email_template_tags( $message, $payment_data, $payment_id, $admin_notice = false ) {
		$admin_order_details_url = self::get_order_url( $payment_id );
		$admin_order_details     = self::get_order_link( $payment_id );

		$cart_items = self::pretty_print_cart_items( $payment_id );

		$checkout_link = self::get_edd_options( 'purchase_page' );
		$links         = self::create_link( $checkout_link, null, null, false );

		if ( $links ) {
			$checkout     = $links['tag'];
			$checkout_url = $links['link'];
		} else {
			$checkout     = '';
			$checkout_url = '';
		}

		$contact_link = self::get_edd_options( 'contact_link' );
		$links        = self::create_link( $contact_link, null, null, false );
		if ( $links ) {
			$contact     = $links['tag'];
			$contact_url = $links['link'];
		} else {
			$contact     = '';
			$contact_url = '';
		}

		$recovery_url 		= add_query_arg( array( 'edd_action' => 'recover_payment', 'payment_id' => $payment_id ), edd_get_checkout_uri() );
		$recovery_url_link 	= '<a href="'.$recovery_url.'">Checkout page</a>';

		$stage = self::get_stage( $payment_id );

		$links           = self::get_unsubscribe_link( $payment_id );
		$unsubscribe     = $links['tag'];
		$unsubscribe_url = $links['link'];

		$payment_meta      = edd_get_payment_meta( $payment_id );
		$email             = $payment_meta['email'];
		$users_orders_text = __( 'View <a href="%1$s">user\'s orders</a>.', 'edd-sales-recovery' );
		$users_orders_url  = add_query_arg( 'user', $email, self::$payment_history_url );
		$users_orders      = sprintf( $users_orders_text, $users_orders_url );

		if( isset( self::$discount_expiration ) ) {
			$discount_expiration = date( 'F j, Y', strtotime( self::$discount_expiration ) );
		} else {
			$discount_expiration = '';
		}

		$slug    = defined( 'EDD_SLUG' ) ? EDD_SLUG : 'downloads';

		$message = str_replace( '{admin_order_details_url}', $admin_order_details_url, $message );
		$message = str_replace( '{admin_order_details}', $admin_order_details, $message );
		$message = str_replace( '{cart_items}', $cart_items, $message );
		$message = str_replace( '{checkout_url}', $checkout_url, $message );
		$message = str_replace( '{checkout}', $checkout, $message );
		$message = str_replace( '{contact_url}', $contact_url, $message );
		$message = str_replace( '{contact}', $contact, $message );
		$message = str_replace( '{discount_code}', self::$discount_code, $message );
		$message = str_replace( '{discount_expiration}', $discount_expiration, $message );
		$message = str_replace( '{discount}', self::$discount_text, $message );
		$message = str_replace( '{recovery_url}', $recovery_url_link, $message );
		$message = str_replace( '{site_url}', site_url(), $message );
		$message = str_replace( '{stage}', $stage, $message );
		$message = str_replace( '{store_url}', site_url( $slug ), $message );
		$message = str_replace( '{unsubscribe_url}', $unsubscribe_url, $message );
		$message = str_replace( '{unsubscribe}', $unsubscribe, $message );
		$message = str_replace( '{users_orders_url}', $users_orders_url, $message );
		$message = str_replace( '{users_orders}', $users_orders, $message );

		return $message;
	}


	public static function get_discount_code( $payment_id, $stage, $discount_amount ) {
		global $wpdb;
		$code = get_post_meta( $payment_id, self::$discount_key . $stage, true );
		$discount_id = edd_get_discount_id_by_code( $code );
		if( $discount_id ) {
			$is_valid_discount 			= get_post_meta( $discount_id, '_edd_discount_status', true );
			$is_valid_discount_amount 	= get_post_meta( $discount_id, '_edd_discount_amount', true );
			if( $is_valid_discount == 'active' && $is_valid_discount_amount == $discount_amount  ) {
				return $code;
			} else {
				return $code = '';
			}
		} else {
			return $code = '';
		}
	}


	public static function set_discount_code( $payment_id, $stage, $code ) {
		delete_post_meta( $payment_id, self::$discount_key . $stage );
		add_post_meta( $payment_id, self::$discount_key . $stage, $code, true );
	}


	public static function create_discount( $payment_id, $discount, $stage ) {
		$code = self::get_discount_code( $payment_id, $stage, $discount );
		$name = $discount . esc_html__( '% off', 'edd-sales-recovery' );

		if ( empty( $code ) ) {
			$code = strtoupper( uniqid() );
			$code = apply_filters( 'eddsr_create_discount_code', $code, $payment_id, $discount, $stage );

			switch ( $stage ) {
				case self::STAGE_INITIAL:
					$period = self::get_edd_options( 'interim_period', 1 );
					$expire = preg_replace( '#,.*$#', '', $period );
					break;

				case self::STAGE_INTERIM:
					$expire = self::get_edd_options( 'final_period', 28 );
					break;

				case self::STAGE_FINAL:
					$expire = self::get_edd_options( 'final_discount_period', 3 );
					break;
			}

			$expiration = "+{$expire} days";

			$meta = array(
				'amount' => $discount,
				'code' => $code,
				'expiration' => $expiration,
				'max' => 1,
				'name' => $name,
				'start' => 'now',
				'type' => 'percent',
				'use_once' => 1,
				'uses' => 0,
			);
			$meta = apply_filters( 'eddsr_create_discount', $meta );
			edd_store_discount( $meta );
			self::set_discount_code( $payment_id, $stage, $code );

			$discount_id = edd_get_discount_id_by_code( $code );
			$expiration  = edd_get_discount_expiration( $discount_id );
			$text        = esc_html__( 'Sales recovery created discount code "%1$s" for %2$s expiring %3$s', 'edd-sales-recovery' );
			edd_insert_payment_note( $payment_id, sprintf( $text, $code, $name, $expiration ) );
			add_post_meta( $discount_id, self::$payment_key, $payment_id, true );
		} else {
			$discount_id = edd_get_discount_id_by_code( $code );
			$expiration  = edd_get_discount_expiration( $discount_id );
		}

		self::$discount_code       = $code;
		self::$discount_expiration = $expiration;
		self::$discount_text       = $name;

		return $code;
	}


	public static function edd_complete_purchase( $payment_id ) {
		global $wpdb;

		$discount_id = get_post_meta( $payment_id, '_edd_payment_discount_id', true );
		if ( empty( $discount_id ) ) {
			return;
		}

		$code = edd_get_discount_code( $discount_id );
		if ( empty( $code ) ) {
			return;
		}

		$query = "
			SELECT m.post_id
			FROM {$wpdb->postmeta} m
			WHERE 1 = 1
				AND m.meta_key = '" . self::$discount_key . "'
				AND m.meta_value = '" . $code . "'
		";

		$in_recovery = $wpdb->get_col( $query );
		if ( empty( $in_recovery ) ) {
			return;
		}

		$text    = esc_html__( 'Sales recovery succeeded. %1$s', 'edd-sales-recovery' );
		$link    = self::get_order_link( $payment_id );
		$content = sprintf( $text, $link );
		foreach ( $in_recovery as $success_id ) {
			edd_insert_payment_note( $success_id, $content );
			self::end_recovery_process( $success_id, true );
		}
	}


	public static function edd_view_order_details_totals_after( $payment_id ) {
		if ( ! self::is_sale_recoverable( $payment_id ) ) {
			return;
		} else {
			$process = self::get_process_mode( $payment_id );
			if ( self::STATUS_PROCESS == $process ) {
				$label     = esc_html__( 'Stop', 'edd-sales-recovery' );
				$confirm   = self::get_confirm_code( $payment_id );
				$link_args = array(
					'edd-action' => 'end_recovery_process',
					'purchase_id' => $payment_id,
					self::CONFIRM_KEY => $confirm,
				);

				$link   = add_query_arg( $link_args );
				$button = $link . '" class="right button-secondary">' . $label;

				$label     = esc_html__( 'Resend', 'edd-sales-recovery' );
				$link_args = array(
					'edd-action' => 'resend_recovery',
					'purchase_id' => $payment_id,
					self::CONFIRM_KEY => self::create_nonce( 'resend_recovery' ),
				);

				$link    = add_query_arg( $link_args );
				$button .= '</a> <a href="' . $link . '" class="right button-secondary">' . $label;
			} else {
				$label     = esc_html__( 'Start', 'edd-sales-recovery' );
				$link_args = array(
					'edd-action' => 'recover_sale',
					'purchase_id' => $payment_id,
					self::CONFIRM_KEY => self::create_nonce( 'recover_sale' ),
				);

				$link   = add_query_arg( $link_args );
				$button = $link . '" class="right button-secondary">' . $label;
			}
		}

		echo '
			<div class="edd-end-recovery-process edd-admin-box-inside">
			<p><span class="label">' . esc_html__( 'Recovery Process', 'edd-sales-recovery' ) . '</span> <a href="' . $button . '</a></p>
			</div>
		';
	}


	public static function show_user_profile( $user ) {
		$unsubscribe = get_user_meta( $user->ID, self::$unsubscribe_key, true );
		$checked     = checked( 1, $unsubscribe, false );
		echo '
			<h3 id="' . self::ID . '">' . esc_html__( 'EDD Sales Recovery', 'edd-sales-recovery' ) . '</h3>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="' . self::$unsubscribe_key . '">' . esc_html__( 'Sales Recovery Unsubscribe', 'edd-sales-recovery' ) . '</label>
						</th>
						<td>
							<input name="' . self::$unsubscribe_key . '" type="checkbox" id="' . self::$unsubscribe_key . '" value="1" ' . $checked . ' />
							<span class="description">' . esc_html__( 'Unsubscribe from current and future sales recovery attempts?', 'edd-sales-recovery' ) . '</span>
						</td>
					</tr>
				</tbody>
			</table>
		';
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function personal_options_update( $user_id ) {
		global $wpdb;

		if ( current_user_can( 'edit_user', $user_id ) && isset( $_POST[ self::$unsubscribe_key ] ) ) {
			if ( empty( $_POST[ self::$unsubscribe_key ] ) ) {
				delete_user_meta( $user_id, self::$unsubscribe_key );
			} else {
				$current = get_user_meta( $user_id, self::$unsubscribe_key, true );
				// don't reprocess
				if ( $current ) {
					return;
				}

				add_user_meta( $user_id, self::$unsubscribe_key, 1, true );

				// stop all open sales recovery processes for user
				$query = "
					SELECT p.ID
					FROM {$wpdb->posts} p
					WHERE 1 = 1
						AND p.post_parent = 0
						AND p.post_type = '" . self::PAYMENT_POST_TYPE . "'
						AND p.post_author = ' . $user_id . '
						AND p.post_status = '" . self::STATUS_RECOVERY . "'
				";

				$in_recovery = $wpdb->get_col( $query );
				if ( empty( $in_recovery ) ) {
					return;
				}

				$text = esc_html__( 'Sales recovery process stopped per user unsubscribe', 'edd-sales-recovery' );
				foreach ( $in_recovery as $payment_id ) {
					edd_insert_payment_note( $payment_id, $text );
					self::end_recovery_process( $payment_id );
				}
			}
		}

		do_action( 'eddsr_personal_options_update', $user_id );
	}


	public static function set_edd() {
		if ( is_null( self::$edd ) && function_exists( 'EDD' ) ) {
			self::$edd = EDD();
		}
	}


	public static function version_check() {
		$valid_version = true;

		$valid_base = true;
		if ( ! class_exists( EDD_SR_REQ_CLASS ) ) {
			$valid_base = false;
		} elseif ( ! defined( 'EDD_VERSION' ) ) {
			$valid_base = false;
		} elseif ( ! version_compare( EDD_VERSION, EDD_SR_REQ_VERSION, '>=' ) ) {
			$valid_base = false;
		}

		if ( ! $valid_base ) {
			$valid_version = false;
			self::set_notice( 'eddsr_notice_version' );
		}

		if ( ! $valid_version ) {
			$deactivate_reason = esc_html__( 'Failed version check', 'edd-sales-recovery' );
			aihr_deactivate_plugin( self::BASE, EDD_SR_NAME, $deactivate_reason );
			self::check_notices();
		}

		return $valid_version;
	}


	public static function update() {
		$version_key = self::SLUG . 'version';
		$version     = get_option( $version_key );
		if ( empty( $version ) || $version < self::VERSION ) {
			eddsr_requirements_check( true );
			self::initialize_settings();
			update_option( $version_key, self::VERSION );
		}
	}


	public static function initialize_settings() {
		$edd_options = edd_get_settings();
		if ( empty( $edd_options ) ) {
			$edd_options = array();
		}

		$do_update = false;
		$options   = array(
			'edd_settings_emails',
			'edd_settings_extensions',
		);
		foreach ( $options as $option ) {
			$settings = self::$option( array() );

			// If EDD is at version 2.5 or later...
			if ( version_compare( EDD_VERSION, 2.5, '>=' ) && isset( $settings[ self::SLUG . $option ] ) ) {
				$settings = $settings[ self::SLUG . $option ];
			}

			foreach ( $settings as $setting ) {
				if ( ! isset( $setting['std'] ) ) {
					continue;
				}

				$id  = $setting['id'];
				$std = $setting['std'];

				if ( ! isset( $edd_options[ $id ] ) ) {
					$edd_options[ $id ] = $std;
					$do_update          = true;
				}
			}
		}

		if ( $do_update ) {
			unregister_setting( 'edd_settings', 'edd_settings', 'edd_settings_sanitize' );
			update_option( 'edd_settings', $edd_options );
		}
	}


	public static function email_test_sales_recovery( $stage = 'initial' ) {
		$payment_id   = 0;
		$payment_data = array();

		$to = edd_get_admin_notice_emails();

		$subject = self::get_edd_options( 'eddsr_' . $stage . '_subject', sprintf( esc_html__( '%s Sales Recovery', 'edd-sales-recovery' ), ucwords( $stage ) ) );
		$subject = edd_email_template_tags( $subject, $payment_data, $payment_id, false );

		$body    = self::get_edd_options( 'eddsr_' . $stage . '_email', EDD_Sales_Recovery::sales_recovery_template( 'initial' != $stage ) );
		$message = self::get_email_body( $body, $payment_data, $payment_id );

		EDD()->emails->send( $to, $subject, $message );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function edd_update_payment_status( $payment_id, $new_status, $old_status ) {
		$end_recovery = array( self::STATUS_ABANDONED, 'failed' );
		if ( in_array( $new_status, $end_recovery ) ) {
			self::end_recovery_process( $payment_id );
		}
	}


	/**
	 * @codingStandardsIgnoreStart
	 */
	public static function edd_eddsr_initial_header() {
		$email_body = EDD_Sales_Recovery::get_edd_options( 'eddsr_initial_email', EDD_Sales_Recovery::sales_recovery_template( false ) );
		$email_body = stripslashes( $email_body );
		ob_start();

?>

	<a href="#email-initial-preview" id="open-email-initial-preview" class="button-secondary" title="<?php _e( 'Preview Initial Sales Recovery', 'edd-sales-recovery' ); ?> "><?php _e( 'Preview Initial Sales Recovery', 'edd-sales-recovery' ); ?></a>
	<a href="<?php echo wp_nonce_url( add_query_arg( array( 'edd_action' => 'send_test_initial_email' ) ), 'edd-test-initial-email' ); ?>" title="<?php _e( 'This will send a demo initial sales recovery to the EDD From Email emails listed above.', 'edd-sales-recovery' ); ?>" class="button-secondary"><?php _e( 'Send Initial Sales Recovery Test Email', 'edd-sales-recovery' ); ?></a>

	<div id="email-initial-preview-wrap" style="display:none;">
		<div id="email-initial-preview">
			<?php echo EDD()->emails->build_email( edd_email_preview_template_tags( $email_body ) ); ?>
		</div>
	</div>

<?php
		echo ob_get_clean();
	}
	// @codingStandardsIgnoreEnd


	/**
	 * @codingStandardsIgnoreStart
	 */
	public static function edd_eddsr_interim_header() {
		$email_body = EDD_Sales_Recovery::get_edd_options( 'eddsr_interim_email', EDD_Sales_Recovery::sales_recovery_template( false ) );
		$email_body = stripslashes( $email_body );
		ob_start();

?>

	<a href="#email-interim-preview" id="open-email-interim-preview" class="button-secondary" title="<?php _e( 'Preview Interim Sales Recovery', 'edd-sales-recovery' ); ?> "><?php _e( 'Preview Interim Sales Recovery', 'edd-sales-recovery' ); ?></a>
	<a href="<?php echo wp_nonce_url( add_query_arg( array( 'edd_action' => 'send_test_interim_email' ) ), 'edd-test-interim-email' ); ?>" title="<?php _e( 'This will send a demo interim sales recovery to the EDD From Email emails listed above.', 'edd-sales-recovery' ); ?>" class="button-secondary"><?php _e( 'Send Interim Sales Recovery Test Email', 'edd-sales-recovery' ); ?></a>

	<div id="email-interim-preview-wrap" style="display:none;">
		<div id="email-interim-preview">
			<?php echo EDD()->emails->build_email( edd_email_preview_template_tags( $email_body ) ); ?>
		</div>
	</div>

<?php
		echo ob_get_clean();
	}
	// @codingStandardsIgnoreEnd


	/**
	 * @codingStandardsIgnoreStart
	 */
	public static function edd_eddsr_final_header() {
		$email_body = EDD_Sales_Recovery::get_edd_options( 'eddsr_final_email', EDD_Sales_Recovery::sales_recovery_template( false ) );
		$email_body = stripslashes( $email_body );
		ob_start();

?>

	<a href="#email-final-preview" id="open-email-final-preview" class="button-secondary" title="<?php _e( 'Preview Final Sales Recovery', 'edd-sales-recovery' ); ?> "><?php _e( 'Preview Final Sales Recovery', 'edd-sales-recovery' ); ?></a>
	<a href="<?php echo wp_nonce_url( add_query_arg( array( 'edd_action' => 'send_test_final_email' ) ), 'edd-test-final-email' ); ?>" title="<?php _e( 'This will send a demo final sales recovery to the EDD From Email emails listed above.', 'edd-sales-recovery' ); ?>" class="button-secondary"><?php _e( 'Send Final Sales Recovery Test Email', 'edd-sales-recovery' ); ?></a>

	<div id="email-final-preview-wrap" style="display:none;">
		<div id="email-final-preview">
			<?php echo EDD()->emails->build_email( edd_email_preview_template_tags( $email_body ) ); ?>
		</div>
	</div>

<?php
		echo ob_get_clean();
	}
	// @codingStandardsIgnoreEnd


	/**
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function redirect( $use_referrer = false ) {
		$redirect = false;
		if ( $use_referrer && ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			$redirect = $_SERVER['HTTP_REFERER'];
			$redirect = remove_query_arg( array( 'edd_action', '_wpnonce' ), $redirect );
		} else {
			$redirect = remove_query_arg( array( 'edd_action', '_wpnonce' ) );
		}

		if ( $redirect ) {
			wp_safe_redirect( $redirect );
			exit;
		}
	}


	public static function set_edd_options( $key, $default = null ) {
		$edd_options = edd_get_settings();
		if ( isset( $edd_options[ self::SLUG . $key ] ) ) {
			$edd_options[ self::SLUG . $key ] = $default;
		} else {
			$edd_options[ $key ] = $default;
		}

		update_option( 'edd_settings', $edd_options );
	}
}

?>