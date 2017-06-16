jQuery(function($){

	$('body').on( 'change', 'select#_wc_deposit_type', function() {
		$('._wc_deposit_payment_plans_field').hide();
		$('._wc_deposit_amount_field').hide();
		$('._wc_deposit_multiple_cost_by_booking_persons_field').hide();

		if ( 'percent' === $(this).val() || 'fixed' === $(this).val() ) {
			$('._wc_deposit_amount_field').show();
		} else if ( 'plan' === $(this).val() ) {
			$('._wc_deposit_payment_plans_field').show();
		}

		if ( 'fixed' === $(this).val() && 'booking' === $('#product-type').val() ) {
			$('._wc_deposit_multiple_cost_by_booking_persons_field').show();
		}
	});

	$('select#_wc_deposit_type').change();

});