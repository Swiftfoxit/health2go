<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 2:29 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'JB_Event_Admin' ) ) {
	class JB_Event_Admin {

		public function __construct() {
			add_filter( 'jobboard_admin_sections', array( $this, 'add_admin_sections' ) );
		}

		public function add_admin_sections( $sections ) {
			$sections['page-setting']['fields'][] = array(
				'id'       => 'page-events',
				'type'     => 'select',
				'data'     => 'pages',
				'title'    => esc_html__( 'Events', JB_EVENT_TEXT_DOMAIN ),
				'subtitle' => esc_html__( 'Page for Event listing.', JB_EVENT_TEXT_DOMAIN ),
				'desc'     => esc_html__( '(search, archive, taxonomy, location, tags)', JB_EVENT_TEXT_DOMAIN ),
			);

			return $sections;
		}

	}
}