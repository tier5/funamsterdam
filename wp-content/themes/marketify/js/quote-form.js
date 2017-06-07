(function($) {
	$(document).ready(function() {
		$('.img-select ul.gfield_checkbox li input[type=checkbox]').on('change', function() {
			if($(this).is(':checked')){
				$(this).closest('li').addClass('checked');
			} else {
				$(this).closest('li').removeClass('checked');
			}
		});
		$('.quote-form .gfield_contains_required input').on('blur', function() {
			var li = $(this).closest('li');
			li.find('.validation_message').remove();
			if($(this).val().trim() == '') {
				li.addClass('gfield_error').append('<div class="gfield_description validation_message">This field is required.</div>');
			} else {
				li.removeClass('gfield_error');
			}
		});
		$(document).on( 'click', '.quote-popup-trigger', function(e) {
			e.preventDefault();
			$.magnificPopup.open({
				items : {
					src : $(this).attr( 'href' ),
					type : 'inline',
				},
				callbacks: {
					open: function() {
						$(window).resize();
					}
				}
			});
			return false;
		});
	});
})(jQuery);