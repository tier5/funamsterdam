<?php

namespace GFPDF\View;

use GFPDF\Helper\Helper_Abstract_Model;
use GFPDF\Helper\Helper_Abstract_View;
use GFPDF\Helper\Helper_Field_Container;
use GFPDF\Helper\Helper_Field_Container_Void;
use GFPDF\Helper\Helper_Abstract_Form;
use GFPDF\Helper\Helper_PDF;
use GFPDF\Helper\Helper_Abstract_Options;
use GFPDF\Helper\Helper_Data;
use GFPDF\Helper\Helper_Misc;
use GFPDF\Helper\Helper_Templates;

use Psr\Log\LoggerInterface;

use GFPDF\Helper\Fields\Field_Products;

use GFFormsModel;
use GFCommon;
use GF_Field;

use mPDF;
use Exception;

/**
 * PDF View
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
 * View_PDF
 *
 * A general class for PDF display
 *
 * @since 4.0
 */
class View_PDF extends Helper_Abstract_View {

	/**
	 * Set the view's name
	 *
	 * @var string
	 *
	 * @since 4.0
	 */
	protected $view_type = 'PDF';

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
	 * @var \GFPDF\Helper\Helper_Abstract_Options
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
	 * @param \GFPDF\Helper\Helper_Misc                      $misc       Our miscellanious methods
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
	 * Our PDF Generator
	 *
	 * @param  array $entry    The Gravity Forms Entry to process
	 * @param  array $settings The Gravity Form PDF Settings
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function generate_pdf( $entry, $settings ) {

		$controller = $this->getController();
		$model      = $controller->model;
		$form       = $this->gform->get_form( $entry['form_id'] );

		$this->fix_wp_external_links_plugin_conflict();

		/**
		 * Load our arguments that should be accessed by our PDF template
		 *
		 * @var array
		 */
		$args = $this->templates->get_template_arguments(
			$form,
			$this->misc->get_fields_sorted_by_id( $form['id'] ),
			$entry,
			$model->get_form_data( $entry ),
			$settings,
			$this->templates->get_config_class( $settings['template'] ),
			$this->misc->get_legacy_ids( $entry['id'], $settings )
		);

		/**
		 * Show $form_data array if requested
		 */
		if ( isset( $_GET['data'] ) && $this->gform->has_capability( 'gravityforms_view_settings' ) && isset( $args['form_data'] ) ) {
			echo '<pre>';
			print_r( $args['form_data'] );
			echo '</pre>';
			exit;
		}

		/* Enable Multicurrency support */
		$this->misc->maybe_add_multicurrency_support();

		/**
		 * Set out our PDF abstraction class
		 */
		$pdf = new Helper_PDF( $entry, $settings, $this->gform, $this->data, $this->misc, $this->templates );
		$pdf->set_filename( $model->get_pdf_name( $settings, $entry ) );

		try {

			/* Initialise our PDF helper class */
			$pdf->init();
			$pdf->set_template();

			/* Increment our rudimentary PDF counter */
			$this->options->increment_pdf_count();

			/* Set display type and allow user to override the behaviour */
			$settings['pdf_action'] = apply_filters( 'gfpdfe_pdf_output_type', $settings['pdf_action'] ); /* Backwards compat */
			if ( $settings['pdf_action'] == 'download' ) {
				$pdf->set_output_type( 'download' );
			}

			/* Add Backwards compatibility support for our v3 Tier 2 Add-on */
			if ( isset( $settings['advanced_template'] ) && strtolower( $settings['advanced_template'] ) == 'yes' ) {

				/* Check if we should process this document using our legacy system */
				if ( $model->handle_legacy_tier_2_processing( $pdf, $entry, $settings, $args ) ) {
					return true;
				}
			}

			/* Determine if we should show the print dialog box */
			if ( isset( $_GET['print'] ) ) {
				$pdf->set_print_dialog( true );
			}

			/* Render the PDF template HTML */
			$pdf->render_html( $args );

			/* Generate PDF */
			$pdf->generate();

		} catch ( Exception $e ) {

			$this->log->addError( 'PDF Generation Error', [
				'entry'     => $entry,
				'settings'  => $settings,
				'exception' => $e->getMessage(),
			] );

			if ( $this->gform->has_capability( 'gravityforms_view_entries' ) ) {
				wp_die( $e->getMessage() );
			}

			wp_die( esc_html__( 'There was a problem generating your PDF', 'gravity-forms-pdf-extended' ) );
		}
	}

	/**
	 * The WP External Links plugin conflicts with Gravity PDF when trying to display the PDF
	 * This method disables the \WPEL_Front::scan() method which was causes the problem
	 *
	 * See https://github.com/GravityPDF/gravity-pdf/issues/386
	 *
	 * @since 4.1
	 */
	private function fix_wp_external_links_plugin_conflict() {
		if ( function_exists( 'wpel_init' ) ) {
			add_filter( 'wpel_apply_settings', function () {
				return false;
			} );
		}
	}


	/**
	 * Ensure a PHP extension is added to the end of the template name
	 *
	 * @param  string $name The PHP template
	 *
	 * @return string
	 *
	 * @since  4.0
	 */
	public function get_template_filename( $name ) {
		if ( substr( $name, -4 ) !== '.php' ) {
			$name = $name . '.php';
		}

		return $name;
	}

	/**
	 * Start the PDF HTML Generation Process
	 *
	 * @param  array                              $entry  The Gravity Forms Entry Array
	 * @param \GFPDF\Helper\Helper_Abstract_Model $model
	 * @param  array                              $config Any configuration data passed in
	 *
	 * @return string The generated HTML
	 *
	 * @since 4.0
	 */
	public function process_html_structure( $entry, Helper_Abstract_Model $model, $config = [] ) {
		/* Determine whether we should output or return the results */
		$config['meta'] = ( isset( $config['meta'] ) ) ? $config['meta'] : [];
		$echo           = ( isset( $config['meta']['echo'] ) ) ? $config['meta']['echo'] : true; /* whether to output or return the generated markup. Default is echo */

		if ( ! $echo ) {
			ob_start();
		}

		/* Generate the markup */
		?>

		<div id="container">
            <?php
            /*
             * See https://gravitypdf.com/documentation/v4/gfpdf_pre_html_fields/ for more details about this action
             * @since 4.1
             */
            do_action( 'gfpdf_pre_html_fields', $entry, $config );
            ?>

			<?php $this->generate_html_structure( $entry, $model, $config ); ?>

            <?php
            /*
             * See https://gravitypdf.com/documentation/v4/gfpdf_post_html_fields/ for more details about this action
             * @since 4.1
             */
            do_action( 'gfpdf_post_html_fields', $entry, $config );
            ?>
		</div>

		<?php

		if ( ! $echo ) {
			return ob_get_clean();
		}

		return null;
	}

	/**
	 * Build our HTML structure
	 *
	 * @param  array $entry  The Gravity Forms Entry Array
	 * @param  array $config Any configuration data passed in
	 *
	 * @return string         The generated HTML
	 *
	 * @since 4.0
	 */
	public function generate_html_structure( $entry, Helper_Abstract_Model $model, $config = [] ) {

		/* Set up required variables */
		$form        = $this->gform->get_form( $entry['form_id'] );
		$products    = new Field_Products( new GF_Field(), $entry, $this->gform, $this->misc );
		$page_number = 0;
		$container   = ( isset( $config['meta']['enable_css_ready_classes'] ) && false === $config['meta']['enable_css_ready_classes'] ) ? new Helper_Field_Container_Void() : new Helper_Field_Container();

		/* Allow the config to be changed through a filter */
		$config['meta'] = ( isset( $config['meta'] ) ) ? $config['meta'] : [];
		$config         = apply_filters( 'gfpdf_pdf_configuration', $config, $entry, $form );

		/* Get the user configuration values */
		$skip_marked_fields             = ( isset( $config['meta']['exclude'] ) ) ? $config['meta']['exclude'] : true; /* whether we should exclude fields with a CSS value of 'exclude'. Default to true */
		$skip_conditional_fields        = ( isset( $config['meta']['conditional'] ) ) ? $config['meta']['conditional'] : true; /* whether we should skip fields hidden with conditional logic. Default to true. */
		$show_title                     = ( isset( $config['meta']['show_title'] ) ) ? $config['meta']['show_title'] : false; /* whether we should show the form title. Default to true */
		$show_page_names                = ( isset( $config['meta']['page_names'] ) ) ? $config['meta']['page_names'] : false; /* whether we should show the form's page names. Default to false */
		$show_html_fields               = ( isset( $config['meta']['html_field'] ) ) ? $config['meta']['html_field'] : false; /* whether we should show the form's html fields. Default to false */
		$show_individual_product_fields = ( isset( $config['meta']['individual_products'] ) ) ? $config['meta']['individual_products'] : false; /* Whether to show individual fields in the entry. Default to false - they are grouped together at the end of the form */

		/* Skip over any of the following blacklisted fields */
		$blacklisted = apply_filters( 'gfpdf_blacklisted_fields', [ 'captcha', 'password', 'page' ] );

		/* Display the form title, if needed */
		$this->show_form_title( $show_title, $form );

		/* Loop through the fields and output or skip if needed */
		foreach ( $form['fields'] as $key => $field ) {

			/* Load our page name, if needed */
			if ( $show_page_names === true && $field->pageNumber !== $page_number ) {
				$this->display_page_name( $page_number, $form, $container );
				$page_number++;
			}

			/*
			 * @TODO Create middleware filter to check if the field should be skipped.
			 * This will reduce code duplication and increase plugin flexibility
			 */

			/* Skip any fields with the css class 'exclude', if needed */
			if ( $skip_marked_fields !== false && strpos( $field->cssClass, 'exclude' ) !== false ) {
				/* To prevent display issues we will output the column markup needed */
				$container->maybe_display_faux_column( $field );

				continue;
			}

			/* Skip over any hidden fields (usually by conditional logic), if needed */
			if ( $skip_conditional_fields === true && GFFormsModel::is_field_hidden( $form, $field, [], $entry ) ) {
				/* To prevent display issues we will output the column markup needed */
				$container->maybe_display_faux_column( $field );

				continue;
			}

			/* Skip over any product fields, if needed */
			if ( $show_individual_product_fields === false && GFCommon::is_product_field( $field->type ) ) {
				/* To prevent display issues we will output the column markup needed */
				$container->maybe_display_faux_column( $field );
				continue;
			}

			/* Skip HTML fields, if needed */
			if ( $show_html_fields === false && $field->type == 'html' ) {
				/* To prevent display issues we will output the column markup needed */
				$container->maybe_display_faux_column( $field );

				continue;
			}

			/* Skip over any fields we don't want to include */
			if ( in_array( $field->type, $blacklisted ) ) {
				/* To prevent display issues we will output the column markup needed */
				$container->maybe_display_faux_column( $field );

				continue;
			}

			/* Let's output our field */
			$this->process_field( $field, $entry, $form, $config, $products, $container, $model );
		}

		/* correctly close / cleanup the HTML container if needed */
		$container->close();

		/* Output product table, if needed */
		if ( $show_individual_product_fields === false && ! $products->is_empty() ) {
			echo $products->html();
		}

	}

	/**
	 * Handle our field loader and display the generated HTML
	 *
	 * @param  GF_Field                             $field    The field to process
	 * @param  array                                $entry    The Gravity Form Entry
	 * @param  array                                $form     The Gravity Form Field
	 * @param  array                                $config   The user-passed configuration data
	 * @param  \GFPDF\Helper\Fields\Field_Products  $products A Field_Products Object
	 * @param  \GFPDF\Helper\Helper_Field_Container $container
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function process_field( GF_Field $field, $entry, $form, $config, Field_Products $products, Helper_Field_Container $container, Helper_Abstract_Model $model ) {

		/*
		* Set up our configuration variables
		*/
		$config['meta']           = ( isset( $config['meta'] ) ) ? $config['meta'] : []; /* ensure we have a meta key */
		$show_empty_fields        = ( isset( $config['meta']['empty'] ) ) ? $config['meta']['empty'] : false; /* whether to show empty fields or not. Default is false */
		$load_legacy_css          = ( isset( $config['meta']['legacy_css'] ) ) ? $config['meta']['legacy_css'] : false; /* whether we should add our legacy field class names (v3.x.x) to our fields. Default to false */
		$show_section_description = ( isset( $config['meta']['section_content'] ) ) ? $config['meta']['section_content'] : false; /* whether we should include a section breaks content. Default to false */

		$class = $model->get_field_class( $field, $form, $entry, $products );

		/* Try and display our HTML */
		try {

			/* Only load our HTML if the field is NOT empty, or the $empty config option is true */
			if ( ! $class->is_empty() || $show_empty_fields === true ) {
				/* Load our legacy CSS class names */
				if ( $load_legacy_css === true ) {
					$this->load_legacy_css( $field );
				}

				/**
				 * Add CSS Ready Class Float Support to mPDF
				 * Open a HTML container if needed
				 */
				$container->generate( $field );

				echo ( $field->type !== 'section' ) ? $class->html() : $class->html( $show_section_description );
			} else {
				/* To prevent display issues we will output the column markup needed */
				$container->maybe_display_faux_column( $field );
			}
		} catch ( Exception $e ) {
			$this->log->addError( 'PDF Generation Error', [
				'field'     => $field,
				'entry'     => $entry,
				'config'    => $config,
				'form_id'   => $form['id'],
				'exception' => $e->getMessage(),
			] );
		}
	}

	/**
	 * If enabled, we'll show the Gravity Form Title in the document
	 *
	 * @param  boolean $show_title Whether or not to show the title
	 * @param  array   $form       The Gravity Form array
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function show_form_title( $show_title, $form ) {
		/* Show the form title, if needed */
		if ( $show_title !== false ) {

			/* Load our HTML */
			$html = $this->load( 'form_title', [ 'form' => $form ], false );

			/* Run it through a filter and output */
			echo apply_filters( 'gfpdf_pdf_form_title_html', $html, $form );
		}
	}

	/**
	 * Output the current page name HTML
	 *
	 * @param  integer                $page The current page number
	 * @param  array                  $form The form array
	 * @param  Helper_Field_Container $container
	 *
	 * @return string The page HTML output
	 *
	 *
	 * @since    4.0
	 */
	public function display_page_name( $page, $form, Helper_Field_Container $container ) {

		/* Only display the current page name if it exists */
		if ( isset( $form['pagination']['pages'][ $page ] ) && strlen( trim( $form['pagination']['pages'][ $page ] ) ) > 0 ) {

			/* correctly close / cleanup the HTML container if needed */
			$container->close();

			/* Load our HTML */
			$html = $this->load( 'page_title', [ 'form' => $form, 'page' => $page ], false );

			/* Run it through a filter and output */
			echo apply_filters( 'gfpdf_field_page_name_html', $html, $page, $form );
		}
	}

	/**
	 * Automatically render our core PDF fields and add styles in templates to simplify there usage for users
	 *
	 * @param  string $html The current HTML template being processed
	 * @param  array  $form
	 * @param  array  $entry
	 * @param  array  $settings
	 *
	 * @return string
	 * @since 4.0
	 */
	public function autoprocess_core_template_options( $html, $form, $entry, $settings ) {
		/* Prevent core styles loading if a v3 template or using our legacy Tier 2 add-on */
		$template_info = $this->templates->get_template_info_by_id( $settings['template'] );
		if (
			( esc_html__( 'Legacy', 'gravity-forms-pdf-extended' ) !== $template_info['group'] ) &&
			( empty( $settings['advanced_template'] ) || 'Yes' !== $settings['advanced_template'] )
		) {
			$html = $this->get_core_template_styles( $settings, $entry ) . $html;
		}

		return $html;
	}

	/**
	 * Loads the core template styles and runs it through a filter
	 *
	 * @param  array $entry    The Gravity Form entry being processed
	 * @param  array $settings The current PDF settings
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function get_core_template_styles( $settings, $entry ) {
		$form = $this->gform->get_form( $entry['form_id'] );

		$html = $this->load_core_template_styles( $settings );

		$html = apply_filters( 'gfpdf_pdf_core_template_html_output', $html, $form, $entry, $settings );
		$html = apply_filters( 'gfpdf_pdf_core_template_html_output_' . $form['id'], $html, $form, $entry, $settings );

		return $html;
	}

	/**
	 * Load our core PDF template settings
	 *
	 * @param $settings
	 *
	 * @return string|\WP_Error
	 *
	 * @since 4.0
	 */
	public function load_core_template_styles( $settings ) {
		$controller = $this->getController();
		$model      = $controller->model;

		/* Run our settings through the preprocessor which requires an array with a 'settings' key */
		$args     = $model->preprocess_template_arguments( [ 'settings' => $settings ] );
		$settings = $args['settings'];

		return $this->load( 'core_template_styles', [ 'settings' => $settings ], false );
	}
}
