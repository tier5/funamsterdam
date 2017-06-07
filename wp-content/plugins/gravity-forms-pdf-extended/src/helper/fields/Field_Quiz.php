<?php

namespace GFPDF\Helper\Fields;

use GFPDF\Helper\Helper_Abstract_Fields;
use GFPDF\Helper\Helper_QueryPath;

use GFFormsModel;

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
class Field_Quiz extends Helper_Abstract_Fields {

	/**
	 * Return the HTML form data
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function form_data() {

		$value = $this->value();
		$label = GFFormsModel::get_label( $this->field );
		$data  = [];

		$data['field'][ $this->field->id . '.' . $label ] = $value;
		$data['field'][ $this->field->id ]                = $value;
		$data['field'][ $label ]                          = $value;

		/* Backwards compatible */
		$data['field'][ $this->field->id . '.' . $label . '_name' ] = $value;
		$data['field'][ $this->field->id . '_name' ]                = $value;
		$data['field'][ $label . '_name' ]                          = $value;

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
		$value = apply_filters( 'gform_entry_field_value', $this->get_value(), $this->field, $this->entry, $this->form );

		/* Return early to prevent any problems with when field is empty or the quiz plugin isn't enabled */
		if ( ! class_exists( 'GFQuiz' ) || ! is_string( $value ) || trim( $value ) == false ) {
			return parent::html( '' );
		}

		/**
		 * Add class to the quiz images so mPDF can style them (limited cascade support)
		 * We'll try use our DOM reader to correctly process the HTML, otherwise use string replace
		 */
		try {
			$qp    = new Helper_QueryPath();
			$value = $qp->html5( $value, 'img' )->addClass( 'gf-quiz-img' )->top( 'html' )->innerHTML5();
		} catch ( Exception $e ) {
			$value = str_replace( '<img ', '<img class="gf-quiz-img" ', $value );
		}

		return parent::html( $value );
	}

	/**
	 * Get the standard GF value of this field
	 *
	 * @return string|array
	 *
	 * @since 4.0
	 */
	public function value() {

		/* Get the field value */
		$value = $this->get_value();
		$value = ( ! is_array( $value ) ) ? [ $value ] : $value;

		$formatted = [];

		/* Loop through our results */
		foreach ( $value as $item ) {
			foreach ( $this->field->choices as $choice ) {
				if ( $choice['value'] == $item ) {
					$formatted[] = [
						'text'      => esc_html( $choice['text'] ),
						'isCorrect' => $choice['gquizIsCorrect'],
						'weight'    => ( isset( $choice['gquizWeight'] ) ) ? $choice['gquizWeight'] : '',
					];
				}
			}
		}

		/* Ensure results are formatted to v3 expectations */
		if ( 1 === sizeof( $formatted ) ) {
			return $formatted[0];
		}

		/* Return our results, if we have any */
		if ( 0 < sizeof( $formatted ) ) {
			return $formatted;
		}

		/* Return the default expected structure */

		return [];
	}
}
