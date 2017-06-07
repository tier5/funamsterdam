=== Gravity PDF ===
Contributors: blue-liquid-designs
Plugin URI: https://gravitypdf.com/
Donate link: https://gravitypdf.com/donate-to-plugin/
Tags: gravity, forms, pdf, automation, attachment, email
Requires at least: 4.2
Tested up to: 4.7
Stable tag: 4.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl.txt

Automatically generate, email and download PDF documents with Gravity Forms and Gravity PDF.

== Description ==

**Gravity PDF is the ultimate solution for generating digital PDF documents using Gravity Forms and WordPress.**

https://www.youtube.com/watch?v=z8zKKrjmNjY

The plugin ships with four highly-customisable PDF templates perfectly suited for displaying your user’s data. Within seconds you can personalise the documents with your company logo, change the font, size, colour and the paper size. If the templates don't suit, [have one tailor made just for you](https://gravitypdf.com/integration-services/) or [roll your own](https://gravitypdf.com/documentation/v4/developer-start-customising/).

> Digital document management with WordPress and Gravity Forms just became a breeze!

= Feature =

* There’s no third-party APIs needed when generating your PDFs. That means no chance of third-party data breaches, no monthly fees or rate limits. You control the software and the documents it generates.
* We support all languages, including complex symbol-based languages like Chinese and Japanese, as well as Right to Left (RTL) written languages such as Arabic and Hebrew.
* Automatically email your PDF when a user completes a form. Have it emailed to people in your organisation, the user, or both. You can also conditionally generate and email the PDF.
* Using Gravity Forms developer-licensed payment add-ons – like PayPal, Authorize.net or Stripe – you can restrict access to the PDF until after a payment is captured.
* [Protecting your user’s sensitive information is at the heart of Gravity PDF](https://gravitypdf.com/documentation/v4/user-pdf-security/). The plugin’s security settings give you granular control over who has access to the PDFs generated.
* Our [JavaScript-powered font manager](https://gravitypdf.com/documentation/v4/user-custom-fonts/) allows you to install and use your favourite fonts. Now you can keep in line with your corporate style guide, or create beautiful PDF typography.
* [The documentation](https://gravitypdf.com/documentation/v4/user-installation/) has everything from basic install instructions to advanced developer how-to guides. Our friendly team is also on hand to [provide FREE general support](https://gravitypdf.com/support/).
* PHP, HTML and CSS come easy? [You’ll find creating your own PDF templates a breeze](https://gravitypdf.com/documentation/v4/developer-start-customising/). If not, [we offer PDF design services](https://gravitypdf.com/integration-services/) tailored just for you. We can even auto-fill existing PDFs!

= Requirements =

Gravity PDF can be run on most shared web hosting without any issues. It requires **PHP 5.4+** (PHP 7.0+ recommended) and at least 64MB of WP Memory (128MB+ recommended). You'll also need to be running WordPress 4.2+ and have [Gravity Forms 1.9+](https://www.e-junkie.com/ecom/gb.php?cl=54585&c=ib&aff=235154) (affiliate link).

If you aren't sure Gravity PDF will meet your needs (and haven't got a Gravity Forms license yet) you can [try out the software via our demo site](https://demo.gravitypdf.com).

= Documentation & Support =

[We have extensive documentation on using Gravity PDF](https://gravitypdf.com/documentation/v4/five-minute-install/), and our friendly support team provides [FREE basic support via our website](https://gravitypdf.com/support/#contact-support) (we also check the WordPress.org forums but submitting a ticket via GravityPDF.com will get a faster response).

= Custom PDF Integration =

We offer **comprehensive PDF integration services** and do all the PDF development and integration into Gravity Forms for you. You tell us what you want and our friendly and experienced developers will design, develop and install custom PDF templates tailor specifically for you. We can even auto-fill your existing PDF documents. [Find out more at GravityPDF.com](https://gravitypdf.com/integration-services/).

= Contribute =

All development for Gravity PDF [is handled via GitHub](https://github.com/GravityPDF/gravity-pdf/). Opening new issues and submitting pull requests are welcome.

[Our public roadmap is available on Trello](https://trello.com/b/60YGv1J3/roadmap). We'd love it if you vote and comment on your favourite ideas.

You can also keep up to date with Gravity PDF by [subscribing to our newsletter](https://gravitypdf.com/#signup-top), [following us on Twitter](https://twitter.com/gravitypdf) or [liking us on Facebook](https://www.facebook.com/gravitypdf).

Also, if you enjoy using the software [we'd love it if you could give us a review!](https://wordpress.org/support/view/plugin-reviews/gravity-forms-pdf-extended)

*Note: When Gravity Forms isn't installed and you activate Gravity PDF we display a notice that includes an affiliate link to their website.*

== Installation ==

[You'll find detailed installation instructions on GravityPDF.com](https://gravitypdf.com/documentation/v4/user-installation/).

== Screenshots ==

1. Our on-boarding experience will have you up and running in 5 minutes flat.
2. Set up the global PDF settings then head straight to configuring your first PDF.
3. Control the default paper size, PDF template and font/size/colour.
4. Advanced security options give you granular control of PDF access.
5. Tools like the font manager and custom PDF installer are readily accessible.
6. Our JavaScript-powered font manager will make using custom fonts a breeze.
7. A snapshot of your form’s PDF setup.
8. When adding a new PDF all the important settings are up front in the “General” tab.
9. Override the default appearance settings on a per-PDF basis.
10. Each template has its own PDF settings for greater control of the look and feel of your document.
11. Header and Footer support is built-in.
12. Advanced format and security settings can be applied to individual PDFs.
13. PDFs can be accessed from the Gravity Forms entry list page.
14. They also appear on the individual entry pages for easy access.
15. Zadani is a minimalist business-style template that will generate a well-spaced document great for printing.
16. Rubix uses stylish containers to create an aesthetically pleasing design.
17. Focus Gravity providing a classic layout which epitomises Gravity Forms Print Preview. It’s the familiar layout you’ve come to love.
18. Blank Slate provides a print-friendly template focusing solely on the user-submitted data.

== Changelog ==

= 4.1.1 =
* Bug: Add check to see if headers are already sent before trying to redirect to the welcome / update page (GH#601)
* Bug: Fixed issue accessing the Advanced Template Manager in Safari browser (GH#603)
* Bug: Ensure the Advanced Template Manager notice and error messages have the correct styles in the Form PDF Settings pages (GH#604)
* Bug: Fix PDF generation problem using the legacy v3 URL structure (GH#605)

= 4.1.0 =
* Feature: Advanced PDF Template Manager. Upload, View, Select and Delete PDF templates with ease (GH#486)
* Feature: Add PDF Mergetags which output PDF URLs and compliment the [gravitypdf] shortcode which output HTML links (GH#404)
* Feature: Add four-column CSS Ready Class support to core PDFs. Note: if you have run "Setup Custom Templates" you will need to re-run it to take advantage of this feature (GH#461)
* Feature: Added support for the WP External Links plugin (GH#386)
* Feature: Added filter to show radio, checkbox, select, multiselect and product field values in core PDF templates (GH#600)
* Enhancement: Gravity PDF Review Notice now only shows up on Gravity Forms pages (#528)
* Enhancement: Convert all strings to American format so they can be correctly translated using Glotpress (GH#525)
* Enhancement: Added Australian, New Zealand and UK language packs (GH#525)
* Enhancement: Add support for Gravity Forms 2.2 Logging Module (GH#596)
* Dev Feature: Added 'Author URI' and 'Tags' headers to PDF template files which get displayed in the Advanced Template Manager (GH#558)
* Dev Feature: Include $this as eighth parameter in 'gfpdf_field_html_value' filter (GH#549)
* Dev Feature: Add 'gfpdf_field_section_break_html' filter to returned Section Field HTML for the PDF (GH#548)
* Dev Feature: Add actions before and after the core template HTML is generated; 'gfpdf_pre_html_fields' and 'gfpdf_post_html_fields' respectively (GH#546)
* Dev Feature: Template PHP Configuration files can impliment setUp and TearDown interfaces which fire when templates are installed or deleted through the Advanced Template Manager (GH#545)
* Dev Feature: Added Font Create and Delete endpoints to API – GPDFAPI::add_pdf_font() and GPDFAPI::delete_pdf_font() (GH#541)
* Dev Feature: Allow Rich Text Editor height to be controlled through the 'size' property when used in template config (GH#540)
* Dev Feature: Allow images in radio buttons using the new `'class' => 'image-radio-buttons'` property when used in template config (GH#539)
* Dev Changes: Use Gravity Forms copy of Chosen JS (GH#563)
* Dev Changes: All production CSS and JS saved to /dest/ directory as part of Advanced Template Manager update
* Dev Changes: Standardised all AJAX Authentication so Nonce and Capability checks are easily checked (GH#538)
* Dev Changes: Rename all instances of "depreciated" with "deprecated" in our files and classes (GH#535)
* Dev Changes: Contact our localised JS data to camelCase (GH#532)
* Dev Changes: Utilised PHP5.4 array syntax in code (GH#521)
* Bug: Reset Gravity Forms Merge Tag JS when PDF template changes (GH#551)
* Bug: Fix incorrect variable reference to $include_list_styles which uses 'gfpdf_include_list_styles' to change the behaviour (GH#547)
* Bug: Fix PHP notice in PDF when no products selected in form (GH#523)
* Bug: Fix issue with Gravity PDF update screen showing and not showing at incorrect times (GH#514)
* Bug: Fix false positive when checking if the PDF tmp directory is readable (GH#519)
* Bug: Fix error when using GLOB_BRACE flag in glob() function (GH#562)
* Bug: Remove OTF fonts from being uploaded due to poor support in Mpdf (GH#569)
* Bug: Additional PHP7.1 fixes merged from upstream Mpdf package
* Bug: Allow TTF file mime type to be correctly detected in WordPress 4.7.3 (GH#571)
* Bug: Ensure PDF Delete dialog shows up after being previously 'cancelled' (GH#588)
* Bug: Ensure duplicate mergetags aren't included after PDF template change (GH#589)
* Bug: Fix PHP Notice if there's no active capabilities for a role (GH#590)

= 4.0.6 =
* Correctly register our PDF link with the WP Rewrite API when "Almost Pretty" permalinks are active (GH#502)
* Correctly process mergetags in password field for Tier 2 PDF templates (GH#503)
* Allow mergetags to be saved in HTML attributes in our Header / Footer settings - DEV NOTE: all Rich Text Editor settings fields should be output with `wp_kses_post( $html )` (GH#492)
* Process mergetags before Header / Footer settings get passed to wp_kses_post() on output (GH#512)
* Renamed `check_wordpress()` method to `is_compatible_wordpress_version()` to prevent false positive using ConfigServer eXploit Scanner (GH#500)
* Explicitly set a forward slash after the home_url() when building PDF links (GH#511)
* Resolve incorrect page numbering in Mpdf's Table of Contents
* Change Helper_Misc->get_contrast() to choose white in more cases (GH#506)

= 4.0.5 =
* Add support for "Almost Pretty" permalinks for web servers that don't support Mod Rewrite (IIS) (GH#488)
* Add PHP 7.1 support – resolves two string-to-array issues (GH#495)
* Add <p> and <br> tags to Rich Text Paragraph field in PDF – using wpautop() (GH#490)
* Disable product table when enabling the 'individual_products' option in core templates (GH#493)

= 4.0.4 =
* Prevent Finder (Mac) and Ghostscript viewing / processing password-protected PDFs without a password (GH#467)
* Fix Font Manager display issues for users running a version of WP lower than 4.5 (GH#470)
* Ensure new lines in Header / Footer automatically convert to <p> or <br> tags using wpautop() (GH#472)
* Fix issue in $form_data where Radio / Checkbox fields wouldn't display site-owner entered HTML (GH#415)
* Fixed conflict with Enhanced Media Library plugin (GH#433)
* Fixed issue with encoded characters in saved PDF filename (GH#475)
* Fixed issue where PDF settings would always set to "active" when saved (GH#477)
* Fixed depreciation notice for multisites using WordPress 4.6 (GH#479)
* Apply esc_html() and esc_url() to PDF name and URL in admin area (GH#484)

= 4.0.3 =
* Fix incorrect product calculations when using decimal comma format eg. 1.000,50 (GH#442)
* Rename $config variable to $html_config in core templates (GH#451)
* Don't chain CSS in our default setters or set fixed font size in templates (GH#446)
* Fix display issues for certain characters with DejaVu Sans font family in PDFs (GH#456)
* Ensure QueryPath produces valid UTF-8 data after processing (GH#452)
* Re-running the Custom Template Setup will override working directory templates with same name (GH#457)
* Fixed legacy Name field PHP warnings (GH#448)
* Replace translations with their escaped function counterparts (GH#463)
* Duplicating PDFs will now be inactive by default (GH#458)
* Tweaked the "Show Page Names" field description (GH#449)

= 4.0.2 =
* Fixes issue displaying address fields in v4 PDFs (GH#429)
* Fixes internal logging issues and added Gravity Forms 1.1 support (GF#428)
* Fixes notice when form pagination information is not available (GH#437)
* Fixes notice when using GPDFAPI::product_table() on form that had no products (GH#438)
* Fixes caching issue with GravityView Enable Notifications plugin that caused PDF attachment not to be updated (GH#436)

= 4.0.1 =
* Fixes PHP notice when viewing PDF and Category field is empty (GH#419)
* Fixes PHP notice when viewing PDF and custom font directory is empty (GH#416)
* Fixes Font Manager / Help Search features due to Underscore.js conflict when PHP's deprecated ASP Tags enabled (GH#417)
* Allows radio and checkbox values to show HTML in PDFs (GH#415)
* Fixes PDF letter spacing issue with upper and lower case characters (GH#418)
* Fixes character display problems using core Arial font in PDFs (GH#420)
* Fixes documentation search error on PDF Help tab (GH#424)
* Add additional check when cleaning up TMP directory (GH#427)

= 4.0 =

* Minimum PHP version changed from PHP 5.2 to PHP 5.4. ENSURE YOUR WEB SERVER IS COMPATIBLE BEFORE UPDATING (Forms -> Settings -> PDF -> System Status)
* Minimum WordPress version changed from 3.9 to 4.2. ENSURE YOU ARE RUNNING THE MINIMUM VERISON OF WP BEFORE UPDATING (Forms -> Settings -> PDF -> System Status)
* Minimum Gravity Forms version changed from 1.8 to 1.9. ENSURE YOU ARE RUNNING THE MINIMUM VERISON OF GRAVITY FORMS BEFORE UPDATING (Forms -> Settings -> PDF -> System Status)
* Maintained backwards compatibility with v3 for 80% of users. Review our migration guide for additional information (https://gravitypdf.com/documentation/v4/v3-to-v4-migration/)
* Created full user interface for managing plugin settings. All settings are now stored in the database
* Overhaul PDF designs that ship with software. Now comes with 4 completely free templates (two are all-new and two are enhanced v3 favourites)
* Added CSS Ready class support in PDFs. Two and three column classes now work in PDF
* Users can apply conditional logic to PDFs via new UI
* Control font, size and colour via new UI
* Control paper size of generated PDF via new UI
* Control advanced security settings via new UI
* Control customisable PDF template options via new UI
* Control PDF header / footers via UI
* Control PDF background via UI
* Change PDF format (PDF/A-1b or PDF/X-1a) via UI
* Password Protect PDF via UI and change end-user privilages
* Added [gravitypdf] shortcode to allow users to display PDF links on confirmation pages, notifications, or anywhere else
* Allow user to change the action of the PDF link view in admin area (view or download)
* Added timeout parameter when unauthenticated user who submitted the form (matched by IP) attempts to access PDF. Defaults to 20 minutes
* Added ability to make a PDF "public". This disabled all security precautions on PDF. Use with caution.
* Deprecated configuration.php and created a migration feature which users can run if that file is detected. Removes /output/ directory during migration (where v3 stored PDFs saved to disk).
* Duplicating Gravity Form will also duplicate Gravity PDF settings assigned to that form. Importing / Exporting forms will also include PDF settings
* Better installation and upgrade experience for users with automated redirect to landing page after install / major update (can be disabled in settings)
* Created a font manager so users have a user interface to install and use their favourite fonts. Support for TTF and certain OTF font files
* Allow users to enable Right to Left language support from UI
* Created uninstaller which removes all trace of plugin from website
* Help tab allows users to live search our documentation
* Remove need to initialise the plugin when first installed
* Remove need to initialise fonts when uploaded to our /fonts/ directory
* Cleanup PDFs from disk when finished with them (also cleans up any stay files every 24 hours)
* Detect if our /tmp/ directory is accessible by browser and suggest ways to fix
* Allow all directories in /PDF_EXTENDED_TEMPLATES/ directory to be moved / renamed via filters (including the base directory)
* Create GPDFAPI class to allow devs to easily build ontop of plugin
* Cleaned up PDF template markup so developers can focus soley on their template code without any extra overhead. See our documentation for more details (https://gravitypdf.com/documentation/v4/developer-start-customising/)
* Enhanced PDF templates by allowing an image and configuration class
* Added large number of new actions and filters and provided documentation and examples for them on our website
* Allow developers to add or remove individual security layers via filters
* Updated mPDF from 5.7 to 6.1
* Added support for Gravity Forms Logging plugin
* Added better product data to $form_data['field']
* Added PHPDocs to all classes / methods / functions
* Fix PDF_EXTENDED_TEMPLATES location in legacy Multisite networks (WP3.5 or lower)
* Automatically make $field array available to PDF templates (array of current form fields accessible by field ID)
* Automatically make $settings array available to PDF templates (the current PDF configuration settigns)
* Automatically make $config array available to PDF templates (the initialised template config class - if any)
* Automatically make $form, $entry and $form_data available to PDF templates
* Automatically make $gfpdf object available to PDF templates (the main Gravity PDF object containing all our helper classes)

See [CHANGELOG.txt](https://github.com/GravityPDF/gravity-pdf/blob/master/CHANGELOG.txt) for v3 changelog history.

== Upgrade Notice ==

= 4.0.4 =
This patch fixes a PDF security by-passing issue. If you use the PDF Security settings update immediately.

= 4.0.3 =
The core PDF templates have been updated to version 1.1. If you've previously run the Custom Template Setup make sure you run it again to take advantage of the changes.

= 4.0 =
**WARNING**: This major release is not 100% backwards compatibile with v3. Review our upgrade guide AND do a full backup before proceeding with the update (https://goo.gl/htd6CK).
