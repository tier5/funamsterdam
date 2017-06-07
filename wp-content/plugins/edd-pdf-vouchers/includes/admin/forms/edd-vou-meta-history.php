<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $edd_vou_model, $edd_payment_id;

//model class
$model = $edd_vou_model;

$orderdata = $model->edd_vou_get_post_meta_ordered( $payment_id );
$allorderdata = $model->edd_vou_get_all_ordered_data( $payment_id );

$cart_details 	= edd_get_payment_meta_cart_details( $payment_id );

$prefix = EDD_VOU_META_PREFIX;

$edd_payment_id = $payment_id;

// Check Voucher is enable and cart details are not empty
if( !empty( $orderdata ) && !empty( $cart_details ) ) {

?>

<div id="edd-vou-voucher-details" class="postbox">
	<h3 class="hndle"><span><?php _e( 'Voucher Details', 'eddvoucher' ); ?></span></h3>
	<div class="edd-vou-inside">

		<table class="widefat edd-vou-history-table">
			<tr class="edd-vou-history-title-row">
				<th width="8%"><?php _e( 'Logo', 'eddvoucher' ); ?></th>
				<th width="17%"><?php _e( 'Download Title', 'eddvoucher' ); ?></th>
				<th width="15%"><?php _e( 'Code', 'eddvoucher' ); ?></th>
				<th width="45%"><?php _e( 'Voucher Data', 'eddvoucher' ); ?></th>
				<th width="10%"><?php _e( 'Expires', 'eddvoucher' ); ?></th>
				<th width="5%"><?php _e( 'Qty', 'eddvoucher' ); ?></th>
			</tr>
			<?php
				foreach ( $cart_details as $download_data ) {
					
					$download_pdt_ids 	= array();
					$download_is_bundle = '';
					
					$download_id = $download_data['id'];
					
					// Get download items
					$download_items = isset( $download_data['item_number'] ) ? $download_data['item_number'] : array();
					
					// Get variable option id
					$price_id = isset( $download_items['options'] ) && isset( $download_items['options']['price_id'] ) ? $download_items['options']['price_id'] : null;
					
					// If product is variable
					$download_pdt_key = !empty( $price_id ) ? $download_id . '_' . $price_id : $download_id;
					
					// If product type is 'bundle'
					if( edd_is_bundled_product( $download_id ) ) {
						
						$download_is_bundle = 'edd-vou-pdt-bundle';
						
						$download_bundled_products = edd_get_bundled_products( $download_id );
						
						if( !empty( $download_bundled_products ) ) {
							
							echo '<tr class="edd-vou-pdt-row"><td colspan="6"><b>'.$download_data['name'].'</b></td></tr>';
							
							foreach ( $download_bundled_products as $download_bundled_product ) {
								$download_pdt_ids[$download_bundled_product] = $download_bundled_product;
							}
						}
					} else {
						$download_pdt_ids[$download_pdt_key] = $download_id;
					}
					
					foreach ( $download_pdt_ids as $download_key => $download_id ) {
						
						//vouchers data of pdf
						$voucherdata 	= isset( $orderdata[$download_key] ) ? $orderdata[$download_key] : array();
						
						//get all voucher details from order meta
						$allvoucherdata = isset( $allorderdata[$download_key] ) ? $allorderdata[$download_key] : array();
						
						// If product is variable then taking variable option name
						if ( isset( $price_id ) ) {
							$product_sub_title = ' - ' . edd_get_price_option_name( $download_id, $price_id, $payment_id );
						} else {
							$product_sub_title = '';
						}
						
						//Get download recipient meta setting
						$recipient_data	= $model->edd_vou_get_download_recipient_meta( $download_id );																				
						
						if( !empty( $voucherdata ) ) { // Check Voucher Data are not empty?>
							
							<tr class="edd-vou-pdt-row <?php echo $download_is_bundle; ?>">
								<td class="edd-vou-history-td"><img src="<?php echo $allvoucherdata['vendor_logo']['src'] ?>" alt="" width="57" height="30" /></td>
								<td class="edd-vou-history-td">							
								
								<?php echo get_the_title( $download_id ) . $product_sub_title; //$download_data['name']; ?>
									
								<?php 
									
									// Get recipient details
									if ( !empty( $allvoucherdata['recipient_name'] ) ) {
										echo "<div><b>".$recipient_data['recipient_name_label'].": </b>". $allvoucherdata['recipient_name'] ."</div>";
									}
									if ( !empty( $allvoucherdata['recipient_email'] ) ) {
										echo "<div><b>".$recipient_data['recipient_email_label'].": </b>". $allvoucherdata['recipient_email'] ."</div>";
									}
									if ( !empty( $allvoucherdata['recipient_message'] ) ) {
										echo "<div><b>".$recipient_data['recipient_message_label'].": </b>". $allvoucherdata['recipient_message'] ."</div>";
									}
									
									// Get voucher download files
									$download_files = edd_get_download_files( $download_id, $price_id );				
									
									foreach ( $download_files as $download_file_key => $download_file ) {
										
										$check_key = strpos( $download_file_key, 'edd_vou_pdf' );
												
										if( !empty( $download_file ) && $check_key !== false ) {
											
											// Get payment key
											$payment_key = edd_get_payment_key( $payment_id );
											
											// Get user email
											$email       = edd_get_payment_user_email( $payment_id );
											
											// Get download file url
											$download_url = edd_get_download_file_url( $payment_key, $email, $download_file_key, $download_id, $price_id );		
											
											// Download param string
											$download_params	= substr( $download_url, strpos( $download_url, '?' ) + 1 );
											
											// Download Param array
											$download_params_arr= wp_parse_args( $download_params );
											
											//Voucher download key
											$vou_download_key	= isset( $download_params_arr['download_key'] ) ? $download_params_arr['download_key'] : '';
											
											//Remove order query arguments
											$download_url	= remove_query_arg( 'download_key', $download_url );
											
											//add arguments array
											$add_arguments	= array(
																	'edd_vou_admin'			=> true,
																	'edd_vou_payment_id'	=> $payment_id,
																	'vou_download_key'		=> $vou_download_key
																);
											
											//PDF Download URL
											$download_url	= add_query_arg( $add_arguments, $download_url );
										
											echo '<div><a href="'.$download_url.'">'.$download_file['name'].'</a></div>';
											
										}
									}
								?>
								</td>
								<td class="edd-vou-history-td"><?php echo $voucherdata['codes']; ?></td>
								<td class="edd-vou-history-td">
									<p><strong><?php _e( 'Vendor\'s Address', 'eddvoucher' ); ?></strong></p>
									<p><?php echo !empty( $allvoucherdata['vendor_address'] ) ? nl2br( $allvoucherdata['vendor_address'] ) : __( 'N/A', 'eddvoucher' ); ?></p>
									<p><strong><?php _e( 'Site URL', 'eddvoucher' ); ?></strong></p>
									<p><?php echo !empty( $allvoucherdata['website_url'] ) ? $allvoucherdata['website_url'] : __( 'N/A', 'eddvoucher' ); ?></p>
									<p><strong><?php _e( 'Redeem Instructions', 'eddvoucher' ); ?></strong></p>
									<p><?php echo !empty( $allvoucherdata['redeem'] ) ? nl2br( $allvoucherdata['redeem'] ) : __( 'N/A', 'eddvoucher' ); ?></p>
								<?php
									if( !empty( $allvoucherdata['avail_locations'] ) ) {
				
										echo '<p><strong>' . __( 'Locations', 'eddvoucher' ) . '</strong></p>';
								
										foreach ( $allvoucherdata['avail_locations'] as $location ) {
								
											if( !empty( $location[$prefix.'locations'] ) ) {
											
												if( !empty( $location[$prefix.'map_link'] ) ) {
													echo '<p><a target="_blank" style="text-decoration: none;" href="' . $location[$prefix.'map_link'] . '">' . $location[$prefix.'locations'] . '</a></p>';
												} else {
													echo '<p>' . $location[$prefix.'locations'] . '</p>';
												}
											}
										}
									}
								?>
								</td>
								<td class="edd-vou-history-td"><?php echo !empty( $allvoucherdata['exp_date'] ) ? $model->edd_vou_get_date_format( $allvoucherdata['exp_date'] ) : __( 'N/A', 'eddvoucher' ); ?></td>
								<td class="edd-vou-history-td"><?php echo $download_data['quantity']; ?></td>
							</tr><?php 
						}
					}
				} ?>
		</table>
		<div class="clear"></div>
		
	</div><!-- /.inside -->
</div><!-- /#edd-payment-notes -->

<?php } ?>