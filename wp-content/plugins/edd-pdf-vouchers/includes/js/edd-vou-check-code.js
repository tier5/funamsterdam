jQuery( document ).ready( function( $ ) {
// Check Voucher code is valid or not
	$( document ).on( 'click', '#edd_vou_check_voucher_code', function() {
	
		//Voucher Code
		var voucode = $( '#edd_vou_voucher_code' ).val().trim();
		
		if( voucode == '' || voucode == 'undefine' ) {
			
			//hide submit row
			$( '.edd-vou-voucher-code-submit-wrap' ).fadeOut();
			
			$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-success' ).addClass( 'edd-vou-voucher-code-error' ).html( EddVouCheck.check_code_error ).show();
			
		} else {
			
			//show loader
			$( '.edd-vou-check-voucher-code-loader' ).css( 'display', 'inline' );
			
			//hide error message
			$( '.edd-vou-voucher-code-msg' ).hide();
			
			var data = {
							action	: 'edd_vou_check_voucher_code',
							voucode	: voucode,
							ajax	: true
						};
			//call ajax to chcek voucher code
			jQuery.post( EddVouCheck.ajaxurl, data, function( response ) {
				 
				 
				var response_data = jQuery.parseJSON(response);
				
				if( response_data.success) {
					
					//show submit row
					if( response_data.upcoming || response_data.expire ){
						$( '.edd-vou-voucher-code-submit-wrap' ).fadeOut();
						$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-success' ).addClass( 'edd-vou-voucher-code-error' ).html( response_data.success ).show();
					} else {
						$( '.edd-vou-voucher-code-submit-wrap' ).fadeIn();
						$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-error' ).addClass( 'edd-vou-voucher-code-success' ).html( response_data.success ).show();
					}
					
					if( response_data.product_detail ) {
						$( '.edd-vou-voucher-code-msg' ).append(response_data.product_detail);
					}
					
				} else if( response_data.error ) {
					
					//hide submit row
					$( '.edd-vou-voucher-code-submit-wrap' ).fadeOut();
					
					$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-success' ).addClass( 'edd-vou-voucher-code-error' ).html( EddVouCheck.code_invalid ).show();
					
				} else if ( response_data.used ) {
					
					//hide submit row 
					$( '.edd-vou-voucher-code-submit-wrap' ).fadeOut();
					
					$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-success' ).addClass( 'edd-vou-voucher-code-error' ).html( response_data.used ).show();
					
				}
				//hide loader
				$( '.edd-vou-check-voucher-code-loader' ).hide();
				
			});
		}
	});
	
	// Submit Voucher code
	$( document ).on( 'click', '#edd_vou_voucher_code_submit', function() {
	
		//Voucher Code
		var voucode = $( '#edd_vou_voucher_code' ).val().trim();
		
		if( ( voucode == '' || voucode == 'undefine' ) ) {
			
			//hide submit row
			$( '.edd-vou-voucher-code-submit-wrap' ).fadeOut();
			
			$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-success' ).addClass( 'edd-vou-voucher-code-error' ).html( EddVouCheck.check_code_error ).show();
			
		} else {
			
			//show loader
			$( '.edd-vou-voucher-code-submit-loader' ).css( 'display', 'inline' );
			
			//hide error message
			$( '.edd-vou-voucher-code-msg' ).hide();
			
			var data = {
							action		: 'edd_vou_save_voucher_code',
							voucode		: voucode
						};
			//call ajax to save voucher code
			jQuery.post( EddVouCheck.ajaxurl, data, function( response ) {
				//alert( response );
				if( response ) {
					
					//Voucher Code
					$( '#edd_vou_voucher_code' ).val( '' );
					
					//hide submit row
					$( '.edd-vou-voucher-code-submit-wrap' ).fadeOut();
					
					$( '.edd-vou-voucher-code-msg' ).removeClass( 'edd-vou-voucher-code-error' ).addClass( 'edd-vou-voucher-code-success' ).html( EddVouCheck.code_used_success ).show();
					
				}
				//hide loader
				$( '.edd-vou-voucher-code-submit-loader' ).hide();
				
			});
		}
		
	});
	
	// Validate Recipient fields on click add to cart button
	$('.edd-add-to-cart').on('click', function () {
		
		// Get recipient name details
		var recipient_name 					= $("#edd_vov_recipient_name").val();
		var recipient_name_is_required 		= $("#edd_vov_recipient_name").data("required");
		var recipient_name_label 			= $("#edd_vov_recipient_name").data("label");
		
		// Get recipient email details
		var recipient_email 				= $("#edd_vou_recipient_email").val();
		var recipient_email_is_required 	= $("#edd_vou_recipient_email").data("required");
		var recipient_email_label 			= $("#edd_vou_recipient_email").data("label");
		
		// Get recipient message details
		var recipient_message 				= $("textarea#edd_vou_recipient_message").val();		
		var recipient_message_is_required 	= $("textarea#edd_vou_recipient_message").data("required");
		var recipient_message_label 		= $("textarea#edd_vou_recipient_message").data("label");
		
		// Initialize error variale
		var error = false;
		$(".edd-vou-error").html('');		
		
		if( recipient_name_is_required == "on" ) { // if recipient name is required then only check for validation
			
			if( recipient_name == '' || recipient_name == 'undefined' ) {
				var error_message = EddVouCheck.recipient_required_error+" "+recipient_name_label+".";
				$("#edd_vov_recipient_name").siblings(".edd-vou-error").html( error_message );
				error = true;
			}	
		}									
		
		if( recipient_email_is_required ) { // if recipient email is required then only check for validation
			
			if( recipient_email == '' || recipient_email == 'undefined' ) {
				var error_message = EddVouCheck.recipient_required_error+" "+recipient_email_label+".";
				$("#edd_vou_recipient_email").siblings(".edd-vou-error").html( error_message );			
				error = true;
			}  else if( !validateEmail( recipient_email ) ) {
				var error_message = EddVouCheck.recipient_email_invalid_error+" "+recipient_email_label+".";
				$("#edd_vou_recipient_email").siblings(".edd-vou-error").html( error_message );			
				error = true;
			}
		} else {			
			if ( $(this).parents('form').find('#edd_vou_recipient_email').length > 0 ) {
				if( !validateEmail( recipient_email ) ) {
					var error_message = EddVouCheck.recipient_email_invalid_error+" "+recipient_email_label+".";
					$("#edd_vou_recipient_email").siblings(".edd-vou-error").html( error_message );			
					error = true;
				}
			}
		}
		
		
		if( recipient_message_is_required ) { // if recipient message is required then only check for validation
						
			if( recipient_message == '' || recipient_message == 'undefined' ) {
				var error_message = EddVouCheck.recipient_required_error+" "+recipient_message_label+".";	
				$("textarea#edd_vou_recipient_message").siblings(".edd-vou-error").html( error_message );			
				error = true;
			}	
		}		
		
		if( error == true ) {
						
			return false;
		}
		
	});
	
	// Function that validates email address through a regular expression.
	function validateEmail(email) {
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if (filter.test(email)) {
			return true;
		}
		else {
			return false;
		}
	}
			
});