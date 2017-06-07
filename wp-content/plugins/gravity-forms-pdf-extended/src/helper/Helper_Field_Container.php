<?php

namespace GFPDF\Helper;

use GF_Field;

/**
 * Splits up the PDF fields so that floats can be better supported in respect to
 * Gravity Forms CSS Ready Classes.
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
 */
class Helper_Field_Container {

	/**
	 * Holds the current width of our container based on the field passed in
	 * The value is out of 100
	 *
	 * @var integer
	 *
	 * @since 4.0
	 */
	private $current_width = 0;

	/**
	 * Boolean value to tell if the element is currently opened
	 *
	 * @var boolean
	 *
	 * @since 4.0
	 */
	private $currently_open = false;

	/**
	 * Matches class names to width percentages
	 *
	 * @var array
	 *
	 * @since 4.0
	 */
	private $class_map = [
		'gf_left_half'      => 50,
		'gf_right_half'     => 50,
		'gf_left_third'     => 33.3,
		'gf_middle_third'   => 33.3,
		'gf_right_third'    => 33.3,
		'gf_first_quarter'  => 25,
		'gf_second_quarter' => 25,
		'gf_third_quarter'  => 25,
		'gf_fourth_quarter' => 25,
	];

	/**
	 * The HTML tag used when opening the container
	 *
	 * @var string
	 *
	 * @since 4.0
	 */
	private $open_tag = '<div class="row-separator">';

	/**
	 * The HTML tag used when closing the container
	 *
	 * @var string
	 *
	 * @since 4.0
	 */
	private $close_tag = '</div>';

	/**
	 * The Gravity Form fields we should not wrap in a container
	 *
	 * @var array
	 *
	 * @since 4.0
	 */
	private $skip_fields = [
		'page',
		'section',
		'html',
	];

	/**
	 * Holds the number of times a new row has been open
	 *
	 * @var int
	 *
	 * @since 4.0
	 */
	private $counter = 0;

	/**
	 * Set up the object
	 *
	 * @param array $config Allow user to override the open / close tag and which fields are skipped
	 *
	 * @since 4.0
	 */
	public function __construct( $config = [] ) {
		if ( isset( $config['open_tag'] ) ) {
			$this->open_tag = $config['open_tag'];
		}

		if ( isset( $config['close_tag'] ) ) {
			$this->close_tag = $config['close_tag'];
		}

		if ( isset( $config['skip_fields'] ) ) {
			$this->skip_fields = $config['skip_fields'];
		}
	}


	/**
	 * Handles the opening and closing of our container
	 *
	 * @param  GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function generate( GF_Field $field ) {

		/* Check if we are processing a field that should not be floated and treat it as a 100% field */
		$this->process_skipped_fields( $field );

		/* Check if we need to close the container */
		if ( $this->currently_open ) {
			$this->handle_open_container( $field );
		}

		/* Open the tag if not currently opened*/
		if ( ! $this->currently_open ) {
			$this->handle_closed_container( $field );
		}
	}

	/**
	 * Close the current container if still open.
	 * This is usually called publically after the form loop
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function close() {
		if ( $this->currently_open ) {
			$this->close_container();
			$this->reset();
		}
	}

	/**
	 * Will check if the current field will fit in the open row, or if a new row needs to be open
	 * to accomidate the field.
	 *
	 * @param GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return boolean
	 *
	 * @since 4.0
	 */
	public function does_fit_in_row( GF_Field $field ) {

		if ( true === $this->currently_open ) {
			$width = $this->get_field_width( $field->cssClass ); /* current field width */

			/* Check if the new field will fit in the row */
			if ( 100 >= ( $this->current_width + $width ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Output placeholder HTML if empty or hidden field is part of CSS Ready Class columns
	 * and the row is currently open and the field will fit in that row without opening another row
	 *
	 * @param GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return void
	 */
	public function maybe_display_faux_column( GF_Field $field ) {

		/* Check if we should create a placeholder column */
		if ( $this->does_fit_in_row( $field ) ) {
			echo '<div id="field-' . $field->id . '" class="gfpdf-column-placeholder gfpdf-field ' . $field->cssClass . '"></div>';

			/* Increase column width */
			$this->increment_width( $field->cssClass );
		}
	}

	/**
	 * Open the container
	 *
	 * @param  GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function handle_closed_container( GF_Field $field ) {
		$this->start();
		$this->open_container();
		$this->increment_width( $field->cssClass );
	}

	/**
	 * Determine if we should close a container based on its classes
	 *
	 * @param  GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function handle_open_container( GF_Field $field ) {

		/* if the current field width is more than 100 we will close the container */
		if ( false === $this->does_fit_in_row( $field ) ) {
			$this->close();
		} else {
			$this->increment_width( $field->cssClass );
		}
	}

	/**
	 * Process our skipped Gravity Form fields (close the container if needed)
	 *
	 * @param  GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return boolean true if we processed a skipped field, false otherwise
	 *
	 * @since 4.0
	 */
	private function process_skipped_fields( GF_Field $field ) {
		/* if we have a skipped field and the container is open we will close it */
		if ( in_array( $field->type, $this->skip_fields ) ) {
			$this->strip_field_of_any_classmaps( $field );
			$this->close();

			return true;
		}

		return false;
	}

	/**
	 * Remove any mapped classes from our skipped fields
	 *
	 * @param  GF_Field $field The Gravity Form field currently being processed
	 *
	 * @return void
	 *
	 * @since  4.0
	 */
	private function strip_field_of_any_classmaps( GF_Field $field ) {
		$field->cssClass = str_replace( array_keys( $this->class_map ), ' ', $field->cssClass );
	}

	/**
	 * Output the open tag
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function open_container() {

		$class = $this->is_row_odd_or_even();
		echo str_replace( 'row-separator', 'row-separator ' . $class, $this->open_tag );

		$this->increment_row_counter();
	}

	/**
	 * Output the close tag
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function close_container() {
		echo $this->close_tag;
	}

	/**
	 * Mark our class as currently being open
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function start() {
		$this->currently_open = true;
	}

	/**
	 * Reset our class back to its original state
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function reset() {
		$this->currently_open = false;
		$this->current_width  = 0;
	}

	/**
	 * Increment our current field width
	 *
	 * @param string $classes The field classes
	 *
	 * @return void
	 *
	 * @since  4.0
	 */
	private function increment_width( $classes ) {
		$this->current_width += $this->get_field_width( $classes );
	}

	/**
	 * Loop through all classes and return our class map if found, or 100
	 *
	 * @param  String $classes The field classes
	 *
	 * @return integer The field width based on assigned class
	 *
	 * @since  4.0
	 */
	private function get_field_width( $classes ) {
		$classes = explode( ' ', $classes );

		foreach ( $classes as $class ) {
			if ( isset ( $this->class_map[ $class ] ) ) {
				/* return field width */
				return $this->class_map[ $class ];
			}
		}

		/* no match, so assuming full width */

		return 100;
	}

	/**
	 * Checks if the row counter is currently odd or even
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	private function is_row_odd_or_even() {
		return ( $this->counter % 2 ) ? 'even' : 'odd';
	}

	/**
	 * Increases the internal row counter
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	private function increment_row_counter() {
		$this->counter++;
	}
}
