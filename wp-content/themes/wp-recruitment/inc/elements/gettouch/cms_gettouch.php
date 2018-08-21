<?php
vc_map(array(
    "name" => 'CMS Get Touch',
    "base" => "cms_gettouch",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', 'wp-recruitment'),
    "params" => array(

        array(
            "type" => "textarea",
            "heading" => esc_html__("Description", 'wp-recruitment'),
            "param_name" => "gettouch_content",
        ),
        
        array(
            "type" => "attach_image",
            "heading" => esc_html__( 'Icon', "wp-recruitment" ),
            "param_name" => "gettouch_icon",
            "value" => '',
        ),

    )
));

class WPBakeryShortCode_cms_gettouch extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}

?>