<?php
/**
 * JobBoard Similar Admin.
 *
 * Action/filter hooks used for JobBoard Similar admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Similar/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Similar_Admin')) :
    class JB_Similar_Admin{

        function __construct()
        {
            add_filter('jobboard_admin_sections', array($this, 'sections_admin'));
        }

        function sections_admin($sections){
            $sections['search-settings']['fields'][] = array(
                'id'       => 'search-similar',
                'type'     => 'switch',
                'title'    => esc_html__( 'Similar Keywords', JB_SIMILAR_TEXT_DOMAIN ),
                'subtitle' => esc_html__( 'Enable trending search.', JB_SIMILAR_TEXT_DOMAIN ),
                'default'  => true,
            );

            return $sections;
        }
    }
endif;

new JB_Similar_Admin();