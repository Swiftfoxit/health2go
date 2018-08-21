<?php
vc_map(array(
    "name" => esc_html__('Jobs Carousel', "wp-recruitment"),
    "base" => "cms_jobs",
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
            "heading"       => esc_html__('Post in row', "wp-recruitment"),
            "param_name"    => "post_per_row",
            "std"           => 4,
        ),
        array(
            "type" => "cms_template",
            "param_name" => "cms_template",
            "shortcode" => "cms_jobs",
            "admin_label" => true,
            "heading" => esc_html__("Layout", "wp-recruitment")
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Style", 'wp-recruitment'),
            "param_name" => "job_style",
            "value" => array(
                "Style 1" => "style1",
                "Style 2" => "style2",
                "Style 3" => "style3",
            ),
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_jobs--featured.php",
                    "cms_jobs--recent.php",
                    "cms_jobs--recent2.php",
                )
            ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Button View All", 'wp-recruitment'),
            "param_name" => "btn_view_all",
            "value" => array(
                "Show" => "show",
                "Hidden" => "hidden",
            ),
            "dependency" => array(
                "element"=>"cms_template",
                "value"=>array(
                    "cms_jobs--recent.php",
                )
            ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Columns XS Devices", 'wp-recruitment'),
            "param_name" => "col_xs",
            "edit_field_class" => "vc_col-sm-3",
            "value" => array(
                "Default" => "",
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
                "Default" => "",
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
                "Default" => "",
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
                "Default" => "",
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
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Custom Class', "wp-recruitment"),
            "param_name"    => "custom_class",
            "admin_label"   => true,
        ),
    )
));

class WPBakeryShortCode_cms_jobs extends CmsShortCode
{

    protected function content($atts, $content = null)
    {

        $new_atts = shortcode_atts(array(
            'title'         => '',
            'job_style'     => 'style1',
            'custom_class'  => '',
            'type'          => 'recent',
            'limit'         => 12,
            'items'         => 1,
            'cms_template'  => 'cms_jobs--recent.php',
        ),$atts);
        $atts = array_merge($new_atts,$atts);

        wp_enqueue_style('owl-carousel',get_template_directory_uri() . '/assets/css/owl.carousel.css','','2.0.0b','all');
        wp_enqueue_script('owl-carousel',get_template_directory_uri() . '/assets/js/owl.carousel.min.js',array('jquery'),'2.0.0b',true);
        wp_enqueue_script('cms-jobs', get_template_directory_uri() . '/inc/elements/jobboard/cms-jobs.js', array('owl-carousel'), '1.0.0', true);

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

        $atts['template'] = 'template-'.str_replace('.php','',$atts['cms_template']). ' '. $atts['custom_class'];

        if(!is_wp_error($content) && !empty($content)) {
            return parent::content($atts, $content);
        }
    }
}