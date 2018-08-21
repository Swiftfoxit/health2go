<?php
vc_map(array(
    "name" => esc_html__('Specialism List', "wp-recruitment"),
    "base" => "cms_specialism_list",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('JobBoard', "wp-recruitment"),
    "params" => array(
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Show Items", "wp-recruitment"),
            "admin_label"   => true,
            "param_name"    => "show",
            "value"         => array(
                esc_html__('All', 'wp-recruitment')       => "all",
                esc_html__('Custom', 'wp-recruitment')   => "custom",
            )
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__( 'Number', "wp-recruitment" ),
            "param_name"    => "number",
            "value"         => 5,
            'description'   => esc_html__('limit items.', "wp-recruitment" ),
            "dependency" => array(
                "element"=>"show",
                "value"=>array(
                    "custom",
                )
            )
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Order By", "wp-recruitment"),
            "admin_label"   => true,
            "param_name"    => "orderby",
            "value"         => array(
                esc_html__('Count', 'wp-recruitment')   => "count",
                esc_html__('Name', 'wp-recruitment')    => "name",
                esc_html__('Random', 'wp-recruitment')  => "random",
            )
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Order", "wp-recruitment"),
            "admin_label"   => true,
            "param_name"    => "order",
            "value"         => array(
                esc_html__('Ascending', 'wp-recruitment')    => "ASC",
                esc_html__('Descending', 'wp-recruitment')   => "DESC",
            )
        ),
        array(
            "type"          => "checkbox",
            "heading"       => esc_html__('Hide Empty', "wp-recruitment"),
            'description'   => esc_html__('Hide null items.', "wp-recruitment" ),
            "param_name"    => "hide_empty",
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__( 'Title Length', "wp-recruitment" ),
            'description'   => esc_html__( 'Limit title chars.', "wp-recruitment" ),
            "param_name"    => "length",
            "dependency" => array(
                "element"=>"specialism_layout",
                "value"=>array(
                    "layout1",
                )
            )
        ),
        array(
            'type'          => 'vc_link',
            "heading"       => esc_html__( 'Specialism Page', "wp-recruitment" ),
            'description'   => esc_html__('Link to specialism page.', "wp-recruitment" ),
            "param_name"    => "page",
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
                "element"=>"specialism_layout",
                "value"=>array(
                    "layout1",
                )
            )
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Layout", 'wp-recruitment'),
            "param_name" => "specialism_layout",
            "value" => array(
                "Layout 1" => "layout1",
                "Layout 2" => "layout2",
                "Layout 3" => "layout3",
                "Layout 4" => "layout4",
                "Layout 5" => "layout5",
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__( 'Title Text', "wp-recruitment" ),
            "param_name"    => "title_text",
            "dependency" => array(
                "element"=>"specialism_layout",
                "value"=>array(
                    "layout2",
                    "layout3",
                    "layout4",
                    "layout5",
                )
            )
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Custom Class', "wp-recruitment"),
            "param_name"    => "custom_class",
            "admin_label"   => true,
        ),
    )
));

class WPBakeryShortCode_cms_specialism_list extends CmsShortCode
{

    protected function content($atts, $content = null)
    {

        $atts = shortcode_atts(array(
            'show'      => 'all',
            'length'    => '',
            'job_style'    => 'style1',
            'custom_class'    => '',
            'number'    => 5,
            'orderby'   => 'count',
            'order'     => 'ASC',
            'hide_empty'=> false,
            'page'      => '',
            'title_text'      => '',
            'specialism_layout'      => 'layout1',
        ),$atts);

        $query = array(
            'taxonomy'  => 'jobboard-tax-specialisms',
            'hide_empty'=> (boolean)$atts['hide_empty']
        );

        if($atts['orderby'] && $atts['orderby'] != 'random'){
            $query['orderby']   = $atts['orderby'];
            $query['order']     = $atts['order'];
        }

        if($atts['show'] == 'custom'){
            $query['number']    = (int)$atts['number'];
        }

        $content = get_terms($query);

        if(!is_wp_error($content) && !empty($content)) {

            if($atts['orderby'] === 'random') shuffle($content);

            return parent::content($atts, $content);
        }
    }
}