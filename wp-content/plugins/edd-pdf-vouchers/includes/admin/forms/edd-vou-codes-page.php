<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page
 *
 * The code for the plugins main settings page
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */		
	global $current_user;
 ?>
<div class="wrap">

	<!-- plugin name -->
	<h2 class="edd-vou-settings-title"><?php _e( 'Voucher Codes', 'eddvoucher' ); ?></h2><br />

		<!-- beginning of the left meta box section -->
		<div class="content edd-vou-content-section">
		
			<h2 class="nav-tab-wrapper edd-vou-h2">
				<?php 
		
					if( in_array( EDD_VOU_VENDOR_ROLE, $current_user->roles ) ) { // Check vendor user role
			
						$voucher_codes_page_url = add_query_arg( array( 'page' => 'edd-vou-codes' ), admin_url( 'admin.php' ) );
						
					} else {
						
						$voucher_codes_page_url = add_query_arg( array( 'post_type' => EDD_VOU_MAIN_POST_TYPE, 'page' => 'edd-vou-codes' ), admin_url( 'edit.php' ) );
					}
					$purchased_code_url 	= add_query_arg( array( 'vou-data' => 'purchased' ), $voucher_codes_page_url );
					$used_code_url 			= add_query_arg( array( 'vou-data' => 'used' ), $voucher_codes_page_url );
					$expired_code_url 		= add_query_arg( array( 'vou-data' => 'expired' ), $voucher_codes_page_url );
					
					$tab_prchased = ' nav-tab-active';
					$tab_used     = '';
					$tab_expired  = '';
					
					if( isset( $_GET['vou-data'] ) && $_GET['vou-data'] == 'purchased' ) {
			
						$tab_prchased = ' nav-tab-active';
						
					} elseif ( isset( $_GET['vou-data'] ) && $_GET['vou-data'] == 'used' ) {
						
						$tab_used = ' nav-tab-active';
						$tab_prchased = '';

					} elseif ( isset( $_GET['vou-data'] ) && $_GET['vou-data'] == 'expired' ) {
						
						$tab_expired = ' nav-tab-active';
						$tab_prchased = '';	
					}
				?>
		        <a class="nav-tab<?php echo $tab_prchased; ?>" href="<?php echo $purchased_code_url;  ?>"><?php _e('Purchased Voucher Codes','eddvoucher');?></a>
		        <a class="nav-tab<?php echo $tab_used; ?>" href="<?php echo $used_code_url; ?>"><?php _e('Used Voucher Codes','eddvoucher');?></a>
		        <a class="nav-tab<?php echo $tab_expired; ?>" href="<?php echo $expired_code_url; ?>"><?php _e('Unused Voucher Codes','eddvoucher');?></a>
		    </h2><!--nav-tab-wrapper-->
		    <!--beginning of tabs panels-->
			 <div class="edd-voucher-code-content">
			 
			 	<?php
					if( !empty( $tab_prchased ) ) {
						
						include_once( EDD_VOU_ADMIN . '/forms/edd-vou-purchased-list.php');
						
					} elseif ( !empty( $tab_used ) ) {
						
						include_once( EDD_VOU_ADMIN . '/forms/edd-vou-used-list.php');

					} elseif ( !empty( $tab_expired ) ) {
						
						include_once( EDD_VOU_ADMIN . '/forms/edd-vou-purchased-list-expire.php');
					}
				?>
			 <!--end of tabs panels-->
			 </div>
		<!--end of the left meta box section -->
		</div><!--.content edd-vou-content-section-->
	
<!--end .wrap-->
</div>