<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Deposits_Settings class
 */
class WC_Deposits_Settings {

	/** @var Settings Tab ID */
	private $settings_tab_id = 'deposits';

	/** @var object Class Instance */
	private static $instance;

	/**
	 * Get the class instance
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Load in the new settings tabs.
		add_action( 'woocommerce_get_sections_products', array( $this, 'add_woocommerce_settings_tab' ), 50 );
		add_action( 'woocommerce_get_settings_products', array( $this, 'get_settings' ), 50, 2 );
	}

	/**
	 * Add settings tab to woocommerce
	 */
	public function add_woocommerce_settings_tab( $settings_tabs ) {
		$settings_tabs[ $this->settings_tab_id ] = __( 'Deposits', 'woocommerce-deposits' );
		return $settings_tabs;
	}

	/**
	 * Returns settings array.
	 * @return array settings
	 */
	public function get_settings( $settings, $current_section ) {
		if ( $current_section !== 'deposits' ) {
			return $settings;
		}

		$payment_gateways        = WC()->payment_gateways->payment_gateways();
		$payment_gateway_options = array();

		foreach ( $payment_gateways as $gateway ) {
			$payment_gateway_options[ $gateway->id ] = $gateway->get_title();
		}

		wc_enqueue_js( "
			jQuery('#wc_deposits_default_enabled').change(function(){
				if ( 'optional' === jQuery(this).val() || 'forced' === jQuery(this).val() ) {
					jQuery('#wc_deposits_default_amount').closest('tr').show();
				} else {
					jQuery('#wc_deposits_default_amount').closest('tr').hide();
				}
			}).change();
		" );

		return apply_filters( 'woocommerce_deposits_get_settings',
			array(
				array(
					'name' => __( 'Sitewide Deposits Configuration', 'woocommerce-deposits' ),
					'type' => 'title',
					'desc' => __( 'These settings affect all products sitewide. You can override these settings on a per product basis to make exceptions.', 'woocommerce-deposits' ),
					'id'   => 'deposits_defaults'
				),
				array(
					'name'     => __( 'Enable Deposits by Default', 'woocommerce-deposits' ),
					'type'     => 'select',
					'desc'     => __( 'You must set a default amount below if setting this option to "yes".', 'woocommerce-deposits' ),
					'default'  => 'no',
					'id'       => 'wc_deposits_default_enabled',
					'desc_tip' => true,
					'options'  => array(
						'optional' => __( 'Yes - deposits are optional', 'woocommerce-deposits' ),
						'forced'   => __( 'Yes - deposits are required', 'woocommerce-deposits' ),
						'no'       => __( 'No', 'woocommerce-deposits' )
					),
				),
				array( 'name' => __( 'Default Deposit Amount (%)', 'woocommerce-deposits' ),
					'type'        => 'text',
					'desc'        => __( 'The default deposit amount percentage.', 'woocommerce-deposits' ),
					'default'     => '',
					'placeholder' => __( 'n/a', 'woocommerce-deposits' ),
					'id'          => 'wc_deposits_default_amount',
					'desc_tip'    => true
				),
				array(
					'name'     => __( 'Disable Payment Gateways', 'woocommerce-deposits' ),
					'type'     => 'multiselect',
					'class'    => 'wc-enhanced-select',
					'css'      => 'width: 450px;',
					'desc'     => __( 'Select payment gateways that should be disabled when accepting deposits.', 'woocommerce-deposits' ),
					'default'  => '',
					'id'       => 'wc_deposits_disabled_gateways',
					'desc_tip' => true,
					'options'  => $payment_gateway_options
				),
				array( 'type' => 'sectionend', 'id' => 'deposits_defaults' ),
			)
		);
	}
}

WC_Deposits_Settings::get_instance();
