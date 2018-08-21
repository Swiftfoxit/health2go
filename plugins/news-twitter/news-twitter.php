<?php
/**
 * Plugin Name: News Twitter
 * Plugin URI: http://zotheme.com/
 * Description: Twitter tweets plugin is a twitter widget plugin display twitter accounts latest tweets on your WordPress blog.
 * Version: 1.0.5
 * Author: FOX
 * Author URI: http://zotheme.com/
 * License: GPLv2 or later
 * Text Domain: news-twitter
 */
 
if (! defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

/**
 * Main Class
 *
 * @class ZNews_twitter
 *
 * @version 1.0.0
 */
if (! class_exists("ZNews_twitter")) {

    final class ZNews_twitter
    {

        public $plugin_dir;
        public $plugin_url;

        public $theme_dir;
        public $theme_url;

        public static function instance()
        {
            static $instance = null;
            
            if (null === $instance) {
                
                $instance = new ZNews_twitter();
                
                // globals.
                $instance->setup_globals();
                
                // includes.
                $instance->includes();
                
                // actions.
                $instance->setup_actions();
            }
            
            return $instance;
        }

        /**
         * globals value.
         * 
         * @global path + uri.
         */
        private function setup_globals()
        {
            $this->file = __FILE__;
            $this->plugin_dir = plugin_dir_path($this->file);
            $this->plugin_url = plugin_dir_url($this->file);

            $this->theme_dir = trailingslashit(get_template_directory() . '/news-twitter');
            $this->theme_url = trailingslashit(get_template_directory_uri() . '/news-twitter');
        }

        /**
         * setup all actions + filter.
         * 
         * @package CMSMK
         * @version 1.0.0
         */
        private function setup_actions()
        {
        	add_action('widgets_init', array($this, 'load_widgets'));
        	add_action('wp_enqueue_scripts', array($this, 'add_scrips'));

            add_action('wp', array($this, 'get_layouts'));
        }

        /**
         * include files.
         * 
         * @package CMSMK
         * @version 1.0.0
         */
        private function includes()
        {
        	// admin
        	require_once $this->plugin_dir . 'inc/class.admin.php';
        	
        	// widgets.
        	require_once $this->plugin_dir . 'inc/class.widgets.php';

            // shortcode
            require_once $this->plugin_dir . 'inc/vc.addon.php';
        }
        
        /**
         * include widgets.
         * 
         * @version 1.0.0
         */
        function load_widgets()
        {
        	register_widget('ZNews_Twitter_Widget');
        }

        public static function get_token($consumer_key, $consumer_secret){
        	
        	$credentials = $consumer_key . ':' . $consumer_secret;
        	$toSend = base64_encode($credentials);
        	
        	// http post arguments
        	$args = array(
        			'method' => 'POST',
        			'httpversion' => '1.1',
        			'blocking' => true,
        			'headers' => array(
        					'Authorization' => 'Basic ' . $toSend,
        					'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        			),
        		'body' => array( 'grant_type' => 'client_credentials' )
        	);
        	
        	$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
        	
        	if(is_wp_error($response))
        		return ;
        	
        	$keys = json_decode(wp_remote_retrieve_body($response));
        	
        	// if key null.
        	if(!$keys)
        		return ;
        	
        	// return key.
        	return $keys->access_token;
        }
        
        public static function get_twitter_feed($twitter_id, $token){
        	
        	// we have bearer token wether we obtained it from API or from options
        	$args = array(
        			'httpversion' => '1.1',
        			'blocking' => true,
        			'headers' => array(
        					'Authorization' => "Bearer $token"
        			)
        	);

            $count = get_option('newstwitter_items_syn', 5);
        	
        	$api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$twitter_id.'&count='.$count;
        	
        	$response = wp_remote_get($api_url, $args);
        	
        	if(is_wp_error($response)) 
        		return ;
        	
        	if(!$response)
        		return ;
        		
        	return wp_remote_retrieve_body($response);
        }
        
        /**
         * add front-end scripts.
         * 
         * @package CMSMK
         * @version 1.0.0
         */
        function add_scrips(){
        	
        	wp_enqueue_script('jquery.bxslider', $this->plugin_url . 'js/jquery.bxslider.min.js', array('jquery'), '4.1.2', true);
        	
        	wp_enqueue_script('news-twitter', $this->plugin_url . 'js/news-twitter.js', array('jquery.bxslider'), '1.0.0', true);
        }

        /**
         *  get layouts.
         *
         * @return array|void
         */
        function get_layouts(){

            $styles = array();

            if(!is_dir($this->theme_dir))
                return $styles;

            /* get all files demo .xml */
            $files = scandir($this->theme_dir);

            $files = array_diff($files, array('..', '.'));

            if(empty($files))
                return $styles;

            $default_headers = array(
                'Name' => 'Name',
                'Version' => 'Version'
            );

            foreach ($files as $file){
                $file_data = get_file_data( $this->theme_dir . $file, $default_headers);

                if($file_data['Name'])
                    $styles[$file_data['Name']] = $file;
            }

            return $styles;
        }
    }

    if(!function_exists('znews_twitter')){
        
        function znews_twitter()
        {
            return ZNews_twitter::instance();
        }
    }
    
    if (defined('ZNews_twitter_LATE_LOAD')) {
        
        add_action('plugins_loaded', 'znews_twitter', (int) ZNews_twitter_LATE_LOAD);
    } else {
        
        znews_twitter();
    }
}