var Marketify = {};

Marketify.App = ( function($) {
  function menuToggle() {
		$( '.js-toggle-nav-menu--primary' ).click(function(e) {
			e.preventDefault();

			$( '.nav-menu--primary' ).toggleClass( 'active' );
		});
  }
	function menuSearch() {
		$( '.js-toggle-search' ).click(function(e) {
			e.preventDefault();

			$( '.search-form-overlay' ).toggleClass( 'active' );
		});
	}

	function footerHeight() {
		var checks = $( '.site-info, .footer-widget-areas' );

		checks.each(function() {
			var min      = 0;
			var children = $(this).children();

			children.each(function() {
				if ( $(this).outerHeight() > min )
					min = $(this).outerHeight();
			});

			if ( $(window).width() < 978 )
				children.css( 'height', 'auto' );
			else
				children.css( 'height', min );
		});
	}

	function soliloquySliders() {
		if ( $(window).width() < 500 ) {
			var sliders = $( '.soliloquy' );

			$.each(sliders, function() {
				var image = $(this).find( 'img' ),
				    src   = image.prop( 'src' );

				console.log( src );

				$(this)
					.find( 'li' )
					.css({
						'height'           : $(window).outerHeight(),
						'background-image' : 'url(' + src + ')',
						'background-size'  : 'cover'
					});

				image.hide();
			});
		}
	}

  function initVideos() {
    var video = $( '.header-outer .wp-video video' ).get(0);

    if ( typeof video === 'undefined' ) {
      return;
    }

    var vide = $( '.header-outer .wp-video' ).vide();

    vide.resize();

    function adjustHeight() {
      if ( $(window).width() < 768 ) {
        $( video ).hide();
      } else {
        $( video ).show();
      }
    }

    adjustHeight();

    $(window).resize(function() {
      adjustHeight();
    });

    return;
  }

	return {
		init : function() {
      menuToggle();
			menuSearch();
			footerHeight();
			soliloquySliders();
      initVideos();

			$(window).resize(function() {
				footerHeight();
				soliloquySliders();
			});

			$(document).on( 'click', '.popup-trigger', function(e) {
				e.preventDefault();

				Marketify.App.popup({
					items : {
						src : $(this).attr( 'href' ),
						fixedContentPos: false,
						fixedBgPos: false,
						overflowY: 'scroll'
					}
				});
			});

			$( '.edd_download.content-grid-download' ).attr( 'style', '' );

			$( '.edd-slg-login-wrapper' ).each(function() {
				var link  = $(this).find( 'a' );
				var title = link.attr( 'title' );

				link.html(title).prepend( '<span></span' );
			});

      // section title shims
      $( '.edd_form fieldset > span legend' ).unwrap();

			$( '.gform_title, #bbpress-forums #bbp-user-wrapper h2.entry-title, #bbpress-forums fieldset.bbp-form legend, .fes-form h1, .fes-headers, .edd_form *:not(span) > legend' ).wrapInner( '<span></span>' );

			$('body').on('click.eddwlOpenModal', '.edd-add-to-wish-list', function (e) {
				$( '#edd-wl-modal-label' ).wrapInner( '<span></span>' );
			});


      // sorting widget
			$( '.download-sorting input, .download-sorting select' ).change(function(){
				$(this).closest( 'form' ).submit();
			});

			$( '.download-sorting span' ).click( function(e) {
				e.preventDefault();
				$(this).prev().attr( 'checked', true );
				$(this).closest( 'form' ).submit();
			});

			$( '.entry-image' ).bind( 'touchstart', function(e) {
				$(this).toggleClass( 'hover' );
			});

			$( '.individual-testimonial .avatar' ).wrap( '<div class="avatar-wrap"></div>' );

      function pagi() {
        $( '.edd_downloads_list' ).each(function() {
          var pagi = $(this).find( $( '#edd_download_pagination' ) );

          pagi.insertAfter( '.edd_downloads_list' );
        });

      }

      pagi();

      $( '.download-gallery__image' ).magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        }
      });

		},

		popup : function( args ) {
			return $.magnificPopup.open( $.extend( args, {
				type         : 'inline',
				overflowY    : 'hidden',
				removalDelay : 250
			} ) );
		},

	}
} )(jQuery);

jQuery(document).ready(function() {
	Marketify.App.init();
});
