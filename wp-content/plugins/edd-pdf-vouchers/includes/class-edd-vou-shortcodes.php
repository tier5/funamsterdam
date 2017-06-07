<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Shortcodes Class
 *
 * Handles shortcodes functionality of plugin
 *
 * @package Easy Digital Downloads - Voucher Extension
 * @since 1.0.0
 */
class EDD_Vou_Shortcodes {
	
	var $model;
	
	function __construct(){
		
		global $edd_vou_model;
		
		$this->model	= $edd_vou_model;
		
	}
	
	/**
	 * Voucher Code Title Container
	 * 
	 * Handles to display voucher code title content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_code_title_container( $atts, $content ) {
		
		$html = $voucher_codes_html = '';
		$codes = array();
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 		=> '',
									 		'color' 		=> '#000000',
									 		'fontsize' 		=> '10',
									 		'textalign' 	=> 'left',
								 		), $atts ) );
		 		
		$bgcolor_css = $color_css = $textalign_css = $fontsize_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		if( !empty( $textalign ) ) {
			$textalign_css = 'text-align: ' . $textalign . ';';
		}
		if( !empty( $fontsize ) ) {
			$fontsize_css = 'font-size: ' . $fontsize . 'pt;';
		}
		
		if( !empty( $content ) && trim( $content ) != '' ) {
			
			$html .= '<table class="edd_vou_textblock" style="padding: 0px 5px; ' . $textalign_css . $bgcolor_css . $color_css . $fontsize_css . '">
						<tr>
							<td>
								'.wpautop( $content ).'
							</td>
						</tr>
					</table>';
		}
		
		return $html;
	}
	
	/**
	 * Voucher Code Container
	 * 
	 * Handles to display voucher code content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_code_container( $atts, $content ) {
		
		$html = $voucher_codes_html = '';
		$codes = array();
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 		=> '',
									 		'color' 		=> '#000000',
									 		'fontsize' 		=> '10',
									 		'textalign' 	=> 'left',
									 		'codeborder'	=> '',
									 		'codetextalign'	=> 'left',
									 		'codecolumn'	=> '1',
								 		), $atts ) );
	 	
		if( isset( $_GET['edd_vou_pdf_action'] ) && $_GET['edd_vou_pdf_action'] == 'preview' ) { // Check Test PDF Template
			
			$codes =array( 
								__( '[The voucher code will be inserted automatically here]', 'eddvoucher' ),
							
							);
		} else {
			
			if( ! isset( $_GET['download_id'] ) && isset( $_GET['download'] ) ) {
				$_GET['download_id'] = $_GET['download'];
			}
			
			//If download from order page
			$admin_download_key	= ( isset( $_GET['vou_download_key'] ) ) ? $_GET['vou_download_key'] : '';
			
			$args	=  array(
						'downloadid' => ( isset( $_GET['download_id'] ) )? (int) $_GET['download_id']		: '',
						'email'    => ( isset( $_GET['email'] ) )		 ? rawurldecode( $_GET['email'] )	: '',
						'expire'   => ( isset( $_GET['expire'] ) )		 ? rawurldecode( $_GET['expire'] )	: '',
						'file_key' => ( isset( $_GET['file'] ) )		 ? $_GET['file']					: '',
						'price_id' => ( isset( $_GET['price_id'] ) )	 ? (int) $_GET['price_id']			: false,
						'key'      => ( isset( $_GET['download_key'] ) ) ? $_GET['download_key']			: $admin_download_key
					);
			
			if( base64_encode( base64_decode( $_GET['expire'] ) ) === $_GET['expire'] ) {
				$args['expire'] = base64_decode( $_GET['expire'] );
			}
			
			// Download Process Arguments
			$args	= apply_filters( 'edd_process_download_args', $args );
			
			if( $args['downloadid'] === '' || $args['email'] === '' || $args['file_key'] === '' ) {
				return false;
			}
			
		    extract( $args );
			
		    if( !empty( $_GET['edd_vou_payment_id'] ) ) { //download from backend
		    	$orderid	= $_GET['edd_vou_payment_id'];
		    } else {
				$orderid	= edd_verify_download_link( $downloadid, $key, $email, $expire, $file_key );
		    }
			
			if( !empty( $orderid ) ) {
				
				//orderdata
				$orderdata = $this->model->edd_vou_get_post_meta_ordered( $orderid );
				
				$voucher_key = $downloadid;
				// code commented related to variable product
				/*if( !empty( $price_id ) ) {
					
					$voucher_key = $downloadid . '_' . $price_id;
				}*/
				
				//vouchers data of pdf
				$voucherdata = isset( $orderdata[$voucher_key] ) ? $orderdata[$voucher_key] : array();
				
				//voucher code
				if( isset( $voucherdata['codes'] ) && !empty( $voucherdata['codes'] ) ) {
					
					$codes = explode( ',', trim( $voucherdata['codes'] ) );
				}
				
			} 
		}
		
		$codeborder_attr = $codetextalign_css = '';
		if( !empty( $codeborder ) ) {
			$codeborder_attr .= 'border="' . $codeborder . '"';
		}
		if( !empty( $codetextalign ) ) {
			$codetextalign_css .= 'text-align: ' . $codetextalign . ';';
		}
		
		return '<table width="100%" ' . $codeborder_attr . 'style="padding: 5px; ' . $codetextalign_css . '">
					<tr>
						<td>
							' . wpautop($content) . '
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Voucher Redeem Container
	 * 
	 * Handles to display voucher redeem instructions
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_redeem_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 	=> ''
								 		), $atts ) );
		 		
		$bgcolor_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		
		return '<table class="edd_vou_messagebox" style="padding: 0px 5px; ' . $bgcolor_css . '">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Voucher Site Logo Container
	 * 
	 * Handles to display voucher site logo container
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_site_logo_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
		 		), $atts ) );
		 
		 return '<table class="edd_vou_sitelogobox" style="text-align: center">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Voucher Logo Container
	 * 
	 * Handles to display voucher logo container
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_logo_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
		 		), $atts ) );
		 
		 return '<table class="edd_vou_logobox" style="text-align: center">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Voucher Expire Date Container
	 * 
	 * Handles to display voucher expire date content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_expire_date_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 	=> ''
								 		), $atts ) );
		 	
		$bgcolor_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		
		return '<table class="edd_vou_expireblock" style="padding: 0px 5px; ' . $bgcolor_css . '">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Vendor's Address Container
	 * 
	 * Handles to display vendor's address content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_vendor_address_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 	=> ''
								 		), $atts ) );
		 	
		$bgcolor_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		
		return '<table class="edd_vou_venaddrblock" style="padding: 0px 5px; ' . $bgcolor_css . '">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Website URL Container
	 * 
	 * Handles to display website URL content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_siteurl_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 	=> ''
								 		), $atts ) );
		 	
		$bgcolor_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		
		return '<table class="edd_vou_siteurlblock" style="padding: 0px 5px; ' . $bgcolor_css . '">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
		
	}
	
	/**
	 * Voucher Locations Container
	 * 
	 * Handles to display voucher locations content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_location_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 	=> ''
								 		), $atts ) );
		 		
		$bgcolor_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		
		return '<table class="edd_vou_locblock" style="padding: 0px 5px; ' . $bgcolor_css . '">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Custom Container
	 * 
	 * Handles to display custom content
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0 
	 */
	public function edd_vou_custom_container( $atts, $content ) {
		
		$content = str_replace( '<p></p>', '', $content );
		
		extract( shortcode_atts( array(	
									 		'bgcolor' 	=> ''
								 		), $atts ) );
		 		
		$bgcolor_css = '';
		if( !empty( $bgcolor ) ) {
			$bgcolor_css = 'background-color: ' . $bgcolor . ';';
		}
		
		return '<table class="edd_vou_customblock" style="padding: 0px 5px; ' . $bgcolor_css . '">
					<tr>
						<td>
							'.wpautop( $content ).'
						</td>
					</tr>
				</table>';
	}
	
	/**
	 * Check Voucher Code
	 * 
	 * Handles to display check voucher code
	 * 
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.1.1
	 */
	public function edd_vou_check_code( $attr, $content ) {
		
		ob_start();
		if ( is_user_logged_in() ) { // check is user loged in
			
			do_action( 'edd_vou_check_code_content' );
			
		} else {
			
			_e( 'You need to be logged in to your account to see check voucher code.', 'eddvoucher' );
		}
		$content .= ob_get_clean();
		
		return $content;
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding proper hoocks for the shortcodes.
	 *
	 * @package Easy Digital Downloads - Voucher Extension
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		add_shortcode( 'edd_vou_code_title', array( $this, 'edd_vou_code_title_container' ) ); // for voucher code title
		add_shortcode( 'edd_vou_code', array( $this, 'edd_vou_code_container' ) ); // for voucher code
		add_shortcode( 'edd_vou_redeem', array( $this, 'edd_vou_redeem_container' ) ); //for redeem instruction
		add_shortcode( 'edd_vou_site_logo', array( $this, 'edd_vou_site_logo_container' ) ); //for voucher site logo
		add_shortcode( 'edd_vou_logo', array( $this, 'edd_vou_logo_container' ) ); //for voucher logo
		add_shortcode( 'edd_vou_expire_date', array( $this, 'edd_vou_expire_date_container' ) ); //for voucher expire date
		add_shortcode( 'edd_vou_vendor_address', array( $this, 'edd_vou_vendor_address_container' ) ); //for vendor's address
		add_shortcode( 'edd_vou_siteurl', array( $this, 'edd_vou_siteurl_container' ) ); //for website url
		add_shortcode( 'edd_vou_location', array( $this, 'edd_vou_location_container' ) ); //for voucher locations
		add_shortcode( 'edd_vou_custom', array( $this, 'edd_vou_custom_container' ) ); //for custom
		add_shortcode( 'edd_vou_check_code', array( $this, 'edd_vou_check_code' ) ); //for check voucher code
	}
}