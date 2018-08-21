<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/8/2016
 * Time: 8:43 AM
 */
if (! defined ( 'ABSPATH' )) {
    exit (); // Exit if accessed directly
}

if (! class_exists ( 'ZNews_Twitter_VC_Addon' )) {

    class ZNews_Twitter_VC_Addon {

        function __construct()
        {
            add_action('vc_before_init', array($this, 'add_params'));

            add_action('init', array($this, 'add_shortcode'));
        }

        function add_shortcode(){
            add_shortcode('z-news-twitter', array($this, 'shortcode_news_twitter'));
        }

        function shortcode_news_twitter($atts, $content = ''){

            $mode = $row = $speed = $auto = $ticker = $minslides = $maxslides = $slidewidth = $controls = $pager = $layout = '';

            $row_index = 0;

            extract(shortcode_atts(array(
                'mode' => 'horizontal',
                'row' => 1,
                'speed' => '5000',
                'auto' => 1,
                'ticker' => 0,
                'minslides' => 1,
                'maxslides' => 1,
                'slidewidth' => 0,
                'controls' => 0,
                'pager' => 0,
                'layout' => '',
            ), $atts));

            $oauth_access_token = get_option('newstwitter_access_token') ? get_option('newstwitter_access_token') : '1448479668-XGbfaCCz0TJprkLqnTl3jX9ruXliSC4iAuGXrPK' ;
            $oauth_access_token_secret = get_option('newstwitter_access_token_secret') ? get_option('newstwitter_access_token_secret') : 'WSXjpqZ4U0VsfVngxudTCuR7TSN43C9cfhRczh2iLrwOi';
            $consumer_key = get_option('newstwitter_consumer_key') ? get_option('newstwitter_consumer_key') : 'qasf2CiPwubl0ISq6KBaHOfPo';
            $consumer_secret = get_option('newstwitter_consumer_secret') ? get_option('newstwitter_consumer_secret') : '17zku2rZDLrVmAs7WQlgdGEZmmadXMlibELHHBzHPygpbAIf4V';

            $screen_name = get_option('newstwitter_screen_name') ? get_option('newstwitter_screen_name') : 'realjoomlaman';
            $cache_time = get_option('newstwitter_cache_time', 10);

            // bx options.
            $bx_options = ' data-mode="'.esc_attr($mode).'" data-speed="'.esc_attr($speed).'" data-auto="'.esc_attr($auto).'" data-ticker="'.esc_attr($ticker).'" data-minslides="'.esc_attr($minslides).'" data-maxslides="'.esc_attr($maxslides).'" data-slidewidth="'.esc_attr($slidewidth).'" data-controls="'.esc_attr($controls).'" data-pager="'.esc_attr($pager).'"';

            // get cache data.
            $transient = get_transient($screen_name);

            // if data null
            if(!$transient){

                // get token.
                $token = get_option('_znews_twitter_token');

                if(!$token){

                    $token = znews_twitter()->get_token($consumer_key, $consumer_secret);

                    if($token) update_option('_znews_twitter_token', $token);

                }

                // get feed.
                $response = znews_twitter()->get_twitter_feed($screen_name, $token);

                // cache time.
                set_transient($screen_name, $response, 60 * (int)$cache_time);

                $transient = $response;

            }

            /* post data. */
            $twitter = json_decode($transient, true);

            /* number items. */
            $items_count = count($twitter) - 1;

            $layout = $layout ? znews_twitter()->theme_dir . $layout : znews_twitter()->plugin_dir . 'templates/feed.php';

            /* template. */
            $template = apply_filters('znews_twitter/widget/template', $layout);

            ob_start();

            if(file_exists($template)){

                $file_data = get_file_data( $template, array('Name' => 'Name', 'Version' => 'Version'));

                /* if template name = null. */
                if($file_data['Name'] == '')
                    $file_data['Name'] = 'default';

                $bx_class = apply_filters('znews_twitter/slider/class', array('news-twitter', 'bxslider', 'nt-layout-' . sanitize_title($file_data['Name'])));

                do_action('znews_twitter/slider/before');

                echo '<div class="'.implode(' ', $bx_class).'"'. $bx_options .'>';

                require $template;

                echo '</div>';

                do_action('znews_twitter/slider/after');
            }

            return ob_get_clean();
        }

        function add_params()
        {
            if (!function_exists("vc_map"))
                return;

            vc_map(array(
                    "name" => esc_html__("News Twitter", "news-twitter"),
                    "base" => "z-news-twitter",
                    "class" => "z-news-twitter",
                    "icon" => 'icon-wpb-tweetme',
                    "description" => esc_html__("Recent Tweets", "news-twitter"),
                    "params" => array(
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Min Slides", "news-twitter"),
                            "param_name" => "minslides",
                            "description" => esc_html__("The minimum number of slides to be shown. Slides will be sized down if carousel becomes smaller than the original size (Default 1).", "news-twitter"),
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Max Slides", "news-twitter"),
                            "param_name" => "maxslides",
                            "description" => esc_html__("The maximum number of slides to be shown. Slides will be sized up if carousel becomes larger than the original size (Default 1).", "news-twitter"),
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Slide Width", "news-twitter"),
                            "param_name" => "slidewidth",
                            "description" => esc_html__("The width of each slide. This setting is required for all horizontal carousels (Default 0) !", "news-twitter"),
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Rows", "news-twitter"),
                            "param_name" => "row",
                            "description" => esc_html__("items rows only for (Default 1)", "news-twitter"),
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Slide Speed", "news-twitter"),
                            "param_name" => "speed",
                            "description" => esc_html__("Slide transition duration ( Default 5000 in ms)", "news-twitter"),
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Layout", "news-twitter"),
                            "param_name" => "layout",
                            "admin_label" => true,
                            "value" => array_merge(array(esc_html__("Default", "news-twitter") => '',), znews_twitter()->get_layouts()),
                            "group" => esc_html__("Layout", "news-twitter"),
                            "description" => esc_html__("You can select style for twitter feed.", "news-twitter"),
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Mode", "news-twitter"),
                            "param_name" => "mode",
                            "admin_label" => true,
                            "value" => array(
                                esc_html__("Horizontal", "news-twitter") => '',
                                esc_html__("Vertical", "news-twitter") => 'vertical',
                                esc_html__("Fade", "news-twitter") => 'fade'
                            ),
                            "group" => esc_html__("Mode", "news-twitter"),
                            "description" => esc_html__("Type of transition between slides (Default Horizontal).", "news-twitter"),
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Auto Slider", "news-twitter"),
                            "param_name" => "auto",
                            "value" => array(
                                esc_html__("Yes", "news-twitter") => 1
                            ),
                            "std" => 1,
                            "admin_label" => true,
                            "group" => esc_html__("Mode", "news-twitter"),
                            "description" => esc_html__("Slides will automatically transition.", "news-twitter"),
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Ticker Mode", "news-twitter"),
                            "param_name" => "ticker",
                            "value" => array(
                                esc_html__("Yes", "news-twitter") => 1
                            ),
                            "admin_label" => true,
                            "group" => esc_html__("Mode", "news-twitter"),
                            "description" => esc_html__("Use slider in ticker mode (similar to a news ticker)", "news-twitter"),
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Controls", "news-twitter"),
                            "param_name" => "controls",
                            "value" => array(
                                esc_html__("Yes", "news-twitter") => 1
                            ),
                            "admin_label" => true,
                            "group" => esc_html__("Controls", "news-twitter"),
                            "description" => esc_html__("If true, Next & Prev controls will be added", "news-twitter"),
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Pager", "news-twitter"),
                            "param_name" => "pager",
                            "value" => array(
                                esc_html__("Yes", "news-twitter") => 1
                            ),
                            "admin_label" => true,
                            "group" => esc_html__("Controls", "news-twitter"),
                            "description" => esc_html__("If true, a pager will be added", "news-twitter"),
                        ),
                    )
                )
            );
        }
    }
}

new ZNews_Twitter_VC_Addon();