<?php
/**
 * @Template: class.jr-admin.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 12-Dec-17
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}
if ( ! class_exists( 'JB_Resources_Admin' ) ) {
    class JB_Resources_Admin {

        public function __construct() {
            add_filter( 'jobboard_admin_sections', array( $this, 'add_admin_sections' ) );
        }

        public function add_admin_sections( $sections ) {
            $sections['page-setting']['fields'][] = array(
                'id'       => 'page-resources',
                'type'     => 'select',
                'data'     => 'pages',
                'title'    => esc_html__( 'Recruitment Resources', JB_RESOURCES_TEXT_DOMAIN ),
                'subtitle' => esc_html__( 'Page for Recruitment resource listing.', JB_RESOURCES_TEXT_DOMAIN ),
            );

            return $sections;
        }

    }
}