<?php
/*
Plugin Name: CSS Above The Fold
Plugin URI: http://blogestudio.com
Description: Faster CSS browser rendering displaying selected fragments of your theme stylesheet file directly into the head section.
Version: 1.0
Author: Pau Iglesias, Blogestudio
License: GPLv2 or later
*/


// Avoid script calls via plugin URL
if (!function_exists('add_action'))
	die;

// Quick context check
if (is_admin())
	return;


/**
 * CSS Above The Fold plugin class
 *
 * @package WordPress
 * @subpackage CSS Above The Fold
 */

// Avoid declaration plugin class conflicts
if (!class_exists('BE_CSS_Above_The_Fold')) {
	
	// Create object plugin
	add_action('wp_head', array('BE_CSS_Above_The_Fold', 'wp_head'), 0);

	// Main class
	class BE_CSS_Above_The_Fold {



		/**
		 * WP head hook css
		 */
		public static function wp_head() {
			
			// Initialize
			$refresh = true;
			$path = STYLESHEETPATH.'/style.css';
			
			// Check saved value
			$css = get_option('bs_css_atf');
			if (!empty($css)) {
				$refresh = false;
				$t = (int) get_option('bs_css_atf_timestamp');
				if (!empty($t) && file_exists($path)) {
					$tm = @filemtime($path);
					if (empty($tm) || $tm != $t)
						$refresh = true;
				}
			}
			
			// Print
			if (!$refresh) {
				self::print_css($css);
			
			// Process
			} else {
				
				// Load file
				$css = file_get_contents($path);
				if (!empty($css)) {
					
					// Extract
					$start = 0;
					$inline = '';
					while (false !== ($fragment = self::get_fragment($css, $start, $end))) {
						$start = $end;
						$inline .= str_replace('*/', '', str_replace('/*', '', preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $fragment.' */')));
					}
					
					// Check content
					$compressed = '';
					if (!empty($inline)) {
						
						// Filter before compress with replaced background URL paths
						$inline = apply_filters('be_css_atf_css', self::replace_background_url($inline));
						
						// Compress entire CSS
						$compressed = apply_filters('be_css_atf_css_compressed', self::compress($inline));
					}
					
					// Save compressed css and style file datetime
					update_option('bs_css_atf', $compressed);
					update_option('bs_css_atf_timestamp', @filemtime($path));
					
					// And print
					if (!empty($compressed))
						self::print_css($compressed);
				}
			}
		}



		/**
		 * Print stylesheet in page
		 */
		private static function print_css($css) {
			echo "\n".apply_filters('be_css_atf_style_open', '<style>').str_ireplace('</style>', '', $css).apply_filters('be_css_atf_style_close', '</style>')."\n";
		}



		/**
		 * Extract a [css-above-the-fold] fragment from a given position
		 */
		private static function get_fragment($css, $start, &$end) {
			
			// Open tag
			if (false !== ($pos1 = stripos($css, '[css-above-the-fold]', $start))) {
				
				// Close tag
				if (false !== ($pos2 = stripos($css, '[/css-above-the-fold]', $pos1))) {
					
					// Adjust position
					$pos1 += 20;
					
					// Extract fragment
					$fragment = trim(substr($css, $pos1, $pos2 - $pos1));
					
					// Fix close comment mark without open
					if (false !== ($pos3 = strpos($fragment, '*/'))) {
						if (false === strpos(substr($fragment, 0, $pos3), '/*'))
							$fragment = substr($fragment, $pos3 + 2);
					}
					
					// Next position
					$end = $pos2 + 21;
					
					// Done
					return $fragment;
				}
			}
			
			// End
			return false;
		}



		/**
		 * Replaces background URL to root path
		 */
		private static function replace_background_url($css) {
			
			// Prepare URL root path
			$root = @parse_url(get_stylesheet_directory_uri());
			$root = empty($root['path'])? '/' : rtrim($root['path'], '/').'/';
			
			// Clear out whitespace and quotes
			$css = preg_replace('/\(\s+/', '(', $css);
			$css = preg_replace('/\(\s+/', '(', $css);
			$css = preg_replace('/(?:\'|\")/', '', $css);
			
			// Prepend the path to lines that do not have a "//" anywhere
			$css = preg_replace('/(url\((?!.*\/\/))/i', '$1'.$root, $css);
			
			// Done
			return $css;
		}



		/**
		 * Compress CSS
		 */
		private static function compress($css) {
			$css = preg_replace('!\s+!', ' ', trim($css));
			$css = str_replace(array("\r\n", "\r", "\n", "\t"), '', trim($css));
			$css = str_replace('{ ', '{', $css);
			$css = str_replace(' {', '{', $css);
			$css = str_replace(' }', '}', $css);
			$css = str_replace('} ', '}', $css);
			$css = str_replace(';}', '}', $css);
			$css = str_replace(': ', ':', $css);
			$css = str_replace(' :', ':', $css);
			$css = str_replace('; ', ';', $css);
			$css = str_replace(', ', ',', $css);
			return $css;
		}



	}
}