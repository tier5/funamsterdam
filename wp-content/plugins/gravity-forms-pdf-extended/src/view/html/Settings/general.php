<?php

/**
 * General Settings View
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

?>

<?php $this->tabs(); ?>


<div id="pdfextended-settings">
	<h3>
		<span>
		    <i class="fa fa-cog"></i>
			<?php esc_html_e( 'General Settings', 'gravity-forms-pdf-extended' ); ?>
		</span>
	</h3>

	<form method="post" action="options.php">
		<?php settings_fields( 'gfpdf_settings' ); ?>

		<table id="pdf-general" class="form-table">
			<?php do_settings_fields( 'gfpdf_settings_general', 'gfpdf_settings_general' ); ?>
		</table>

		<div id="gfpdf-advanced-options">
			<h3>
				<span>
				    <i class="fa fa-lock"></i>
					<?php esc_html_e( 'Security Settings', 'gravity-forms-pdf-extended' ); ?>
				</span>
			</h3>

			<table id="pdf-general-security" class="form-table">
				<?php do_settings_fields( 'gfpdf_settings_general_security', 'gfpdf_settings_general_security' ); ?>
			</table>
		</div>

		<div class="gfpdf-advanced-options"><a href="#"><?php esc_html_e( 'Show Advanced Options...', 'gravity-forms-pdf-extended' ); ?></a></div>

		<?php
			if ( $args['edit_cap'] ) {
				submit_button();
			}
		?>
	</form>

	<?php
	/* See https://gravitypdf.com/documentation/v4/gfpdf_post_general_settings_page/ for more details about this action */
	do_action( 'gfpdf_post_general_settings_page' );
	?>
</div>