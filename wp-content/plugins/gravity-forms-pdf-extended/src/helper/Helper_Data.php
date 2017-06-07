<?php

namespace GFPDF\Helper;

/**
 * Data overloaded Helper Class
 * Cache shared data across the plugin
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
 * @since 4.0
 *
 * @property string  $short_title                     The plugin's short title used with Gravity Forms
 * @property string  $title                           The plugin's main title used with Gravity Forms
 * @property string  $slug                            The plugin's slug used with Gravity Forms
 * @property boolean $is_installed                    If the plugin has been successfully installed
 * @property string  $permalink                       The plugin's PDF permalink regex
 * @property string  $working_folder                  The plugin's working directory name
 * @property string  $settings_url                    The plugin's URL to the settings page
 * @property string  $memory_limit                    The current PHP memory limit
 * @property string  $upload_dir                      The current path to the WP upload directory
 * @property string  $upload_dir_url                  The current URL to the WP upload directory
 * @property array   $form_settings                   A cache of the current form's PDF settings
 * @property string  $template_location               The current path to the PDF working directory
 * @property string  $template_location_url           The current URL to the PDF working directory
 * @property string  $template_font_location          The current path to the PDF font directory
 * @property string  $template_fontdata_location      The current path to the PDF tmp font directory
 * @property string  $template_tmp_location           The current path to the PDF tmp location
 * @property string  $multisite_template_location     The current path to the multisite PDF working directory
 * @property string  $multisite_template_location_url The current URL to the multisite PDF working directory
 *
 */
class Helper_Data {

	/**
	 * Location for the overloaded data
	 *
	 * @var array
	 *
	 * @since 4.0
	 */
	private $data = [];

	/**
	 * PHP Magic Method __set()
	 * Run when writing data to inaccessible properties
	 *
	 * @param string $name  Name of the peroperty being interacted with
	 * @param mixed  $value Data to assign to the $name property
	 *
	 * @since 4.0
	 */
	public function __set( $name, $value ) {
		$this->data[ $name ] = $value;
	}

	/**
	 * PHP Magic Method __get()
	 * Run when reading data from inaccessible properties
	 *
	 * @param string $name Name of the property being interacted with
	 *
	 * @return mixed        The data assigned to the $name property is returned
	 *
	 * @since 4.0
	 */
	public function &__get( $name ) {
		/* Check if we actually have a key matching what was requested */
		if ( array_key_exists( $name, $this->data ) ) {
			/* key exists, so return */
			return $this->data[ $name ];
		}

		/* Not found so generate error */
		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE );

		/* because we are returning by reference we need return something that can be referenced */
		$value = null;

		return $value;
	}

	/**
	 * PHP Magic Method __isset()
	 * Triggered when isset() or empty() is called on inaccessible properties
	 *
	 * @param  string $name Name of the property being interacted with
	 *
	 * @return boolean       Whether property exists
	 *
	 * @since 4.0
	 */
	public function __isset( $name ) {
		return isset( $this->data[ $name ] );
	}

	/**
	 * PHP Magic Method __isset()
	 * Triggered when unset() is called on inaccessible properties
	 *
	 * @param  string $name Name of the property being interacted with
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function __unset( $name ) {
		unset( $this->data[ $name ] );
	}

	/**
	 * Set up addon array for use tracking active addons
	 *
	 * @since  3.8
	 */
	public function set_addon_details() {
		$this->addon = [];
	}

	/**
	 * Set up any default data that should be stored
	 *
	 * @return void
	 *
	 * @since 3.8
	 */
	public function init() {
		$this->set_plugin_titles();
	}

	/**
	 * Set up our short title, long title and slug used in settings pages
	 *
	 * @return  void
	 *
	 * @since  4.0
	 */
	public function set_plugin_titles() {
		$this->short_title = esc_html__( 'PDF', 'gravity-forms-pdf-extended' );
		$this->title       = esc_html__( 'Gravity PDF', 'gravity-forms-pdf-extended' );
		$this->slug        = 'pdf';
	}

	/**
	 * A key-value array to be used in a localized script call for our Gravity PDF javascript files
	 *
	 * @param \GFPDF\Helper\Helper_Abstract_Options $options
	 * @param \GFPDF\Helper\Helper_Abstract_Form    $gform
	 *
	 * @return array
	 *
	 * @since  4.0
	 */
	public function get_localised_script_data( Helper_Abstract_Options $options, Helper_Abstract_Form $gform ) {

		$custom_fonts = array_values( $options->get_custom_fonts() );

		/* See https://gravitypdf.com/documentation/v4/gfpdf_localised_script_array/ for more details about this filter */

		return apply_filters( 'gfpdf_localised_script_array', [
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'ajaxNonce'      => wp_create_nonce( 'gfpdf_ajax_nonce' ),
			'currentVersion' => PDF_EXTENDED_VERSION,
			'pdfWorkingDir'  => PDF_TEMPLATE_LOCATION,
			'customFontData' => json_encode( $custom_fonts ),

			'spinnerUrl'      => admin_url( 'images/spinner-2x.gif' ),
			'spinnerAlt'      => esc_html__( 'Loading...', 'gravity-forms-pdf-extended' ),
			'continue'        => esc_html__( 'Continue', 'gravity-forms-pdf-extended' ),
			'uninstall'       => esc_html__( 'Uninstall', 'gravity-forms-pdf-extended' ),
			'cancel'          => esc_html__( 'Cancel', 'gravity-forms-pdf-extended' ),
			'delete'          => esc_html__( 'Delete', 'gravity-forms-pdf-extended' ),
			'active'          => esc_html__( 'Active', 'gravity-forms-pdf-extended' ),
			'inactive'        => esc_html__( 'Inactive', 'gravity-forms-pdf-extended' ),
			'conditionalText' => esc_html__( 'this PDF if', 'gravity-forms-pdf-extended' ),
			'enable'          => esc_html__( 'Enable', 'gravity-forms-pdf-extended' ),
			'disable'         => esc_html__( 'Disable', 'gravity-forms-pdf-extended' ),
			'updateSuccess'   => esc_html__( 'Successfully Updated', 'gravity-forms-pdf-extended' ),
			'deleteSuccess'   => esc_html__( 'Successfully Deleted', 'gravity-forms-pdf-extended' ),
			'no'              => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			'yes'             => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
			'standard'        => esc_html__( 'Standard', 'gravity-forms-pdf-extended' ),
			'advanced'        => esc_html__( 'Advanced', 'gravity-forms-pdf-extended' ),
			'select'          => esc_html__( 'Select', 'gravity-forms-pdf-extended' ),
			'version'         => esc_html__( 'Version', 'gravity-forms-pdf-extended' ),
			'group'           => esc_html__( 'Group', 'gravity-forms-pdf-extended' ),
			'tags'            => esc_html__( 'Tags', 'gravity-forms-pdf-extended' ),

			'migratingSite'         => esc_html__( 'Migrating site #%s', 'gravity-forms-pdf-extended' ),
			'siteMigrationComplete' => esc_html__( 'Site #%s migration complete.', 'gravity-forms-pdf-extended' ),
			'migrationError'        => esc_html__( 'Migration Error', 'gravity-forms-pdf-extended' ),
			'siteMigrationErrors'   => esc_html__( 'Site #%s migration errors.', 'gravity-forms-pdf-extended' ),

			'addNewTemplate'      => esc_html__( 'Add New Template', 'gravity-forms-pdf-extended' ),
			'showAdvancedOptions' => esc_html__( 'Show Advanced Options...', 'gravity-forms-pdf-extended' ),
			'hideAdvancedOptions' => esc_html__( 'Hide Advanced Options...', 'gravity-forms-pdf-extended' ),
			'thisFormHasNoPdfs'   => esc_html__( "This form doesn't have any PDFs.", 'gravity-forms-pdf-extended' ),
			'letsGoCreateOne'     => esc_html__( "Let's go create one", 'gravity-forms-pdf-extended' ),
			'installedPdfs'       => esc_html__( 'Installed PDFs', 'gravity-forms-pdf-extended' ),

			'searchPlaceholder' => esc_html__( 'Search the Gravity PDF Knowledgebase...', 'gravity-forms-pdf-extended' ),

			'requiresGravityPdfVersion'                  => esc_html__( 'Requires Gravity PDF v%s', 'gravity-forms-pdf-extended' ),
			'templateNotCompatibleWithGravityPdfVersion' => esc_html__( 'This PDF template is not compatible with your version of Gravity PDF. This template required Gravity PDF v%s.', 'gravity-forms-pdf-extended' ),
			'templateDetails'                            => esc_html__( 'Template Details', 'gravity-forms-pdf-extended' ),
			'currentTemplate'                            => esc_html__( 'Current Template', 'gravity-forms-pdf-extended' ),
			'showPreviousTemplate'                       => esc_html__( 'Show previous template', 'gravity-forms-pdf-extended' ),
			'showNextTemplate'                           => esc_html__( 'Show next template', 'gravity-forms-pdf-extended' ),
			'uploadInvalidNotZipFile'                    => esc_html__( 'Upload is not a valid template. Upload a .zip file.', 'gravity-forms-pdf-extended' ),
			'uploadInvalidExceedsFileSizeLimit'          => esc_html__( 'Upload exceeds the 10MB limit.', 'gravity-forms-pdf-extended' ),
			'templateSuccessfullyInstalled'              => esc_html__( 'Template successfully installed', 'gravity-forms-pdf-extended' ),
			'templateSuccessfullyUpdated'                => esc_html__( 'Template successfully updated', 'gravity-forms-pdf-extended' ),
			'templateSuccessfullyInstalledUpdated'       => esc_html__( 'PDF Template(s) Successfully Installed / Updated', 'gravity-forms-pdf-extended' ),
			'problemWithTheUpload'                       => esc_html__( 'There was a problem with the upload. Reload the page and try again.', 'gravity-forms-pdf-extended' ),
			'doYouWantToDeleteTemplate'                  => sprintf( esc_html__( "Do you really want to delete this PDF template?%sClick 'Cancel' to go back, 'OK' to confirm the delete.", 'gravity-forms-pdf-extended' ), "\n\n" ),
			'couldNotDeleteTemplate'                     => esc_html__( 'Could not delete template.', 'gravity-forms-pdf-extended' ),
			'templateInstallInstructions'                => esc_html__ ( 'If you have a PDF template in .zip format you may install it here. You can also update an existing PDF template (this will override any changes you have made).', 'gravity-forms-pdf-extended' ),
		] );
	}
}
