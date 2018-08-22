<?php
/**
 * JB_Map_Shortcodes class
 *
 * @class       JB_Map_Shortcodes
 * @version     1.0.0
 * @package     JobBoard/Map
 * @category    Class
 * @author      FOX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'JB_Map_Shortcodes' ) ):

	class JB_Map_Shortcodes {

		function __construct() {
			add_action( 'vc_before_init', array( $this, 'vc' ) );
			add_shortcode( 'jobboard-shortcode-map', array( $this, 'shortcodes_map' ) );
			add_shortcode( 'jobboard-shortcode-map-2', array( $this, 'shortcodes_map_2' ) );

		}

		function shortcodes_map() {

			jb_map()->add_map_scripts();
			jb_map()->add_jobs_scripts();

			ob_start();

			jb_map()->get_template( 'map.php' );

			return ob_get_clean();
		}

		public function shortcodes_map_2() {
			jb_map()->add_map_scripts();
			jb_map()->add_event_script();

			ob_start();

			jb_map()->get_template( 'map.php' );

			return ob_get_clean();
		}

		function vc() {
			vc_map(
				array(
					"name"                    => __( "Live Map", "jobboard-map" ),
					"base"                    => "jobboard-shortcode-map",
					"category"                => __( "JobBoard", "jobboard-map" ),
					"show_settings_on_create" => false
				),
				array(
					"name"                    => __( "Map", "jobboard-map" ),
					"base"                    => "jobboard-shortcode-map-2",
					"category"                => __( "JobBoard", "jobboard-map" ),
					"show_settings_on_create" => false
				)
			);
		}


	}

endif;

new JB_Map_Shortcodes();

