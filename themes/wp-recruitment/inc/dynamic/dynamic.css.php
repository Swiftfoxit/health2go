<?php

/**
 * Auto create css from Meta Options.
 * 
 * @author CMSSuperHeroes Team
 * @version 1.0.0
 */
class CMSSuperHeroes_DynamicCss
{

    function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'generate_css'));
    }

    /**
     * generate css inline.
     *
     * @since Recruitment 1.0.9
     */
    public function generate_css()
    {
        wp_enqueue_style('custom-dynamic',get_template_directory_uri() . '/assets/css/custom-dynamic.css');
        $_dynamic_css = $this->css_render();
        wp_add_inline_style('custom-dynamic', $_dynamic_css);
    }

    /**
     * header css
     *
     * @since Recruitment 1.0.9
     * @return string
     */
    public function css_render()
    {
        global $opt_theme_options, $opt_meta_options;
        ob_start();

        /* Start Style */
        if (is_page() && isset($opt_meta_options['page_pagetitle_padding']['padding-top'])) {
            echo "body #cms-page-title { 
                padding-top: ".esc_attr($opt_meta_options['page_pagetitle_padding']['padding-top']).";
            }";
        }
        if (is_page() && isset($opt_meta_options['page_pagetitle_padding']['padding-bottom'])) {
            echo "body #cms-page-title {
                padding-bottom: ".esc_attr($opt_meta_options['page_pagetitle_padding']['padding-bottom']).";
            }";
        }

        if (is_page() && isset($opt_meta_options['page_content_padding']['padding-top'])) {
            echo "body #cms-content.site-content { 
                padding-top: ".esc_attr($opt_meta_options['page_content_padding']['padding-top']).";
            }";
        }
        if (is_page() && isset($opt_meta_options['page_content_padding']['padding-bottom'])) {
            echo "body #cms-content.site-content {
                padding-bottom: ".esc_attr($opt_meta_options['page_content_padding']['padding-bottom']).";
            }";
        }

        if (is_page() && isset($opt_meta_options['content_background_color'])) {
            echo "body #cms-content.site-content {
                background-color: ".esc_attr($opt_meta_options['content_background_color']).";
            }";
        }
        
        if(function_exists('is_jb_jobs') && is_jb_jobs()){
            global $jobboard_options;
            $page_job_ID = $jobboard_options['page-jobs'];

            if($page_job_padding = get_post_meta($page_job_ID, 'page_content_padding', true)){
                echo "body #cms-content.site-content {
                    padding-top: ".esc_attr($page_job_padding['padding-top']).";
                    padding-bottom: ".esc_attr($page_job_padding['padding-bottom']).";
                }";
            }

            if($page_pagetitle_padding = get_post_meta($page_job_ID, 'page_pagetitle_padding', true)){
                echo "body #cms-page-title {
                    padding-top: ".esc_attr($page_pagetitle_padding['padding-top']).";
                    padding-bottom: ".esc_attr($page_pagetitle_padding['padding-bottom']).";
                }";
            }
        }

        if(function_exists('is_jb_jobs') && is_jb_jobs()){
            echo get_post_meta(jb_page_id('jobs'), '_wpb_shortcodes_custom_css', true);
        }

        /* Custom CSS */
        if(!empty($opt_theme_options['custom_css']))
            echo esc_html($opt_theme_options['custom_css']);

        return ob_get_clean();
    }
}

new CMSSuperHeroes_DynamicCss();