<?php
vc_map(array(
    "name" => 'CMS Text Box',
    "base" => "cms_textbox",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', 'wp-recruitment'),
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Layout", 'wp-recruitment'),
            "param_name" => "layout",
            "value" => array(
                "Layout 1" => "layout1",
                "Layout 2" => "layout2",
            ),
        ),
        array(
            "type" => "attach_image",
            "heading" => esc_html__("Image",'wp-recruitment'),
            "param_name" => "image",
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Title", 'wp-recruitment'),
            "param_name" => "title",
        ),
        array(
            "type" => "textarea",
            "heading" => esc_html__("Description", 'wp-recruitment'),
            "param_name" => "tb_content",
        ),
        array(
            "type" => "attach_image",
            "heading" => esc_html__("Icon",'wp-recruitment'),
            "param_name" => "icon",
             "dependency" => array(
                "element"=>"layout",
                "value"=>array(
                    "layout2",
                )
            ),
        ),
        array(
            "type" => "textarea",
            "heading" => esc_html__("Button Text", 'wp-recruitment'),
            "param_name" => "button_text",
            "dependency" => array(
                "element"=>"layout",
                "value"=>array(
                    "layout2",
                )
            ),
        ),
        array(
            "type" => "vc_link",
            "class" => "",
            "heading" => esc_html__("Button Link", "wp-recruitment"),
            "param_name" => "link",
            "value" => '',
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Box Background Color",'wp-recruitment'),
            "param_name" => "box_bg_color",
            "value" => "",
            "dependency" => array(
                "element"=>"layout",
                "value"=>array(
                    "layout2",
                )
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Box Padding", 'wp-recruitment'),
            "param_name" => "box_padding",
            "description" => "Enter: ..px;",
            "dependency" => array(
                "element"=>"layout",
                "value"=>array(
                    "layout2",
                )
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Content Padding", 'wp-recruitment'),
            "param_name" => "content_padding",
            "description" => "Enter: ..px;",
            "dependency" => array(
                "element"=>"layout",
                "value"=>array(
                    "layout2",
                )
            ),
        ),
    )
));

class WPBakeryShortCode_cms_textbox extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}

?>