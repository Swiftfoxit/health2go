<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 9/27/2017
 * Time: 9:44 AM
 */

if ( function_exists( 'vc_map' ) && function_exists( 'jb_get_country' ) ) {

	$value    = array();
    foreach (jb_get_country() as $key => $name){
        $value[$name] = $key;
    }
	$value[esc_html__( 'All Countries', "wp-recruitment" )] = '0';
	$value[esc_html__( 'All Locations', "wp-recruitment" )] = 'all';

	vc_map( array(
		"name"     => esc_html__( 'Jobs by Location', "wp-recruitment" ),
		"base"     => "cms_locations",
		"icon"     => "cs_icon_for_vc",
		"category" => esc_html__( 'JobBoard', "wp-recruitment" ),
		"params"   => array(
			array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Layout Type", 'wp-recruitment'),
	            "param_name" => "layout_type",
	            "value" => array(
	                "Carousel" => "carousel",
	                "Grid" => "grid",
	            ),
	        ),

			array(
				"type"        => "dropdown",
				"heading"     => esc_html__( "Country", "wp-recruitment" ),
				"param_name"  => "country",
				"admin_label" => true,
				"value"       => $value,
				"description" => __( "Choose a country.", "wp-recruitment" ),
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Count", "wp-recruitment" ),
				"param_name"  => "count",
				"value"       => 4,
				"description" => __( "Enter count of locatons.", "wp-recruitment" )
			),
			array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Location Child List", 'wp-recruitment'),
	            "param_name" => "location_child",
	            "value" => array(
	            	"Hidden" => "hidden",
	                "Show" => "show",
	            ),
	            'dependency' => array(
	                'element' => 'country',
	                'value' => '0',
	            ),
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => esc_html__("Location Child Limit", 'wp-recruitment'),
	            "param_name" => "location_child_limit",
	            'dependency' => array(
	                'element' => 'location_child',
	                'value' => 'show',
	            ),
	            'description' => 'Default: 8'
	        ),
			array(
	            "type" => "dropdown",
	            "heading" => esc_html__("View All Locations", 'wp-recruitment'),
	            "param_name" => "view_all",
	            "value" => array(
	                "Show" => "show",
	                "Hidden" => "hidden",
	            ),
	        ),
			/* Carousel Settings */
			array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Columns XS Devices", 'wp-recruitment'),
	            "param_name" => "col_xs",
	            "edit_field_class" => "vc_col-sm-3",
	            "value" => array(
	                "Default" => "1",
	                "1" => "1",
	                "2" => "2",
	                "3" => "3",
	                "4" => "4",
	            ),
	            "std" => '',
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Columns SM Devices", 'wp-recruitment'),
	            "param_name" => "col_sm",
	            "edit_field_class" => "vc_col-sm-3",
	            "value" => array(
	                "Default" => "2",
	                "1" => "1",
	                "2" => "2",
	                "3" => "3",
	                "4" => "4",
	            ),
	            "std" => '',
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Columns MD Devices", 'wp-recruitment'),
	            "param_name" => "col_md",
	            "edit_field_class" => "vc_col-sm-3",
	            "value" => array(
	                "Default" => "3",
	                "1" => "1",
	                "2" => "2",
	                "3" => "3",
	                "4" => "4",
	            ),
	            "std" => '',
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Columns LG Devices", 'wp-recruitment'),
	            "param_name" => "col_lg",
	            "edit_field_class" => "vc_col-sm-3",
	            "value" => array(
	                "Default" => "4",
	                "1" => "1",
	                "2" => "2",
	                "3" => "3",
	                "4" => "4",
	                "5" => "5",
	                "6" => "6",
	            ),
	            "std" => '',
	        ),
	        array(
	            'type'       => 'textfield',
	            'heading'    => esc_html__('Margin Items', 'wp-recruitment'),
	            'param_name' => 'margin',
	            'value'      => '',
	            'description' => 'Enter number: ...( Default 30 )',
	            'dependency' => array(
	                'element' => 'layout_type',
	                'value' => 'carousel',
	            ),
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Loop Items", 'wp-recruitment'),
	            "param_name" => "loop",
	            "value" => array(
	                "No" => "0",
	                "Yes" => "1",
	            ),
	            'dependency' => array(
	                'element' => 'layout_type',
	                'value' => 'carousel',
	            ),
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Auto Play", 'wp-recruitment'),
	            "param_name" => "autoplay",
	            "value" => array(
	                "No" => "0",
	                "Yes" => "1",
	            ),
	            'dependency' => array(
	                'element' => 'layout_type',
	                'value' => 'carousel',
	            ),
	        ),
		)
	) );
}
class WPBakeryShortCode_cms_locations extends CmsShortCode
{

    protected function content($atts, $content = null)
    {

        $new_atts = shortcode_atts(array(
            'country'         => '',
            'count'         => '',
            'col_xs'         => '1',
            'col_sm'         => '2',
            'col_md'         => '3',
            'col_lg'         => '4',
            'nav'         => '1',
            'autoplay'         => '0',
            'loop'         => '0',
            'margin'         => '30',
        ),$atts);
        $atts = array_merge($new_atts,$atts);

        wp_enqueue_style('owl-carousel',get_template_directory_uri() . '/assets/css/owl.carousel.css','','2.0.0b','all');
        wp_enqueue_script('owl-carousel',get_template_directory_uri() . '/assets/js/owl.carousel.min.js',array('jquery'),'2.0.0b',true);
        wp_enqueue_script('cms-jobs', get_template_directory_uri() . '/inc/elements/jobboard/cms-jobs.js', array('owl-carousel'), '1.0.0', true);

        return parent::content($atts, $content);
    }
}