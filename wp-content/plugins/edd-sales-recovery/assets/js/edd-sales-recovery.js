jQuery( document ).ready( function ($) {
	if ( $( '#email-initial-preview-wrap' ).length ) {
		var emailInitialPreview = $( '#email-initial-preview' );
		$( '#open-email-initial-preview' ).colorbox({
			inline: true,
			href: emailInitialPreview,
			width: '80%',
			height: 'auto'
		});
	}

	if ( $( '#email-interim-preview-wrap' ).length ) {
		var emailInterimPreview = $( '#email-interim-preview' );
		$( '#open-email-interim-preview' ).colorbox({
			inline: true,
			href: emailInterimPreview,
			width: '80%',
			height: 'auto'
		});
	}

	if ( $( '#email-final-preview-wrap' ).length ) {
		var emailFinalPreview = $( '#email-final-preview' );
		$( '#open-email-final-preview' ).colorbox({
			inline: true,
			href: emailFinalPreview,
			width: '80%',
			height: 'auto'
		});
	}

	if ( $( '#edd_settings\\[eddsr_recovery_start_date\\]' ).length ) {
		$( '#edd_settings\\[eddsr_recovery_start_date\\]' ).datepicker({
			dateFormat: 'yy-mm-dd'
		});
	}
});
