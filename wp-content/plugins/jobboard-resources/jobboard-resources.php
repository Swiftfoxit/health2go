<?php
/**
 * Plugin Name: JobBoard Resources
 * Plugin URI: http://fsflex.com/
 * Description: Recruitment Resources for JobBoard plugin.
 * Version: 1.0.0
 * Author: KP
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-resources
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_RESOURCES_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_Resources')) {
    class JB_Resources
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_Resources();
                self::$instance->setup_globals();

                if ( ! function_exists( 'is_plugin_active' ) ) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if ( is_plugin_active('jobboard/jobboard.php')){
                    self::$instance->includes();
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
            require_once $this->plugin_directory . 'inc/class.jr-post.php';
            new JR_Post();
            require_once $this->plugin_directory . 'inc/class.jr-admin.php';
            new JB_Resources_Admin();
            require_once $this->plugin_directory . 'inc/class.jr-template.php';
            new JB_Resources_Template();
            require_once $this->plugin_directory . 'inc/class.jr-handle.php';
            new JB_Resources_Handle();
        }

        function template_path() {
            return apply_filters( 'jr/template/path', 'jobboard/add-ons/resources/' );
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/resources/', $this->plugin_directory . 'templates/');
        }
    }
}

function jb_resources(){
    return JB_Resources::instance();
}

$GLOBALS['jb_resources'] = jb_resources();