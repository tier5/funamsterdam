jQuery( document ).ready( function( $ ) {
	
	jQuery('input[name=_edd_vou_exp_type]').change(function(){
		
		var value = jQuery( 'input[name=_edd_vou_exp_type]:checked' ).val();
		
		if( value == 'specific_date' ){
			
			jQuery( '._edd_vou_exp_date' ).show();
			jQuery( '._edd_vou_days_diff' ).hide();
			jQuery( '._edd_vou_custom_days' ).hide();
			jQuery( '.custom-desc' ).hide();
			
		} else if( value == 'based_on_purchase' ){
			
			jQuery( '._edd_vou_days_diff ' ).show();
			jQuery( '._edd_vou_exp_date' ).hide();
			
			var edd_vou_days_diff = jQuery('select[name=_edd_vou_days_diff] option:selected').val();
			if( edd_vou_days_diff == 'cust' ){
				jQuery( '._edd_vou_custom_days' ).show();
				jQuery( '.custom-desc' ).hide();
			}else{
				jQuery( '._edd_vou_custom_days' ).hide();
				jQuery( '.custom-desc' ).show();
			}
		}
	});
	
	var exp_type = jQuery( 'input[name=_edd_vou_exp_type]:checked' ).val();
	
	if( exp_type == 'based_on_purchase' ){
		jQuery( '._edd_vou_exp_date' ).hide();
		
		var edd_vou_days_diff = jQuery('select[name=_edd_vou_days_diff] option:selected').val();
		
		if( edd_vou_days_diff == 'cust' ){
			jQuery( '._edd_vou_custom_days' ).show();
			jQuery( '.custom-desc' ).hide();
		}else{
			jQuery( '._edd_vou_custom_days' ).hide();
			jQuery( '.custom-desc' ).show();
		}
	}else if( exp_type == 'specific_date' ){
		
		jQuery( '._edd_vou_exp_date' ).show();
		jQuery( '._edd_vou_days_diff' ).hide();
		jQuery( '._edd_vou_custom_days' ).hide();
	}else{
		
		jQuery( '._edd_vou_exp_date' ).show();
		jQuery( '._edd_vou_days_diff' ).hide();
		jQuery( '._edd_vou_custom_days' ).hide();
		jQuery( '.custom-desc' ).hide();
	}
	
	
	jQuery('select[name=_edd_vou_days_diff]').change(function() {
		
		var days_diff = jQuery(this).val();
		
        if( days_diff == 'cust' ){
        	jQuery( '._edd_vou_custom_days' ).show();
        	jQuery( '.custom-desc' ).hide();
        }else{
        	jQuery( '._edd_vou_custom_days' ).hide();
        	jQuery( '.custom-desc' ).show();
        }
	 	
	});
	
	
	//on click of used codes button
	$( document ).on( "click", ".edd-vou-meta-vou-purchased-data", function() {
		
		var popupcontent = $(this).parent().find( '.edd-vou-purchased-codes-content' );
		popupcontent.show();
		$(this).parent().find( '.edd-vou-popup-overlay' ).show();
		$('html, body').animate({ scrollTop: popupcontent.offset().top - 50 }, 500);
		
	});
	$( document ).on( "click", ".edd-vou-meta-vou-used-data", function() {
		
		var popupcontent = $(this).parent().find( '.edd-vou-used-codes-content' );
		popupcontent.show();
		$(this).parent().find( '.edd-vou-popup-overlay' ).show();
		$('html, body').animate({ scrollTop: popupcontent.offset().top - 50 }, 500);
		
	});
	//, .edd-vou-meta-vou-import-data
	$( document ).on( "click", ".edd-vou-meta-vou-import-data", function() {
		
		$( '.edd-vou-codes-error' ).hide();
		$('.edd-vou-file-errors').hide();
		$('.edd-vou-delete-code').val('');
		$('.edd-vou-no-of-voucher').val('');
		$('.edd-vou-code-prefix').val('');
		$('.edd-vou-code-seperator').val('');
		//$('.edd-vou-code-pattern').val('');
		$('.edd-vou-csv-sep').val('');
		$('.edd-vou-csv-enc').val('');
		$('.edd-vou-csv-file').val('');
		
		$( '.edd-vou-import-content' ).show();
		$( '.edd-vou-import-overlay' ).show();
		
		var importcodecontent = $( '.edd-vou-import-content' );
		$('html, body').animate({ scrollTop: importcodecontent.offset().top - 60 }, 500);
		
	});
	
	
	//on click of close button or overlay
		
	$( document ).on( "click", ".edd-vou-popup-overlay, .edd-vou-close-button", function() {
		
		//when import csv file popup is open
		if( $('.edd-vou-file-errors').length > 0 ) {
			$('.edd-vou-file-errors').hide();
			$('.edd-vou-file-errors').html('');
		}
		
		//common code for both popup of voucher codes used and import csv file
		$( '.edd-vou-popup-content' ).hide();
		$( '.edd-vou-popup-overlay' ).hide();
	});
	
	//on click of import coupon codes button, import code
	$( document ).on( "click", ".edd-vou-import-btn", function() {
		
		var existing_code = $('#_edd_vou_codes').val();
		var delete_code = $( '.edd-vou-delete-code' ).val();
		var no_of_voucher = $( '.edd-vou-no-of-voucher' ).val();
		var code_prefix = $( '.edd-vou-code-prefix' ).val();
		var code_seperator = $( '.edd-vou-code-seperator' ).val();
		var code_pattern = $( '.edd-vou-code-pattern' ).val().toLowerCase();
			
		$( '.edd-vou-file-errors' ).html('').hide();
		
		var error_msg = '';
		if( no_of_voucher == '' ) {
			error_msg += EddVouMeta.noofvouchererror;
		}
		if( code_pattern == '' ) {
			
			error_msg += EddVouMeta.patternemptyerror;
			
		} else if( code_pattern.indexOf('l') == '-1' && code_pattern.indexOf('d') == '-1' ) {
			
			error_msg += EddVouMeta.generateerror;
		}
		
		if( error_msg != '' ) {
			$( '.edd-vou-file-errors' ).html(error_msg).show();
		} else {
		
			$( '.edd-vou-loader' ).show();
			var data = {
							action			: 'edd_vou_import_code',
							noofvoucher		: no_of_voucher,
							codeprefix		: code_prefix,
							codeseperator	: code_seperator,
							codepattern		: code_pattern,
							existingcode	: existing_code,
							deletecode		: delete_code
						};
		
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			$.post(ajaxurl, data, function(response) {
				var import_code = response;
				$( '.edd-vou-loader' ).hide();
				$( '#_edd_vou_codes' ).val(import_code);
				$( '.edd-vou-popup-content' ).hide();
				$( '.edd-vou-popup-overlay' ).hide();
				$( '#edd_vou_codes_error' ).hide();
				
				var voucodecontent = $('#_edd_vou_codes').removeClass( 'edd-vou-codes-red-border' );
				$('html, body').animate({ scrollTop: voucodecontent.offset().top - 50 }, 500);
				
			});
		}
	});
	
	//ajax call to get voucher codes from csv file
	$( document ).on( "click", ".edd-vou-meta-vou-import-codes", function() {
		
		$( '.edd-vou-file-errors' ).hide();
		$( '.edd-vou-file-errors' ).html('');
		
		var fseprator = $('.edd-vou-csv-sep').val();
		var fenclosure = $('.edd-vou-csv-enc').val();
		var existing_code = $('#_edd_vou_codes').val();
		var ext = '';
		var filename = '';
		var error = false;
		var errorstr = '';
		
		$('.edd-vou-csv-file').filter(function(){
			
			filename = $(this).val();
			//alert(filename);
			ext = filename.substring(filename.lastIndexOf('.') + 1);
			
			if( filename == '' ) {
				error = true;
				errorstr += EddVouMeta.fileerror;
			}
			if( filename != '' && ext != 'csv') {
				error = true;
				errorstr += EddVouMeta.filetypeerror;
			}
		});
		
		if( error == true ) { //check file type must be csv
			
			$('.edd-vou-file-errors').show();
			$('.edd-vou-file-errors').html(errorstr);
			return false;
			
		} else {
			
			if( filename != '' ) {
				 
				$('#edd_vou_existing_code').val( existing_code );
				
				$('form#edd_vou_import_csv').ajaxForm({
				    beforeSend: function() {
				    },
				    uploadProgress: function(event, position, total, percentComplete) {
				    },
				    success: function() {
				    },
					complete: function(xhr) {
						
						//alert('ajaxfileupload---'+xhr.responseText);
						$('textarea#_edd_vou_codes').val(xhr.responseText);
						$( '.edd-vou-popup-content' ).hide();
						$( '.edd-vou-popup-overlay' ).hide();
						$( '#edd_vou_codes_error' ).hide();
						$('.edd-vou-csv-file').attr({ value: '' });
						//filename = '';
														
						var voucodecontent = $('#_edd_vou_codes').removeClass( 'edd-vou-codes-red-border' );
						$('html, body').animate({ scrollTop: voucodecontent.offset().top - 50 }, 500);
						
					}
				});
			}
		}
	});
	
	//repeater field add more
	jQuery( document ).on( "click", ".edd-vou-repeater-add", function() {
	
		jQuery(this).prev('div.edd-vou-meta-repater-block')
			.clone()
			.insertAfter('.edd-vou-meta-repeat div.edd-vou-meta-repater-block:last');
			
		jQuery(this).parent().find('div.edd-vou-meta-repater-block:last input').val('');
		jQuery(this).parent().find('div.edd-vou-meta-repater-block:last .edd-vou-repeater-remove').show();
		
	});
	
	//remove repeater field
	jQuery( document ).on( "click", ".edd-vou-repeater-remove", function() {
	   jQuery(this).parent('.edd-vou-meta-repater-block').remove();
	});
	
	edd_vou_manage_voucher_option();
	
	// Hide edd voucher by changed download type bundle
	$( document ).on( 'change', '#_edd_product_type', function() {

		edd_vou_manage_voucher_option();
	});
	
	// Hide edd voucher by clicked enable voucher
	/*$( document ).on( 'click', '#edd_variable_pricing', function() {
		edd_vou_manage_voucher_option();
	});*/
	
	// Change available total codes description by usability
	$( document ).on( 'click', '.edd-vou-using-type', function() {

		var usability = jQuery( this ).val();
		jQuery( '.edd-vou-avail-code-desc' ).hide();
		
		if( usability == '1' ) {
			jQuery( '.edd-vou-avail-code-desc' ).show();
		}
	});
	
	// Check recipient name
	if($("#_edd_vou_enable_recipient_name").is(':checked'))
	    jQuery( '.edd_vou_recipient_name' ).parents("tr").show();  // checked
	else
	    jQuery( '.edd_vou_recipient_name' ).parents("tr").hide();  // unchecked
		    
	$( document ).on( 'change', '#_edd_vou_enable_recipient_name', function() {
						
		if($("#_edd_vou_enable_recipient_name").is(':checked'))
		    jQuery( '.edd_vou_recipient_name' ).parents("tr").show();  // checked
		else
		    jQuery( '.edd_vou_recipient_name' ).parents("tr").hide();  // unchecked
	});
	
	// Check recipient email
	if($("#_edd_vou_enable_recipient_email").is(':checked'))
	    jQuery( '.edd_vou_recipient_email' ).parents("tr").show();  // checked
	else
	    jQuery( '.edd_vou_recipient_email' ).parents("tr").hide();  // unchecked
		    
	$( document ).on( 'change', '#_edd_vou_enable_recipient_email', function() {
						
		if($("#_edd_vou_enable_recipient_email").is(':checked'))
		    jQuery( '.edd_vou_recipient_email' ).parents("tr").show();  // checked
		else
		    jQuery( '.edd_vou_recipient_email' ).parents("tr").hide();  // unchecked
	});
	
	// Check recipient message
	if($("#_edd_vou_enable_recipient_message").is(':checked'))
	    jQuery( '.edd_vou_recipient_message' ).parents("tr").show();  // checked
	else
	    jQuery( '.edd_vou_recipient_message' ).parents("tr").hide();  // unchecked
		    
	$( document ).on( 'change', '#_edd_vou_enable_recipient_message', function() {
						
		if($("#_edd_vou_enable_recipient_message").is(':checked'))
		    jQuery( '.edd_vou_recipient_message' ).parents("tr").show();  // checked
		else
		    jQuery( '.edd_vou_recipient_message' ).parents("tr").hide();  // unchecked
	});
	
	// Check Voucher Code is not empty on clicked publish/update button
	$( document ).on( 'click', '#publish', function() {
		
		var error = 'false';
		var product_type 	=  $( '#_edd_product_type' ).val();
		//var variable_type 	=  $( '#edd_variable_pricing' ).is(':checked');
		var check_code		= true;
		
		//if( product_type == 'bundle' || variable_type ) {
		if( product_type == 'bundle' ) {
			check_code = false;
		}
		
		$( '#edd_vou_codes_error' ).hide();
		$( '#edd_vou_days_error' ).hide();
		
		if( $( '#_edd_vou_enable' ).is( ':checked' ) && check_code ) {
			
			var codes = $( '#_edd_vou_codes' ).removeClass( 'edd-vou-codes-red-border' ).val();
			if( codes == '' || codes == 'undefined' ) {
				
				$( this ).parent().find( '.spinner' ).hide();
				$( this ).removeClass( 'button-primary-disabled' );
				$( '#edd_vou_codes_error' ).show();
				
				if( $('#_edd_vou_codes').is(':visible') ) {
					
					var voucodecontent = $('#_edd_vou_codes').addClass( 'edd-vou-codes-red-border' ).focus();
					$( '#edd_vou_meta' ).removeClass( 'edd-vou-codes-red-border' );
					
				} else {
					
					var voucodecontent = $('#edd_vou_meta').addClass( 'edd-vou-codes-red-border' ).focus();
					$( '#_edd_vou_codes' ).removeClass( 'edd-vou-codes-red-border' );
					
				}
				
				
				$('html, body').animate({ scrollTop: voucodecontent.offset().top - 50 }, 500);
				
				var error = 'true';
				//return false;
			}
		}
		
		/*var exp_type			= jQuery( 'input[name=_edd_vou_exp_type]:checked' ).val();	
		var edd_vou_days_diff	= jQuery('select[name=_edd_vou_days_diff] option:selected').val();
		
		if( exp_type == 'based_on_purchase' && edd_vou_days_diff == 'cust' ) {
			
			var days = $( '#_edd_vou_custom_days' ).removeClass( 'woo-vou-codes-red-border' ).val();
			if( days == '' || days == 'undefined' ) {
				
				$( this ).parent().find( '.spinner' ).hide();
				$( this ).removeClass( 'button-primary-disabled' );
				$( '#edd_vou_days_error' ).show();
				
				var voucodecontent = $('#_edd_vou_custom_days').addClass( 'woo-vou-codes-red-border' ).focus(); 
				$('html, body').animate({ scrollTop: voucodecontent.offset().top - 50 }, 500);
				
				var error = 'true';
				
			}else if( ( days != '' && !edd_vou_is_numeric(days) ) || days < '1' ) {
				
				$( this ).parent().find( '.spinner' ).hide();
				$( this ).removeClass( 'button-primary-disabled' );
				$( '#edd_vou_days_error' ).show();
				
				var voucodecontent = $('#_edd_vou_custom_days').addClass( 'woo-vou-codes-red-border' ).focus(); 
				$('html, body').animate({ scrollTop: voucodecontent.offset().top - 50 }, 500);
				
				var error = 'true';
			}
		}*/

		// validate url
		var website_url = $("#_edd_vou_website").val();
		if( $( '#_edd_vou_enable' ).is( ':checked' ) && website_url != '' && !edd_vou_is_url_valid(website_url) ) {
			
			$( this ).parent().find( '.spinner' ).hide();
			$( this ).removeClass( 'button-primary-disabled' );
			$('#edd_vou_website_url_error').show();
			
			websitecontent = $('#_edd_vou_website').addClass('edd-vou-codes-red-border').focus();
			
			$('html, body').animate({ scrollTop: websitecontent.offset().top - 50 }, 500);			
			var error = 'true';
		}
		
		if( error == 'true' ){
			return false;
		}else {
			return true;
		}
		
	});
	
	// Check Voucher Code validate on key up
	$( document ).on( 'keyup', '#_edd_vou_codes', function() {
		
		var codes = $( this ).val();
		if( codes == '' || codes == 'undefined' ) {
			
			$( this ).addClass( 'edd-vou-codes-red-border' );
			$( '#edd_vou_codes_error' ).show();
			
		} else {
			
			$( this ).removeClass( 'edd-vou-codes-red-border' );
			$( '#edd_vou_codes_error' ).hide();
		}
	});
	
	jQuery(".edd-vou-dropdown-wrapper select").each(function () {
		jQuery(this).chosen({search_contains:true});
	});
});

function edd_vou_manage_voucher_option() {
	
	jQuery( '#edd_vou_meta' ).show();
	
	//var enable_variable = jQuery( '#edd_variable_pricing' ).is( ":checked" );
	
	var product_type =  jQuery( '#_edd_product_type' ).val() ;
	
	//if( enable_variable || product_type == 'bundle' ) {// Check if download type is bundle or enable_variable is not checked
	if( product_type == 'bundle' ) {// Check if download type is bundle or enable_variable is not checked
		jQuery( '#edd_vou_meta' ).hide();
	}
}

function edd_vou_is_numeric(input){
	
    return (input - 0) == input && (''+input).replace(/^\s+|\s+$/g, "").length > 0;
}

// The function that allow only number [0-9]
function edd_vou_is_number_key_per_page( evt ) {
	
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}

function edd_vou_is_url_valid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}