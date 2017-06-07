<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

	global $post;
		
?> 	

	<div class="edd-vou-popup-content edd-vou-import-content">
					
		<div class="edd-vou-header">
			<div class="edd-vou-header-title"><?php _e( 'Generate / Import Codes', 'eddvoucher' ); ?></div>
			<div class="edd-vou-popup-close"><a href="javascript:void(0);" class="edd-vou-close-button"><img src="<?php echo EDD_VOU_URL .'includes/images/tb-close.png'; ?>" alt="<?php _e( 'Close','eddvoucher' ); ?>"></a></div>
		</div>
			
		<div class="edd-vou-popup">

			<div class="edd-vou-file-errors"></div>
			<form method="POST" action="" enctype="multipart/form-data" id="edd_vou_import_csv">
				<table class="form-table edd-vou-import-table">
					<tbody>
						<tr>
							<td colspan="2"><strong><?php _e( 'General', 'eddvoucher' ); ?><strong></td>
						</tr>
						<tr>
							<td scope="col" class="edd-vou-field-title"><?php _e( 'Delete Existing Code', 'eddvoucher' ); ?></td>
							<td>
								<select name="edd_vou_delete_code" class="edd-vou-delete-code">
									<option value=""><?php _e( 'No', 'eddvoucher' ); ?></option>
									<option value="y"><?php _e( 'Yes', 'eddvoucher' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong><?php _e( 'Generate Options', 'eddvoucher' ); ?><strong>
							</td>
						</tr>
					</tbody>
					<tbody id="edd-vou-code-generate-part">
						<tr>
							<td scope="col" class="edd-vou-field-title"><?php _e( 'Number of Voucher Codes', 'eddvoucher' ); ?></td>
							<td>
								<input type="text" class="edd-vou-no-of-voucher" value="" />
							</td>
						</tr>
						<tr class="edd-vou-submisssion-tr">
							<td scope="col" class="edd-vou-field-title"><?php _e( 'Submission', 'eddvoucher' ); ?></td>
							<td>
								<span class="edd-vou-prefix-span"><strong><?php _e( 'Prefix', 'eddvoucher' ); ?></strong></span>
								<span class="edd-vou-seperator-span"><strong><?php _e( 'Seperator', 'eddvoucher' ); ?></strong></span>
								<span class="edd-vou-pattern-span"><strong><?php _e( 'Pattern', 'eddvoucher' ); ?></strong></span><br />
								<input type="text" class="edd-vou-code-prefix" value="" />
								<input type="text" class="edd-vou-code-seperator" value="" />
								<input type="text" class="edd-vou-code-pattern" value="LLDDD" /><br />
								<span class="description">
									<strong><?php _e( 'L', 'eddvoucher' ); ?></strong> - <?php _e( 'letter', 'eddvoucher' ); ?>, <strong><?php _e( 'D', 'eddvoucher' ); ?></strong> - <?php _e( 'digit', 'eddvoucher' ); ?>
									<small><?php _e( 'e.g. CODE_LLDDD results in CODE_WT108', 'eddvoucher' ); ?></small>
								</span>
							</td>
						</tr>
						<tr>
							<td scope="col"></td>
							<td>
								<input type="button" class="edd-vou-import-btn button-secondary" value="<?php _e( 'Generate Codes', 'eddvoucher' ); ?>" />
								<img class="edd-vou-loader" src="<?php echo EDD_VOU_URL . 'includes/images/ajax-loader.gif'; ?>" alt="<?php _e('Loading...', 'eddvoucher'); ?>" />
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td colspan="2">
								<strong><?php _e( 'Import Options', 'eddvoucher' ); ?><strong>
							</td>
						</tr>
					</tbody>
					<tbody id="edd-vou-code-import-part">
						<tr>
							<td scope="col"><?php _e( 'CSV Separator', 'eddvoucher' ); ?></td>
							<td>
								<input type="text" id="edd_vou_csv_sep" name="edd_vou_csv_sep" class="edd-vou-csv-sep"/>
							</td>
						</tr>
						<tr>
							<td scope="col"><?php _e( 'CSV Enclosure', 'eddvoucher' ); ?></td>
							<td>
								<input type="text" id="edd_vou_csv_enc" name="edd_vou_csv_enc" class="edd-vou-csv-enc"/>
							</td>
						</tr>
						<tr>
							<td scope="col" class="edd-vou-field-title"><?php _e( 'Upload CSV File', 'eddvoucher' ); ?></td>
							<td>
								<input type="file" id="edd_vou_csv_file" name="edd_vou_csv_file" class="edd-vou-csv-file"/>
							</td>
						</tr>
						<tr>
							<td scope="col"></td>
							<td>
								<input type="hidden" id="edd_vou_existing_code" name="edd_vou_existing_code" value="" />
								<input type="submit" name="edd_vou_import_csv" id="edd_vou_import_csv" value="<?php _e( 'Import Codes', 'eddvoucher' ); ?>" class="button-secondary edd-vou-meta-vou-import-codes">
							</td>
						</tr>
							
					</tbody>
				</table>
			</form>
		</div><!--.edd-vou-popup-->
	</div><!--.edd-vou-popup-content-->
	
	<div class="edd-vou-popup-overlay edd-vou-import-overlay"></div>