<?php
/**
 * Purchased Voucher Code
 *
 * The html markup for the purchased voucher code popup
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
$purchasedcodes = $this->model->edd_vou_get_purchased_codes_by_download_id( $postid );

$html = '';
		
$html .= '<div class="edd-vou-popup-content edd-vou-purchased-codes-content">
		
			<div class="edd-vou-header">
				<div class="edd-vou-header-title">'.__( 'Purchased Voucher Codes', 'eddvoucher' ).'</div>
				<div class="edd-vou-popup-close"><a href="javascript:void(0);" class="edd-vou-close-button"><img src="' . EDD_VOU_URL .'includes/images/tb-close.png" alt="'.__( 'Close','eddvoucher' ).'"></a></div>
			</div>';

$generatpdfurl = add_query_arg( array( 
										'edd-vou-used-gen-pdf'	=>	'1',
										'download_id'			=>	$postid
									));
$exportcsvurl = add_query_arg( array( 
										'edd-vou-used-exp-csv'	=>	'1',
										'download_id'			=>	$postid
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
							</tr>';
								if( !empty( $purchasedcodes ) &&  count( $purchasedcodes ) > 0 ) { 
										
										foreach ( $purchasedcodes as $key => $voucodes_data ) {
											
											//voucher order id
											$orderid 		= $voucodes_data['order_id'];
											
											//voucher order date
											$orderdate 		= $voucodes_data['order_date'];
											$orderdate 		= !empty( $orderdate ) ? $this->model->edd_vou_get_date_format( $orderdate ) : '';
											
											//buyer's name who has purchased voucher code				
											$buyername 		=  $voucodes_data['buyer_name'];
											
											//voucher code purchased
											$voucode 		= $voucodes_data['vou_codes'];
											
									$html .= '<tr>
											<td>'.$voucode.'</td>
											<td>'.$buyername.'</td>
											<td>'.$orderdate.'</td>
											<td>'.$orderid.'</td>
										</tr>';
										
									}
									
								} else { 
									$html .= '<tr>
											<td colspan="4">'.__( 'No voucher codes purchased yet.','eddvoucher' ).'</td>
										</tr>';
								}	
$html .= '					</tbody>
					</table>
			</div><!--.edd-vou-popup-->

		</div><!--.edd-vou-popup-content-->
		<div class="edd-vou-popup-overlay"></div>';

echo $html;
?>