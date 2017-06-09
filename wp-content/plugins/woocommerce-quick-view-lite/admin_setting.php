<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit;   
}

if ( ! empty( $_POST ) && check_admin_referer('phoen_quick_view_setting_my_action', 'phoen_quick_view_setting_my_fields') ) {
		
	if( sanitize_text_field( $_POST['phoen_quick_view_submit'] )== 'Save changes')
	{
		
		global $wpdb,$table_prefix;

		$checkqv =  sanitize_text_field( $_POST['checkqv'] );

		$buttonlabel =  sanitize_text_field( $_POST['buttonlabel'] );

		$win_bag_color =  sanitize_text_field( $_POST['win_bag_color'] );

		$but_qk_color =  sanitize_text_field( $_POST['but_qk_color'] );

		$col_pop_but_colr =  sanitize_text_field( $_POST['col_pop_but_colr'] );

		$col_pop_but_h_colr =  sanitize_text_field( $_POST['col_pop_but_h_colr'] );

		$option = 'phoen_quick_view';
		
		$data = array(
						'status'=>$checkqv,'button_label'=>$buttonlabel ,'popup_bg'=>$win_bag_color,
						'button_quick_view_color'=>$but_qk_color,'close_popup_btn_color'=>$col_pop_but_colr,
						'close_popup_btn_hcolor'=>$col_pop_but_h_colr
					);

		$new_value = json_encode($data);
		
		$query_check = update_option( $option, $new_value);
		
		if($query_check == 1){ ?>

			<div class="updated" id="message">

				<p><strong>Setting updated.</strong></p>

			</div>

			<?php
		}
		else
		{
			?>
				<div class="error below-h2" id="message"><p> Something Went Wrong Please Try Again With Valid Data.</p></div>
			<?php
		}
			
	}
	
}

$data = get_option('phoen_quick_view');

$row  = json_decode($data);

?>

<div id="profile-page" class="wrap">

<?php
	
	$tab = sanitize_text_field( $_GET['tab'] ); 
?>
	
	<h2>Quick View plugin  Options </h2>
	
	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a class="nav-tab <?php if($tab == 'general' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=quick_view_setting&amp;tab=general">Setting</a>
		<a class="nav-tab <?php if($tab == 'premium'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=quick_view_setting&amp;tab=premium">Premium</a>
	</h2>

	<form novalidate="novalidate" method="post" action="" >

	<?php wp_nonce_field( 'phoen_quick_view_setting_my_action', 'phoen_quick_view_setting_my_fields' ); 

	if($tab == 'general' || $tab == '')
	{
		
		$plugin_dir_url =  plugin_dir_url( __FILE__ );	?>
		
		<div class="meta-box-sortables" id="normal-sortables">
			<div class="postbox " id="pho_wcpc_box">
				<h3><span class="upgrade-setting">Upgrade to the PREMIUM VERSION</span></h3>
				<div class="inside">
					<div class="pho_check_pin">
						<div class="column two">
							<p>Switch to the premium version of Woocommerce Quick View Plugin Options  to get the benefit of all features!</p>
							<div class="pho-upgrade-btn">
								<a href="http://www.phoeniixx.com/product/woocommerce-quick-view/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/premium-btn.png"></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<style>
			.postbox {
				background: #fff none repeat scroll 0 0;
				border: 1px solid #e5e5e5;
				box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
				min-width: 255px;
			}
			#pho_wcpc_box.postbox h3 {
				padding: 0 0 0 10px;
			}
			.postbox .inside {
				margin: 11px 0;
				position: relative;
			}
			.postbox h3{padding:10px;}
		</style>
		<table class="form-table">
			
			<tbody>
				<h3 class="setting-title">General Options</h3>
				<tr class="user-nickname-wrap">
					<th><label for="checkqv">Enable Plugin</label></th>
					<td><input type="checkbox" value="enable" <?php if(esc_attr($row->status) == 'enable'){ echo "checked"; } ?> id="checkqv" name="checkqv" ></td>
				</tr>
			</tbody>	
			
			<tbody>
				<tr id="buttonlabel" class="user-nickname-wrap" style="display:<?php if($qry['buttontype'] == 1){ echo "none"; } ?>">
					<th><label for="buttonlabel">Button Label</label></th>
					<td><label for="buttonlabel"><input type="text" value="<?php echo esc_attr($row->button_label); ?>" id="buttonlabel" name="buttonlabel" ></label></td>
				</tr>
			</tbody>

			<tbody>	
				<tr class="user-user-login-wrap">
					<th><label for="win_bag_color">Popup background color</label></th>
					<td><input type="text" class="regular-text" value="<?php echo esc_attr($row->popup_bg); ?>" id="win_bag_color" name="win_bag_color"></td>
				</tr>

				<tr class="user-user-login-wrap">
					<th><label for="but_qk_color">Button “Quick View” color</label></th>
					<td><input type="text" class="regular-text" value="<?php echo esc_attr($row->button_quick_view_color); ?>" id="but_qk_color" name="but_qk_color"></td>
				</tr>
			</tbody>

			<tbody>	
				<tr class="user-user-login-wrap">
					<th><label for="col_pop_but_colr">Close Popup button color</label></th>
					<td><input type="text" class="regular-text" value="<?php echo esc_attr($row->close_popup_btn_color); ?>" id="col_pop_but_colr" name="col_pop_but_colr"></td>
				</tr>
				
				<tr class="user-user-login-wrap">
					<th><label for="col_pop_but_h_colr">Close Popup button hover color</label></th>
					<td><input type="text" class="regular-text" value="<?php echo esc_attr($row->close_popup_btn_hcolor); ?>" id="col_pop_but_h_colr" name="col_pop_but_h_colr"></td>
				</tr>
			</tbody>

		</table>

		<p class="submit"><input type="submit" value="Save changes" class="button button-primary" id="submit" name="phoen_quick_view_submit"></p>
			
		<?php
	}
	if($tab == 'premium')
	{
		
		require_once(dirname(__FILE__).'/premium_setting.php');
	}
	?>

	</form>

</div>

<script>

jQuery(document).ready(function($)

{

	jQuery("#win_bag_color").wpColorPicker();

	jQuery("#but_qk_color").wpColorPicker();

	
	jQuery("#col_pop_but_colr").wpColorPicker();
	
	jQuery("#col_pop_but_h_colr").wpColorPicker();


	var custom_upload;

	
	$(document).on("click",".uploadimage",uploadimage_button);

    function uploadimage_button(){

		textid = this.id+'1';

        var custom_upload = wp.media({

        title: 'Add Media',

        button: {

            text: 'Insert Image'

        },

        multiple: false  // Set this to true to allow multiple files to be selected

    })

    .on('select', function() {

        var attachment = custom_upload.state().get('selection').first().toJSON();

        $('.custom_media_image').attr('src', attachment.url);

        $('#'+textid).val(attachment.url);

        

    })

    .open();

 

    }
		
});
</script>
<style>
.form-table th {
    width: 270px;
	padding: 25px;
}
.form-table td {
	
    padding: 20px 10px;
}
.form-table {
	background-color: #fff;
}
h3 {
    padding: 10px;
}
.px-multiply{ color:#ccc; vertical-align:bottom;}

.long{ display:inline-block; vertical-align:middle; }

.wid{ display:inline-block; vertical-align:middle; }

.up{ display:block;}

.grey{ color:#b0adad;}
</style>
