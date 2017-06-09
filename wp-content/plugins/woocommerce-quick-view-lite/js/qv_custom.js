jQuery(document).ready(function($){
	
	$('body').prepend('<div id="wc-quick-view-popup"><div class="wc-quick-view-overlay"></div><div class="quick-wcqv-wrapper"><div class="quick-wcqv-main"><div class="quick-wcqv-head"><a href="#" id="wc-quick-view-close" class="quick-wcqv-close">X</a></div><div id="wc-quick-view-content" class="woocommerce single-product"><img id="img-ader" src="'+blog2+'images/loading.gif"/></div></div></div></div>');
	
	var wc_popup    = $(document).find( '#wc-quick-view-popup' ),
        wc_overlay  = wc_popup.find( '.wc-quick-view-overlay'),
        wc_content  = wc_popup.find( '#wc-quick-view-content' ),
        wc_close    = wc_popup.find( '#wc-quick-view-close' );
		
		
		jQuery(document).on('click','.ajax',showQuickView);
		function showQuickView(e){
			
			e.preventDefault();
			
			var pro_id = $(this).attr('pro_id'); 
		    var action = $(this).attr('action'); 
		    //var product_type = $(this).attr('product_type'); 
		if( ! wc_popup.hasClass( 'active' ) ) {
                wc_popup.addClass('active');
		}    
		$.ajax({
			type: 'POST',
			url : ph_Ajax.ajaxurl,
			data : {
					pid : pro_id,
					action : action
					},
			success: function(data) {
				
				wc_content.html(data);

			}
		});
		}

						
	

		wc_close.on( 'click', function(e) {
			
            e.preventDefault();
           
            wc_popup.removeClass('active');

            setTimeout(function () {
                wc_content.html('<img id="img-ader" src="'+blog2+'images/loading.gif"/>');
            }, 1000);
       
        });	
		
		wc_overlay.on( 'click', function(e){
				
			e.preventDefault();
           
            wc_popup.removeClass('active');

            setTimeout(function () {
                wc_content.html('<img id="img-ader" src="'+blog2+'images/loading.gif"/>');
            }, 1000);
			
        });
		
		
	
		

});

