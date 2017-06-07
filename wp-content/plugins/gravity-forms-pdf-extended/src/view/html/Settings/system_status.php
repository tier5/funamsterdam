<?php

/**
 * System Status Settings View
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2016, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 *
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

<div class="hr-divider"></div>

<h3>
    <span>
        <i class="fa fa-dashboard"></i>
        <?php esc_html_e( 'Installation Status', 'gravity-forms-pdf-extended' ); ?>
    </span>
</h3>

<table id="pdf-system-status" class="form-table">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'WP Memory Available', 'gravity-forms-pdf-extended' ); ?> <?php gform_tooltip( 'pdf_status_wp_memory' ); ?>
		</th>

		<td>

			<?php
				$ram_icon = 'fa fa-check-circle';
				if ( $args['memory'] < 128 && $args['memory'] !== -1 ) {
					$ram_icon = 'fa fa-exclamation-triangle';
				}
			?>

			<?php if ( $args['memory'] === -1 ): ?>
				<?php echo esc_html__( 'Unlimited', 'gravity-forms-pdf-extended' ); ?>
			<?php else: ?>
				<?php echo $args['memory']; ?>MB
			<?php endif; ?>

			<span class="<?php echo $ram_icon; ?>"></span>

			<?php if ( $args['memory'] < 128 && $args['memory'] !== -1 ): ?>
				<span class="gf_settings_description">
                    <?php echo sprintf( esc_html__( 'We strongly recommend you have at least 128MB of available WP Memory (RAM) assigned to your website. %sFind out how to increase this limit%s.', 'gravity-forms-pdf-extended' ), '<br /><a href="https://gravitypdf.com/documentation/v4/user-increasing-memory-limit/">', '</a>' ); ?>
                </span>
			<?php endif; ?>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'WordPress Version', 'gravity-forms-pdf-extended' ); ?>
		</th>

		<td>
			<?php echo $args['wp']; ?>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'Gravity Forms Version', 'gravity-forms-pdf-extended' ); ?>
		</th>

		<td>
			<?php echo $args['gf']; ?>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'PHP Version', 'gravity-forms-pdf-extended' ); ?>
		</th>

		<td>
			<?php echo $args['php']; ?>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'Direct PDF Protection', 'gravity-forms-pdf-extended' ); ?> <?php gform_tooltip( 'pdf_protection' ); ?>
		</th>

		<td>

			<!-- A placeholder for our JS which will do the check for us, thereby preventing any load time by checking in PHP directly -->
			<div id="gfpdf-direct-pdf-protection-check" data-nonce="<?php echo wp_create_nonce( 'gfpdf-direct-pdf-protection' ); ?>">
				<noscript><?php esc_html_e( 'You need JavaScript enabled to perform this check.', 'gravity-forms-pdf-extended' ); ?></noscript>

				<div id="gfpdf-direct-pdf-check-protected" style="display: none">
					<?php esc_html_e( 'Protected', 'gravity-forms-pdf-extended' ); ?> <span class="fa fa-check-circle"></span>
				</div>

				<div id="gfpdf-direct-pdf-check-unprotected" style="display: none">
					<strong><?php esc_html_e( 'Unprotected', 'gravity-forms-pdf-extended' ); ?></strong> <span class="fa fa-times-circle"></span>

					<span class="gf_settings_description">
						<?php printf( esc_html__( "We've detected the PDFs saved in Gravity PDF's %stmp%s directory can be publically accessed.", 'gravity-forms-pdf-extended' ), '<code>', '</code>' ); ?><br>
						<?php printf( esc_html__( 'We recommend you use our %sgfpdf_tmp_location%s filter to %smove the folder outside your public website directory%s.', 'gravity-forms-pdf-extended' ), '<code>', '</code>', '<a href="https://gravitypdf.com/documentation/v4/gfpdf_tmp_location/">', '</a>' ); ?>
					</span>
				</div>
			</div>
		</td>
	</tr>

</table>
