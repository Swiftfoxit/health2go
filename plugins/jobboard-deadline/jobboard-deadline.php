<?php
/**
 * Plugin Name: JobBoard Deadline
 * Plugin URI: http://fsflex.com/
 * Description: Set deadline for jobs listing add-on for JobBoard plugin.
 * Version: 1.0.0
 * Author: FOX
 * Author URI: https://github.com/vianhtu
 * License: GPLv2 or later
 * Text Domain: jobboard-deadline
 */
if (! defined('ABSPATH')) {
    exit();
}
define('JB_DEADLINE_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_Deadline')) {
    class JB_Deadline
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new JB_Deadline();
                self::$instance->setup_globals();

                if (!function_exists('is_plugin_active')) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if (is_plugin_active('jobboard/jobboard.php')) {
                    self::$instance->actions();
                }
            }

            return self::$instance;
        }

        private function setup_globals()
        {
            $this->file = __FILE__;
            $this->basename = plugin_basename($this->file);
            $this->plugin_directory = plugin_dir_path($this->file);
            $this->plugin_directory_uri = plugin_dir_url($this->file);
        }

        private function actions()
        {
            add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ));
            add_action( 'jobboard_loop_meta', array($this, 'get_template_countdown'), 100);
            add_action( 'jobboard_table_basket_title', array($this, 'get_template_countdown'), 100);
            add_filter( 'jobboard_template_part', array($this, 'get_template_apply'), 10, 2);
            add_filter( 'jobboard_job_apply_validate', array($this, 'apply_job_expired'));
            add_filter( 'jobboard_job_sections', array($this, 'add_job_sections'));
            add_filter( 'jobboard_add_job_fields', array($this, 'add_job_fields'));
            add_filter( 'fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter( 'fsflex_update_plugin_jobboard-deadline_data', array($this, 'plugin_info'));
        }

        function add_scripts(){
            wp_register_script('jquery-countdown', $this->plugin_directory_uri . 'assets/js/jquery.countdown.min.js', array('jquery'), '2.2.0', true);
            wp_enqueue_script('jobboard-deadline', $this->plugin_directory_uri . 'assets/js/jobboard-deadline.js', array('jquery', 'jquery-countdown'), time(), true);
        }

        function apply_job_expired($validate){
            if(!isset($_POST['id'])){
                return $validate;
            }

            $post_id    = sanitize_key($_POST['id']);
            $deadline   = get_post_meta($post_id, '_deadline', true);

            if(!$deadline){
                return $validate;
            }

            if(current_time( 'timestamp' ) > strtotime($deadline)){
                $validate = sprintf(esc_html__( '%s have Expired!', JB_DEADLINE_TEXT_DOMAIN ), get_the_title($_POST['id']));
                jb_notice_add( $validate, 'error');
            }

            return $validate;
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/deadline/', $this->plugin_directory . 'templates/');
        }

        function get_template_countdown(){
            global $post;

            if(empty($post->ID)){
                return;
            }

            if(!$deadline = get_post_meta($post->ID, '_deadline', true)){
                return;
            }

            $deadline = date('Y/m/d H:i', strtotime($deadline));

            $this->get_template('countdown.php', array('time' => $deadline));
        }

        function get_template_apply($located, $template_name){
            global $post;

            if($template_name != 'apply/apply.php'){
                return $located;
            }

            if(!$deadline = get_post_meta($post->ID, '_deadline', true)){
                return $located;
            }

            if(current_time( 'timestamp' ) < strtotime($deadline)){
                return $located;
            }

            return jb_get_locate_template( 'apply-expired.php', JB()->template_path() . 'add-ons/deadline/', $this->plugin_directory . 'templates/' );
        }

        function add_job_sections($sections){
            $sections['basic']['fields'][] = array(
                'id'         => '_deadline',
                'title'      => esc_html__('Deadline', JB_DEADLINE_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Select job deadline (m/d/Y H:i).', JB_DEADLINE_TEXT_DOMAIN ),
                'placeholder'=> esc_html__('m/d/Y H:i',JB_DEADLINE_TEXT_DOMAIN),
                'type'       => 'text'
            );
            return $sections;
        }

        function add_job_fields($fields){
            $fields[] = array(
                'id'         => 'deadline-heading',
                'title'      => esc_html__('Job Deadline', JB_DEADLINE_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Recruitment limited time, Candidate cannot apply after job expired.', JB_DEADLINE_TEXT_DOMAIN ),
                'type'       => 'heading',
                'heading'    => 'h3'
            );
            $fields[] = array(
                'id'         => '_deadline',
                'title'      => esc_html__('Deadline', JB_DEADLINE_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Select job deadline (m/d/Y H:i).', JB_DEADLINE_TEXT_DOMAIN ),
                'placeholder'=> esc_html__('m/d/Y H:i',JB_DEADLINE_TEXT_DOMAIN),
                'type'       => 'text'
            );
            return $fields;
        }

        function add_update($slugs = array()){
            $slugs[] = 'jobboard-deadline';
            return $slugs;
        }

        function plugin_info(){
            return 'jobboard-deadline';
        }
    }
}

function jb_deadline(){
    return JB_Deadline::instance();
}

$GLOBALS['jobboard_deadline'] = jb_deadline();