<?php
/**
 * Plugin Name: JobBoard Search
 * Plugin URI: http://fsflex.com/
 * Description: Advanced search for JobBoard plugin.
 * Version: 1.0.3
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-search
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_SEARCH_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_Search')) {
    class JB_Search
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_Search();
                self::$instance->setup_globals();

                if ( ! function_exists( 'is_plugin_active' ) ) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if ( is_plugin_active('jobboard/jobboard.php')){
                    self::$instance->includes();
                    self::$instance->actions();
                }
            }

            return self::$instance;
        }

        private function setup_globals()
        {
            $this->file = __FILE__;
            /* base name. */
            $this->basename = plugin_basename($this->file);
            /* base plugin. */
            $this->plugin_directory = plugin_dir_path($this->file);
            $this->plugin_directory_uri = plugin_dir_url($this->file);
        }

        private function includes(){
            require_once $this->plugin_directory . 'inc/class.js-update.php';
            require_once $this->plugin_directory . 'inc/class.js-shortcodes.php';

            if($this->is_request('frontend')){
                require_once $this->plugin_directory . 'inc/template-functions.php';
                require_once $this->plugin_directory . 'inc/template-hooks.php';
            }
        }

        private function actions(){
            if($this->is_request('frontend')){
                add_action('pre_get_posts', array($this, 'pre_get_posts'));
            }
        }

        function is_request( $type ) {
            switch ( $type ) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined( 'DOING_AJAX' );
                case 'cron' :
                    return defined( 'DOING_CRON' );
                case 'frontend' :
                    return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
            }
        }

        function pre_get_posts($q){

            if(is_jb_search()){

                $tax_query = array();

                if(!empty($_GET['type'])){
                    $tax_query[] = array(
                        'taxonomy' => 'jobboard-tax-types',
                        'field'    => 'term_id',
                        'terms'    => $_GET['type']
                    );
                }

                if(!empty($_GET['specialism'])){
                    $tax_query[] = array(
                        'taxonomy' => 'jobboard-tax-specialisms',
                        'field'    => 'term_id',
                        'terms'    => $_GET['specialism']
                    );
                }

                if(!empty($_GET['location'])){
                    $tax_query[] = array(
                        'taxonomy' => 'jobboard-tax-locations',
                        'field'    => 'term_id',
                        'terms'    => $_GET['location']
                    );
                }

                $q->set('tax_query', $tax_query);
            }

            remove_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/search/', $this->plugin_directory . 'templates/');
        }
    }
}

function jb_search(){
    return JB_Search::instance();
}

$GLOBALS['jobboard_search'] = jb_search();