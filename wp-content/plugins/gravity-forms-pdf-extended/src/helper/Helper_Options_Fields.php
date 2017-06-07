<?php

namespace GFPDF\Helper;

/**
 * Give a standardised format to queue admin notices
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
 * Class to set up the settings api fields
 *
 * @since 4.0
 */
class Helper_Options_Fields extends Helper_Abstract_Options implements Helper_Interface_Filters {

	/**
	 * Add our filters
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function add_filters() {

		/* Conditionally enable specific fields */
		add_filter( 'gfpdf_form_settings_advanced', [ $this, 'get_advanced_template_field' ] );

		parent::add_filters();
	}

	/**
	 * Retrieve the array of registered fields
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_registered_fields() {

		/**
		 * Gravity PDF settings
		 * Filters are provided for each settings section to allow extensions and other plugins to add their own option
		 * which will be processed by our settings API
		 */
		$gfpdf_settings = [

			/*
			 * General Settings
			 *
			 * See https://gravitypdf.com/documentation/v4/gfpdf_settings_general/ for more details about this filter
			 */
			'general'                         => apply_filters( 'gfpdf_settings_general',
				[
					'default_pdf_size' => [
						'id'         => 'default_pdf_size',
						'name'       => esc_html__( 'Default Paper Size', 'gravity-forms-pdf-extended' ),
						'desc'       => esc_html__( 'Set the default paper size used when generating PDFs.', 'gravity-forms-pdf-extended' ),
						'type'       => 'select',
						'options'    => $this->get_paper_size(),
						'inputClass' => 'large',
						'chosen'     => true,
						'class'      => 'gfpdf_paper_size',
					],

					'default_custom_pdf_size' => [
						'id'       => 'default_custom_pdf_size',
						'name'     => esc_html__( 'Custom Paper Size', 'gravity-forms-pdf-extended' ),
						'desc'     => esc_html__( 'Control the exact paper size. Can be set in millimeters or inches.', 'gravity-forms-pdf-extended' ),
						'type'     => 'paper_size',
						'size'     => 'small',
						'chosen'   => true,
						'required' => true,
						'class'    => 'gfpdf-hidden gfpdf_paper_size_other',
					],

					'default_template' => [
						'id'         => 'default_template',
						'name'       => esc_html__( 'Default Template', 'gravity-forms-pdf-extended' ),
						'desc'       => sprintf( esc_html__( 'Choose an existing template or purchased more %sfrom our theme shop%s. You can also %sbuild your own%s or %shire us%s to create a custom solution.', 'gravity-forms-pdf-extended' ), '<a href="https://gravitypdf.com/shop/">', '</a>', '<a href="https://gravitypdf.com/documentation/v4/developer-start-customising/">', '</a>', '<a href="https://gravitypdf.com/integration-services/">', '</a>' ),
						'type'       => 'select',
						'options'    => $this->templates->get_all_templates_by_group(),
						'std'        => 'zadani',
						'inputClass' => 'large',
						'chosen'     => true,
						'tooltip'    => '<h6>' . esc_html__( 'Templates', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Gravity PDF comes with %sfour completely-free and highly customizable designs%s. You can also purchase additional templates from our theme shop, hire us to integrate existing PDFs or, with a bit of technical know-how, build your own.', 'gravity-forms-pdf-extended' ), '<strong>', '</strong>' ),
					],

					'default_font' => [
						'id'         => 'default_font',
						'name'       => esc_html__( 'Default Font', 'gravity-forms-pdf-extended' ),
						'desc'       => sprintf( esc_html__( 'Set the default font type used in PDFs. Choose an existing font or %sinstall your own%s.', 'gravity-forms-pdf-extended' ), '<a href="' . $this->data->settings_url . '&tab=tools#manage_fonts">', '</a>' ),
						'type'       => 'select',
						'options'    => $this->get_installed_fonts(),
						'inputClass' => 'large',
						'chosen'     => true,
						'tooltip'    => '<h6>' . esc_html__( 'Fonts', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Gravity PDF comes bundled with fonts for most languages world-wide. Want to use a specific font type? Use the font installer (found in the Tools tab).', 'gravity-forms-pdf-extended' ),
					],

					'default_font_size' => [
						'id'    => 'default_font_size',
						'name'  => esc_html__( 'Default Font Size', 'gravity-forms-pdf-extended' ),
						'desc'  => esc_html__( 'Set the default font size used in PDFs.', 'gravity-forms-pdf-extended' ),
						'desc2' => 'pt',
						'type'  => 'number',
						'size'  => 'small',
						'std'   => 10,
					],

					'default_font_colour' => [
						'id'   => 'default_font_colour',
						'name' => esc_html__( 'Default Font Color', 'gravity-forms-pdf-extended' ),
						'type' => 'color',
						'std'  => '#000000',
						'desc' => esc_html__( 'Set the default font color used in PDFs.', 'gravity-forms-pdf-extended' ),
					],

					'default_rtl' => [
						'id'      => 'default_rtl',
						'name'    => esc_html__( 'Reverse Text (RTL)', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Script like Arabic and Hebrew are written right to left.', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						],
						'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						'tooltip' => '<h6>' . esc_html__( 'Reverse Text (RTL)', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( "Enable RTL if you are writing in Arabic, Hebrew, Syriac, N'ko, Thaana, Tifinar, Urdu or other RTL languages.", 'gravity-forms-pdf-extended' ),
					],

					'default_action' => [
						'id'      => 'default_action',
						'name'    => esc_html__( 'Entry View', 'gravity-forms-pdf-extended' ),
						'desc'    => sprintf( esc_html__( 'Select the default action used when accessing a PDF from the %sGravity Forms entries list%s page.', 'gravity-forms-pdf-extended' ), '<a href="' . admin_url( 'admin.php?page=gf_entries' ) . '">', '</a>' ),
						'type'    => 'radio',
						'options' => [
							'View'     => esc_html__( 'View', 'gravity-forms-pdf-extended' ),
							'Download' => esc_html__( 'Download', 'gravity-forms-pdf-extended' ),
						],
						'std'     => 'View',
						'tooltip' => '<h6>' . esc_html__( 'Entry View', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Choose to view the PDF in your web browser or download the document to your computer.', 'gravity-forms-pdf-extended' ),
					],

					'update_screen_action' => [
						'id'      => 'update_screen_action',
						'name'    => esc_html__( "Show What's New", 'gravity-forms-pdf-extended' ),
						'desc'    => "When updating to a new release we'll redirect you to our What's New page.",
						'type'    => 'radio',
						'options' => [
							'Enable'  => esc_html__( 'Enable', 'gravity-forms-pdf-extended' ),
							'Disable' => esc_html__( 'Disable', 'gravity-forms-pdf-extended' ),
						],
						'std'     => 'Enable',
						'tooltip' => '<h6>' . esc_html__( "Show What's New Page", 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( "When upgrading Gravity PDF to a new major release (4.x) we'll automatically redirect you to our What's New page so you can see the changes. Bug fix and security releases are excluded (4.x.x).", 'gravity-forms-pdf-extended' ),
					],
				]
			),

			/* See https://gravitypdf.com/documentation/v4/gfpdf_settings_general_security/ for more details about this filter */
			'general_security'                => apply_filters( 'gfpdf_settings_general_security',
				[
					'admin_capabilities' => [
						'id'          => 'admin_capabilities',
						'name'        => esc_html__( 'User Restriction', 'gravity-forms-pdf-extended' ),
						'desc'        => esc_html__( 'Restrict PDF access to users with any of these capabilities. The Administrator Role always has full access.', 'gravity-forms-pdf-extended' ),
						'type'        => 'select',
						'options'     => $this->get_capabilities(),
						'std'         => 'gravityforms_view_entries',
						'inputClass'  => 'large',
						'chosen'      => true,
						'multiple'    => true,
						'required'    => true,
						'placeholder' => esc_html__( 'Select Capability', 'gravity-forms-pdf-extended' ),
						'tooltip'     => '<h6>' . esc_html__( 'User Restriction', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( "Only logged in users with any selected capability can view generated PDFs they don't have ownership of. Ownership refers to an end user who completed the original Gravity Form entry.", 'gravity-forms-pdf-extended' ),
					],

					'default_restrict_owner' => [
						'id'      => 'default_restrict_owner',
						'name'    => esc_html__( 'Default Owner Restrictions', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Set the default PDF owner permissions. When enabled, the original entry owner will NOT be able to view the PDFs (unless they have one of the above capabilities).', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Enable', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'Disable', 'gravity-forms-pdf-extended' ),
						],
						'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						'tooltip' => '<h6>' . esc_html__( 'Restrict Owner', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Enable this setting if your PDFs should not be viewable by the end user. This can be set on a per-PDF basis.', 'gravity-forms-pdf-extended' ),
					],

					'logged_out_timeout' => [
						'id'      => 'logged_out_timeout',
						'name'    => esc_html__( 'Logged Out Timeout', 'gravity-forms-pdf-extended' ),
						'desc'    => sprintf( esc_html__( 'Limit how long a %slogged out%s users has direct access to the PDF after completing the form. Set to 0 to disable time limit (not recommended).', 'gravity-forms-pdf-extended' ), '<em>', '</em>' ),
						'desc2'   => esc_html__( 'minutes', 'gravity-forms-pdf-extended' ),
						'type'    => 'number',
						'size'    => 'small',
						'std'     => 20,
						'tooltip' => '<h6>' . esc_html__( 'Logged Out Timeout', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Logged out users can view PDFs when their IP matches the one assigned to the Gravity Form entry. Because IP addresses can change, a time-based restriction also applies.', 'gravity-forms-pdf-extended' ),
					],
				]
			),

			/* Extension Settings */
			'extensions'                      => apply_filters( 'gfpdf_settings_extensions',
				[]
			),

			/* License Settings */
			'licenses'                        => apply_filters( 'gfpdf_settings_licenses',
				[]
			),

			/*
			 * Tools Settings
			 *
			 * See https://gravitypdf.com/documentation/v4/gfpdf_settings_tools/ for more details about this filter
			 */
			'tools'                           => apply_filters( 'gfpdf_settings_tools',
				[
					'setup_templates' => [
						'id'      => 'setup_templates',
						'name'    => esc_html__( 'Setup Custom Templates', 'gravity-forms-pdf-extended' ),
						'desc'    => sprintf( esc_html__( 'Setup environment for building custom templates. %sSee docs to get started%s.', 'gravity-forms-pdf-extended' ), '<a href="https://gravitypdf.com/documentation/v4/developer-first-custom-pdf/">', '</a>' ),
						'type'    => 'button',
						'std'     => esc_html__( 'Run Setup', 'gravity-forms-pdf-extended' ),
						'options' => 'copy',
						'tooltip' => '<h6>' . esc_html__( 'Setup Custom Templates', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'The setup will copy the plugin templates to your uploads directory so you can freely create and modify PDF templates without the risk of overriding your modifications when the plugin updates.', 'gravity-forms-pdf-extended' ),
					],

					'manage_fonts' => [
						'id'      => 'manage_fonts',
						'name'    => esc_html__( 'Fonts', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Add, update or remove custom fonts.', 'gravity-forms-pdf-extended' ),
						'type'    => 'button',
						'std'     => esc_html__( 'Manage Fonts', 'gravity-forms-pdf-extended' ),
						'options' => 'install_fonts',
						'tooltip' => '<h6>' . esc_html__( 'Install Fonts', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Custom fonts can be installed for use in your PDFs. Only %s.ttf%s font files are supported.', 'gravity-forms-pdf-extended' ), '<code>', '</code>', '<code>', '</code>', '<code>', '</code>' ),
					],
				]
			),

			/*
			 * Form (PDF) Settings
			 *
			 * See https://gravitypdf.com/documentation/v4/gfpdf_form_settings/ for more details about this filter
			 */
			'form_settings'                   => apply_filters( 'gfpdf_form_settings',
				[
					'name' => [
						'id'       => 'name',
						'name'     => esc_html__( 'Name', 'gravity-forms-pdf-extended' ),
						'type'     => 'text',
						'required' => true,
						'tooltip'  => '<h6>' . esc_html__( 'PDF Name', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Distinguish between multiple PDFs by giving it an easy-to-remember name (for internal use). Use the %sFilename%s field below to set the actual PDF name.', 'gravity-forms-pdf-extended' ), '<em>', '</em>' ),
					],

					'template' => [
						'id'         => 'template',
						'name'       => esc_html__( 'Template', 'gravity-forms-pdf-extended' ),
						'desc'       => sprintf( esc_html__( 'Choose an existing template or purchased more %sfrom our theme shop%s. You can also %sbuild your own%s or %shire us%s to create a custom solution.', 'gravity-forms-pdf-extended' ), '<a href="https://gravitypdf.com/shop/">', '</a>', '<a href="https://gravitypdf.com/documentation/v4/developer-start-customising/">', '</a>', '<a href="https://gravitypdf.com/integration-services/">', '</a>' ),
						'type'       => 'select',
						'options'    => $this->templates->get_all_templates_by_group(),
						'std'        => $this->get_option( 'default_template', 'zadani' ),
						'inputClass' => 'large',
						'chosen'     => true,
						'tooltip'    => '<h6>' . esc_html__( 'Templates', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Gravity PDF comes with %sfour completely-free and highly customizable designs%s. You can also purchase additional templates from our theme shop, hire us to integrate existing PDFs or, with a bit of technical know-how, build your own.', 'gravity-forms-pdf-extended' ), '<strong>', '</strong>' ),
					],

					'notification' => [
						'id'          => 'notification',
						'name'        => esc_html__( 'Notifications', 'gravity-forms-pdf-extended' ),
						'desc'        => esc_html__( 'Automatically attach PDF to the selected notifications.', 'gravity-forms-pdf-extended' ),
						'type'        => 'select',
						'options'     => [],
						'inputClass'  => 'large',
						'chosen'      => true,
						'multiple'    => true,
						'placeholder' => esc_html__( 'Choose a Notification', 'gravity-forms-pdf-extended' ),
						'tooltip'     => '<h6>' . esc_html__( 'Notifications', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Automatically generate and attach the PDF to your selected notifications. Conditional Logic for both the PDF and the notification applies. Inactive PDFs are also not sent.', 'gravity-forms-pdf-extended' ),
					],

					'filename' => [
						'id'         => 'filename',
						'name'       => esc_html__( 'Filename', 'gravity-forms-pdf-extended' ),
						'type'       => 'text',
						'desc'       => 'The name used when saving a PDF. Mergetags are allowed.',
						'tooltip'    => '<h6>' . esc_html__( 'Filename', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'Set an appropriate filename for the generated PDF. You should exclude the .pdf extension from the name. The following are invalid characters and will be converted to an underscore %s_%s when the PDF is generated: %s', 'gravity-forms-pdf-extended' ), '<code>', '</code>', '<code>/ \ " * ? | : < ></code>' ),
						'inputClass' => 'merge-tag-support mt-hide_all_fields',
						'required'   => true,
					],

					'conditional' => [
						'id'         => 'conditional',
						'name'       => esc_html__( 'Conditional Logic', 'gravity-forms-pdf-extended' ),
						'type'       => 'conditional_logic',
						'desc'       => esc_html__( 'Enable conditional logic', 'gravity-forms-pdf-extended' ),
						'class'      => 'conditional_logic',
						'inputClass' => 'conditional_logic_listener',
						'tooltip'    => '<h6>' . esc_html__( 'Conditional Logic', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Create rules to dynamically enable or disable PDFs. This includes attaching to notifications and viewing from your admin area.', 'gravity-forms-pdf-extended' ),
					],

					'conditionalLogic' => [
						'id'    => 'conditionalLogic',
						'type'  => 'hidden',
						'class' => 'gfpdf-hidden',
					],

				]
			),

			/*
			 * Form (PDF) Settings Appearance
			 *
			 * See https://gravitypdf.com/documentation/v4/gfpdf_form_settings_appearance/ for more details about this filter
			 */
			'form_settings_appearance'        => apply_filters( 'gfpdf_form_settings_appearance',
				[
					'pdf_size' => [
						'id'         => 'pdf_size',
						'name'       => esc_html__( 'Paper Size', 'gravity-forms-pdf-extended' ),
						'desc'       => esc_html__( 'Set the paper size used when generating PDFs.', 'gravity-forms-pdf-extended' ),
						'type'       => 'select',
						'options'    => $this->get_paper_size(),
						'std'        => $this->get_option( 'default_pdf_size', 'A4' ),
						'inputClass' => 'large',
						'class'      => 'gfpdf_paper_size',
						'chosen'     => true,
					],

					'custom_pdf_size' => [
						'id'       => 'custom_pdf_size',
						'name'     => esc_html__( 'Custom Paper Size', 'gravity-forms-pdf-extended' ),
						'desc'     => esc_html__( 'Control the exact paper size. Can be set in millimeters or inches.', 'gravity-forms-pdf-extended' ),
						'type'     => 'paper_size',
						'size'     => 'small',
						'chosen'   => true,
						'required' => true,
						'class'    => 'gfpdf-hidden gfpdf_paper_size_other',
						'std'      => $this->get_option( 'default_custom_pdf_size' ),
					],

					'orientation' => [
						'id'         => 'orientation',
						'name'       => esc_html__( 'Orientation', 'gravity-forms-pdf-extended' ),
						'type'       => 'select',
						'options'    => [
							'portrait'  => esc_html__( 'Portrait', 'gravity-forms-pdf-extended' ),
							'landscape' => esc_html__( 'Landscape', 'gravity-forms-pdf-extended' ),
						],
						'inputClass' => 'large',
						'chosen'     => true,
					],

					'font' => [
						'id'         => 'font',
						'name'       => esc_html__( 'Font', 'gravity-forms-pdf-extended' ),
						'type'       => 'select',
						'options'    => $this->get_installed_fonts(),
						'std'        => $this->get_option( 'default_font' ),
						'desc'       => sprintf( esc_html__( 'Set the font type used in PDFs. Choose an existing font or %sinstall your own%s.', 'gravity-forms-pdf-extended' ), '<a href="' . $this->data->settings_url . '&tab=tools#manage_fonts">', '</a>' ),
						'inputClass' => 'large',
						'chosen'     => true,
						'tooltip'    => '<h6>' . esc_html__( 'Fonts', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Gravity PDF comes bundled with fonts for most languages world-wide. Want to use a specific font type? Use the font installer (found in the Forms -> Settings -> Tools tab).', 'gravity-forms-pdf-extended' ),
						'class'      => 'gfpdf_font_type',
					],

					'font_size' => [
						'id'    => 'font_size',
						'name'  => esc_html__( 'Font Size', 'gravity-forms-pdf-extended' ),
						'desc'  => esc_html__( 'Set the font size to use in the PDF.', 'gravity-forms-pdf-extended' ),
						'desc2' => 'pt',
						'type'  => 'number',
						'size'  => 'small',
						'std'   => $this->get_option( 'default_font_size', 10 ),
						'class' => 'gfpdf_font_size',
					],

					'font_colour' => [
						'id'    => 'font_colour',
						'name'  => esc_html__( 'Font Color', 'gravity-forms-pdf-extended' ),
						'type'  => 'color',
						'std'   => $this->get_option( 'default_font_colour', '#000000' ),
						'desc'  => esc_html__( 'Set the font color to use in the PDF.', 'gravity-forms-pdf-extended' ),
						'class' => 'gfpdf_font_colour',
					],

					'rtl' => [
						'id'      => 'rtl',
						'name'    => esc_html__( 'Reverse Text (RTL)', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Script like Arabic and Hebrew are written right to left.', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						],
						'std'     => $this->get_option( 'default_rtl', 'No' ),
						'tooltip' => '<h6>' . esc_html__( 'Reverse Text (RTL)', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( "Enable RTL if you are writing in Arabic, Hebrew, Syriac, N'ko, Thaana, Tifinar or Urdu.", 'gravity-forms-pdf-extended' ),
					],

				]
			),

			/**
			 * Form (PDF) Settings Custom Appearance
			 * This filter allows templates to add custom options for use specific to that template
			 * Gravity PDF autoloads a PHP template file if it exists and loads it up with this filter
			 *
			 * See https://gravitypdf.com/documentation/v4/developer-template-configuration-and-image/#template-configuration for more details
			 */
			'form_settings_custom_appearance' => apply_filters( 'gfpdf_form_settings_custom_appearance',
				[]
			),

			/*
			 * Form (PDF) Settings Advanced
			 *
			 * See https://gravitypdf.com/documentation/v4/gfpdf_form_settings_advanced/ for more details about this filter
			 */
			'form_settings_advanced'          => apply_filters( 'gfpdf_form_settings_advanced',
				[
					'format' => [
						'id'      => 'format',
						'name'    => esc_html__( 'Format', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Generate a PDF in the selected format.', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Standard' => 'Standard',
							'PDFA1B'   => 'PDF/A-1b',
							'PDFX1A'   => 'PDF/X-1a',
						],
						'std'     => 'Standard',
						'tooltip' => '<h6>' . esc_html__( 'PDF Format', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( "Generate a document adhearing to the appropriate PDF standard. When not in %sStandard%s mode, watermarks, alpha-transparent PNGs and security options can NOT be used.", 'gravity-forms-pdf-extended' ), '<em>', '</em>' ),
					],

					'security' => [
						'id'      => 'security',
						'name'    => esc_html__( 'Enable PDF Security', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Password protect generated PDFs, or restrict user capabilities.', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						],
						'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
					],

					'password' => [
						'id'         => 'password',
						'name'       => esc_html__( 'Password', 'gravity-forms-pdf-extended' ),
						'type'       => 'text',
						'desc'       => 'Password protect the PDF, or leave blank to disable password protection.',
						'inputClass' => 'merge-tag-support mt-hide_all_fields',
					],

					'privileges' => [
						'id'          => 'privileges',
						'name'        => esc_html__( 'Privileges', 'gravity-forms-pdf-extended' ),
						'desc'        => 'Restrict end user capabilities by removing privileges.',
						'type'        => 'select',
						'options'     => $this->get_privilages(),
						'std'         => [
							'copy',
							'print',
							'print-highres',
							'modify',
							'annot-forms',
							'fill-forms',
							'extract',
							'assemble',
						],
						'inputClass'  => 'large',
						'chosen'      => true,
						'tooltip'     => '<h6>' . esc_html__( 'Privileges', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'You can prevent the end user completing certain actions to the PDF – such as copying text, printing, adding annotations or extracting pages.', 'gravity-forms-pdf-extended' ),
						'multiple'    => true,
						'placeholder' => esc_html__( 'Select End User PDF Privileges', 'gravity-forms-pdf-extended' ),
					],

					'image_dpi' => [
						'id'      => 'image_dpi',
						'name'    => esc_html__( 'Image DPI', 'gravity-forms-pdf-extended' ),
						'type'    => 'number',
						'size'    => 'small',
						'std'     => 96,
						'tooltip' => '<h6>' . esc_html__( 'Image DPI', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Control the image DPI (dots per inch) in PDFs. Set to 300 when professionally printing document.', 'gravity-forms-pdf-extended' ),
					],

					'save' => [
						'id'      => 'save',
						'name'    => esc_html__( 'Always Save PDF', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'Force a PDF to be saved to disk when a new entry is created.', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						],
						'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						'tooltip' => '<h6>' . esc_html__( 'Save PDF', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( "By default, PDFs are not automatically saved to disk. Enable this option to force the PDF to be generated and saved. Useful when using the %sgfpdf_post_pdf_save%s hook to copy the PDF to an alternate location.", 'gravity-forms-pdf-extended' ), '<code>', '</code>' ),
					],

					'public_access' => [
						'id'      => 'public_access',
						'name'    => esc_html__( 'Enable Public Access', 'gravity-forms-pdf-extended' ),
						'desc'    => sprintf( esc_html__( 'Allow %sanyone%s with a direct link to access the PDF. %sThis disables all %ssecurity protocols%s for this PDF.%s ', 'gravity-forms-pdf-extended' ), '<strong>', '</strong>', '<em>', '<a href="https://gravitypdf.com/documentation/v4/user-pdf-security/">', '</a>', '</em>' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						],
						'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						'tooltip' => '<h6>' . esc_html__( 'Public Access', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( "When public access is on all security protocols are disabled and anyone worldwide can view the PDF document for ALL your form's entries. For most users the standard security measures will be adequate and public access should remain disabled.", 'gravity-forms-pdf-extended' ),
					],

					'restrict_owner' => [
						'id'      => 'restrict_owner',
						'name'    => esc_html__( 'Restrict Owner', 'gravity-forms-pdf-extended' ),
						'desc'    => esc_html__( 'When enabled, the original entry owner will NOT be able to view the PDFs.', 'gravity-forms-pdf-extended' ),
						'type'    => 'radio',
						'options' => [
							'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
							'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
						],
						'std'     => $this->get_option( 'default_restrict_owner', 'No' ),
						'tooltip' => '<h6>' . esc_html__( 'Restrict Owner', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Enable this setting if your PDFs should not be viewable by the end user.', 'gravity-forms-pdf-extended' ),
					],
				]
			),
		];

		/* See https://gravitypdf.com/documentation/v4/gfpdf_registered_fields/ for more details about this filter */

		return apply_filters( 'gfpdf_registered_fields', $gfpdf_settings );
	}

	/**
	 * Enable advanced templating field if the user has our legacy premium plugin installed
	 *
	 * Dev notice: We're going to rewrite and rename the Tier 2 premium add-on and utilise template headers to automatically handle
	 * advanced templates without the need for user intervention, which is why this method doesn't have a filter to manually
	 * enable it.
	 *
	 * @param  array $settings The 'form_settings_advanced' array
	 *
	 * @return array
	 *
	 * @since 4.0
	 *
	 */
	public function get_advanced_template_field( $settings ) {

		if ( ! class_exists( 'gfpdfe_business_plus' ) ) {
			return $settings;
		}

		$settings['advanced_template'] = [
			'id'      => 'advanced_template',
			'name'    => esc_html__( 'Enable Advanced Templating', 'gravity-forms-pdf-extended' ),
			'desc'    => esc_html__( 'By enabling, a PDF template will no longer be treated as HTML.', 'gravity-forms-pdf-extended' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
		];

		return $settings;
	}

	/**
	 * Return the optional template-specific form title field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_form_title_display_field() {
		return apply_filters( 'gfpdf_form_title_display_setting', [
			'id'      => 'show_form_title',
			'name'    => esc_html__( 'Show Form Title', 'gravity-forms-pdf-extended' ),
			'desc'    => esc_html__( 'Display the form title at the beginning of the PDF.', 'gravity-forms-pdf-extended' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific page names field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_page_names_display_field() {
		return apply_filters( 'gfpdf_page_names_display_setting', [
			'id'      => 'show_page_names',
			'name'    => esc_html__( 'Show Page Names', 'gravity-forms-pdf-extended' ),
			'desc'    => sprintf( esc_html__( 'Display form page names on the PDF. Requires the use of the %sPage Break field%s.', 'gravity-forms-pdf-extended' ), '<a href="https://www.gravityhelp.com/documentation/article/page-break/">', '</a>' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific HTML field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_html_display_field() {
		return apply_filters( 'gfpdf_html_display_setting', [
			'id'      => 'show_html',
			'name'    => esc_html__( 'Show HTML Fields', 'gravity-forms-pdf-extended' ),
			'desc'    => esc_html__( 'Display HTML fields in the PDF.', 'gravity-forms-pdf-extended' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific section content field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_section_content_display_field() {
		return apply_filters( 'gfpdf_section_content_display_setting', [
			'id'      => 'show_section_content',
			'name'    => esc_html__( 'Show Section Break Description', 'gravity-forms-pdf-extended' ),
			'desc'    => esc_html__( 'Display the Section Break field description in the PDF.', 'gravity-forms-pdf-extended' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific hidden field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_conditional_display_field() {
		return apply_filters( 'gfpdf_conditional_display_setting', [
			'id'      => 'enable_conditional',
			'name'    => esc_html__( 'Enable Conditional Logic', 'gravity-forms-pdf-extended' ),
			'desc'    => esc_html__( 'When enabled the PDF will adhere to the form field conditional logic.', 'gravity-forms-pdf-extended' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
			'tooltip' => '<h6>' . esc_html__( 'Enable Conditional Logic', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Enable this option to hide failed conditional logic fields in the PDF.', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific empty field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_empty_display_field() {
		return apply_filters( 'gfpdf_empty_display_setting', [
			'id'      => 'show_empty',
			'name'    => esc_html__( 'Show Empty Fields', 'gravity-forms-pdf-extended' ),
			'desc'    => esc_html__( 'Display Empty fields in the PDF.', 'gravity-forms-pdf-extended' ),
			'type'    => 'radio',
			'options' => [
				'Yes' => esc_html__( 'Yes', 'gravity-forms-pdf-extended' ),
				'No'  => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
			],
			'std'     => esc_html__( 'No', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific header field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_header_field() {
		return apply_filters( 'gfpdf_header_field_setting', [
			'id'         => 'header',
			'name'       => esc_html__( 'Header', 'gravity-forms-pdf-extended' ),
			'type'       => 'rich_editor',
			'size'       => 8,
			'desc'       => sprintf( esc_html__( 'The header is included at the top of each page. For simple columns %stry this HTML table snippet%s.', 'gravity-forms-pdf-extended' ), '<a href="https://gist.github.com/jakejackson1/997b5dedf0a5e665e8ef">', '</a>' ),
			'inputClass' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right mt-hide_all_fields',
			'tooltip'    => '<h6>' . esc_html__( 'Header', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'When inserting images in the header set the size to %sLarge%s or %sFull Size%s.', 'gravity-forms-pdf-extended' ), '<em>', '</em>', '<em>', '</em>' ),
		] );
	}

	/**
	 * Return the optional template-specific first page header field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_first_page_header_field() {
		return apply_filters( 'gfpdf_first_page_header_field_setting', [
			'id'         => 'first_header',
			'name'       => esc_html__( 'First Page Header', 'gravity-forms-pdf-extended' ),
			'type'       => 'rich_editor',
			'size'       => 8,
			'desc'       => esc_html__( 'Override the header on the first page of the PDF.', 'gravity-forms-pdf-extended' ),
			'inputClass' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right mt-hide_all_fields',
			'toggle'     => esc_html__( 'Use different header on first page of PDF?', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific footer field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_footer_field() {
		return apply_filters( 'gfpdf_footer_field_setting', [
			'id'         => 'footer',
			'name'       => esc_html__( 'Footer', 'gravity-forms-pdf-extended' ),
			'type'       => 'rich_editor',
			'size'       => 8,
			'desc'       => sprintf( esc_html__( 'The footer is included at the bottom of every page. For simple columns %stry this HTML table snippet%s.', 'gravity-forms-pdf-extended' ), '<a href="https://gist.github.com/jakejackson1/e6179a96cd97ef0a8457">', '</a>' ),
			'inputClass' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right mt-hide_all_fields',
			'tooltip'    => '<h6>' . esc_html__( 'Footer', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'For simple text footers use the left, center and right alignment buttons in the editor. You can also use the special %s{PAGENO}%s and %s{nbpg}%s tags to display page numbering.', 'gravity-forms-pdf-extended' ), '<em>', '</em>', '<em>', '</em>' ),
		] );
	}

	/**
	 * Return the optional template-specific first page footer field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_first_page_footer_field() {
		return apply_filters( 'gfpdf_first_page_footer_field_setting', [
			'id'         => 'first_footer',
			'name'       => esc_html__( 'First Page Footer', 'gravity-forms-pdf-extended' ),
			'type'       => 'rich_editor',
			'size'       => 8,
			'desc'       => esc_html__( 'Override the footer on the first page of the PDF.', 'gravity-forms-pdf-extended' ),
			'inputClass' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right mt-hide_all_fields',
			'toggle'     => esc_html__( 'Use different footer on first page of PDF?', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific background color field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_background_color_field() {
		return apply_filters( 'gfpdf_background_color_field_setting', [
			'id'   => 'background_color',
			'name' => esc_html__( 'Background Color', 'gravity-forms-pdf-extended' ),
			'type' => 'color',
			'std'  => '#FFF',
			'desc' => esc_html__( 'Set the background color for all pages.', 'gravity-forms-pdf-extended' ),
		] );
	}

	/**
	 * Return the optional template-specific background image field
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_background_image_field() {
		return apply_filters( 'gfpdf_background_image_field_setting', [
			'id'      => 'background_image',
			'name'    => esc_html__( 'Background Image', 'gravity-forms-pdf-extended' ),
			'type'    => 'upload',
			'desc'    => esc_html__( 'The background image is included on all pages. For optimal results, use an image the same dimensions as the paper size.', 'gravity-forms-pdf-extended' ),
			'tooltip' => '<h6>' . esc_html__( 'Background Image', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'For the best results, use a JPG or non-interlaced 8-Bit PNG that has the same dimensions as the paper size.', 'gravity-forms-pdf-extended' ),
		] );
	}
}
