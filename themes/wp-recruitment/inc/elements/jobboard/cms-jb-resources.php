<?php
/**
 * @Template: cms-jb-resources.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 20-Dec-17
 */
if (function_exists('vc_map') && is_plugin_active('jobboard-resources/jobboard-resources.php')) {
    vc_map(array(
        "name" => esc_html__('Jobboard Resources', "wp-recruitment"),
        "base" => "cms_jb_resources",
        "icon" => "cs_icon_for_vc",
        "category" => esc_html__('JobBoard', "wp-recruitment"),
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Title", "wp-recruitment"),
                "param_name" => "title",
                "value" => __("Recruitment Resources", "wp-recruitment")
            ),
            array(
                "type" => "textfield",
                "heading" => __("Limit", "wp-recruitment"),
                "param_name" => "limit",
                "value" => '4',
            )
        )
    ));
}

class WPBakeryShortCode_cms_jb_resources extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        $new_atts = shortcode_atts(array(
            'title' => __("Recruitment Resources", "wp-recruitment"),
            'limit' => '4'
        ), $atts);
        $atts = array_merge($new_atts, $atts);
        $args = array(
            'posts_per_page' => $atts['limit'],
            'offset' => 0,
            'post_type' => 'jb-resources',
            'post_status' => 'publish'
        );
        $atts['posts'] = get_posts($args);
        wp_enqueue_script('jobboard-resources.js', jb_resources()->plugin_directory_uri . 'assets/jobboard-resources.js', array(), 'all', true);
        $params = array(
            'ajax_url' => admin_url('admin-ajax.php')
        );
        wp_localize_script('jobboard-resources.js', 'data_ajax', $params);
        return parent::content($atts, $content);
    }
}