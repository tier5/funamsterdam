<?php

/**
 * Title: s2Member shortcodes
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.5
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Extensions_S2Member_Shortcodes {
	/**
	 * Index to identify shortcodes
	 */
	private $index = 0;

	/**
	 * Payment errors
	 */
	private $error = array();

	/**
	 * Constructs and initializes s2Member pay shortcodes
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'handle_payment' ) );

		add_shortcode( 'pronamic_ideal_s2member', array( $this, 'shortcode_pay' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Create an hash
	 *
	 * @param array $data
	 * @return string
	 */
	public function create_hash( $data ) {
		ksort( $data );

		return sha1( implode( '', $data ) . AUTH_SALT );
	}

	//////////////////////////////////////////////////

	/**
	 * Handles the generation of the form from shortcode arguments.
	 *
	 * Expected shortcode example (made by generator)
	 *
	 * [pronamic_ideal_s2member cost="10" period="1 Y" level="1" description="asdfasdfasdfas asdf asdf asdfa" ]
	 *
	 * period represents one of the predetermined durations they can
	 * selected from the dropdown.
	 *
	 * cost is set by the shortcode generator.  Must be ISO standard format ( . as decimal seperator )
	 *
	 * level is the level access upon payment will be granted.
	 *
	 * description is text shown at payment.
	 *
	 * @param array $atts All arguments inside the shortcode
	 */
	public function shortcode_pay( $atts ) {
		$this->index++;

		$defaults = array(
			'period'         => null,
			'cost'           => null,
			'level'          => null,
			'description'    => __( 'iDEAL s2Member Payment || {{order_id}}', 'pronamic_ideal' ),
			'button_text'    => __( 'Pay', 'pronamic_ideal' ),
			'ccaps'          => null,
			'payment_method' => null,
		);

		// Combine the passed options
		$atts = shortcode_atts( $defaults, $atts );
		$atts['order_id'] = uniqid();

		// Output
		$output = '';

		// Get the config ID
		$config_id = get_option( 'pronamic_pay_s2member_config_id' );

		// Get the gateway from the configuration
		$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

		if ( $gateway ) {
			if ( null !== $atts['payment_method'] ) {
				$supported_payment_methods = $gateway->get_supported_payment_methods();

				if ( array_key_exists( $atts['payment_method'], $supported_payment_methods ) ) {
					$gateway->set_payment_method( $atts['payment_method'] );
				} else {
					$atts['payment_method'] = null;
				}
			}

			// Data
			$data = new Pronamic_WP_Pay_Extensions_S2Member_PaymentData( $atts );

			// Hash
			$hash_data = array(
				'order_id'       => $atts['order_id'],
				'period'         => $atts['period'],
				'cost'           => $atts['cost'],
				'level'          => $atts['level'],
				'description'    => $atts['description'],
				'ccaps'          => $atts['ccaps'],
				'payment_method' => $atts['payment_method'],
			);

			// Output
			$output .= $this->payment_error();

			$output .= '<form method="post" action="">';

			if ( ! is_user_logged_in() ) {
				$output .= sprintf(
					'<label for="%s">%s</label>',
					esc_attr( 'pronamic_pay_s2member_email' ),
					esc_html__( 'Email', 'pronamic_ideal' )
				);
				$output .= ' ';
				$output .= sprintf(
					'<input id="%s" name="%s" value="%s" type="text" />',
					esc_attr( 'pronamic_pay_s2member_email' ),
					esc_attr( 'pronamic_pay_s2member_email' ),
					$data->get_email()
				);
				$output .= ' ';
			}

			$output .= $gateway->get_input_html();

			$output .= ' ';

			$output .= Pronamic_IDeal_IDeal::htmlHiddenFields( array(
				'pronamic_pay_s2member_index'                => $this->index,
				'pronamic_pay_s2member_hash'                 => $this->create_hash( $hash_data ),
				'pronamic_pay_s2member_data[order_id]'       => $atts['order_id'],
				'pronamic_pay_s2member_data[period]'         => $atts['period'],
				'pronamic_pay_s2member_data[cost]'           => $atts['cost'],
				'pronamic_pay_s2member_data[level]'          => $atts['level'],
				'pronamic_pay_s2member_data[description]'    => $atts['description'],
				'pronamic_pay_s2member_data[ccaps]'          => $atts['ccaps'],
				'pronamic_pay_s2member_data[payment_method]' => $atts['payment_method'],
			) );

			$output .= sprintf(
				'<input name="%s" value="%s" type="submit" />',
				esc_attr( 'pronamic_pay_s2member' ),
				esc_attr( $atts['button_text'] )
			);

			$output .= '</form>';
		}

		return $output;
	}

	//////////////////////////////////////////////////

	/**
	 * Handle payment
	 */
	public function handle_payment() {
		if ( filter_has_var( INPUT_POST, 'pronamic_pay_s2member' ) ) {
			$index = filter_input( INPUT_POST, 'pronamic_pay_s2member_index', FILTER_SANITIZE_STRING );
			$hash = filter_input( INPUT_POST, 'pronamic_pay_s2member_hash', FILTER_SANITIZE_STRING );
			$data = filter_input( INPUT_POST, 'pronamic_pay_s2member_data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

			if ( $hash === $this->create_hash( $data ) ) {
				// Config
				$config_id = get_option( 'pronamic_pay_s2member_config_id' );

				// Gateway
				$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $config_id );

				// Data
				$data = new Pronamic_WP_Pay_Extensions_S2Member_PaymentData( $data );

				$email = $data->get_email();

				if ( ! empty( $email ) ) {
					// Start
					$payment = Pronamic_WP_Pay_Plugin::start( $config_id, $gateway, $data, $data->get_payment_method() );

					update_post_meta( $payment->get_id(), '_pronamic_payment_s2member_period', $data->get_period() );
					update_post_meta( $payment->get_id(), '_pronamic_payment_s2member_level', $data->get_level() );
					update_post_meta( $payment->get_id(), '_pronamic_payment_s2member_ccaps', $data->get_ccaps() );

					$error = $gateway->get_error();

					if ( is_wp_error( $error ) ) {
						// Set error message
						$this->error[ $index ] = array( Pronamic_WP_Pay_Plugin::get_default_error_message() );

						foreach ( $error->get_error_messages() as $message ) {
							$this->error[ $index ][] = $message;
						}
					} else {
						// Redirect
						$gateway->redirect( $payment );
					}
				}
			}
		}
	}

	/**
	 * Payment error for shortcode
	 *
	 * @param int $index Shortcode index
	 * @return bool/string Default: false. Error string in case of payment error
	 *
	 * @since 1.1.0
	 */
	public function payment_error( $index = null ) {
		if ( ! is_int( $index ) ) {
			$index = $this->index;
		}

		if ( isset( $this->error[ $index ] ) ) {
			return sprintf(
				'<p><strong>%s</strong><br><em>%s: %s</em></p>',
				$this->error[ $index ][0],
				__( 'Error', 'pronamic_ideal' ),
				$this->error[ $index ][1]
			);
		}

		return false;
	}
}
