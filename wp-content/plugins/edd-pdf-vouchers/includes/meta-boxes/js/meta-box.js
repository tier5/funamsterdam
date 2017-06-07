/**
 * All Types Meta Box Class JS
 *
 * JS used for the custom metaboxes and other form items.
 *
 * Copyright 2011 Ohad Raz (admin@bainternet.info)
 * @since 1.0
 */

//var jQuery =jQuery.noConflict();
function eddVouUpdateRepeaterFields(){
    
      
    /**
     * Datepicker Field.
     *
     * @since 1.0
     */
    jQuery('.edd-vou-meta-date').each( function() {
      
      var jQuerythis  = jQuery(this),
          format = jQuerythis.attr('rel');
  
      jQuerythis.datepicker( { showButtonPanel: true, dateFormat: format } );
      
    });
    
    jQuery('.edd-vou-meta-datetime').each( function() {
      
      var jQuerythis  = jQuery(this),
          format = jQuerythis.attr('rel');
      jQuerythis.datetimepicker({ampm: true,dateFormat : format,  minDate: '0'});//
      
    });
  
    /**
     * Timepicker Field.
     *
     * @since 1.0
     */
    jQuery('.edd-vou-meta-time').each( function() {
      
      var jQuerythis   = jQuery(this),
          format   = jQuerythis.attr('rel'),
          aampm    = jQuerythis.attr('data-ampm');
      if ('true' == aampm)
        aampm = true;
      else
        aampm = false;

      jQuerythis.timepicker( { showSecond: true, timeFormat: format, ampm: aampm } );
      
    });
  
    /**
     * Colorpicker Field.
     *
     * @since 1.0
     */
    /*
    
    
    
    /**
     * Select Color Field.
     *
     * @since 1.0
     */
    jQuery('.edd-vou-meta-color-select').click( function(){
      var jQuerythis = jQuery(this);
      var id = jQuerythis.attr('rel');
      jQuery(this).siblings('.edd-vou-meta-color-picker').farbtastic("#" + id).toggle();
      return false;
    });
  
    /**
     * Add Files.
     *
     * @since 1.0
     */
    jQuery('.edd-vou-meta-add-file').click( function() {
      var jQueryfirst = jQuery(this).parent().find('.file-input:first');
      jQueryfirst.clone().insertAfter(jQueryfirst).show();
      return false;
    });
    
     jQuery('.edd-vou-meta-add-fileadvanced').click( function() {
      var jQueryfirst = jQuery(this).parent().find('.file-input-advanced:first');
      jQueryfirst.clone().insertAfter(jQueryfirst).show();
      return false;
    });
  
    /**
     * Delete File.
     *
     * @since 1.0
     */
  	jQuery( document ).on('click','.edd-vou-meta-upload .edd-vou-meta-delete-file',function(e){
      
      var jQuerythis   = jQuery(this),
          jQueryparent = jQuerythis.parent(),
          data     = jQuerythis.attr('rel');
          
      jQuery.post( ajaxurl, { action: 'at_delete_file', data: data }, function(response) {
        response == '0' ? ( alert( 'File has been successfully deleted.' ), jQueryparent.remove() ) : alert( 'You do NOT have permission to delete this file.' );
      });
      
      return false;
    
    });
  
    /**
     * Reorder Images.
     *
     * @since 1.0
     */
    jQuery('.edd-vou-meta-images').each( function() {
      
      var jQuerythis = jQuery(this), order, data;
      
      jQuerythis.sortable( {
        placeholder: 'ui-state-highlight',
        update: function (){
          order = jQuerythis.sortable('serialize');
          data   = order + '|' + jQuerythis.siblings('.edd-vou-meta-images-data').val();
  
          jQuery.post(ajaxurl, {action: 'at_reorder_images', data: data}, function(response){
            response == '0' ? alert( 'Order saved!' ) : alert( "You don't have permission to reorder images." );
          });
        }
      });
      
    });
    
    /**
     * repeater sortable
     * @since 2.1
     */
    jQuery('.repeater-sortable').sortable();
	
	/**
     * enable chosen
     */
    eddVouFancySelect();
  
  }
var Ed_array = Array;
jQuery(document).ready(function($) {

	 /**
     * DateTimepicker Field.
     *
     * @since 1.0
     */
 	 
    jQuery('.edd-vou-meta-datetime').each( function() {
      
      var jQuerythis  = jQuery(this),
          format = jQuerythis.attr('rel');
  		
      jQuerythis.datetimepicker({ampm: true,dateFormat : format, minDate: '0'});//,timeFormat:'hh:mm:ss',showSecond:true
      
    });
  /**
   *  conditinal fields
   *  @since 2.9.9
   */
  jQuery(".conditinal_control").click(function(){
    if(jQuery(this).is(':checked')){
      jQuery(this).next().show('fast');    
    }else{
      jQuery(this).next().hide('fast');    
    }
  });

  /**
   * enable chosen
   * @since 2.9.8
   */
  eddVouFancySelect();

  /**
   * repeater sortable
   * @since 2.1
   */
  jQuery('.repeater-sortable').sortable(); 
  
  /**
   * repater Field
   * @since 1.1
   */
  //edit
  jQuery( document ).on('click','.edd-vou-meta-re-toggle',function(){
    //jQuery(this).prev().toggle('slow');
    if( jQuery(this).prev().is(':visible') ) {
    	jQuery(this).prev().hide();
    } else {
    	jQuery(this).prev().show();
    }
  });
  
  
  /**
   * Datepicker Field.
   *
   * @since 1.0
   */
  jQuery('.edd-vou-meta-date').each( function() {
    
    var jQuerythis  = jQuery(this),
        format = jQuerythis.attr('rel');

    jQuerythis.datepicker( { showButtonPanel: true, dateFormat: format } );
    
  });

  /**
   * Timepicker Field.
   *
   * @since 1.0
   */
  jQuery('.edd-vou-meta-time').each( function() {
    
    var jQuerythis   = jQuery(this),
          format   = jQuerythis.attr('rel'),
          aampm    = jQuerythis.attr('data-ampm');
      if ('true' == aampm)
        aampm = true;
      else
        aampm = false;

      jQuerythis.timepicker( { showSecond: true, timeFormat: format, ampm: aampm } );
    
  });

  /**
   * Colorpicker Field.
   *
   * @since 1.0
   * better handler for color picker with repeater fields support
   * which now works both when button is clicked and when field gains focus.
   */
  if (jQuery.farbtastic){//since WordPress 3.5
  	jQuery( document ).on('focus','.edd-vou-meta-color',function(){
      load_colorPicker(jQuery(this).next());
    });

  	jQuery( document ).on('focusout','.edd-vou-meta-color',function(){
      hide_colorPicker(jQuery(this).next());
    });

    /**
     * Select Color Field.
     *
     * @since 1.0
     */
  	jQuery( document ).on('click','.edd-vou-meta-color-select',function(){
      if (jQuery(this).next('div').css('display') == 'none')
        load_colorPicker(jQuery(this));
      else
        hide_colorPicker(jQuery(this));
    });

    function load_colorPicker(ele){
      colorPicker = jQuery(ele).next('div');
      input = jQuery(ele).prev('input');

      jQuery.farbtastic(jQuery(colorPicker), function(a) { jQuery(input).val(a).css('background', a); });

      colorPicker.show();
      //e.preventDefault();

      //jQuery(document).mousedown( function() { jQuery(colorPicker).hide(); });
    }

    function hide_colorPicker(ele){
      colorPicker = jQuery(ele).next('div');
      jQuery(colorPicker).hide();
    }
    //issue #15
    jQuery('.edd-vou-meta-color').each(function(){
      var colo = jQuery(this).val();
      if (colo.length == 7)
        jQuery(this).css('background',colo);
    });
  }else{
    //jQuery('.edd-vou-meta-color-iris').wpColorPicker();
  }
  
  /**
   * Add Files.
   *
   * @since 1.0
   */
  jQuery('.edd-vou-meta-add-file').click( function() {
    var jQueryfirst = jQuery(this).parent().find('.file-input:first');
    jQueryfirst.clone().insertAfter(jQueryfirst).show();
    return false;
  });
  /*
  *
  * Advanced Add Files
  */
  jQuery( document ).on('click','.edd-vou-meta-add-fileadvanced',function(){
     var jQueryfirst = jQuery(this).parent().find('.file-input-advanced:last');
     jQueryfirst.clone().insertAfter(jQueryfirst).show();
     jQuery(this).parent().find('.file-input-advanced:last .edd-vou-upload-file-link').val('');
     jQuery(this).parent().find('.file-input-advanced:last .edd-vou-upload-file-name').val('');
     return false;
   });
   
  /*
   *
   * Advanced Add Files
   */
  jQuery( document ).on('click','.edd-vou-delete-fileadvanced',function(){
  	var row = jQuery(this).parent().parent().parent( 'tr' );
  	var count =	row.find('.file-input-advanced').length;
	  	if(count > 1) {
	     jQuery(this).parent('.file-input-advanced').remove();
	  	} else {
	  		alert( EddVou.one_file_min );
	  	}
     return false;
   });
   
   // WP 3.5+ uploader
	
  	jQuery( document ).on('click','.edd-vou-upload-fileadvanced',function(e){

		e.preventDefault();
		
		if(typeof wp == "undefined" || EddVou.new_media_ui != '1' ){// check for media uploader
				
			//Old Media uploader
				
			window.formfield = '';
			e.preventDefault();
			
			window.formfield = jQuery(this).closest('.file-input-advanced');
			
			tb_show('', 'media-upload.php?post_id='+ jQuery('#post_ID').val() + '&type=image&amp;TB_iframe=true');
		      //store old send to editor function
		      window.restore_send_to_editor = window.send_to_editor;
		      //overwrite send to editor function
		      window.send_to_editor = function(html) {
		        attachmenturl = jQuery('a', '<div>' + html + '</div>').attr('href');
		        attachmentname = jQuery('a', '<div>' + html + '</div>').html();
		        
		        window.formfield.find('.edd-vou-upload-file-link').val(attachmenturl);
	        	window.formfield.find('.edd-vou-upload-file-name').val(attachmentname);
		        eddVouLoadImagesMuploader();
		        tb_remove();
		        //restore old send to editor function
		        window.send_to_editor = window.restore_send_to_editor;
		      }
	      return false;
		      
		} else {
			
			var file_frame;
			window.formfield = '';
			
			//new media uploader
			var button = jQuery(this);
	
			window.formfield = jQuery(this).closest('.file-input-advanced');
		
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data( 'uploader_title' ),
				button: {
					text: button.data( 'uploader_button_text' ),
				},
				multiple: true  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = $('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){
						// place first attachment in field
						window.formfield.find('.edd-vou-upload-file-link').val(attachment_url);
						window.formfield.find('.edd-vou-upload-file-name').val(attachment.name);
						
					} else{
						window.formfield.find('.edd-vou-upload-file-name').val(attachment.name);
						window.formfield.find('.edd-vou-upload-file-link').val(attachment_url);
						
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();
		}
		
	});

  /**
   * Delete File.
   *
   * @since 1.0
   */
  jQuery( document ).on('click','.edd-vou-meta-upload .edd-vou-meta-delete-file',function(e){
    
    var jQuerythis   = jQuery(this),
        jQueryparent = jQuerythis.parent(),
        data = jQuerythis.attr('rel');
    
    var ind = jQuery(this).index()
    jQuery.post( ajaxurl, { action: 'atm_delete_file', data: data, tag_id: jQuery('#post_ID').val() }, function(response) {
      response == '0' ? ( alert( 'File has been successfully deleted.' ), jQueryparent.remove() ) : alert( 'You do NOT have permission to delete this file.' );
    });
    
    return false;
  
  });

	//Media Uploader
	$( document ).on( 'click', '.edd-vou-meta-upload-button', function() {
	
		var imgfield,showfield;
		imgfield = jQuery(this).prev('input').attr('id');
		showfield = jQuery(this).parents('td').find('.edd-vou-img-view');
		 
		if(typeof wp == "undefined" || EddWpsd.new_media_ui != '1' ){// check for media uploader
				
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	    	
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				
				if(imgfield)  {
					
					var mediaurl = $('img',html).attr('src');
					$('#'+imgfield).val(mediaurl);
					showfield.html('<img src="'+mediaurl+'" />');
					tb_remove();
					imgfield = '';
					
				} else {
					
					window.original_send_to_editor(html);
					
				}
			};
	    	return false;
			
		      
		} else {
			
			var file_frame;
			//window.formfield = '';
			
			//new media uploader
			var button = jQuery(this);
	
			//window.formfield = jQuery(this).closest('.file-input-advanced');
		
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				//title: button.data( 'uploader_title' ),
				/*button: {
					text: button.data( 'uploader_button_text' ),
				},*/
				multiple: false  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = $('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){
						// place first attachment in field
						$('#'+imgfield).val(attachment_url);
						showfield.html('<img src="'+attachment_url+'" />');
						
					} else{
						$('#'+imgfield).val(attachment_url);
						showfield.html('<img src="'+attachment_url+'" />');
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();
			
		}
		
	});

  //new image upload field
  function eddVouLoadImagesMuploader(){
    jQuery(".mupload_img_holder").each(function(i,v){
      if (jQuery(this).next().next().val() != ''){
        if (!jQuery(this).children().size() > 0){
          jQuery(this).append('<img src="' + jQuery(this).next().next().val() + '" style="height: 150px;width: 150px;" />');
          jQuery(this).next().next().next().val("Delete Image");
          jQuery(this).next().next().next().removeClass('edd-vou-meta-upload_image_button').addClass('edd-vou-meta-delete_image_button');
        }
      }
    });
  }
  
  eddVouLoadImagesMuploader();
  //delete img button
  
  jQuery( document ).on('click','.edd-vou-meta-delete_image_button',function(e){
  	jQuery(this).prev().val('');
  	jQuery(this).prev().prev().val('');
  	jQuery(this).prev().prev().prev().html('');
  	jQuery(this).val("Upload Image");
    jQuery(this).removeClass('edd-vou-meta-delete_image_button').addClass('edd-vou-meta-upload_image_button');
  });
 
  //editor resize fix
  /*jQuery(window).resize(function() {
    jQuery.each(Ed_array, function() {
      var ee = this;
      jQuery(ee.getScrollerElement()).width(100); // set this low enough
      width = jQuery(ee.getScrollerElement()).parent().width();
      jQuery(ee.getScrollerElement()).width(width); // set it to
      ee.refresh();
    });
  });*/
  
  
  // WP 3.5+ uploader
	
	var formfield1;
    var formfield2;
    
  	jQuery( document ).on('click','.edd-vou-meta-upload_image_button',function(e){

		e.preventDefault();
		formfield1 = jQuery(this).prev();
		formfield2 = jQuery(this).prev().prev();
		var button = jQuery(this);
			
		if(typeof wp == "undefined" || EddVou.new_media_ui != '1' ){// check for media uploader//
			 
			  tb_show('', 'media-upload.php?post_id='+ jQuery('#post_ID').val() + '&type=image&amp;TB_iframe=true');
		      //store old send to editor function
		      window.restore_send_to_editor = window.send_to_editor;
		      //overwrite send to editor function
		      window.send_to_editor = function(html) {
		      	
		        imgurl = jQuery('img',html).attr('src');
		        
		        if(jQuery('img',html).attr('class')) {
		        	
			        img_calsses = jQuery('img',html).attr('class').split(" ");
			        att_id = '';
			        jQuery.each(img_calsses,function(i,val){
			          if (val.indexOf("wp-image") != -1){
			            att_id = val.replace('wp-image-', "");
			          }
			        });
			
			        jQuery(formfield2).val(att_id);
		        }
		        
		        jQuery(formfield1).val(imgurl);
		        eddVouLoadImagesMuploader();
		        tb_remove();
		        //restore old send to editor function
		        window.send_to_editor = window.restore_send_to_editor;
		      }
		      return false;
		      
		} else {
			
			
			var file_frame;
			
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data( 'uploader_title' ),
				button: {
					text: button.data( 'uploader_button_text' ),
				},
				multiple: true  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = $('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){
						// place first attachment in field
						jQuery(formfield2).val(attachment.id);
	        			jQuery(formfield1).val(attachment_url);
	        			eddVouLoadImagesMuploader();
					
					} else{
						
						jQuery(formfield2).val(attachment.id);
	        			jQuery(formfield1).val(attachment_url);
	        			eddVouLoadImagesMuploader();
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();
		}
		
	});
  
  
  //added for tabs in metabox
  // tab between them
	jQuery('.metabox-tabs li a').each(function(i) {
		var thisTab = jQuery(this).parent().attr('class').replace(/active /, '');
		
		if ( 'active' != jQuery(this).attr('class') )
			jQuery('div.' + thisTab).hide();

		jQuery('div.' + thisTab).addClass('tab-content');
 
		jQuery(this).click(function(){
			// hide all child content
			jQuery(this).parent().parent().parent().children('div').hide();
 
			// remove all active tabs
			jQuery(this).parent().parent('ul').find('li.active').removeClass('active');
 
			// show selected content
			jQuery(this).parent().parent().parent().find('div.'+thisTab).show();
			jQuery(this).parent().parent().parent().find('li.'+thisTab).addClass('active');
		});
	});

	jQuery('.metabox-tabs').show();
	
	eddVouCheckErrorMessage();
  	jQuery( document ).on('click','#_edd_vou_enable',function(e){
		eddVouCheckErrorMessage();
	});
	
  	jQuery( document ).on('blur','#_edd_vou_end_date',function(e){
		eddVouCheckErrorMessage();
	});
		
    /**
     * Select Background Image Option.
     *
     * @since 1.0
     */
    
	// Background style code
    jQuery( document ).on( 'click', '.edd-vou-meta-radio', function(){
    
    	var bg_style = jQuery( this ).val();
    	
		jQuery( '.edd-vou-meta-bg-pattern-wrap' ).hide();
    	jQuery( '.edd-vou-meta-bg-image-wrap' ).hide();
    	
    	if( bg_style == 'image' ) { // Check backgroung image
	    	
	    	jQuery( '.edd-vou-meta-bg-image-wrap' ).show();
	    	
    	} else if( bg_style == 'pattern' ) { // Check backgroung pattern
    		
    		jQuery( '.edd-vou-meta-bg-pattern-wrap' ).show();
    	}
    	
    });
    
    /**
     * Select Background Pattern Field.
     *
     * @since 1.0
     */
    jQuery( document ).on( 'click', '.edd-vou-meta-bg-patterns', function(){
    
    	var pattern = jQuery( this ).attr( 'data-pattern' );
    	jQuery( '.edd-vou-meta-bg-patterns' ).removeClass( 'edd-vou-meta-bg-pattern-selected' );
    	jQuery( '#edd_vou_meta_img_' + pattern ).addClass( 'edd-vou-meta-bg-pattern-selected' );
    	jQuery( '.edd-vou-meta-bg-patterns-opt' ).val( pattern );
    	
    });
    
});

/**
 * chosen enable function
 * @since 2.9.8
 */
function eddVouFancySelect(){
  jQuery(".edd-vou-wrapper select").each(function (){
    if(! jQuery(this).hasClass('no-fancy'))
      jQuery(this).chosen({search_contains:true});
  });
}

function eddVouCheckErrorMessage() {
	
	var end_date = jQuery('#_edd_vou_end_date').val();
	
	if( jQuery('#_edd_vou_enable').is(":checked") && end_date == '' ) {
		
		jQuery('#edd_vou_error_message_box').addClass('edd-vou-error-message').show();
	} else {
		jQuery('#edd_vou_error_message_box').removeClass('edd-vou-error-message').hide();
	}
}
