<?php
vc_map(array(
    "name" => 'CMS Client',
    "base" => "cms_client",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', "wp-recruitment"),
    "params" => array(
        array(
            'type' => 'param_group',
            "heading" => __ ( 'Clients', 'wp-recruitment' ),
            'value' => '',
            'param_name' => 'client',
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'value' => '',
                    'heading' => 'Image',
                    'param_name' => 'image',
                ),
                array(
                    'type' => 'vc_link',
                    'value' => '',
                    'heading' => 'Link',
                    'param_name' => 'link',
                ),
            ),
        ),
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
            'group' => 'Carousel Settings',
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
            'group' => 'Carousel Settings',
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
            'group' => 'Carousel Settings',
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
            'group' => 'Carousel Settings',
        ),
        array(
            'type'       => 'textfield',
            'heading'    => esc_html__('Margin Items', 'wp-recruitment'),
            'param_name' => 'margin',
            'value'      => '',
            'group' => 'Carousel Settings',
            'description' => 'Enter number: ...( Default 30 )',
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Loop Items", 'wp-recruitment'),
            "param_name" => "loop",
            "value" => array(
                "No" => "0",
                "Yes" => "1",
            ),
            "group" => 'Carousel Settings',
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Auto Play", 'wp-recruitment'),
            "param_name" => "autoplay",
            "value" => array(
                "No" => "0",
                "Yes" => "1",
            ),
            "group" => 'Carousel Settings',
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Show Nav", 'wp-recruitment'),
            "param_name" => "nav",
            "value" => array(
                "Yes" => "1",
                "No" => "0",
            ),
            "group" => 'Carousel Settings',
        ),
    )
));

class WPBakeryShortCode_cms_client extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        wp_enqueue_style('owl-carousel',get_template_directory_uri() . '/assets/css/owl.carousel.css','','2.0.0b','all');
        wp_enqueue_script('owl-carousel',get_template_directory_uri() . '/assets/js/owl.carousel.min.js',array('jquery'),'2.0.0b',true);
        wp_enqueue_script('cms-jobs', get_template_directory_uri() . '/inc/elements/jobboard/cms-jobs.js', array('owl-carousel'), '1.0.0', true);
        
        return parent::content($atts, $content);
    }
}

?>