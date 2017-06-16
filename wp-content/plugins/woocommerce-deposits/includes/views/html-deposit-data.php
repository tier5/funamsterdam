<div id="deposits" class="panel woocommerce_options_panel">
	<div class="options_group">
		<?php
			woocommerce_wp_select( array(
				'id'          => '_wc_deposit_enabled',
				'label'       => __( 'Enable Deposits', 'woocommerce-deposits' ),
				'description' => __( 'Specify if users can pay a deposit for this product.', 'woocommerce-deposits' ),
				'options'     => array(
					''         => __( 'Inherit sitewide settings', 'woocommerce-deposits' ),
					'optional' => __( 'Yes - deposits are optional', 'woocommerce-deposits' ),
					'forced'   => __( 'Yes - deposits are required', 'woocommerce-deposits' ),
					'no'       => __( 'No', 'woocommerce-deposits' )
				),
				'style'    => 'min-width:50%;',
				'desc_tip' => true,
				'class'    => 'select'
			) );

			woocommerce_wp_select( array(
				'id'          => '_wc_deposit_type',
				'label'       => __( 'Deposit Type', 'woocommerce-deposits' ),
				'description' => __( 'Choose how customers can pay for this product using a deposit.', 'woocommerce-deposits' ),
				'options'     => array(
					''        => __( 'Inherit sitewide settings', 'woocommerce-deposits' ),
					'percent' => __( 'Percentage', 'woocommerce-deposits' ),
					'fixed'   => __( 'Fixed Amount', 'woocommerce-deposits' ),
					'plan'    => __( 'Payment Plan', 'woocommerce-deposits' )
				),
				'style'    => 'min-width:50%;',
				'desc_tip' => true,
				'class'    => 'select'
			) );

			woocommerce_wp_checkbox( array(
				'id'            => '_wc_deposit_multiple_cost_by_booking_persons',
				'label'         => __( 'Booking Persons', 'woocommerce-deposits' ),
				'description'   => __( 'Multiply fixed deposits by the number of persons booking', 'woocommerce-deposits' ),
				'wrapper_class' => 'show_if_booking'
			) );

			woocommerce_wp_text_input( array(
				'id'          => '_wc_deposit_amount',
				'label'       => __( 'Deposit Amount', 'woocommerce-deposits' ),
				'placeholder' => wc_format_localized_price( 0 ),
				'description' => __( 'The amount of deposit needed. Do not include currency or percent symbols.', 'woocommerce' ),
				'data_type'   => 'price',
				'desc_tip'    => true
			) );
		?>
		<p class="form-field _wc_deposit_payment_plans_field">
			<label for="_wc_deposit_payment_plans"><?php _e( 'Payment Plans', 'woocommerce-deposits' ) ?></label>
			<?php
			$plan_ids = WC_Deposits_Plans_Manager::get_plan_ids();

			if ( ! $plan_ids ) {
				echo __( 'You have not created any payment plans yet.', 'woocommerce-deposits' );
				echo ' <a href="' .  esc_url( admin_url( 'edit.php?post_type=product&page=deposit_payment_plans' ) ) . '" class="button button-small" target="_blank">' . __( 'Create a Payment Plan', 'woocommerce-deposits' ) . '</a>';
			} else {
				?>
				<select id="_wc_deposit_payment_plans" name="_wc_deposit_payment_plans[]" class="wc-enhanced-select" style="min-width: 50%;" multiple="multiple" placeholder="<?php _e( 'Choose some plans', 'woocommerce-deposits' ) ?>">
				<?php
					global $post;

					$values = (array) get_post_meta( $post->ID, '_wc_deposit_payment_plans', true );

					foreach ( $plan_ids as $id => $name ) {
						echo '<option value="' . esc_attr( $id ) . '" ' . selected( in_array( $id, $values ), true ) . '>' . esc_attr( $name ) . '</option>';
					}
				?>
				</select> <img class="help_tip" data-tip="<?php _e( 'Choose which payment plans customers can use for this product.', 'woocommerce-deposits' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16">
			<?php } ?>
		</p>
	</div>
</div>