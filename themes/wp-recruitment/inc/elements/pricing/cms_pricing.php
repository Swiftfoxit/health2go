<?php
vc_map(array(
    "name" => 'CMS Pricing',
    "base" => "cms_pricing",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', 'wp-recruitment'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __ ( 'Title', 'wp-recruitment' ),
            "param_name" => "title_pricing",
            "value" => '',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment')
        ),
        array(
            "type" => "textfield",
            "heading" => __ ( 'Currency', 'wp-recruitment' ),
            "param_name" => "currency_type",
            "value" => '',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment')
        ),
        array(
            "type" => "textfield",
            "heading" => __ ( 'Price', 'wp-recruitment' ),
            "param_name" => "values_price",
            "value" => '',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment')
        ),
        array(
            "type" => "textfield",
            "heading" => __ ( 'Time', 'wp-recruitment' ),
            "param_name" => "pricing_time",
            "value" => 'Month',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment'),
            "description" => 'Week, Month, Year',
        ),
        array(
            'type' => 'param_group',
            "heading" => __ ( 'List Description', 'wp-recruitment' ),
            'value' => '',
            'param_name' => 'description_pricing',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'value' => '',
                    'heading' => 'Enter your description(multiple field)',
                    'param_name' => 'description_item',
                ),
            ),
            "group" => esc_html__("Pricing Settings", 'wp-recruitment'),
        ),
        array(
            "type" => "textfield",
            "heading" => __ ( 'Text Button', 'wp-recruitment' ),
            "param_name" => "text_button",
            "value" => '',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment')
        ),
        array(
            "type" => "vc_link",
            "heading" => __ ( 'Link Button', 'wp-recruitment' ),
            "param_name" => "link_button",
            "value" => '',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment')
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Main Color",'wp-recruitment'),
            "param_name" => "main_color",
            "value" => "",
            "group" => esc_html__("Pricing Settings", 'wp-recruitment')
        ),  
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Popular Item", 'wp-recruitment'),
            "param_name" => "popular_item",
            "value" => array(
                'No' => 'no-popular',
                'Yes' => 'is-popular',
            ),   
            "group" => esc_html__("Pricing Settings", 'wp-recruitment'), 
        ),
        array(
            "type" => "textfield",
            "heading" => __ ( 'Popular Text', 'wp-recruitment' ),
            "param_name" => "popular_text",
            "value" => '',
            "group" => esc_html__("Pricing Settings", 'wp-recruitment'),
            'dependency' => array(
                'element' => 'popular_item',
                'value' => 'is-popular',
            ),
        ),
    )
));

class WPBakeryShortCode_cms_pricing extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}

?>