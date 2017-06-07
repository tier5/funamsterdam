jQuery( document ).ready( function( $ ) {
	
	var pagevoucher = $('#edd_vou_page_voucher');
	pagevoucher.hide();
	var postdiv = $('#postdivrich');

	if($('#edd_vou_page_voucher').length == 1) {
		$('div#titlediv').after('<p class="edd_vou_page_voucher_button"><a href="javascript:void(0)" id="edd_vou_builder_switch">'+EddVouTranObj.offbuttontxt+'</a></p>');	
	}
	
	// On click of the button changing the editor  start
	$( document ).on( 'click', '#edd_vou_builder_switch', function() {
		if(postdiv.is(":visible")) {
				edd_vou_switch_default_editor_visual('content');
				var editor_empty = true;
				if ( tinyMCE.get('content').getContent() != '') {
						editor_empty = false;
						var answer = confirm ( EddVouTranObj.switchanswer );
						if (answer) {
							editor_empty = true;
						}
				}
				if(editor_empty) {
					postdiv.hide();
					pagevoucher.show();
					$('#edd_vou_editor_status').val('true');
					$(this).html(EddVouTranObj.onbuttontxt);
					$('.edd_vou_page_voucher_button').addClass('switch_active');
					return false;
				}
		} else {
			
			postdiv.show();
			pagevoucher.hide();
			$('#edd_vou_editor_status').val('false');
			edd_vou_give_shortcode_to_editor();
			$(this).html(EddVouTranObj.offbuttontxt);
			$('.edd_vou_page_voucher_button').removeClass('switch_active');
			return false;
			
		}
	});
	// On click of the button changing the editor end
		
	// On page load which pagevoucher will be showing start
	if($('#edd_vou_editor_status').val() == 'true'){
		
		pagevoucher.show();
		postdiv.hide();
		$('#edd_vou_builder_switch').html(EddVouTranObj.onbuttontxt);
		$('.edd_vou_page_voucher_button').addClass('switch_active');
				
	} else {
		
		pagevoucher.hide();
		postdiv.show();
		$('#edd_vou_builder_switch').html(EddVouTranObj.offbuttontxt);
		$('.edd_vou_page_voucher_button').removeClass('switch_active');
		
	}
	// On page load which pagevoucher will be showing end 
	
	// On Click of edit will show editor start
	$( document ).on( 'click', '.edd_vou_change', function(pb) {
		
		var element = jQuery(this).closest('.text_column');
		
		$('.edd_vou_controls_editor').hide();
		$('.edd_vou_main_editor').hide();
		$('.edd_vou_editor').show();
		
		if($(this).hasClass('editcode')){
			
			var bg_color = $(this).closest('.textblock').find('.edd_vou_text_bg').val();
			var font_color = $(this).closest('.textblock').find('.edd_vou_text_font_color').val();
			var font_size = $(this).closest('.textblock').find('.edd_vou_text_font_size').val();
			var text_align = $(this).closest('.textblock').find('.edd_vou_text_text_align').val();
			var code_text_align = $(this).closest('.textblock').find('.edd_vou_text_code_text_align').val();
			var code_border = $(this).closest('.textblock').find('.edd_vou_text_code_border').val();
			var code_column = $(this).closest('.textblock').find('.edd_vou_text_code_column').val();
			//var content = $(this).closest('.textblock').find('.edd_vou_text p').html();
			var content = $(this).closest('.textblock').find('.edd_vou_text').html();
			var content_codes = $(this).closest('.textblock').find('.edd_vou_text_codes').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouTextBlock.textblocktitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary text_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
			
			var data = {
							action 			: 'edd_vou_page_builder',
							type			: 'textblock',
							editorid		: 'wpspbrtextblockedit',
							bgcolor			: bg_color,
							fontcolor		: font_color,
							fontsize		: font_size,
							textalign		: text_align,
							codetextalign	: code_text_align,
							codeborder		: code_border,
							codecolumn		: code_column,
						};
			
			jQuery.post(ajaxurl,data,function(response) {
					
					jQuery('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrtextblockedit').setContent(content);
					tinyMCE.get('wpspbrtextblockeditcodes').setContent(content_codes);
					//$('#wpspbrtextblockedit').val(content);
					
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					$('#edd_vou_edit_font_color').css('color',font_color);
			
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					edd_vou_set_colorpicker( $('#edd_vou_edit_font_color') );
					
					$('#edd_vou_edit_bg_color').val(bg_color);
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					$('#edd_vou_edit_font_color').val(font_color);
					$('#edd_vou_edit_font_color').css('color',font_color);
					
			});
			
		} else if($(this).hasClass('editredeem')) {

			var bg_color = $(this).closest('.messagebox').find('.edd_vou_text_bg').val();
			var content = $(this).closest('.messagebox').find('.edd_vou_text').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouMsgBox.msgboxtitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary message_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
					
			var data = {
							action	 : 'edd_vou_page_builder',
							editorid : 'wpspbrmessageedit',
							type	 : 'message',
							bgcolor	 : bg_color
						};
			
			jQuery.post(ajaxurl,data,function(response) {
				
					$('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrmessageedit').setContent(content);
					
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					
					$('#edd_vou_edit_bg_color').css("background-color",bg_color);
					$('#edd_vou_edit_bg_color').val(bg_color);
				
			});
		} else if($(this).hasClass('editexpire')){
			
			var bg_color = $(this).closest('.expireblock').find('.edd_vou_text_bg').val();
			var content = $(this).closest('.expireblock').find('.edd_vou_text').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouExpireBlock.expireblocktitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary expire_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
			
			var data = {
							action 			: 'edd_vou_page_builder',
							type			: 'expireblock',
							editorid		: 'wpspbrexpireblockedit',
							bgcolor			: bg_color
						};
			
			jQuery.post(ajaxurl,data,function(response) {
					
					jQuery('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrexpireblockedit').setContent(content);
					
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
			
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					
					$('#edd_vou_edit_bg_color').val(bg_color);
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					
			});
			
		} else if($(this).hasClass('editvenaddr')){
			
			var bg_color = $(this).closest('.venaddrblock').find('.edd_vou_text_bg').val();
			var content = $(this).closest('.venaddrblock').find('.edd_vou_text').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouVenAddrBlock.venaddrblocktitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary venaddr_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
			
			var data = {
							action 			: 'edd_vou_page_builder',
							type			: 'venaddrblock',
							editorid		: 'wpspbrvenaddrblockedit',
							bgcolor			: bg_color
						};
			
			jQuery.post(ajaxurl,data,function(response) {
					
					jQuery('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrvenaddrblockedit').setContent(content);
					
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
			
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					
					$('#edd_vou_edit_bg_color').val(bg_color);
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					
			});
			
		} else if($(this).hasClass('editsiteurl')){
			
			var bg_color = $(this).closest('.siteurlblock').find('.edd_vou_text_bg').val();
			var content = $(this).closest('.siteurlblock').find('.edd_vou_text').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouSiteURLBlock.siteurlblocktitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary siteurl_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
			
			var data = {
							action 			: 'edd_vou_page_builder',
							type			: 'siteurlblock',
							editorid		: 'wpspbrsiteurlblockedit',
							bgcolor			: bg_color
						};
			
			jQuery.post(ajaxurl,data,function(response) {
					
					jQuery('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrsiteurlblockedit').setContent(content);
					
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
			
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					
					$('#edd_vou_edit_bg_color').val(bg_color);
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					
			});
			
		} else if($(this).hasClass('editloc')){
			
			var bg_color = $(this).closest('.locblock').find('.edd_vou_text_bg').val();
			var content = $(this).closest('.locblock').find('.edd_vou_text').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouLocBlock.locblocktitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary loc_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
			
			var data = {
							action 			: 'edd_vou_page_builder',
							type			: 'locblock',
							editorid		: 'wpspbrlocblockedit',
							bgcolor			: bg_color
						};
			
			jQuery.post(ajaxurl,data,function(response) {
					
					jQuery('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrlocblockedit').setContent(content);
					
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
			
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					
					$('#edd_vou_edit_bg_color').val(bg_color);
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					
			});
			
		} else if($(this).hasClass('editcustom')){
			
			var bg_color = $(this).closest('.customblock').find('.edd_vou_text_bg').val();
			var content = $(this).closest('.customblock').find('.edd_vou_text').html();
			
			$('#edd_vou_edit_form').html('<div class="edd_vou_editor_heading"><h3><strong>'+EddVouCustomBlock.customblocktitle+'</strong></h3></div><div class="edd_vou_editor_controls"><div class="editor_content"></div></div><div class="edd_vou_form_action"><input type="button" id="edd_vou_pbr_save" class="button-primary custom_edit_save" name="save" value="'+EddVouTranObj.btnsave+'" /><input type="button" id="edd_vou_pbr_cancel" class="button-primary text_cancel" name="cancel" value="'+EddVouTranObj.btncancel+'" /></div>');
			
			var data = {
							action 			: 'edd_vou_page_builder',
							type			: 'customblock',
							editorid		: 'wpspbrcustomblockedit',
							bgcolor			: bg_color
						};
			
			jQuery.post(ajaxurl,data,function(response) {
					
					jQuery('.editor_content').html(response);
					edd_vou_init_tiny_mce();
					tinyMCE.get('wpspbrcustomblockedit').setContent(content);
					
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
			
					edd_vou_set_colorpicker( $('#edd_vou_edit_bg_color') );
					
					$('#edd_vou_edit_bg_color').val(bg_color);
					$('#edd_vou_edit_bg_color').css('background-color',bg_color);
					
			});
			
		}
		
		edd_vou_ini_form_editing(element);
	});
	// On Click of edit will show editor end
	
	// On click removing a element form page builder area  start
	$( document ).on( 'click', '.edd_vou_remove', function() {
		
		var answer = confirm ('Click on OK to delete this section, click on Cancel to leave');
		if (answer) {
			$(this).closest('.text_column').remove();
			//alert('html---'+$('.edd_vou_controls').html()+'---html');
			if($('.edd_vou_controls').html() == "") {
				
				$('.edd_vou_builder_area').show();
			}
		}
	});
	// On click removing a element form page builder area end
	
	// On Click of increase/decrease width start
	$( document ).on( 'click', '.edd_vou_greater_width', function() {
		//pb.preventDefault();
		
		var columnwidth = jQuery(this).closest(".text_column"),
			columnsizes = edd_vou_get_column_width(columnwidth),
			widthofblock = $(this).closest('.text_column').find('.edd_vou_txtclass_width').val();
		
		//show hide the button for resizing when selected controll is add to cart 
		if($(this).hasClass('add_cart') && ( columnsizes[3] == '1/1' || columnsizes[3] == '3/4' || columnsizes[3] == '1/2')) {
			
			$(this).closest('.text_column').find('.edd_vou_lesser_width').show();
			
		} else if ($(this).hasClass('add_cart') && ( columnsizes[3] == '1/4' )) {
			
			$(this).closest('.text_column').find('.edd_vou_lesser_width').hide();
		}
		
		if (columnsizes[1]) {
			
			columnwidth.removeClass(columnsizes[0]).addClass(columnsizes[1]);
			/* get updated column size */
			$(this).closest('.text_column').find('.edd_vou_txtclass_width').val(columnsizes[1]);
			columnsizes = edd_vou_get_column_width(columnwidth);
			jQuery(columnwidth).find(".width_size").html(columnsizes[3]);
			
 		}
		
	});
	$( document ).on( 'click', '.edd_vou_lesser_width', function() {
				
		var columnwidth = jQuery(this).closest(".text_column"),
			columnsizes = edd_vou_get_column_width(columnwidth),
			widthofblock = $(this).closest('.text_column').find('.edd_vou_txtclass_width').val();
		
				//hide the lesser width when add to cart is lesser then one_half
				
				if($(this).hasClass('add_cart') && ( columnsizes[3] == '1/1' || columnsizes[3] == '3/4' )) {
					
					$(this).show();
					
				} else if ($(this).hasClass('add_cart') && ( columnsizes[3] == '1/4' )) {
					
					$(this).hide();
				}
		
		if (columnsizes[2]) {
			
			columnwidth.removeClass(columnsizes[0]).addClass(columnsizes[2]);
			/* get updated column size */
			
			$(this).closest('.text_column').find('.edd_vou_txtclass_width').val(columnsizes[2]);
			columnsizes = edd_vou_get_column_width(columnwidth);
			jQuery(columnwidth).find(".width_size").html(columnsizes[3]);
		}
		
	});
	// On Click of increase/decrease width end
	
	// On click of button add a text control start 
	$( document ).on( 'click', '#edd_vou_text_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column textblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcode" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text">'+EddVouTextBlock.textblockdesc+'</div><div class="edd_vou_text_codes">'+EddVouTextBlock.textblockdesccodes+'</div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_text_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_text_bg" id="edd_vou_text_bg" name="edd_vou_text_bg" value=""><input type="hidden" class="edd_vou_text_font_color" id="edd_vou_text_font_color" name="edd_vou_text_font_color" value="#000000"><input type="hidden" class="edd_vou_text_font_size" id="edd_vou_text_font_size" name="edd_vou_text_font_size" value="10"><input type="hidden" class="edd_vou_text_text_align" id="edd_vou_text_text_align" name="edd_vou_text_text_align" value="left"><input type="hidden" class="edd_vou_text_code_text_align" id="edd_vou_text_code_text_align" name="edd_vou_text_code_text_align" value="left"><input type="hidden" class="edd_vou_text_code_border" id="edd_vou_text_code_border" name="edd_vou_text_code_border" value=""><input type="hidden" class="edd_vou_text_code_column" id="edd_vou_text_code_column" name="edd_vou_text_code_column" value="1"></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a text control end 
	
	// On click of button add message box start
	$( document ).on( 'click', '#edd_vou_message_btn', function() {
		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column messagebox full_width draghandle" style="background-color:#FFFFFF;color:#000000;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editredeem" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text">'+EddVouMsgBox.msgboxdesc+'</div><input type="hidden" value="" class="edd_vou_text_bg" id="edd_vou_msg_color" name="edd_vou_msg_color"><input id="edd_vou_messagebox_width" class="edd_vou_txtclass_width" type="hidden" name="edd_vou_text_width" value="full_width"></div></div>');
		if(typeof(Prototype) != "undefined")  {
   			portal = new Portal(settings, options, data);
  		}
		return false;
	});
	// On click of button add message box end
	
	// On click of button add a site logo control start
	$( document ).on( 'click', '#edd_vou_site_logo_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column sitelogoblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouSiteLogoBox.sitelogoboxdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_site_logo_width" name="edd_vou_text_width"></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a settings logo control end 
	
	// On click of button add a logo control start 
	$( document ).on( 'click', '#edd_vou_logo_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column logoblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouLogoBox.logoboxdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_logo_width" name="edd_vou_text_width"></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a logo control end 
	
	// On click of button add a expire date control start 
	$( document ).on( 'click', '#edd_vou_expire_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column expireblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editexpire" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouExpireBlock.expireblockdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_expire_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_text_bg" id="edd_vou_expire_bg" name="edd_vou_expire_bg" value=""></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a expire date control end 
	
	// On click of button add a vendor's address control start 
	$( document ).on( 'click', '#edd_vou_venaddr_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column venaddrblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editvenaddr" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouVenAddrBlock.venaddrblockdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_venaddr_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_text_bg" id="edd_vou_venaddr_bg" name="edd_vou_venaddr_bg" value=""></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a vendor's address control end 
	
	// On click of button add a vendor's address control start 
	$( document ).on( 'click', '#edd_vou_siteurl_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column siteurlblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editsiteurl" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouSiteURLBlock.siteurlblockdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_siteurl_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_text_bg" id="edd_vou_siteurl_bg" name="edd_vou_siteurl_bg" value=""></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a vendor's address control end 
	
	// On click of button add a voucher locations control start 
	$( document ).on( 'click', '#edd_vou_loc_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column locblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editloc" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text">'+EddVouLocBlock.locblockdesc+'</div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_loc_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_text_bg" id="edd_vou_loc_bg" name="edd_vou_loc_bg" value=""></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add a voucher locations control end 
	
	// On click of button add blank box start
	$( document ).on( 'click', '#edd_vou_blank_btn', function() {
		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column blankbox full_width draghandle" style="background-color:#FFFFFF;color:#000000;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouBlankBox.blankboxdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_blank_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_blank_bg" id="edd_vou_blank_bg" name="edd_vou_blank_bg" value=""></div>');
		if(typeof(Prototype) != "undefined")  {
   			portal = new Portal(settings, options, data);
  		}
		return false;
	});
	// On click of button add blank box end
	
	// On click of button add custom box start
	$( document ).on( 'click', '#edd_vou_custom_btn', function() {

		jQuery('.edd_vou_builder_area').hide();
		jQuery('.edd_vou_controls').append('<div class="edd_vou_controls_editor text_column customblock full_width draghandle" style="background-color:#FFFFFF;"><div class="edd_vou_controller"><a class="edd_vou_lesser_width" href="javascript:void(0);"></a><span class="width_size">1/1</span><a class="edd_vou_greater_width" href="javascript:void(0);"></a><a class="edd_vou_change editcustom" href="javascript:void(0);"></a><a class="edd_vou_remove" href="javascript:void(0);"></a></div><div class="edd_vou_text"><p>'+EddVouCustomBlock.customblockdesc+'</p></div><input type="hidden" value="full_width" class="edd_vou_txtclass_width" id="edd_vou_custom_width" name="edd_vou_text_width"><input type="hidden" class="edd_vou_text_bg" id="edd_vou_custom_bg" name="edd_vou_custom_bg" value=""></div>');
			if(typeof(Prototype) != "undefined")  {
   				portal = new Portal(settings, options, data);
  			}
		return false;
	});
	// On click of button add custom box end
	
	//On click of editor cancel  start
	$( document ).on( 'click', '#edd_vou_pbr_cancel', function() {
		
		jQuery('.edd_vou_editor').hide();
		jQuery('.edd_vou_controls_editor').show();
		jQuery('.edd_vou_main_editor').show();
		jQuery('#edd_vou_page_builder').removeClass('edd_vou_edit_mode')

		if(jQuery('#edd_vou_pbr_save').hasClass('tabs_edit_save')) {
			jQuery('#edd_vou_edit_form .edd_vou_textareaeditor').each(function(index) {
				edd_vou_get_tiny_content( "edd_vou_text_editor_"+index );
				//tinyMCE.execCommand("mceRemoveControl", false, "edd_vou_text_editor_"+index);
			});
		} else {
			var editor_ID = tinyMCE.activeEditor.id;
			edd_vou_get_tiny_content( editor_ID );
			//tinyMCE.execCommand('mceRemoveControl', true, editor_ID);
		}
		jQuery('#publish').show();
	});
	//On click of editor cancel  end
	
	// Update the content of page builder start
	$( document ).on( 'click', '#publish, #save-post', function() {
		
		if($('#edd_vou_builder_switch').html() == EddVouTranObj.onbuttontxt) {
			edd_vou_give_shortcode_to_editor();
		}
		
		var metaboxdata = jQuery('.edd_vou_controls').html();
		$('#edd_vou_meta_content').val(metaboxdata);
		
	});
	// Update the content of page builder end

	//make editor to visual mode
	function edd_vou_switch_default_editor_visual(editor) {
		if (jQuery('#wp-'+editor+'-wrap').hasClass('html-active')) {
			switchEditors.go(editor, 'tinymce');
		}
	}
	
	function edd_vou_ini_form_editing(element) {
			
		jQuery('#edd_vou_page_builder').addClass('edd_vou_edit_mode');
		jQuery('#publish').hide();
		
		//On click of save changing the value of textarea to p start
		jQuery('#edd_vou_pbr_save').click(function(e) {
			
			if(jQuery('#edd_vou_pbr_save').hasClass('text_edit_save')) { // save chages for textblock
					
				var edited = edd_vou_get_tiny_content('wpspbrtextblockedit');
				var editedcodes = edd_vou_get_tiny_content('wpspbrtextblockeditcodes');
				//var edited = jQuery('#wpspbrtextblockedit').val();
				
				var bg_color = jQuery('#edd_vou_edit_bg_color').val();
				var font_color = jQuery('#edd_vou_edit_font_color').val();
				var font_size = jQuery('#edd_vou_edit_font_size').val();
				var text_align = jQuery('#edd_vou_edit_text_align').val();
				var code_text_align = jQuery('#edd_vou_edit_code_text_align').val();
				var code_border = jQuery('#edd_vou_edit_code_border').val();
				var code_column = jQuery('#edd_vou_edit_code_column').val();
				
				if(font_color == '') {
					font_color = '#000000';
				}
				if(font_size == '') {
					font_size = '10';
				}
				if(text_align == '') {
					text_align = 'left';
				}
				if(code_text_align == '') {
					code_text_align = 'left';
				}
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var element_to_update = 'edd_vou_text';
					var element_to_update_codes = 'edd_vou_text_codes';
					var bg_color_to_update = 'edd_vou_text_bg';
					var font_color_to_update = 'edd_vou_text_font_color';
					var font_size_to_update = 'edd_vou_text_font_size';
					var text_align_to_update = 'edd_vou_text_text_align';
					var code_text_align_to_update = 'edd_vou_text_code_text_align';
					var code_border_to_update = 'edd_vou_text_code_border';
					var code_column_to_update = 'edd_vou_text_code_column';
					
					if (element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, strong, p')) {

						element.find('.'+element_to_update).html(edited);
						element.find('.'+element_to_update_codes).html(editedcodes);
						element.find('.'+bg_color_to_update).val(bg_color);
						if(bg_color == '') {
							bg_color = '#FFFFFF';
						}
						element.find('.'+bg_color_to_update).closest('.textblock').css('background-color',bg_color);
						//element.find('.'+font_color_to_update).val(font_color);
						//element.find('.'+font_color_to_update).closest('.textblock').css('color',font_color);
						element.find('.'+font_size_to_update).val(font_size);
						element.find('.'+font_size_to_update).closest('.textblock').find('.edd_vou_text').css('font-size',font_size+'pt');
						element.find('.'+text_align_to_update).val(text_align);
						element.find('.'+text_align_to_update).closest('.textblock').css('text-align',text_align);
						element.find('.'+code_text_align_to_update).val(code_text_align);
						element.find('.'+code_border_to_update).val(code_border);
						element.find('.'+code_column_to_update).val(code_column);
						
					} else {
						
						element.find('.'+element_to_update).html(edited);
						element.find('.'+element_to_update_codes).html(editedcodes);
						element.find('.'+bg_color_to_update).val(bg_color);
						//element.find('.'+font_color_to_update).val(font_color);
						element.find('.'+font_size_to_update).val(font_size);
						element.find('.'+text_align_to_update).val(text_align);
						element.find('.'+code_text_align_to_update).val(code_text_align);
						element.find('.'+code_border_to_update).val(code_border);
						element.find('.'+code_column_to_update).val(code_column);
					}
				});
					
			} else if (jQuery('#edd_vou_pbr_save').hasClass('message_edit_save')) {	 // save changes messagebox
					
				var message_content = edd_vou_get_tiny_content('wpspbrmessageedit');
				var messagebgcolor = jQuery('#edd_vou_edit_bg_color').val();
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var content_to_update = 'edd_vou_text';
					var color_to_update = 'edd_vou_text_bg';
					
					if (element.find('.'+content_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, strong, p')) {
						
						element.find('.'+content_to_update).html(message_content);
						element.find('.'+color_to_update).val(messagebgcolor);
						if(messagebgcolor == '') {
							messagebgcolor = '#FFFFFF';
						}
						element.find('.'+color_to_update).closest('.messagebox').css("background-color",messagebgcolor);
						
					} else {
						element.find('.'+content_to_update).html(message_content);
						element.find('.'+color_to_update).val(messagebgcolor);
					}
				});
				//tinyMCE.execCommand("mceRemoveControl", false, "wpspbrmessageedit");
				
			} else if(jQuery('#edd_vou_pbr_save').hasClass('expire_edit_save')) { // save chages for expireblock
					
				var edited = edd_vou_get_tiny_content('wpspbrexpireblockedit');
				var bg_color = jQuery('#edd_vou_edit_bg_color').val();
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var element_to_update = 'edd_vou_text';
					var bg_color_to_update = 'edd_vou_text_bg';
					
					if (element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, p')) {

						element.find('.'+element_to_update).html(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
						if(bg_color == '') {
							bg_color = '#FFFFFF';
						}
						element.find('.'+bg_color_to_update).closest('.expireblock').css('background-color',bg_color);
						
					} else {
						
						element.find('.'+element_to_update).val(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
					}
				});
					
			} else if(jQuery('#edd_vou_pbr_save').hasClass('venaddr_edit_save')) { // save chages for venaddrblock
					
				var edited = edd_vou_get_tiny_content('wpspbrvenaddrblockedit');
				var bg_color = jQuery('#edd_vou_edit_bg_color').val();
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var element_to_update = 'edd_vou_text';
					var bg_color_to_update = 'edd_vou_text_bg';
					
					if (element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, p')) {

						element.find('.'+element_to_update).html(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
						if(bg_color == '') {
							bg_color = '#FFFFFF';
						}
						element.find('.'+bg_color_to_update).closest('.venaddrblock').css('background-color',bg_color);
						
					} else {
						
						element.find('.'+element_to_update).val(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
					}
				});
					
			} else if(jQuery('#edd_vou_pbr_save').hasClass('siteurl_edit_save')) { // save chages for siteurlblock
					
				var edited = edd_vou_get_tiny_content('wpspbrsiteurlblockedit');
				var bg_color = jQuery('#edd_vou_edit_bg_color').val();
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var element_to_update = 'edd_vou_text';
					var bg_color_to_update = 'edd_vou_text_bg';
					
					if (element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, p')) {

						element.find('.'+element_to_update).html(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
						if(bg_color == '') {
							bg_color = '#FFFFFF';
						}
						element.find('.'+bg_color_to_update).closest('.siteurlblock').css('background-color',bg_color);
						
					} else {
						
						element.find('.'+element_to_update).val(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
					}
				});
					
			} else if(jQuery('#edd_vou_pbr_save').hasClass('loc_edit_save')) { // save chages for locblock
					
				var edited = edd_vou_get_tiny_content('wpspbrlocblockedit');
				var bg_color = jQuery('#edd_vou_edit_bg_color').val();
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var element_to_update = 'edd_vou_text';
					var bg_color_to_update = 'edd_vou_text_bg';
					
					if (element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, p')) {

						element.find('.'+element_to_update).html(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
						if(bg_color == '') {
							bg_color = '#FFFFFF';
						}
						element.find('.'+bg_color_to_update).closest('.locblock').css('background-color',bg_color);
						
					} else {
						
						element.find('.'+element_to_update).val(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
					}
				});
					
			} else if(jQuery('#edd_vou_pbr_save').hasClass('custom_edit_save')) { // save chages for customblock
					
				var edited = edd_vou_get_tiny_content('wpspbrcustomblockedit');
				var bg_color = jQuery('#edd_vou_edit_bg_color').val();
				
				jQuery("#edd_vou_edit_form #edd_vou_edit_bg_color").each(function(index) {
					
					var element_to_update = 'edd_vou_text';
					var bg_color_to_update = 'edd_vou_text_bg';
					
					if (element.find('.'+element_to_update).is('div, h1,h2,h3,h4,h5,h6, span, i, b, p')) {

						element.find('.'+element_to_update).html(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
						if(bg_color == '') {
							bg_color = '#FFFFFF';
						}
						element.find('.'+bg_color_to_update).closest('.customblock').css('background-color',bg_color);
						
					} else {
						
						element.find('.'+element_to_update).val(edited);
						element.find('.'+bg_color_to_update).val(bg_color);
					}
				});
					
			}
			 
			jQuery('.edd_vou_editor').hide();
			jQuery('.edd_vou_controls_editor').show();
			jQuery('.edd_vou_main_editor').show();
			jQuery("#edd_vou_edit_form").empty();
			jQuery('#edd_vou_page_builder').removeClass('edd_vou_edit_mode');
			jQuery('#publish').show();
		});
		
		//On click of save changing the value of textarea to p end
	}
	
	if (window.send_to_editor) {
			originalSendToEditor = window.send_to_editor;
	}
	function edd_vou_give_shortcode_to_editor(){
		
		var pbrshortcodes = '<table class="edd_vou_pdf_table">'; // Create table
		var createtr = 0;
		var tdcolspan = 4;
		var i = 0;
		//var l = 0;
		
		jQuery(".text_column").each(function(index) {
			
			var widthclass = $(this).closest('.text_column').find('.edd_vou_txtclass_width').val();
			if( widthclass == 'full_width' ) { // Check 4/4 Width
				tdcolspan = 4;
			} else if( widthclass == 'three_fourth' ) { // Check 3/4 Width
				tdcolspan = 3;
			} else if( widthclass == 'one_half' ) { // Check 2/4 Width
				tdcolspan = 2;
			} else if( widthclass == 'one_fourth' ) { // Check 1/4 Width
				tdcolspan = 1;
			} else {
				tdcolspan = 4;
			}
			
			if( createtr == 0 ) { // First Time Create New Row
				pbrshortcodes += '<tr>';
			}
			createtr += tdcolspan;
			if( createtr > 4 ) { // Check for Create New Row
				createtr = tdcolspan;
				pbrshortcodes += '</tr>';
				pbrshortcodes += '<tr>';
			}
			
			if( tdcolspan > 0) { // Assign Colspan
				pbrshortcodes += '<td colspan="'+tdcolspan+'">';
			} else {
				pbrshortcodes += '<td>';
			}
			
			if($(this).closest('.text_column').hasClass('textblock')) { // shortcode for voucher code
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var content_codes = $(this).closest('.text_column').find('.edd_vou_text_codes').html();
				//var content = $(this).closest('.text_column').find('.edd_vou_text p').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
				var font_color = $(this).closest('.text_column').find('.edd_vou_text_font_color').val();
				var font_size = $(this).closest('.text_column').find('.edd_vou_text_font_size').val();
				var text_align = $(this).closest('.text_column').find('.edd_vou_text_text_align').val();
				var code_text_align = $(this).closest('.text_column').find('.edd_vou_text_code_text_align').val();
				var code_border = $(this).closest('.text_column').find('.edd_vou_text_code_border').val();
				var code_column = $(this).closest('.text_column').find('.edd_vou_text_code_column').val();
				
				pbrshortcodes += '[edd_vou_code_title';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					/*if(font_color != '' && font_color != 'undefined') {
						pbrshortcodes += ' color="'+font_color+'"';
					}*/
					if(font_size != '' && font_size != 'undefined') {
						pbrshortcodes += ' fontsize="'+font_size+'"';
					}
					if(text_align != '' && text_align != 'undefined') {
						pbrshortcodes += ' textalign="'+text_align+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_code_title]';
				pbrshortcodes += '[edd_vou_code';
				
					if(code_text_align != '' && code_text_align != 'undefined') {
						pbrshortcodes += ' codetextalign="'+code_text_align+'"';
					}
					if(code_border != '' && code_border != 'undefined') {
						pbrshortcodes += ' codeborder="'+code_border+'"';
					}
					/*if(code_column != '' && code_column != 'undefined') {
						pbrshortcodes += ' codecolumn="'+code_column+'"';
					}*/
				pbrshortcodes += '] ' + content_codes + ' [/edd_vou_code]';

				
			} else if($(this).closest('.text_column').hasClass('messagebox')){ // shortcode for voucher redeem instruction
			
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
					
				pbrshortcodes += '[edd_vou_redeem';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_redeem]';
				
			} else if($(this).closest('.text_column').hasClass('sitelogoblock')) { // shortcode for voucher site logo
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				
				pbrshortcodes += '[edd_vou_site_logo]' + content + ' [/edd_vou_site_logo]';
				
			} else if($(this).closest('.text_column').hasClass('logoblock')) { // shortcode for voucher logo
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				
				pbrshortcodes += '[edd_vou_logo]' + content + ' [/edd_vou_logo]';
				
			} else if($(this).closest('.text_column').hasClass('expireblock')) { // shortcode for voucher expire date
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
				
				pbrshortcodes += '[edd_vou_expire_date';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_expire_date]';

			} else if($(this).closest('.text_column').hasClass('venaddrblock')) { // shortcode for vendor's address
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
				
				pbrshortcodes += '[edd_vou_vendor_address';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_vendor_address]';

			} else if($(this).closest('.text_column').hasClass('siteurlblock')) { // shortcode for website URL
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
				
				pbrshortcodes += '[edd_vou_siteurl';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_siteurl]';

			} else if($(this).closest('.text_column').hasClass('locblock')) { // shortcode for vendor's address
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
				
				pbrshortcodes += '[edd_vou_location';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_location]';

			} else if($(this).closest('.text_column').hasClass('customblock')) { // shortcode for custom block
				
				var content = $(this).closest('.text_column').find('.edd_vou_text').html();
				var bg_color = $(this).closest('.text_column').find('.edd_vou_text_bg').val();
				
				pbrshortcodes += '[edd_vou_custom';
				
					if(bg_color != '' && bg_color != 'undefined') {
						pbrshortcodes += ' bgcolor="'+bg_color+'"';
					}
					
				pbrshortcodes += '] ' + content + ' [/edd_vou_custom]';

			} else if($(this).closest('.text_column').hasClass('blankbox')){ // shortcode for voucher blank box
				
				pbrshortcodes += '&nbsp;';
				
			}
	
			pbrshortcodes += '</td>'; // Close td part
			
		});
		
		pbrshortcodes += '</tr>'; // Close tr part
		
		pbrshortcodes += '</table>'; // Close table part
		
		edd_vou_switch_default_editor_visual('content');
		tinyMCE.get('content').setContent(pbrshortcodes, {format : 'raw'});
	}
	
	/* function for getting the column width */
	function edd_vou_get_column_width(column) {
		
		if (column.hasClass("full_width"))
			return new Array("full_width", false, "three_fourth", "1/1");
		
		else if (column.hasClass("three_fourth"))
			return new Array("three_fourth", "full_width", "one_half", "3/4");
			
		else if (column.hasClass("one_half"))
			return new Array("one_half", "three_fourth", "one_fourth", "1/2");
		
		else if (column.hasClass("one_fourth"))
			return new Array("one_fourth", "one_half", false, "1/4");
		
		else 
			return false;
	} // end edd_vou_get_column_width()

	edd_vou_set_colorpicker( $('.edd-vou-meta-color-iris') );
});

function edd_vou_init_tiny_mce() {

	jQuery('.pbrtextareahtml').each(function(index) {
	
		var editor_id = jQuery(this).attr('id');
		
		tinymce.execCommand("mceRemoveEditor", false, editor_id);
		tinymce.execCommand("mceAddEditor", false, editor_id);
		
		jQuery(this).closest('.edd_vou_ajax_editor').find('.wp-switch-editor').removeAttr("onclick");
		jQuery(this).closest('.edd_vou_ajax_editor').find('.switch-tmce').click(function() {
			
			jQuery(this).closest('.edd_vou_ajax_editor').find('.wp-editor-wrap').removeClass('html-active').addClass('tmce-active');
			tinyMCE.execCommand("mceAddEditor", false, editor_id);
		});
		
		jQuery(this).closest('.edd_vou_ajax_editor').find('.switch-html').click(function() {
			
			jQuery(this).closest('.edd_vou_ajax_editor').find('.wp-editor-wrap').removeClass('tmce-active').addClass('html-active');
			tinyMCE.execCommand("mceRemoveEditor", false, editor_id);
			
		});
		
	});
}
function edd_vou_get_tiny_content(obj) {
	
	var editor_id = obj,
		response;
	
	try {
		response = tinyMCE.get( editor_id).getContent();
		tinyMCE.execCommand('mceRemoveControl', false,  editor_id);
	}
	catch (err) {
		response = switchEditors.wpautop(jQuery('#'+obj).val());
	}
		return response;
}
function edd_vou_set_colorpicker( obj ) {
	
	//code for color picker
	if( EddVouSettings != '1' ) {
		obj.wpColorPicker();
	} else {
		var inputcolor = obj.prev('input').val();
		obj.prev('input').css('background-color',inputcolor);
		obj.click(function(e) {
			colorPicker = jQuery(this).next('div');
			input = jQuery(this).prev('input');
			jQuery.farbtastic(jQuery(colorPicker), function(a) { jQuery(input).val(a).css('background', a); });
			colorPicker.show();
			e.preventDefault();
			jQuery(document).mousedown( function() { jQuery(colorPicker).hide(); });
		});
	}	
}