<?php

/**
 * Title: Easy Digital Downloads Mister Cash gateway
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.2.5
 * @since 1.1.0
 */
class Pronamic_WP_Pay_Extensions_EDD_MisterCashGateway extends Pronamic_WP_Pay_Extensions_EDD_Gateway {
	/**
	 * Construct and initialize Mister Cash gateway
	 */
	public function __construct() {
		parent::__construct( array(
			'id'             => 'pronamic_pay_mister_cash',
			'admin_label'    => __( 'Bancontact', 'pronamic_ideal' ),
			'checkout_label' => __( 'Bancontact', 'pronamic_ideal' ),
			'payment_method' => Pronamic_WP_Pay_PaymentMethods::BANCONTACT,
		) );
	}
}
