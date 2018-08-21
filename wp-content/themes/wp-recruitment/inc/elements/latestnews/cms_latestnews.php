<?php
vc_map(array(
    "name" => 'CMS Latest News',
    "base" => "cms_latestnews",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('CmsSuperheroes Shortcodes', 'wp-recruitment'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__("Title", 'wp-recruitment'),
            "param_name" => "title",
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Posts Per Page", 'wp-recruitment'),
            "param_name" => "posts_limit",
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Posts Featured", 'wp-recruitment'),
            "param_name" => "post_featured",
            "value" => array(
                "No" => "0",
                "Yes" => "1",
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("View All Text", 'wp-recruitment'),
            "param_name" => "view_all_text",
        ),
        array(
            "type" => "vc_link",
            "heading" => esc_html__("View All Url", 'wp-recruitment'),
            "param_name" => "view_all_url",
        ),
    )
));

class WPBakeryShortCode_cms_latestnews extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}

?>