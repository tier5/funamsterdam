<?php

namespace GFPDF\Model;

use GFPDF\Helper\Helper_Abstract_Model;
use GFPDF\Helper\Helper_Abstract_Form;
use GFPDF\Helper\Helper_Notices;
use GFPDF\Helper\Helper_Abstract_Options;
use GFPDF\Helper\Helper_Data;
use GFPDF\Helper\Helper_Misc;
use GFPDF\Helper\Helper_Templates;

use Psr\Log\LoggerInterface;

/**
 * Settings Model
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2016, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
    This file is part of Gravity PDF.

    Gravity PDF – Copyright (C) 2016, Blue Liquid Designs

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Model_Welcome_Screen
 *
 * A general class for About / Intro Screen
 *
 * @since 4.0
 */
class Model_Settings extends Helper_Abstract_Model {

	/**
	 * Errors with the global form submission process are stored here
	 *
	 * @var array
	 *
	 * @since 4.0
	 */
	public $form_settings_errors;

	/**
	 * Holds the abstracted Gravity Forms API specific to Gravity PDF
	 *
	 * @var \GFPDF\Helper\Helper_Form
	 *
	 * @since 4.0
	 */
	protected $gform;

	/**
	 * Holds our log class
	 *
	 * @var \Monolog\Logger|LoggerInterface
	 *
	 * @since 4.0
	 */
	protected $log;

	/**
	 * Holds our Helper_Notices object
	 * which we can use to queue up admin messages for the user
	 *
	 * @var \GFPDF\Helper\Helper_Misc
	 *
	 * @since 4.0
	 */
	protected $notices;

	/**
	 * Holds our Helper_Abstract_Options / Helper_Options_Fields object
	 * Makes it easy to access global PDF settings and individual form PDF settings
	 *
	 * @var \GFPDF\Helper\Helper_Options_Fields
	 *
	 * @since 4.0
	 */
	protected $options;

	/**
	 * Holds our Helper_Data object
	 * which we can autoload with any data needed
	 *
	 * @var \GFPDF\Helper\Helper_Data
	 *
	 * @since 4.0
	 */
	protected $data;

	/**
	 * Holds our Helper_Misc object
	 * Makes it easy to access common methods throughout the plugin
	 *
	 * @var \GFPDF\Helper\Helper_Misc
	 *
	 * @since 4.0
	 */
	protected $misc;

	/**
	 * Holds our Helper_Templates object
	 * used to ease access to our PDF templates
	 *
	 * @var \GFPDF\Helper\Helper_Templates
	 *
	 * @since 4.0
	 */
	protected $templates;

	/**
	 * Set up our dependancies
	 *
	 * @param \GFPDF\Helper\Helper_Abstract_Form    $gform   Our abstracted Gravity Forms helper functions
	 * @param \Monolog\Logger|LoggerInterface       $log     Our logger class
	 * @param \GFPDF\Helper\Helper_Notices          $notices Our notice class used to queue admin messages and errors
	 * @param \GFPDF\Helper\Helper_Abstract_Options $options Our options class which allows us to access any settings
	 * @param \GFPDF\Helper\Helper_Data             $data    Our plugin data store
	 * @param \GFPDF\Helper\Helper_Misc             $misc    Our miscellaneous class
	 * @param \GFPDF\Helper\Helper_Templates        $templates
	 *
	 * @since 4.0
	 */
	public function __construct( Helper_Abstract_Form $gform, LoggerInterface $log, Helper_Notices $notices, Helper_Abstract_Options $options, Helper_Data $data, Helper_Misc $misc, Helper_Templates $templates ) {

		/* Assign our internal variables */
		$this->gform     = $gform;
		$this->log       = $log;
		$this->options   = $options;
		$this->notices   = $notices;
		$this->data      = $data;
		$this->misc      = $misc;
		$this->templates = $templates;
	}

	/**
	 * Get the form setting error and remove any duplicates
	 *
	 * @since 4.0
	 *
	 * @return  void
	 */
	public function setup_form_settings_errors() {

		/* set up a place to access form setting validation errors */
		$this->form_settings_errors = get_transient( 'settings_errors' );

		/* remove multiple errors for a single form */
		if ( $this->form_settings_errors ) {
			$set                    = false;
			$updated_settings_error = [];

			/* loop through current errors */
			foreach ( $this->form_settings_errors as $error ) {
				if ( $error['setting'] != 'gfpdf-notices' || ! $set ) {
					$updated_settings_error[] = $error;
				}

				if ( $error['setting'] == 'gfpdf-notices' ) {
					$set = true;
				}
			}

			/* update transient */
			set_transient( 'settings_errors', $updated_settings_error, 30 );

			$this->log->addNotice( 'PDF Settings Errors', [
				'original' => $this->form_settings_errors,
				'cleaned'  => $updated_settings_error,
			] );
		}
	}

	/**
	 * If any errors have been passed back from the options.php page we will highlight the actual fields that caused them
	 *
	 * @param  array $settings The get_registered_fields() array
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function highlight_errors( $settings ) {

		/* We fire too late to tap into get_settings_error() so our data storage holds the details */
		$errors = $this->form_settings_errors;

		/* Loop through errors if any and highlight the appropriate settings */
		if ( is_array( $errors ) && sizeof( $errors ) > 0 ) {
			foreach ( $errors as $error ) {

				/* Skip over if not an error */
				if ( $error['type'] !== 'error' ) {
					continue;
				}

				/* Loop through our data until we find a match */
				$found = false;
				foreach ( $settings as $key => &$group ) {
					foreach ( $group as $id => &$item ) {
						if ( $item['id'] === $error['code'] ) {
							$item['class'] = ( isset( $item['class'] ) ) ? $item['class'] . ' gfield_error' : 'gfield_error';
							$found         = true;
							break;
						}
					}

					/* exit outer loop */
					if ( $found ) {
						break;
					}
				}
			}
		}

		return $settings;
	}

	/**
	 * Install the files stored in /src/templates/ to the user's template directory
	 *
	 * @return boolean
	 *
	 * @since 4.0
	 */
	public function install_templates() {

		$destination = $this->templates->get_template_path();
		$copy        = $this->misc->copyr( PDF_PLUGIN_DIR . 'src/templates/', $destination );
		if ( is_wp_error( $copy ) ) {
			$this->log->addError( 'Template Installation Error.' );
			$this->notices->add_error( sprintf( esc_html__( 'There was a problem copying all PDF templates to %s. Please try again.', 'gravity-forms-pdf-extended' ), '<code>' . $this->misc->relative_path( $destination ) . '</code>' ) );

			return false;
		}

		$this->notices->add_notice( sprintf( esc_html__( 'Gravity PDF Custom Templates successfully installed to %s.', 'gravity-forms-pdf-extended' ), '<code>' . $this->misc->relative_path( $destination ) . '</code>' ) );
		$this->options->update_option( 'custom_pdf_template_files_installed', true );

		return true;
	}


	/**
	 * Removes the current font's TTF files from our font directory
	 *
	 * @param  array $fonts The font config
	 *
	 * @return boolean        True on success, false on failure
	 *
	 * @since  4.0
	 */
	public function remove_font_file( $fonts ) {

		$fonts = array_filter( $fonts );
		$types = [ 'regular', 'bold', 'italics', 'bolditalics' ];

		foreach ( $types as $type ) {
			if ( isset( $fonts[ $type ] ) ) {
				$filename = basename( $fonts[ $type ] );

				if ( is_file( $this->data->template_font_location . $filename ) && ! unlink( $this->data->template_font_location . $filename ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Check that the font name passed conforms to our expected nameing convesion
	 *
	 * @param  string $name The font name to check
	 *
	 * @return boolean       True on valid, false on failure
	 *
	 * @since 4.0
	 */
	public function is_font_name_valid( $name ) {

		$regex = '^[A-Za-z0-9 ]+$';

		if ( preg_match( "/$regex/", $name ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Query our custom fonts options table and check if the font name already exists
	 *
	 * @param  string    $name The font name to check
	 * @param int|string $id   The configuration ID (if any)
	 *
	 * @return bool True if valid, false on failure
	 *
	 * @since 4.0
	 */
	public function is_font_name_unique( $name, $id = '' ) {

		/* Get the shortname of the current font */
		$name = $this->options->get_font_short_name( $name );

		/* Loop through default fonts and check for duplicate */
		$default_fonts = $this->options->get_installed_fonts();

		unset( $default_fonts[ esc_html__( 'User-Defined Fonts', 'gravity-forms-pdf-extended' ) ] );

		/* check for exact match */
		foreach ( $default_fonts as $group ) {
			if ( isset( $group[ $name ] ) ) {
				return false;
			}
		}

		$custom_fonts = $this->options->get_option( 'custom_fonts' );

		if ( is_array( $custom_fonts ) ) {
			foreach ( $custom_fonts as $font ) {

				/* Skip over itself */
				if ( ! empty( $id ) && $font['id'] == $id ) {
					continue;
				}

				if ( $name == $this->options->get_font_short_name( $font['font_name'] ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Handles the database updates required to save a new font
	 *
	 * @param  array $fonts
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function install_fonts( $fonts ) {

		$types  = [ 'regular', 'bold', 'italics', 'bolditalics' ];
		$errors = [];

		foreach ( $types as $type ) {

			/* Check if a key exists for this type and process */
			if ( isset( $fonts[ $type ] ) ) {
				$path = $this->misc->convert_url_to_path( $fonts[ $type ] );

				/* Couldn't find file so throw error */
				if ( is_wp_error( $path ) ) {
					$errors[] = sprintf( esc_html__( 'Could not locate font on web server: %s', 'gravity-forms-pdf-extended' ), $fonts[ $type ] );
				}

				/* Copy font to our fonts folder */
				$filename = basename( $path );
				if ( ! is_file( $this->data->template_font_location . $filename ) && ! copy( $path, $this->data->template_font_location . $filename ) ) {
					$errors[] = sprintf( esc_html__( 'There was a problem installing the font %s. Please try again.', 'gravity-forms-pdf-extended' ), $filename );
				}
			}
		}

		/* If errors were found then return */
		if ( sizeof( $errors ) > 0 ) {
			$this->log->addError( 'Install Error.', [
				'errors' => $errors,
			] );

			return [ 'errors' => $errors ];
		} else {
			/* Insert our font into the database */
			$custom_fonts = $this->options->get_option( 'custom_fonts' );

			/* Prepare our font data and give it a unique id */
			if ( empty( $fonts['id'] ) ) {
				$id          = uniqid();
				$fonts['id'] = $id;
			}

			$custom_fonts[ $fonts['id'] ] = $fonts;

			/* Update our font database */
			$this->options->update_option( 'custom_fonts', $custom_fonts );

			/* Cleanup our fontdata directory to prevent caching issues with mPDF */
			$this->misc->cleanup_dir( $this->data->template_fontdata_location );

		}

		/* Fonts sucessfully installed so return font data */

		return $fonts;
	}

	/**
	 * Turn capabilities into more friendly strings
	 *
	 * @param  string $cap The wordpress-style capability
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function style_capabilities( $cap ) {
		$cap = str_replace( 'gravityforms', 'gravity_forms', $cap );
		$cap = str_replace( '_', ' ', $cap );
		$cap = ucwords( $cap );

		return $cap;
	}

	/**
	 * AJAX Endpoint for saving the custom font
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function save_font() {

		/* User / CORS validation */
		$this->misc->handle_ajax_authentication( 'Save Font', 'gravityforms_edit_settings', 'gfpdf_font_nonce' );

		/* Handle the validation and saving of the font */
		$payload = isset( $_POST['payload'] ) ? $_POST['payload'] : '';
		$results = $this->process_font( $payload );

		/* If we reached this point the results were successful so return the new object */
		$this->log->addNotice( 'AJAX Endpoint Successful', [
			'results' => $results,
		] );

		echo json_encode( $results );
		wp_die();
	}

	/**
	 * AJAX Endpoint for deleting a custom font
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function delete_font() {

		/* User / CORS validation */
		$this->misc->handle_ajax_authentication( 'Delete Font', 'gravityforms_edit_settings', 'gfpdf_font_nonce' );

		/* Get the required details for deleting fonts */
		$id    = ( isset( $_POST['id'] ) ) ? $_POST['id'] : '';
		$fonts = $this->options->get_option( 'custom_fonts' );

		/* Check font actually exists and remove */
		if ( isset( $fonts[ $id ] ) ) {

			if ( $this->remove_font_file( $fonts[ $id ] ) ) {
				unset( $fonts[ $id ] );

				/* Cleanup our fontdata directory to prevent caching issues with mPDF */
				$this->misc->cleanup_dir( $this->data->template_fontdata_location );

				if ( $this->options->update_option( 'custom_fonts', $fonts ) ) {
					/* Success */
					$this->log->addNotice( 'AJAX Endpoint Successful' );
					echo json_encode( [ 'success' => true ] );
					wp_die();
				}
			}
		}

		$return = [
			'error' => esc_html__( 'Could not delete Gravity PDF font correctly. Please try again.', 'gravity-forms-pdf-extended' ),
		];

		$this->log->addError( 'AJAX Endpoint Error', $return );

		echo json_encode( $return );

		/* Bad Request */
		wp_die( '', 400 );
	}

	/**
	 * Validate user input and save as new font
	 *
	 * @param  array $font The four font fields to be processed
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function process_font( $font ) {

		/* remove any empty fields */
		$font = array_filter( $font );

		/* Check we have the required data */
		if ( ! isset( $font['font_name'] ) || ! isset( $font['regular'] ) ||
		     strlen( $font['font_name'] ) === 0 || strlen( $font['regular'] ) === 0
		) {

			$return = [
				'error' => esc_html__( 'Required fields have not been included.', 'gravity-forms-pdf-extended' ),
			];

			$this->log->addWarning( 'Validation Failed.', $return );

			echo json_encode( $return );

			/* Bad Request */
			wp_die( '', 400 );
		}

		/* Check we have a valid font name */
		$name = $font['font_name'];

		if ( ! $this->is_font_name_valid( $name ) ) {

			$return = [
				'error' => esc_html__( 'Font name is not valid. Only alphanumeric characters and spaces are accepted.', 'gravity-forms-pdf-extended' ),
			];

			$this->log->addWarning( 'Validation Failed.', $return );

			echo json_encode( $return );

			/* Bad Request */
			wp_die( '', 400 );
		}

		/* Check the font name is unique */
		$shortname = $this->options->get_font_short_name( $name );
		$id        = ( isset( $font['id'] ) ) ? $font['id'] : '';

		if ( ! $this->is_font_name_unique( $shortname, $id ) ) {

			$return = [
				'error' => esc_html__( 'A font with the same name already exists. Try a different name.', 'gravity-forms-pdf-extended' ),
			];

			$this->log->addWarning( 'Validation Failed.', $return );

			echo json_encode( $return );

			/* Bad Request */
			wp_die( '', 400 );
		}

		/* Move fonts to our Gravity PDF font folder */
		$installation = $this->install_fonts( $font );

		/* Check if any errors occured installing the fonts */
		if ( isset( $installation['errors'] ) ) {

			$return = [
				'error' => $installation,
			];

			$this->log->addWarning( 'Validation Failed.', $return );

			echo json_encode( $return );

			/* Bad Request */
			wp_die( '', 400 );
		}

		/* If we got here the installation was successful so return the data */

		return $installation;
	}

	/**
	 * Find the font unique ID from the font name
	 *
	 * @param string $font_name
	 *
	 * @return string The font ID, if any
	 *
	 * @since 4.1
	 */
	public function get_font_id_by_name( $font_name ) {
		$fonts = $this->options->get_option( 'custom_fonts', [] );

		foreach ( $fonts as $id => $font ) {
			if ( $font['font_name'] === $font_name ) {
				return $id;
			}
		}

		return null;
	}

	/**
	 * Create a file in our tmp directory and check if it is publically accessible (i.e no .htaccess protection)
	 *
	 * @param $_POST ['nonce']
	 *
	 * @return boolean
	 *
	 * @since 4.0
	 */
	public function check_tmp_pdf_security() {

		/* User / CORS validation */
		$this->misc->handle_ajax_authentication( 'Check Tmp Directory', 'gravityforms_view_settings', 'gfpdf-direct-pdf-protection' );

		/* Create our tmp file and do our actual check */
		echo json_encode( $this->test_public_tmp_directory_access() );
		wp_die();
	}

	/**
	 * Create a file in our tmp directory and verify if it's protected from the public
	 *
	 * @return boolean
	 *
	 * @since 4.0
	 */
	public function test_public_tmp_directory_access() {
		$tmp_dir       = $this->data->template_tmp_location;
		$tmp_test_file = 'public_tmp_directory_test.txt';
		$return        = true;

		/* create our file */
		file_put_contents( $tmp_dir . $tmp_test_file, 'failed-if-read' );

		/* verify it exists */
		if ( is_file( $tmp_dir . $tmp_test_file ) ) {

			/* Run our test */
			$site_url = $this->misc->convert_path_to_url( $tmp_dir );

			if ( $site_url !== false ) {

				$response = wp_remote_get( $site_url . $tmp_test_file );

				if ( ! is_wp_error( $response ) ) {

					/* Check if the web server responded with a OK status code and we can read the contents of our file, then fail our test */
					if ( isset( $response['response']['code'] ) && $response['response']['code'] === 200 &&
					     isset( $response['body'] ) && $response['body'] === 'failed-if-read' ) {
						$return = false;
					}
				}
			}
		}

		/* Cleanup our test file */
		@unlink( $tmp_dir . $tmp_test_file );

		return $return;
	}

	/**
	 * Gets all the template information for use with our JS template selector
	 *
	 * @param array $strings
	 *
	 * @return array
	 *
	 * @since 4.1
	 */
	public function get_template_data( $strings ) {
		$strings['templateList']          = $this->templates->get_all_template_info();
		$strings['activeDefaultTemplate'] = $this->options->get_option( 'default_template' );

		$form_id = rgget( 'id' );

		if ( $form_id ) {
			$pid = ( rgget( 'pid' ) ) ? rgget( 'pid' ) : false;
			if ( $pid == false ) {
				$pid = ( rgpost( 'gform_pdf_id' ) ) ? rgpost( 'gform_pdf_id' ) : false;
			}

			$pdf = $this->options->get_pdf( $form_id, $pid );

			if ( ! is_wp_error( $pdf ) ) {
				$strings['activeTemplate'] = $pdf['template'];
			}
		}

		return $strings;
	}
}
