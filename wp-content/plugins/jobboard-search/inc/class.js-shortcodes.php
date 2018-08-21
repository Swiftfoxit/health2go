<?php
/**
 * JB_Search_Shortcodes class
 *
 * @class       JB_Search_Shortcodes
 * @version     1.0.0
 * @package     JobBoard/Search
 * @category    Class
 * @author      FOX
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!class_exists('JB_Search_Shortcodes')):

    class JB_Search_Shortcodes {

        function __construct() {
            add_shortcode( 'jobboard-search' , array($this, 'shortcodes_search') );
            add_action( 'vc_before_init', array($this, 'vc'));
        }

        function shortcodes_search($atts = array()){
            $default = shortcode_atts(array(
                'layout' => 'layout1',
                'box_color' => '',
                'search_type' => 'hidden',
                'search_specialism' => 'hidden',
                'search_locations' => 'show',
                'similar_keywords' => 'hidden',
            ), $atts);
            if ($atts == null) {
                $atts = $default;
            } else {
                $atts = array_merge($default, $atts);
            }
            ob_start();
            jb_search_form($atts);
            return ob_get_clean();
        }

        function vc(){
            vc_map( array(
                "name" => __( "JobBoard Search", "jobboard-search" ),
                "base" => "jobboard-search",
                "category" => __( "JobBoard", "jobboard-search"),
                "params" => array(
                    array(
                        "type" => "dropdown",
                        "heading" => esc_html__("Layout", JB_SEARCH_TEXT_DOMAIN),
                        "param_name" => "layout",
                        "value" => array(
                            "Layout 1" => "layout1",
                            "Layout 2" => "layout2",
                        ),
                    ),
                    array(
                        "type" => "colorpicker",
                        "heading" => esc_html__("Box Background Color",JB_SEARCH_TEXT_DOMAIN),
                        "param_name" => "box_color",
                        "value" => "",
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => esc_html__("Search Type", JB_SEARCH_TEXT_DOMAIN),
                        "param_name" => "search_type",
                        "value" => array(
                            "Hidden" => "hidden",
                            "Show" => "show",
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => esc_html__("Search Specialism", JB_SEARCH_TEXT_DOMAIN),
                        "param_name" => "search_specialism",
                        "value" => array(
                            "Hidden" => "hidden",
                            "Show" => "show",
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => esc_html__("Search Locations", JB_SEARCH_TEXT_DOMAIN),
                        "param_name" => "search_locations",
                        "value" => array(
                            "Show" => "show",
                            "Hidden" => "hidden",
                        ),
                    ),
                    array(
                        "type" => "dropdown",
                        "heading" => esc_html__("Similar Keywords", JB_SEARCH_TEXT_DOMAIN),
                        "param_name" => "similar_keywords",
                        "value" => array(
                            "Hidden" => "hidden",
                            "Show" => "show",
                        ),
                    ),
                )
            ) );
        }
    }

endif;

new JB_Search_Shortcodes();