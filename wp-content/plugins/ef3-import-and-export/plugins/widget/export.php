<?php
/**
 * Export Functions
 *
 * @package    Widget_Importer_Exporter
 * @subpackage Functions
 * @copyright  Copyright (c) 2013, DreamDolphin Media, LLC
 * @link       https://github.com/stevengliebe/widget-importer-exporter
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since      0.1
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generate export data
 *
 * @since 0.1
 * @return string Export file contents
 */
function ef3_widgets_generate_export_data()
{

	// Get all available widgets site supports
	$available_widgets = ef3_available_widgets();

	// Get all widget instances for each widget
	$widget_instances = array();
	foreach ($available_widgets as $widget_data) {

		// Get all instances for this ID base
		$instances = get_option('widget_' . $widget_data['id_base']);

		// Have instances
		if (!empty($instances)) {

			// Loop instances
			foreach ($instances as $instance_id => $instance_data) {

				// Key is ID (not _multiwidget)
				if (is_numeric($instance_id)) {
					$unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
					$widget_instances[$unique_instance_id] = $instance_data;
				}

			}

		}

	}

	// Gather sidebars with their widget instances
	$sidebars_widgets = get_option('sidebars_widgets'); // get sidebars and their unique widgets IDs
	$sidebars_widget_instances = array();
    $data = array();
	foreach ($sidebars_widgets as $sidebar_id => $widget_ids) {

		// Skip inactive widgets
		if ('wp_inactive_widgets' == $sidebar_id) {
			continue;
		}

		// Skip if no data or not an array (array_version)
		if (!is_array($widget_ids) || empty($widget_ids)) {
			continue;
		}

		// Loop widget IDs for this sidebar
		foreach ($widget_ids as $widget_id) {

			// Is there an instance for this widget ID?
			if (isset($widget_instances[$widget_id])) {

				// Add to array
				$sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];

                if(strpos($widget_id, 'nav_menu-') !== false) {
                    $menu = wp_get_nav_menu_object($widget_instances[$widget_id]['nav_menu']);
                    $data['nav_menu'][$widget_id] = $menu->slug;
                }

			}

		}

	}

	// Filter pre-encoded data
	$data['sidebars'] = apply_filters('ef3_unencoded_export_data', $sidebars_widget_instances);

	// Return contents
	return apply_filters('ef3_generate_export_data', $data);

}

/**
 * Send export file to user
 *
 * Triggered by URL like /wp-admin/tools.php?page=widget-importer-exporter&export=1
 *
 * The data is JSON with .wie extension in order not to confuse export files with other plugins.
 *
 * @since 0.1
 */
function ef3_widgets_save_export_file($part)
{
	global $wp_filesystem;

	// Generate export file contents
	$file_contents = ef3_widgets_generate_export_data();

	$wp_filesystem->put_contents($part . 'widgets.json', json_encode($file_contents['nav_menu']), FS_CHMOD_FILE);
	$wp_filesystem->put_contents($part . 'widgets.wie', json_encode($file_contents['sidebars']), FS_CHMOD_FILE);
}