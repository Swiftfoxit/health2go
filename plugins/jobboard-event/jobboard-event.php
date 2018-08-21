<?php

/*
Plugin Name: Jobboard Event
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0.0
Author: Quan, KP
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
Text Domain: jobboard-event
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define('JB_EVENT_TEXT_DOMAIN','jobboard');

if ( ! class_exists( 'JB_Event' ) ) {
	class JB_Event {
		public static $instance = null;

		public $file;
		public $basename;
		public $plugin_directory;
		public $plugin_directory_uri;

		public $package;
		public $form;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new JB_Event();
				self::$instance->setup_globals();

				if ( ! function_exists( 'is_plugin_active' ) ) {
					require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				}

				if ( is_plugin_active( 'jobboard/jobboard.php' ) ) {
					self::$instance->includes();
					self::$instance->actions();
				}
			}

			return self::$instance;
		}

		private function setup_globals() {
			$this->file                 = __FILE__;
			$this->basename             = plugin_basename( $this->file );
			$this->plugin_directory     = plugin_dir_path( $this->file );
			$this->plugin_directory_uri = plugin_dir_url( $this->file );
		}

		function get_template( $template_name, $args = array() ) {
			jb_get_template( $template_name, $args, JB()->template_path() . 'add-ons/event/', $this->plugin_directory . 'templates/' );
		}

		private function includes() {
			require_once 'inc/functions-core.php';
			require_once 'inc/template-functions.php';
			require_once 'inc/template-hooks.php';
			require_once 'inc/class.je-post.php';
			require_once 'inc/class.je-template.php';
			require_once 'inc/class.je-admin.php';
			require_once 'inc/class.enqueue-scripts.php';
			new JB_Event_Post();
			new JB_Event_Template();
			new JB_Event_Admin();

		}

		private function actions() {
//			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
		}

		public function plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( $plugin_file !== plugin_basename( __FILE__ ) ) {
				return $plugin_meta;
			}

			$plugin_meta[] = '<a href="https://github.com/vanquan805">' . esc_html__( 'GitHub', 'jobboard-event' ) . '</a>';
			$plugin_meta[] = '<a href="http://fsflex.com/support/" title="' . esc_html__( 'Support forum.', 'jobboard-event' ) . '">' . esc_html__( 'Support', 'jobboard-event' ) . '</a>';
			$plugin_meta[] = '<a href="mailto:vanquan805@gmail.com" title="' . esc_html__( 'Send a email to Dev team.', 'jobboard-event' ) . '">' . esc_html__( 'Contact', 'jobboard-event' ) . '</a>';

			return $plugin_meta;

		}

		function template_path() {
			return apply_filters( 'je/template/path', 'jobboard/add-ons/event/' );
		}
	}
}

if ( ! function_exists( 'jb_event' ) ) {
	function jb_event() {
		return JB_Event::instance();
	}

	$GLOBALS['jobboard_event'] = jb_event();
}