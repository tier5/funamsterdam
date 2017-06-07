jQuery(document).ready(function() {

    var user_who = tpbr_settings.user_who;
    var init_state = tpbr_settings.initial_state;
    var fixed = tpbr_settings.fixed;
    var who = tpbr_settings.guests_or_users;
    var yn_close = tpbr_settings.yn_close;
    var fontsize = tpbr_settings.fontsize;
    var delay = tpbr_settings.delay;
    var border = tpbr_settings.border;
    var message = tpbr_settings.message;
    var url = tpbr_settings.button_url;
    var link = tpbr_settings.button_text;
    var tbcolor = tpbr_settings.color;
    var status = tpbr_settings.status;
    var button = tpbr_settings.yn_button;
    var is_admin_bar = tpbr_settings.is_admin_bar;
    var close_url = tpbr_settings.close_url;

    var usercheck = 'notok';

    if (user_who == 'notloggedin' && who == 'guests'){
        usercheck = 'ok';
    }

    if (user_who == 'loggedin' && who == 'users'){
        usercheck = 'ok';
    }

    if (who == 'all') {
        usercheck = 'ok';
    }

    if (usercheck == 'ok') {

        function shadeColor1(color, percent) {
            var num = parseInt(color.slice(1),16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, G = (num >> 8 & 0x00FF) + amt, B = (num & 0x0000FF) + amt;
            return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
        }

        if (button == 'button') {

            var ltbcolor = shadeColor1(tbcolor, -12);
            var btn_result = '<a id="tpbr_calltoaction" style="background:' + ltbcolor + '; display:inline-block; padding:2px 10px 2px; color:white; text-decoration:none; margin: 0px 20px 1px;border-radius:3px; line-height:29px;" href="' + url + '">' + link + '</a>';
        } else {
            var btn_result = '';
        }

        if (fixed == 'notfixed'){
            var fixed_result = '';
        }

        if (fixed == '' || fixed == null) {
            var fixed_result = '';
        }

        if (border == 'border') {
            var border_result = 'border-bottom:3px solid '+ ltbcolor +' !important; ';
        } else {
            var border_result = '';
        }


        if (jQuery.cookie("tpbr_closer")) {
            var is_closer = jQuery.cookie("tpbr_closer");
        } else {
            if (init_state == 'close') {
                jQuery('.tpbr_closing').hide();
                jQuery('#tpbr_topbar').hide();
                jQuery.cookie("tpbr_closer", '1');
                jQuery('.tpbr_opening').delay(100).slideDown(100);
            } else {
                is_closer = '0';
            }
        }

        if (status == 'active') {

            if (yn_close == 'close') {
            if (fixed === 'fixed'){
                var close_result = '<span class="tpbr_closing" style="display:inline; float:right; position:absolute; right:20px; top:12px; height:20px; width:20px; cursor:pointer; background:url(' + close_url + ')"></span>';
            } else {
                if (is_admin_bar === 'yes'){
                    var close_result = '<span class="tpbr_closing" style="display:inline; float:right; position:absolute; right:20px; top:44px; height:20px; width:20px; cursor:pointer; background:url(' + close_url + ')"></span>';
                } else {
                    var close_result = '<span class="tpbr_closing" style="display:inline; float:right; position:absolute; right:20px; top:12px; height:20px; width:20px; cursor:pointer; background:url(' + close_url + ')"></span>';
                }
            }
        } else {
            var close_result = '';
        }

            if (fixed == 'fixed'){
                if (is_admin_bar === 'yes'){
                    var fixed_result = 'position:fixed; z-index:99999; width:100%; left:0px; top:0; margin-top:32px;';
                    var admin_bar_fix = 'top: 32px !important;';
                } else {
                    var fixed_result = 'position:fixed; z-index:99999; width:100%; left:0px; top:0;';
                    var admin_bar_fix = 'top: 0px !important;';
                }
                setTimeout(function(){
                    if (is_closer == '1') {
                        jQuery('<div class="pushr" style="height:44px;"><div id="tpbr_topbar" style="' + fixed_result + ' background:' + tbcolor + ';' + border_result + '"><div id="tpbr_box" style="padding:6px 0px 5px; background:' + tbcolor + '; margin:0 auto; line-height:32px; text-align:center; width:100%; color:white; font-size:' + fontsize + 'px; font-family: Helvetica, Arial, sans-serif;  font-weight:300;">' + message + btn_result + '</div>' + close_result + '</div>').prependTo('body').hide();
                        jQuery('<div style="' + admin_bar_fix + ' background-color:'+ tbcolor +'; position:fixed !important;" class="tpbr_opening"></div>').prependTo('body');
                    } else {
                        jQuery('<div class="pushr" style="height:44px;"><div id="tpbr_topbar" style="' + fixed_result + ' background:' + tbcolor + ';' + border_result + '"><div id="tpbr_box" style="background:' + tbcolor + '; padding:6px 0px 5px; margin:0 auto; line-height:32px; font-size:' + fontsize + 'px; font-family: Helvetica, Arial, sans-serif; text-align:center; width:100%; color:white; font-weight:300;">' + message + btn_result + '</div>' + close_result + '</div>').prependTo('body').hide().slideDown(100);
                        jQuery('<div style="' + admin_bar_fix + ' background-color:'+ tbcolor +'; position:fixed;" class="tpbr_opening"></div>').prependTo('body').hide();
                    }
                }, delay);
                if (is_closer == '1') {
                    jQuery('<div style="' + admin_bar_fix + ' background-color:'+ tbcolor +'; position:fixed !important;" class="tpbr_opening"></div>').prependTo('body');
                }
            } else {
                if (is_admin_bar === 'yes'){
                    var admin_bar_fix = 'top: 32px !important;';
                } else {
                    var admin_bar_fix = 'top: 0px !important;';
                }
                setTimeout(function() {
                    if (is_closer == '1') {
                        jQuery('<div id="tpbr_topbar" style="' + fixed_result + ' background:' + tbcolor + ';' + border_result + '"><div id="tpbr_box" style="padding:6px 0px 5px; background:' + tbcolor + '; margin:0 auto; line-height:32px; text-align:center; width:100%; color:white; font-size:' + fontsize + 'px; font-family: Helvetica, Arial, sans-serif;  font-weight:300;">' + message + btn_result + '</div>' + close_result + '</div>').prependTo('body').hide();
                        jQuery('<div style="' + admin_bar_fix + ' background-color:'+ tbcolor +';" class="tpbr_opening"></div>').prependTo('body');
                    } else {
                        jQuery('<div id="tpbr_topbar" style="' + fixed_result + ' background:' + tbcolor + ';' + border_result + '"><div id="tpbr_box" style="padding:6px 0px 5px; background:' + tbcolor + '; margin:0 auto; line-height:32px; text-align:center; width:100%; color:white; font-size:' + fontsize + 'px; font-family: Helvetica, Arial, sans-serif;  font-weight:300;">' + message + btn_result + '</div>' + close_result + '</div>').prependTo('body').hide().slideDown(100);
                        jQuery('<div style="' + admin_bar_fix + ' background-color:'+ tbcolor +';" class="tpbr_opening"></div>').prependTo('body').hide();
                    }
                }, delay);

            }

        }

        setTimeout(function(){
            jQuery('.tpbr_closing').click(function() {
                if (fixed == 'fixed'){
                    jQuery('.tpbr_closing').fadeOut(200);
                    jQuery('#tpbr_topbar').slideUp(200);
                    jQuery.cookie("tpbr_closer", '1');
                    jQuery('.tpbr_opening').delay(100).slideDown(100);
                    jQuery('.pushr').delay(100).slideUp(100);
                } else {
                    jQuery('.tpbr_closing').fadeOut(200);
                    jQuery('#tpbr_topbar').slideUp(200);
                    jQuery.cookie("tpbr_closer", '1');
                    jQuery('.tpbr_opening').delay(100).slideDown(100);
                }
            });

            jQuery('.tpbr_opening').click(function() {
                if (fixed == 'fixed'){
                    jQuery('.tpbr_opening').slideUp(100);
                    jQuery('#tpbr_topbar').slideDown(300);
                    jQuery('.tpbr_closing').delay(350).fadeIn(200);
                    jQuery.cookie("tpbr_closer", '0');
                    jQuery('.pushr').delay(350).fadeIn(200);
                } else {
                    jQuery('.tpbr_opening').slideUp(100);
                    jQuery('#tpbr_topbar').slideDown(300);
                    jQuery.cookie("tpbr_closer", '0');
                    jQuery('.tpbr_closing').delay(350).fadeIn(200);
                }
            });

            jQuery('.tpbr_opening').css(
                "backgroundColor", tbcolor
            );

        }, delay);

    }

});
