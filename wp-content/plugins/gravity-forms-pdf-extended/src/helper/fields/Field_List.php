<?php

namespace GFPDF\Helper\Fields;

use GFPDF\Helper\Helper_Abstract_Form;
use GFPDF\Helper\Helper_Misc;
use GFPDF\Helper\Helper_Abstract_Fields;

use GFFormsModel;
use GF_Field_List;

use Exception;

/**
 * Gravity Forms Field
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
 * Controls the display and output of a Gravity Form field
 *
 * @since 4.0
 */
class Field_List extends Helper_Abstract_Fields {

	/**
	 * Check the appropriate variables are parsed in send to the parent construct
	 *
	 * @param object                             $field The GF_Field_* Object
	 * @param array                              $entry The Gravity Forms Entry
	 *
	 * @param \GFPDF\Helper\Helper_Abstract_Form $gform
	 * @param \GFPDF\Helper\Helper_Misc          $misc
	 *
	 * @throws Exception
	 *
	 * @since 4.0
	 */
	public function __construct( $field, $entry, Helper_Abstract_Form $gform, Helper_Misc $misc ) {

		if ( ! is_object( $field ) || ! $field instanceof GF_Field_List ) {
			throw new Exception( '$field needs to be in instance of GF_Field_List' );
		}

		/* call our parent method */
		parent::__construct( $field, $entry, $gform, $misc );
	}

	/**
	 * Return the HTML form data
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function form_data() {

		$data  = [];
		$label = GFFormsModel::get_label( $this->field );
		$html  = $this->html();

		/* Add our List array */
		$list_array = $this->value();
		$list_array = ( 0 < sizeof( $list_array ) ) ? $list_array : '';

		$data['list'][ $this->field->id ] = $list_array;

		/* Add our List HTML */
		$data['field'][ $this->field->id . '.' . $label ] = $html;
		$data['field'][ $this->field->id ]                = $html;
		$data['field'][ $label ]                          = $html;

		return $data;
	}

	/**
	 * Display the HTML version of this field
	 *
	 * @param string $value
	 * @param bool   $label
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function html( $value = '', $label = true ) {

		/* exit early if list field is empty */
		if ( $this->is_empty() ) {
			return parent::html( '' );
		}

		/* get out field value */
		$value   = $this->value();
		$columns = is_array( $value[0] );

		/* Start buffer and generate a list table */
		ob_start();
		?>

		<table autosize="1" class="gfield_list">

			<!-- Loop through the column names and output in a header (if using the advanced list) -->
			<?php if ( $columns ) : $columns = array_keys( $value[0] ); ?>
				<tbody class="head">
				<tr>
					<?php foreach ( $columns as $column ) : ?>
						<th>
							<?php echo esc_html( $column ); ?>
						</th>
					<?php endforeach; ?>
				</tr>
				</tbody>
			<?php endif; ?>

			<!-- Loop through each row -->
			<tbody class="contents">
			<?php foreach ( $value as $item ) : ?>
				<tr>
					<!-- handle the basic list -->
					<?php if ( ! $columns ) : ?>
						<td><?php echo esc_html( $item ); ?></td>
					<?php else : ?><!-- handle the advanced list -->
						<?php foreach ( $columns as $column ) : ?>
							<td>
								<?php echo esc_html( rgar( $item, $column ) ); ?>
							</td>
						<?php endforeach; ?>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>

		</table>

		<?php
		/* get buffer and return HTML */

		return parent::html( ob_get_clean() );
	}

	/**
	 * Get the standard GF value of this field
	 *
	 * @return string|array
	 *
	 * @since 4.0
	 */
	public function value() {
		if ( $this->has_cache() ) {
			return $this->cache();
		}

		$value = maybe_unserialize( $this->get_value() );

		/* make sure value is an array */
		if ( ! is_array( $value ) ) {
			$value = [ $value ];
		}

		/* Remove empty rows */
		$value = $this->remove_empty_list_rows( $value );

		$this->cache( $value );

		return $this->cache();
	}

	/**
	 * Remove empty list rows
	 *
	 * @param  array $list The current list array
	 *
	 * @return array       The filtered list array
	 *
	 * @since 4.0
	 */
	private function remove_empty_list_rows( $list ) {

		/* if list field empty return early */
		if ( ! is_array( $list ) || sizeof( $list ) === 0 ) {
			return $list;
		}

		/* If single list field */
		if ( ! is_array( $list[0] ) ) {
			$list = array_filter( $list );
			$list = array_map( 'esc_html', $list );
		} else {

			/* Loop through the multi-column list */
			foreach ( $list as $id => &$row ) {

				$empty = true;

				foreach ( $row as &$col ) {

					/* Check if there is data and if so break the loop */
					if ( strlen( trim( $col ) ) > 0 ) {
						$col   = esc_html( $col );
						$empty = false;
					}
				}

				/* Remove row from list */
				if ( $empty ) {
					unset( $list[ $id ] );
				}
			}
		}

		return $list;
	}
}
