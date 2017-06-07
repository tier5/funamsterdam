<?php

namespace GFPDF\View;

use GFPDF\Helper\Helper_Abstract_View;
use GFPDF_Major_Compatibility_Checks;
use GFPDF\Helper\Helper_Abstract_Form;
use GFPDF\Helper\Helper_Abstract_Options;
use GFPDF\Helper\Helper_Data;
use GFPDF\Helper\Helper_Misc;
use GFPDF\Helper\Helper_Templates;

use Psr\Log\LoggerInterface;

/**
 * Settings View
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
 * View_Settings
 *
 * A general class for About / Intro Screen
 *
 * @since 4.0
 */
class View_Settings extends Helper_Abstract_View {

	/**
	 * Set the view's name
	 *
	 * @var string
	 *
	 * @since 4.0
	 */
	protected $view_type = 'Settings';

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
	 * Setup our class by injecting all our dependancies
	 *
	 * @param array                                          $data_cache An array of data to pass to the view
	 * @param \GFPDF\Helper\Helper_Form|Helper_Abstract_Form $gform      Our abstracted Gravity Forms helper functions
	 * @param \Monolog\Logger|LoggerInterface                $log        Our logger class
	 * @param \GFPDF\Helper\Helper_Abstract_Options          $options    Our options class which allows us to access any settings
	 * @param \GFPDF\Helper\Helper_Data                      $data       Our plugin data store
	 * @param \GFPDF\Helper\Helper_Misc                      $misc       Our miscellaneous class
	 * @param \GFPDF\Helper\Helper_Templates                 $templates
	 *
	 * @since 4.0
	 */
	public function __construct( $data_cache = [], Helper_Abstract_Form $gform, LoggerInterface $log, Helper_Abstract_Options $options, Helper_Data $data, Helper_Misc $misc, Helper_Templates $templates ) {

		/* Call our parent constructor */
		parent::__construct( $data_cache );

		/* Assign our internal variables */
		$this->gform     = $gform;
		$this->log       = $log;
		$this->options   = $options;
		$this->data      = $data;
		$this->misc      = $misc;
		$this->templates = $templates;
	}

	/**
	 * Load the Welcome Tab tabs
	 *
	 * @since 4.0
	 *
	 * @return void
	 */
	public function tabs() {

		/* Set up any variables we need for the view and display */
		$vars = [
			'selected' => isset( $_GET['tab'] ) ? $_GET['tab'] : 'general',
			'tabs'     => $this->get_avaliable_tabs(),
			'data'     => $this->data,
		];

		/* load the tabs view */
		$this->load( 'tabs', $vars );
	}

	/**
	 * Set up our settings navigation
	 *
	 * @return array The navigation array
	 *
	 * @since 4.0
	 */
	public function get_avaliable_tabs() {
		/**
		 * Store the setting navigation
		 * The array key is the settings order
		 *
		 * @var array
		 */
		$navigation = [
			5 => [
				'name' => esc_html__( 'General', 'gravity-forms-pdf-extended' ),
				'id'   => 'general',
			],

			100 => [
				'name' => esc_html__( 'Tools', 'gravity-forms-pdf-extended' ),
				'id'   => 'tools',
			],

			120 => [
				'name' => esc_html__( 'Help', 'gravity-forms-pdf-extended' ),
				'id'   => 'help',
			],
		];

		/**
		 * Allow additional navigation to be added to the settings page
		 *
		 * @since 3.8
		 */
		return apply_filters( 'gfpdf_settings_navigation', $navigation );
	}

	/**
	 * Pull the system status details and show
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function system_status() {
		global $wp_version;

		$status = new GFPDF_Major_Compatibility_Checks();

		$vars = [
			'memory' => $status->get_ram( $this->data->memory_limit ),
			'wp'     => $wp_version,
			'php'    => phpversion(),
			'gf'     => $this->gform->get_version(),
		];

		$this->log->addNotice( 'System Status', [
			'status' => $vars,
		] );

		/* load the system status view */
		$this->load( 'system_status', $vars );
	}

	/**
	 * Pull the general details and display
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function general() {

		$vars = [
			'edit_cap' => $this->gform->has_capability( 'gravityforms_edit_settings' ),
		];

		/* load the system status view */
		$this->load( 'general', $vars );
	}

	/**
	 * Pull the tools details and show
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function tools() {

		/* prevent unauthorized access */
		if ( ! $this->gform->has_capability( 'gravityforms_edit_settings' ) ) {
			$this->log->addWarning( 'Lack of User Capabilities.' );

			wp_die( esc_html__( 'You do not have permission to access this page', 'gravity-forms-pdf-extended' ) );
		}

		$template_directory = $this->templates->get_template_path();

		$vars = [
			'template_directory'            => $this->misc->relative_path( $template_directory, '/' ),
			'template_files'                => $this->templates->get_core_pdf_templates(),
			'custom_template_setup_warning' => $this->options->get_option( 'custom_pdf_template_files_installed' ),
		];

		/* load the system status view */
		$this->load( 'tools', $vars );
	}

	/**
	 * Add Gravity Forms Tooltips
	 *
	 * @param array $tooltips The existing tooltips
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	public function add_tooltips( $tooltips ) {

		$tooltips['pdf_status_wp_memory'] = '<h6>' . esc_html__( 'WP Memory Available', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Producing PDF documents is hard work and Gravity PDF requires more resources than most plugins. We strongly recommend you have at least 128MB, but you may need more.', 'gravity-forms-pdf-extended' ) );
		$tooltips['pdf_protection']       = '<h6>' . esc_html__( 'Direct PDF Protection', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Apache and Litespeed servers automatically disable public access to the Gravity PDF temporary directory using a %s.htaccess%s file, but other web servers like Nginx do not support this feature. We will check if your PDFs are automatically protected, and let you know what you can do to protect your data if they are not.', 'gravity-forms-pdf-extended' ), '<code>', '</code>', '<code>', '</code>' );

		return apply_filters( 'gravitypdf_registered_tooltips', $tooltips );
	}
}
