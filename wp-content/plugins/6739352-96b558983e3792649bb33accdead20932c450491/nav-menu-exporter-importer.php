<?php
/*
Plugin Name: Nav Menu Exporter and Importer
Description: Export and Import nav menus. Requires WordPress Importer plugin
Author: hissy, megumi themes
Version: 0.1
Text Domain: nav-menu-exporter-importer
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


/**
 * Exporter
 */
function add_mav_menu_to_export() {
	$post_type = get_post_type_object('nav_menu_item');
	?>
	<p><label><input type="radio" name="content" value="<?php echo esc_attr( $post_type->name ); ?>" /> <?php echo esc_html( $post_type->label ); ?></label></p>
	<?php
}
add_action('export_filters','add_mav_menu_to_export');


/**
 * Importer
 */
if ( ! defined( 'WP_LOAD_IMPORTERS' ) )
	return;

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require $class_wp_importer;
}

// include WXR file parsers
$wordpress_importer = ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php';
if ( file_exists( $wordpress_importer ) )
	require_once $wordpress_importer;

/**
 * Nav Menu Importer class
 */
if ( class_exists( 'WP_Import' ) ) {
class Nav_Menu_Importer extends WP_Import {

	function dispatch() {
		$this->header();

		$step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step'];
		switch ( $step ) {
			case 0:
				$this->greet();
				break;
			case 1:
				check_admin_referer( 'import-upload' );
				if ( $this->handle_upload() ) {
					$file = get_attached_file( $this->id );
					set_time_limit(0);
					$this->import( $file );
				}
				break;
		}

		$this->footer();
	}
	
	function greet() {
		wp_import_upload_form( add_query_arg('step', 1) );
	}
	
	function import_end() {
		wp_import_cleanup( $this->id );

		wp_cache_flush();

		echo '<p>' . __( 'All done.', 'wordpress-importer' ) . ' <a href="' . admin_url() . '">' . __( 'Have fun!', 'wordpress-importer' ) . '</a>' . '</p>';
	}
	
	function import( $file ) {
		$this->import_start( $file );

		wp_suspend_cache_invalidation( true );
		// only processing nav menus
		$this->process_menus();
		wp_suspend_cache_invalidation( false );

		$this->import_end();
	}
	
	function process_menus() {
		$count = 0;
		
		foreach ( $this->posts as $item ) {
			$count++;

			// check the item is public nav item
			if ( 'nav_menu_item' == $item['post_type'] && 'draft' != $item['status'] ) {
				
				$menu_slug = false;
				if ( isset($item['terms']) ) {
					// loop through terms, assume first nav_menu term is correct menu
					foreach ( $item['terms'] as $term ) {
						if ( 'nav_menu' == $term['domain'] ) {
							$menu_slug = $term['slug'];
							break;
						}
					}
				}

				// no nav_menu term associated with this menu item
				if ( ! $menu_slug ) {
					_e( 'Menu item skipped due to missing menu slug', 'wordpress-importer' );
					echo '<br>';
					continue;
				}
		
				$menu_id = term_exists( $menu_slug, 'nav_menu' );
				if ( ! $menu_id ) {
					printf( __( 'Menu item skipped due to invalid menu slug: %s', 'wordpress-importer' ), esc_html( $menu_slug ) );
					echo '<br>';
					continue;
				} else {
					$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
				}
				
				// set postmeta
				foreach ( $item['postmeta'] as $meta )
					$$meta['key'] = $meta['value'];
				
				// skip nav item when menu item object is not exists
				switch ($_menu_item_type) {
					case 'taxonomy':
						$_menu_item_object_id = get_term($_menu_item_object_id,$_menu_item_object);
						if ($_menu_item_object_id == null || is_wp_error($_menu_item_object_id)) {
							printf( __( 'Menu item skipped due to %s is not exists', 'nav-menu-exporter-importer' ), esc_html( $_menu_item_object ) );
							echo '<br>';
						}
						break;
					case 'post_type':
						$_menu_item_object_id = get_post($_menu_item_object_id);
						if ($_menu_item_object_id instanceof WP_Post) {
							$_menu_item_object_id = $_menu_item_object_id->ID;
							unset($_post);
						} else {
							printf( __( 'Menu item skipped due to %s is not exists', 'nav-menu-exporter-importer' ), esc_html( $_menu_item_object ) );
							echo '<br>';
						}
						break;
				}
				
				if ($_menu_item_object_id == null || is_wp_error($_menu_item_object_id))
					continue;
				
				// wp_update_nav_menu_item expects CSS classes as a space separated string
				$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
				if ( is_array( $_menu_item_classes ) )
					$_menu_item_classes = implode( ' ', $_menu_item_classes );

				$args = array(
					'menu-item-object-id' => $_menu_item_object_id,
					'menu-item-object' => $_menu_item_object,
					'menu-item-parent-id' => $_menu_item_menu_item_parent,
					'menu-item-position' => intval( $item['menu_order'] ),
					'menu-item-type' => $_menu_item_type,
					'menu-item-title' => $item['post_title'],
					'menu-item-url' => $_menu_item_url,
					'menu-item-description' => $item['post_content'],
					'menu-item-attr-title' => $item['post_excerpt'],
					'menu-item-target' => $_menu_item_target,
					'menu-item-classes' => $_menu_item_classes,
					'menu-item-xfn' => $_menu_item_xfn,
					'menu-item-status' => $item['status']
				);
		
				$r = wp_update_nav_menu_item( $menu_id, 0, $args );
				
				if ( $r && is_wp_error( $r ) ) {
					echo $r->get_error_message();
					echo '<br>';
				} else {
					echo '.';
				}
				
			}
			
		}
		
		echo '<br>';
		printf( __( '%s items processed.', 'nav-menu-exporter-importer' ), esc_html( $count ) );
	}

}

// setup importer
$nav_menu_importer = new Nav_Menu_Importer();

register_importer('nav_menu', __('Nav Menu', 'nav-menu-exporter-importer'), __('Export and Import nav menus. Requires WordPress Importer plugin', 'nav-menu-exporter-importer'), array ($nav_menu_importer, 'dispatch'));

} // class_exists( 'WP_Import' )
