<?php
vc_map(array(
    "name" => esc_html__('Jobs List', "wp-recruitment"),
    "base" => "cms_jobs_grid",
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
            "type"          => "dropdown",
            "heading"       => esc_html__("Query Type", "wp-recruitment"),
            "param_name"    => "type",
            "admin_label"   => true,
            "value"         => array(
                esc_html__("Recent", 'wp-recruitment') => "recent",
                esc_html__("Featured", 'wp-recruitment') => "featured",
                esc_html__("Interest", 'wp-recruitment') => "interest"
            )
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Query Limit', "wp-recruitment"),
            "param_name"    => "limit",
            "std"           => 12,
            "description"   => esc_html__('Limit jobs in query.', "wp-recruitment")
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Custom Class', "wp-recruitment"),
            "param_name"    => "custom_class",
            "admin_label"   => true,
        ),
    )
));

class WPBakeryShortCode_cms_jobs_grid extends CmsShortCode
{

    protected function content($atts, $content = null)
    {

        $atts = shortcode_atts(array(
            'title'         => '',
            'job_style'     => 'style1',
            'custom_class'  => '',
            'type'          => 'recent',
            'limit'         => 12,
            'items'         => 1,
            'class'         => '',
        ),$atts);

        $query = array(
            'post_type'     => 'jobboard-post-jobs',
            'post_status'   => 'publish',
            'posts_per_page'=> $atts['limit']
        );

        if($atts['type'] == 'featured'){
            $query['meta_query'] = array(
                array(
                    'key'     => '_featured',
                    'value'   => '1'
                )
            );
        } elseif ($atts['type'] == 'interest' && function_exists('jb_similar')){
            $query = jb_similar()->similar($query);
        }

        $content = new WP_Query($query);

        if(!is_wp_error($content) && !empty($content)) {
            return parent::content($atts, $content);
        }
    }
}