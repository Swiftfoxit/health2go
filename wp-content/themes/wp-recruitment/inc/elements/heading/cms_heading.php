<?php
vc_map(array(
    "name" => 'CMS Heading',
    "base" => "cms_heading",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', "wp-recruitment"),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__( 'Sub Title', "wp-recruitment" ),
            "param_name" => "hd_subtitle",
            "value" => '',
            "group" => esc_html__("Heading Settings", "wp-recruitment"),
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_heading--layout1.php",
                )
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( 'Title', "wp-recruitment" ),
            "param_name" => "hd_title",
            "value" => '',
            "group" => esc_html__("Heading Settings", "wp-recruitment")
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Title Color","wp-recruitment"),
            "param_name" => "title_color",
            "value" => "",
            "group" => esc_html__("Heading Settings", "wp-recruitment"),
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_heading.php",
                )
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( 'Title Font Size', "wp-recruitment" ),
            "param_name" => "hd_title_font_size",
            "value" => '',
            "group" => esc_html__("Heading Settings", "wp-recruitment"),
            "description" => "Enter: ..px",
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_heading.php",
                )
            ),
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Content Align", "wp-recruitment"),
            "admin_label" => true,
            "param_name" => "content_align",
            "value" => array(
                "Left" => "left",
                "Center" => "center",
                "Right" => "right"
            ),
            "group" => esc_html__("Heading Settings", "wp-recruitment"),
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_heading.php",
                )
            ),
        ),
        array(
            "type" => "textarea",
            "heading" => esc_html__( 'Description', "wp-recruitment" ),
            "param_name" => "hd_description",
            "value" => '',
            "group" => esc_html__("Heading Settings", "wp-recruitment"),
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_heading.php",
                    "cms_heading--layout1.php",
                )
            ),
        ),  
        array(
            'type' => 'cms_template_img',
            'param_name' => 'cms_template',
            "shortcode" => "cms_heading",
            "heading" => esc_html__("Heading Template","wp-recruitment"),
            "admin_label" => true,
            "group" => esc_html__("Template", "wp-recruitment"),
        ),
    )
));

class WPBakeryShortCode_cms_heading extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}

?>