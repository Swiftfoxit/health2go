<?php
vc_map(array(
    "name" => esc_html__('Upload CV', "wp-recruitment"),
    "base" => "cms_cv",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('JobBoard', "wp-recruitment"),
    "params" => array(
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Title', "wp-recruitment"),
            "param_name"    => "title",
            "admin_label"   => true,
        ),
        array(
            "type"          => "textarea",
            "heading"       => esc_html__('Description', "wp-recruitment"),
            "param_name"    => "description",
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Box Background Color",'wp-recruitment'),
            "param_name" => "box_bg_color",
            "value" => "",
        ),
    )
));

class WPBakeryShortCode_cms_cv extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}