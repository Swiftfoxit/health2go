<?php

/**
 * Auto create .css file from Theme Options
 * @author CMSSuperHeroes Team
 * @version 1.0.0
 */

/*
 * Convert HEX to GRBA
 */
if(!function_exists('recruitment_hex_to_rgb')){
    function recruitment_hex_to_rgb($hex,$opacity = 1) {
        $hex = str_replace("#",null, $hex);
        $color = array();
        if(strlen($hex) == 3) {
            $color['r'] = hexdec(substr($hex,0,1).substr($hex,0,1));
            $color['g'] = hexdec(substr($hex,1,1).substr($hex,1,1));
            $color['b'] = hexdec(substr($hex,2,1).substr($hex,2,1));
            $color['a'] = $opacity;
        }
        else if(strlen($hex) == 6) {
            $color['r'] = hexdec(substr($hex, 0, 2));
            $color['g'] = hexdec(substr($hex, 2, 2));
            $color['b'] = hexdec(substr($hex, 4, 2));
            $color['a'] = $opacity;
        }
        $color = "rgba(".implode(', ', $color).")";
        return $color;
    }
}

class CMSSuperHeroes_StaticCss
{

    public $scss;
    
    function __construct()
    {
        if(class_exists('RC_Framework')){
            rc_framework()->scssphp();
        } elseif(class_exists('WPL_MF_Plugin')){
            wpl_mf_plugin()->scssphp();
        }

        if (!class_exists('scssc')){
            return;
        }

        /* scss */
        $this->scss = new scssc();
        
        /* set paths scss */
        $this->scss->setImportPaths(get_template_directory() . '/assets/scss/');
             
        /* generate css over time */
		add_action('wp', array($this, 'generate_over_time'));
        
        /* save option generate css */
       	add_action("redux/options/opt_theme_options/saved", array($this,'generate_file'));
    }
	
    public function generate_over_time(){
    	
    	global $opt_theme_options;

    	if (isset($opt_theme_options) && $opt_theme_options['dev_mode']){
    	    $this->generate_file();
    	}
    }
    /**
     * generate css file.
     *
     * @since Recruitment 1.0.9
     */
    public function generate_file()
    {
        global $opt_theme_options, $wp_filesystem;
        
        if (empty($wp_filesystem) || !isset($opt_theme_options))
            return;
            
        $options_scss = get_template_directory() . '/assets/scss/options.scss';

        /* delete files options.scss */
        $wp_filesystem->delete($options_scss);

        /* write options to scss file */
        $wp_filesystem->put_contents($options_scss, $this->css_render(), FS_CHMOD_FILE); // Save it

        /* minimize CSS styles */
        if (!$opt_theme_options['dev_mode'])
            $this->scss->setFormatter('scss_formatter_compressed');

        /* compile scss to css */
        $css = $this->scss_render();

        $file = "static.css";

        $file = get_template_directory() . '/assets/css/' . $file;

        /* delete files static.css */
        $wp_filesystem->delete($file);

        /* write static.css file */
        $wp_filesystem->put_contents($file, $css, FS_CHMOD_FILE); // Save it
    }
    
    /**
     * scss compile
     * 
     * @since Recruitment 1.0.9
     * @return string
     */
    public function scss_render(){
        /* compile scss to css */
        return $this->scss->compile('@import "master.scss"');
    }
    
    /**
     * main css
     *
     * @since Recruitment 1.0.9
     * @return string
     */
    public function css_render()
    {
        global $opt_theme_options;
        
        ob_start();

        /* forward options to scss. */
        if(!empty($opt_theme_options['primary_color'])) {
            echo '$primary_color:'.esc_attr($opt_theme_options['primary_color']).';';
        }

        if(!empty($opt_theme_options['primary_color'])) {
            echo ".faq-search #searchform, .map-search-form .search-form, .jb-s-wrapper {
                background-color:".recruitment_hex_to_rgb($opt_theme_options['primary_color'],0.5).";
            }";
        }

        if(!empty($opt_theme_options['primary_color'])) {
            echo ".vc_tta-container .vc_tta-accordion.vc_tta-style-modern .vc_tta-panels .vc_tta-panel-body {
                border-left: 1px solid ".recruitment_hex_to_rgb($opt_theme_options['primary_color'],0.4).";
                border-right: 1px solid ".recruitment_hex_to_rgb($opt_theme_options['primary_color'],0.4).";
                border-bottom: 1px solid ".recruitment_hex_to_rgb($opt_theme_options['primary_color'],0.4).";
            }";
        }

        if(!empty($opt_theme_options['primary_color'])) {
            echo ".register-form input[type='text'],
            .register-form input[type='password'],
            .register-form input[type='datetime'],
            .register-form input[type='datetime-local'],
            .register-form input[type='date'],
            .register-form input[type='month'],
            .register-form input[type='time'],
            .register-form input[type='week'],
            .register-form input[type='number'],
            .register-form input[type='email'],
            .register-form input[type='url'],
            .register-form input[type='search'],
            .register-form input[type='tel'],
            .register-form input[type='color'],
            .register-form textarea, .register-form select {
                background-color: ".recruitment_hex_to_rgb($opt_theme_options['primary_color'],0.1).";
            }";
        }

        if(!empty($opt_theme_options['link_color']))
            echo '$link_color:'.esc_attr($opt_theme_options['link_color']).';';

        if(!empty($opt_theme_options['link_color_hover']))
            echo '$link_color_hover:'.esc_attr($opt_theme_options['link_color_hover']).';';
        
        if(!empty($opt_theme_options['footer_bg'])) {
            echo "body #colophon.site-footer {
                background-color:".esc_attr($opt_theme_options['footer_bg']).";
            }";
        }
        if(!empty($opt_theme_options['event_banner']['url'])) {
            echo ".event-modern .jobboard-event-banner {
                background-image: url(".esc_attr($opt_theme_options['event_banner']['url']).");
            }";
        }
        
        return ob_get_clean();
    }
}

new CMSSuperHeroes_StaticCss();