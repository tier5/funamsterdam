;(function($) {
    "use strict";


    $(document).ready(function() {
        $(".cart").addClass("mfp-hide");
        $('body').on('click', '.btn-availability', function(e) {
            e.preventDefault();
            
            $.magnificPopup.open({
              items: {
                src: '.cart', // can be a HTML string, jQuery object, or CSS selector
                type: 'inline'
              },
              removalDelay: 500, //delay removal by X to allow out-animation
              callbacks: {
                beforeOpen: function() {
                   this.st.mainClass = 'mfp-zoom-in cart-form-popup';
                }
              },
              midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
        });


        $('body').on('click', '.btn-price-guarantee', function(e) {
            e.preventDefault();

            $.magnificPopup.open({
              items: {
                src: '.price-guarantee', // can be a HTML string, jQuery object, or CSS selector
                type: 'inline'
              },
              removalDelay: 500, //delay removal by X to allow out-animation
              callbacks: {
                beforeOpen: function() {
                   this.st.mainClass = 'mfp-zoom-in price-guarantee-popup';
                }
              },
              midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
        });

    });

})(jQuery);
