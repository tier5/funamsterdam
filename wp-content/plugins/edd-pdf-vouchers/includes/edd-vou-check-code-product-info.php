<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $edd_vou_model, $current_user;

//model class
$model = $edd_vou_model;

//get payment id
$payment_id		=	$order_id;
 
//get post meta orderdata
$orderdata 		= 	$model->edd_vou_get_post_meta_ordered( $payment_id );

$allorderdata 	= 	$model->edd_vou_get_all_ordered_data( $payment_id );	

$cart_details 	= 	edd_get_payment_meta_cart_details( $payment_id );

//get payment method
$gateway 		= 	edd_get_payment_gateway( $payment_id );

//get payment date
$item         	= 	get_post( $payment_id );
$payment_date   = 	strtotime( $item->post_date );

$prefix 		= 	EDD_VOU_META_PREFIX;
 
//get user information
$user_info		=	edd_get_payment_meta_user_info( $payment_id, true );
 
$customer_address = array();

if ( is_user_logged_in() ){
	$user_id      = get_current_user_id();
	$first_name   = get_user_meta( $user_id, 'first_name', true );
	$last_name    = get_user_meta( $user_id, 'last_name', true );
	$display_name = $current_user->display_name;
	$customer_address      = edd_get_customer_address( $user_id );
	$defaults = array(
							'line1'   => '',
							'line2'   => '',
							'city'    => '',
							'state'   => '',
							'country' => '',
							'zip'     => ''
						);

	$customer_address = wp_parse_args( $customer_address, $defaults );
	 
}

$address = ! empty( $user_info['address'] ) ? $user_info['address'] : $customer_address ;

 
//product information columns
$product_info_columns = apply_filters( 'edd_vou_check_vou_productinfo_fields', array(
													'download_title'		=> __( 'Download Title', 'eddvoucher' ),
													'item_price'			=> __( 'Price', 'eddvoucher' )
												), $order_id, $voucode );
												
//product voucher information columns
$voucher_info_columns = apply_filters( 'edd_vou_check_vou_voucherinfo_fields', array(
													'logo' 			=> __( 'Logo', 'eddvoucher' ),
													'voucher_data' 	=> __( 'Voucher Data', 'eddvoucher' ),
													'expires' 		=> __( 'Expires', 'eddvoucher' ),
												), $order_id, $voucode );
												
//buyer info key parameter
$buyer_info_columns	= apply_filters( 'edd_vou_check_vou_buyerinfo_fields', array(
													'buyer_name'		=> __( 'Name', 'eddvoucher' ),
													'buyer_email'		=> __( 'Email', 'eddvoucher' ),
													'billing_address'	=> __( 'Billing Address', 'eddvoucher' ),
												), $order_id, $voucode );

//order info key parameter
$order_info_columns	= apply_filters( 'edd_vou_check_vou_orderinfo_fields', array(
													'Payment_id'		=> __( 'Payment ID', 'eddvoucher' ),
													'Payment_date'		=> __( 'Payment Date', 'eddvoucher' ),
													'payment_method'	=> __( 'Payment Method', 'eddvoucher' ),
													'payment_total'		=> __( 'Payment Total', 'eddvoucher' ),
													'payment_discount'	=> __( 'Payment Discount', 'eddvoucher' ),
												), $order_id, $voucode );
																								
?>
<div class="edd_vou_product_details">	 
	<h2><?php echo __( 'Product Information', 'eddvoucher' );?></h2>
	<table style="width:100%;" cellpadding="0" cellspacing="0" >
		<thead>
			<tr><?php
		
				if( !empty( $product_info_columns ) ) { //if product info is not empty
					foreach ( $product_info_columns as $col_key => $column ) { ?>
						
						<th><?php echo $column;?></th><?php
					}
				}?>
			</tr>
		</thead>
		<tbody>
			<tr class="edd-vou-pdt-row">
				<?php
				foreach ( $cart_details as $download_data ) {
									
					$download_pdt_ids 	= array();
					
					$download_id = $download_data['id'];
			 
					$download_items = isset( $download_data['item_number'] ) ? $download_data['item_number'] : array();
					 
					// Get variable option id
					$price_id = isset( $download_items['options'] ) && isset( $download_items['options']['price_id'] ) ? $download_items['options']['price_id'] : null;
					
					// If product is variable
					$download_pdt_key = !empty( $price_id ) ? $download_id . '_' . $price_id : $download_id;
					
					$download_pdt_ids[$download_pdt_key] = $download_id;
					
					foreach ( $download_pdt_ids as $download_key => $download_id ) {
						
						//vouchers data
						$voucherdata 	= isset( $orderdata[$download_key] ) ? $orderdata[$download_key] : array();
						
						//get all voucher details from order meta
						$allvoucherdata = isset( $allorderdata[$download_key] ) ? $allorderdata[$download_key] : array();
						
						// If product is variable then taking variable option name
						$product_sub_title = '';
						if ( edd_has_variable_prices($download_id) ) {
							$product_sub_title = ' - ' . edd_get_price_option_name( $download_id, $price_id, $payment_id );
						}

						//Get download recipient meta setting
						$recipient_data	= $model->edd_vou_get_download_recipient_meta( $download_id );	

						$vouchers_array = explode( ', ', $voucherdata['codes'] );

						if( !empty( $voucherdata ) && in_array( $voucode, $vouchers_array ) ) { // Check Voucher Data are not empty
							?> <td  class="edd-vou-history-td"> <?php
							 
							 	echo '<a target="_blank" href="'. get_permalink( $download_id ) . '">' .  get_the_title( $download_id ) . $product_sub_title  . '</a>';
							 	
							 	$download_item	= get_the_title( $download_id ) . $product_sub_title; //$download_data['name'];
								
								// Get recipient details
								if ( !empty( $allvoucherdata['recipient_name'] ) ) {
									echo "<div><b>".$recipient_data['recipient_name_label'].": </b>". $allvoucherdata['recipient_name'] ."</div>";
								}
								if ( !empty( $allvoucherdata['recipient_email'] ) ) {
									echo "<div><b>".$recipient_data['recipient_email_label'].": </b>". $allvoucherdata['recipient_email'] ."</div>";
								}
								if ( !empty( $allvoucherdata['recipient_message'] ) ) {
									echo "<div><b>".$recipient_data['recipient_message_label'].": </b>". $allvoucherdata['recipient_message'] ."</div>";
								} ?> 
								
								</td> 
								
								<td  class="edd-vou-history-td">
									<?php	
									$payment_meta   = edd_get_payment_meta( $payment_id );
									if( edd_has_variable_prices($download_id) ) {
										
										$prices			= get_post_meta( $download_id, 'edd_variable_prices', true );
										
										$variable_price	= isset($prices[$price_id]) ? $prices[$price_id] : '';
										$amount			= isset($variable_price['amount']) ? $variable_price['amount'] : 0;
										
										echo edd_currency_symbol( $payment_meta['currency'] ) . $amount;
										
									} else {
										echo edd_currency_symbol( $payment_meta['currency'] ) . edd_get_download_price( $download_id );
									} ?>
								</td>
							<?php
						}
					}
				} ?>
			</tr>
		</tbody>
	</table>
	
	<h2><?php echo __( 'Voucher Information', 'eddvoucher' ); ?></h2>
	<table style="width:100%;" cellpadding="0" cellspacing="0" >
		<thead>
			<tr><?php
		
				if( !empty( $voucher_info_columns ) ) { //if voucher info column is not empty
					foreach ( $voucher_info_columns as $col_key => $column ) { ?>
						
						<th><?php echo $column;?></th><?php
					}
				}?>
			</tr>
		</thead>
		<tbody>
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
					
					$download_pdt_ids[$download_pdt_key] = $download_id;
					
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
						
						$vouchers_array = explode( ', ', $voucherdata['codes'] );

						if( !empty( $voucherdata ) && in_array( $voucode, $vouchers_array ) ) { // Check Voucher Data are not empty?>
						
							<tr class="edd-vou-pdt-row <?php echo $download_is_bundle; ?>">
								<td class="edd-vou-history-td"><img src="<?php echo $allvoucherdata['vendor_logo']['src'] ?>" alt="" width="57" height="30" /></td>
								
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
							</tr><?php 
						}
					}
				} ?>
			</tbody>
		</table>
	<h2><?php echo __( 'Buyer Information', 'eddvoucher' ); ?></h2>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr><?php
				if( !empty( $buyer_info_columns ) ) { //if product info is not empty
					foreach ( $buyer_info_columns as $col_key => $column ) { ?>
						<th><?php echo $column;?></th><?php
					}
				}?>
			</tr>
		</thead>
		<tbody>
			<tr class="edd-vou-pdt-row">
				<td class="edd-vou-history-td"> <?php echo $user_info['first_name'].' '.$user_info['last_name']; ?>  </td>
				<td class="edd-vou-history-td"> <?php echo $user_info['email']; ?> </td>
				<td class="edd-vou-history-td"> 
					<?php if ( ! empty( $address ) ) { ?>
						<p><?php echo $address['line1']; ?></p>
						<p><?php echo $address['line2']; ?></p>
						<p><?php echo $address['city']; ?></p>
						<p><?php echo $address['state']; ?></p>
						<p><?php echo $address['country']; ?></p>
						<p><?php echo $address['zip']; ?></p>				
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<h2><?php echo __( 'Payment Information', 'eddvoucher' );?></h2>
	<table  style="width:100%;"  cellpadding="0" cellspacing="0">
		<thead>
			<tr><?php
				if( !empty( $order_info_columns ) ) { //if product info is not empty
					foreach ( $order_info_columns as $col_key => $column ) { ?>
						<th><?php echo $column;?></th><?php
					}
				}?>
			</tr>
		</thead>
		<tbody>
			<tr class="edd-vou-pdt-row">
				<td class="edd-vou-history-td"> <?php echo $order_id; ?>  </td>
				<td class="edd-vou-history-td"> <?php echo $model->edd_vou_get_date_format( date( $payment_date ) ); ?> </td>
				<td class="edd-vou-history-td"> <?php echo edd_get_gateway_admin_label( $gateway ); ?> </td>
				<td class="edd-vou-history-td"> 
				<?php
						$payment_meta   = edd_get_payment_meta( $payment_id );
						echo edd_currency_symbol( $payment_meta['currency'] ) . esc_attr( edd_format_amount( edd_get_payment_amount( $payment_id ) ) ); 
				?> 
				</td>
				<td class="edd-vou-history-td"><?php if ( isset( $user_info['discount'] ) && $user_info['discount'] !== 'none' ) { echo '<code>' . $user_info['discount'] . '</code>'; } else { _e( 'None', 'easy-digital-downloads' ); } ?></td>
			</tr>
		</tbody>
	</table>
</div>

