<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 10:58 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'JB_Event_Template' ) ) {
	class JB_Event_Template {
		public function __construct() {
			add_filter( 'template_include', array( $this, 'template_loader' ) );
		}

		function template_loader( $template ) {
			if ( is_post_type_archive( 'jb-events' ) ) {
				$file   = 'archive-event.php';
				$find[] = $file;
				$find[] = JB_Event()->template_path() . $file;
			}

			if ( is_single() && is_jb_event() ) {
				$file   = 'single-event.php';
				$find[] = $file;
				$find[] = JB_Event()->template_path() . $file;
			}

			if ( isset( $file ) ) {
				$template = locate_template( array_unique( $find ) );
				if ( ! $template ) {
					$template = JB_Event()->plugin_directory . 'templates/' . $file;
				}
			}

			return apply_filters( 'jobboard_event_template_include', $template );
		}
	}
}