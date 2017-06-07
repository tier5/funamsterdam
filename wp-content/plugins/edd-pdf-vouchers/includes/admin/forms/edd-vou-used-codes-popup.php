<?php
/**
 * Used Voucher Code
 *
 * The html markup for the used voucher code popup
 * 
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $edd_vou_model;

$model = $edd_vou_model;

$prefix = EDD_VOU_META_PREFIX;
		
//Get Voucher Details by post id
$usedcodes = $this->model->edd_vou_get_used_codes_by_download_id( $postid );

$html = '';
		
$html .= '<div class="edd-vou-popup-content edd-vou-used-codes-content">
		
			<div class="edd-vou-header">
				<div class="edd-vou-header-title">'.__( 'Used Voucher Codes', 'eddvoucher' ).'</div>
				<div class="edd-vou-popup-close"><a href="javascript:void(0);" class="edd-vou-close-button"><img src="' . EDD_VOU_URL .'includes/images/tb-close.png" alt="'.__( 'Close','eddvoucher' ).'"></a></div>
			</div>';

$generatpdfurl = add_query_arg( array( 
										'edd-vou-used-gen-pdf'	=>	'1',
										'download_id'			=>	$postid,
										'edd_vou_action'		=>	'used'
									));
$exportcsvurl = add_query_arg( array( 
										'edd-vou-used-exp-csv'	=>	'1',
										'download_id'			=>	$postid,
										'edd_vou_action'		=>	'used'
									));
			
$html .= '		<div class="edd-vou-popup used-codes">
					
					<div>
						<a href="'.$exportcsvurl.'" id="edd-vou-export-csv-btn" class="button-secondary" title="'.__('Export CSV','eddvoucher').'">'.__('Export CSV','eddvoucher').'</a>
						<a href="'.$generatpdfurl.'" id="edd-vou-pdf-btn" class="button-secondary" title="'.__('Generate PDF','eddvoucher').'">'.__('Generate PDF','eddvoucher').'</a>
					</div>
					
					<table class="form-table" border="1">
						<tbody>
							<tr>
								<th scope="row">'.__( 'Voucher Code', 'eddvoucher' ).'</th>
								<th scope="row">'.__( 'Buyer\'s Name', 'eddvoucher' ).'</th>
								<th scope="row">'.__( 'Order Date', 'eddvoucher' ).'</th>
								<th scope="row">'.__( 'Order ID', 'eddvoucher' ).'</th>
								<th scope="row">'.__( 'Redeem By', 'eddvoucher' ).'</th>
							</tr>';
								if( !empty( $usedcodes ) &&  count( $usedcodes ) > 0 ) { 
										
									foreach ( $usedcodes as $key => $voucodes_data ) { 
										
										//voucher order id
										$orderid 		= $voucodes_data['order_id'];
										
										//voucher order date
										$orderdate 		= $voucodes_data['order_date'];
										$orderdate 		= !empty( $orderdate ) ? $this->model->edd_vou_get_date_format( $orderdate ) : '';
										
										//buyer's name who has used voucher code				
										$buyername 		=  $voucodes_data['buyer_name'];
										
										//voucher code used
										$voucode 		= $voucodes_data['vou_codes'];
										
										//user data used
										$user_id 		= $voucodes_data['redeem_by'];										
										$user_detail  	= get_userdata( $user_id );
										$user_profile 	= add_query_arg( array('user_id' => $user_id), admin_url('user-edit.php') );
										$display_name 	= isset( $user_detail->display_name ) ? $user_detail->display_name : '';
										
										if( !empty( $display_name ) ){
											$display_name = '<a href="'.$user_profile.'">'.$display_name.'</a>';
										} else {
											$display_name = __( 'N/A', 'eddvoucher' );
										}
		
									$html .= '<tr>
											<td>'.$voucode.'</td>
											<td>'.$buyername.'</td>
											<td>'.$orderdate.'</td>
											<td>'.$orderid.'</td>
											<td>'.$display_name.'</td>
										</tr>';
										
									}
									
								} else { 
									$html .= '<tr>
											<td colspan="4">'.__( 'No voucher codes used yet.','eddvoucher' ).'</td>
										</tr>';
								}	
$html .= '					</tbody>
					</table>
			</div><!--.edd-vou-popup-->

		</div><!--.edd-vou-used-codes-popup-->
		<div class="edd-vou-popup-overlay edd-vou-used-codes-popup-overlay"></div>';

echo $html;
?>