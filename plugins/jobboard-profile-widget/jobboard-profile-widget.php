<?php
/**
 * Plugin Name: JobBoard Profile Widget
 * Plugin URI: http://fsflex.com/
 * Description: Widgets profile manager for JobBoard users.
 * Version: 1.0.3
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-profile-widget
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_PROFILE_WIDGET_TEXT_DOMAIN','jobboard');

if (! class_exists('JB_Profile_Widget')) {
    class JB_Profile_Widget
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_Profile_Widget();
                self::$instance->setup_globals();

                if ( ! function_exists( 'is_plugin_active' ) ) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if( is_plugin_active('jobboard/jobboard.php')){
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

        private function actions(){
            add_action('wp_enqueue_scripts', array($this, 'add_scripts'));
            add_action('widgets_init', array($this, 'add_widgets'));

            if((! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' )) {
                add_action('jobboard_profile_widget_content', array($this, 'get_template_profile'));
                add_action('jobboard_profile_widget_candidate', 'jb_template_candidate_navigation');
                add_action('jobboard_profile_widget_employer', 'jb_template_employer_navigation');
                add_action('jobboard_profile_widget_other', array($this, 'get_template_other'));
                add_action('jobboard_profile_widget_guest', 'jb_template_login_from');
            }
        }

        private function includes(){
            require_once $this->plugin_directory . 'inc/class.jp-update.php';
        }

        function add_scripts(){
            wp_enqueue_style('jobboard-profile-widget-css', $this->plugin_directory_uri . 'assets/css/profile-widget.css');
        }

        function add_widgets(){
            if(!class_exists( 'JB_Widget' )){
                include_once( JB()->plugin_directory . 'abstracts/abstract-jb-widget.php' );
            }
            include_once('widgets/class-jb-widget-profile.php');
            register_widget( 'JB_Widget_Profile' );
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/profile-widget/', $this->plugin_directory . 'templates/');
        }

        function get_template_profile(){
            if($user_id = get_current_user_id()){
                if(is_jb_candidate($user_id)){
                    do_action('jobboard_profile_widget_candidate');
                } elseif (is_jb_employer($user_id)){
                    do_action('jobboard_profile_widget_employer');
                } else {
                    do_action('jobboard_profile_widget_other');
                }
            } else {
                do_action('jobboard_profile_widget_guest');
            }
        }

        function get_template_other(){
            $this->get_template('profile-other.php');
        }
    }
}

function jb_profile(){
    return JB_Profile_Widget::instance();
}

$GLOBALS['jobboard_profile'] = jb_profile();