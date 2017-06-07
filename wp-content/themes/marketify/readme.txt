=== Marketify ===
Contributors: Astoundify
Requires at least: WordPress 4.3
Tested up to: WordPress 4.3.1
Version: 2.3.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: white, two-columns, one-column, right-sidebar, left-sidebar, fluid-layout, custom-background, custom-header, theme-options, full-width-template, featured-images, flexible-header, custom-menu, translation-ready

== Copyright ==

Marketify Theme, Copyright 2014-2015 Astoundify -
Marketify is distributed under the terms of the GNU GPL.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

The Marketify theme bundles the following third-party resources:

Bootstrap v3.0.3
Copyright 2013 Twitter, Inc
Licensed under the Apache License v2.0
http://www.apache.org/licenses/LICENSE-2.0

Slick.js v1.5.7, Copyright 2015 Ken Wheeler
Licenses: MIT/GPL2
Source: https://github.com/kenwheeler/slick/

salvattore.js Copyright (c) 2013-2015 Rolando Murillo and Giorgio Leveroni
License: MIT/GPL2
Source: https://github.com/rnmp/salvattore/

Magnific-Popup Copyright (c) 2014-2015 Dmitry Semenov (http://dimsemenov.com)
Licenses: MIT
Source: https://github.com/dimsemenov/Magnific-Popup

vide 0.4.1 Copyright (c) 2015 Ilya Makarov
Licenses: MIT
Source: https://github.com/VodkaBears/Vide

Ionicons icon font, Copyright (c) 2014 Drifty (http://drifty.com/)
License: MIT
Source: https://github.com/driftyco/ionicons

== Changelog ==

= 2.3.1: January 13, 2015 =

* Fix: Error on minimal page template when no featured image is enabled.
* Fix: Correct URL on loading assetes in integrations.
* Fix: Invalid HTML in archive-download.php template file.
* Fix: Remove invalid sanitize_callback on customizer checkboxes.
* Fix: Placeholder color in Chrome and Safari.
* Fix: Featured & Popular slider positioning when using RTL.
* Fix: Gravity Forms styles.

= 2.3.0: January 7, 2015 =

* New: Featured Image backgrounds on Minimal page template.
* New: Blog layout and style updates.
* Fix: Download widths in Easy Digital Downloads 2.5+
* Fix: Hide (x) close in page header.
* Fix: Check for menu tasks being registered when setting FES icons.
* Fix: Use a full width row when purchase count is hidden in the product details.
* Fix: Hide hover when team images have no social accounts.
* Fix: Gallery image navigation styles.

= 2.2.1: December 10, 2015 =

* Fix: Build script created invalid style.css file.

= 2.2.0: December 9, 2015 =

* New: Filters for custom menu icons: `marketify_nav_menu_cart_icon_left` and `marketify_nav_menu_search_icon_left`
* New: Use a popup gallery for navigating images on a standard download.
* Fix: Respect widget settings for hiding purchase count.
* Fix: Recaptcha size on vendor contact form (FES).
* Fix: Single download details button spacing.
* Fix: Use "Site Logo" string instead of "Header Image".
* Fix: Search form overlay on single download pages.
* Fix: Scroll only one slide on individual testimonials when only one is showing.

= 2.1.0: November 27, 2015 =

* New: Helpful notices when a widget is placed in the wrong widget area.
* New: Update for future Frontend Submissions submission form compatibility.
* New: Hide "Popular in X" automatically when sorting results.
* Fix: Correct "Author Since" date on Vendor Profile pages.
* Fix: Blog avatar showing the correct user.
* Fix: Love It heart icon.

= 2.0.0: November 20, 2015 =

Version 2.0.0 of Marketify is a total rewrite of the theme. Please do not update directly on your production server.
You should always test the update on a staging server first.

Please thoroughly review: http://marketify.astoundify.com/article/888-upgrading-to-marketify-2-0-0

This update brings both functionality and visual changes. Marketify has been refocused on being a digital marketplace
with extraneous functionalities being deprecated or removed.

* New: Setup Guide to help you get going within minutes.
* New: Style updates including an updated primary menu with a more flexible responsive menu.
       Updated Icon pack to Ionicons which includes hundreds of new icons. http://ionicons.com/
       More consistent and flexible styling throughout the theme.
* New: Individually control the featured areas of standard, audio, and video downloads.
* New: Full support for FacetWP.
* New: Share your downloads, posts, and pages with Jetpack: http://marketify.astoundify.com/article/787-download-single-share
* New: Choose with image upload to use as the grid image automatically in your submission form.
* New: Three separate footer column widget areas.
* New: Widgetized vendor sidebar for Frontend Submissions.
* New: Full Frontend Submissions 2.3+ support.
* New: Ability to adjust /download/ slug permalinks based on customized labels.
* New: Use WordPress' core audio player to improve speed and reduce assets.
* New: Rewrite of all responsive modules.
* Fix: Hundreds of stability improvements and code hardening. Reviewed by Justin Tadlock of ThemeReview.co
* Deprecated: Frontend Submissions Product Details. Replaced with http://marketify.astoundify.com/article/889-download-single-meta
* Deprecated: Soliloquy Slider support. Replaced with http://marketify.astoundify.com/article/777-home-feature-callout
* Deprecated: Custom bbPress styles.
* Deprecated: Custom user contact methods.
* Removed: Projects by WooThemes Support

= 1.2.5: January 20, 2015 =

* New: Add Envato WordPress Toolkit to TMGPA
* Fix: Make sure taxonomy archives respect the selected terms.
* Fix: Always get the current author in the grid.
* Fix: Respect the shortcode count for the Features widget.
* Fix: Make sure self-hosted videos can embed properly.

= 1.2.4.1: January 13, 2015 =

* Fix: Make sure the blog page respects its set featured image.

= 1.2.4: January 12, 2015 = 

* Fix: Allow pages with default shortcodes to be overwritten by providing their own in the page content.
* Fix: Better checking for page custom headers (including vendor page template).
* Fix: Make sure parameters in all shortcodes are respected.
* Fix: Bullets in EDD Taxonomy children widget.

= 1.2.3.2: December 17, 2014 =

* Fix: Add support for updated FES vendor URLs

= 1.2.3.1: December 17, 2014 =

* New: Add support for quantity forms on purchase buttons in EDD 2.2.
* Fix: Make sure sorting when on search results works properly.
* Fix: Don't stretch images in Flexslider.
* Fix: Avoid overly caching download details widget to avoid stale information.

= 1.2.3: October 20, 2014 =

* New: [downloads] shortcode required on "Likes" page template.
* New: Hooks in vendor profile page template to output custom information
* Fix: Cache download count for vendor profiles
* Fix: Love It pagination not working
* Fix: Load the real excerpt and not the post content
* Fix: More reliable self hosted videos for Video format
* Fix: Improve wish list page display
* Fix: Improve FES FPD widget title display
* Fix: Allow grid image size to be set in customizer
* Tweak: Reduce the height of page headers

= 1.2.2.1: August 20, 2014 =

* Fix: Fix pagination positioning on certain pages
* Fix: Updated Earnings icon in FES dashboard
* Fix: Don't escape vendor description/bio which caused the output of HTML tags
* Fix: Make sure the proper classes are still assigned to download grid items
* Fix: Allow the Shop page template to inert its own content

= 1.2.2: July 24, 2014 =

* New: Masonry/stackable grids
* Fix: Respect search for products when on product pages.
* Fix: Better gallery/product image stability with recent FES fixes.
* Fix: Responsive fixes for homepage taxonomy widget.

= 1.2.1.2: July 5, 2014 =

* Fix: Always show the product slider/grid if there are any attached images that aren't also featured.
* Fix: Sorting on shop page template
* Fix: Don't show vendor contact form if viewing your own profile.

= 1.2.1.1: June 25, 2014 =

* Fix: If there is only a featured image and it's not attached to the parent, still show it.
* Fix: Make sure JS settings are always passed to the Testimonials widget

= 1.2.1: June 12, 2014 =

* New: Add support for [edd_register] shortcode.
* Fix: Don't cut off elements when setting equal heights.
* Fix: Load the full size cover image for vendor stores.
* Fix: Improve nested comment styling on mobile.
* Fix: Improve the stability of gallery and featured image output on downloads.
* Fix: Various CSS tweaks and improvements.

= 1.2.0: May 21, 2014 =

* Note: This is a fairly major update. There should be no backwards compatibility issues but it is always important to test on in a development environment before updating your production website.

* New: Projects by WooThemes support.
* New: Product page layout using inline previews moves buy now/action buttons to "Product Details" widget.
* New: Recent Blog Posts widget can be styled like other grid items.
* New: Manually set Audio Previews using FES and a meta key of `preview_files`
* New: Output ratings breakdown in widget if available.
* New: Homepage taxonomy widget to display tags or categories in a "styled" way.
* New: Add a "Description" field to some homepage widgets.
* New: Images for the "Audio" post format are output under the audio player.
* New: Author profiles can now have a header background image when using FES add an upload fields to the vendor profile with the meta key of `cover_image`.
* Fix: Soliloquy 2.0.0+ compatibility. Requires Soliloquy 2.0.0+ to continue using widget.
* Fix: FES 2.2.0+ compatibility. Requires FES 2.2.0+ to continue using.
* Fix: Make sure ratings schema is properly output
* Fix: Only output the first audio file for audio previews in the grid to improve load times.
* Fix: Truncate titles longer than one line (optional in "Appearance > Customize")
* Fix: Hide comments title when comments are disabled.
* Fix: Make the Frontend Submissions menu responsive.
* Fix: Sorting on search results is now accurate.

= 1.1.1: February 20, 2014 =

* New: EDD Wish List support.
* Fix: Love It Pro heart styling.
* Fix: Make sure the demo link is properly centered when using custom prices.
* Fix: Make sure minimal page template has readable text in certain areas.
* Fix: Allow hover to be touched on touch devices.
* Fix: Make sure Shop and Popular page templates can be used on static homepages and just work better in general.

= 1.1.0: February 4, 2014 =

* New: Alternate single product view.
* New: Sorting widget for Download Archive widget area
* New: Second Homepage design that includes a large search bar.
* New: Download info can be forced to show on all grid items.
* New: "Curated Downloads" widget to show specific downloads.
* New: "Recent Posts" widget to show posts from the blog on the homepage.
* New: Send an email directly to a vendor from their profile page.
* New: Allow audio downloads to have a featured background header image.
* New: Default image placeholder when no grid image is set.
* New: Allow product info on grid items to be be toggled on/off/auto.
* Fix: Don't force the Features widget image size.
* Fix: Use Download Archive widget area on Popular Items page template.
* Fix: Make sure the author archives use the proper EDD_SLUG.
* Fix: "Shop" page template so downloads appear properly.

= 1.0.3: January 23, 2014 =

* New: "Shop" Page Template so the standard archives can be set as the homepage.
* New: Add styling support for Custom Prices extension.
* Fix: Make sure searching results in the proper results depending on location.
* Fix: Hide star ratings when replying to a review (as they are not needed).
* Fix: Make sure the pause button on the audio preview displays on mobile.
* Fix: Avoid duplicate hook names to avoid duplicate output of content.
* Fix: Maintain compatability with Features by WooThemes
* Fix: Various responsive tweaks.

= 1.0.2: January 17, 2014 =

* New: "Real" pagination instead of arrows.
* Fix: Maintain compatibility with Features by WooThemes
* Fix: iPad/tablet responsive breakpoints.
* Fix: IE11 display bugs.
* Fix: Make the [downloads] shortcode respond and act like the /downloads/ archive.

= 1.0.1: January 13, 2014 =

* New: Preview audio files directly from the download grid.
* New: Styling support for Mailbag subscription plugin.
* Fix: [downloads] shortcode title formatting.
* Fix: Don't use the old script font for the default logo.
* Fix: Make sure the header search displays correctly in Firefox.
* Fix: Make sure searching in the header searches for downloads on the homepage.
* Fix: When using the "Light" footer style make sure the default link color is dark.
* Fix: Make sure the "Love It" heart is displayed in the correct spot when you have not loved.
* Fix: Make sure action buttons are not blurred in Chrome on Windows.
* Fix: Avoid "jumping" when loading download archive sliders.
* Fix: bbPress forum home search box alignment.

= 1.0: January 8, 2014 =

First release!
