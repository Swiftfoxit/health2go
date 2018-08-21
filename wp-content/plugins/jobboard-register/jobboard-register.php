<?php
/**
 * Plugin Name: JobBoard Register
 * Plugin URI: http://fsflex.com/
 * Description: User (Employer & Candidate) register form.
 * Version: 1.0.7
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-register
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_REGISTER_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_Register')) {
    class JB_Register
    {
        public static $instance = null;

        public $registed = false;
        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_Register();
                self::$instance->setup_globals();
                self::$instance->init();

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

        private function init(){
            require_once $this->plugin_directory . 'inc/class.jr-install.php';
            register_activation_hook($this->file, array(new JobBoard_Register_Install(), 'install'));
        }

        private function includes(){
            if($this->is_request('frontend')) {
                require_once $this->plugin_directory . 'inc/class.jr-formhandler.php';
            }
            require_once $this->plugin_directory . 'inc/class.jr-admin.php';
            require_once $this->plugin_directory . 'inc/class.jr-update.php';
            require_once $this->plugin_directory . 'inc/class.jr-emails.php';
            require_once $this->plugin_directory . 'inc/class.jr-shortcodes.php';
        }

        /**
         * plugin actions.
         */
        private function actions(){
            add_filter( 'the_content', array($this, 'the_content') );
            add_action( 'jobboard_register_form', 'jb_template_form_dynamic', 10);
            add_action( 'jobboard_register_form', array($this, 'get_template_actions'), 20, 2);
            add_action( 'jobboard_form_login_after', array($this, 'get_template_button'));
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

        function the_content($content){

            if(!is_page() || $content != ''){
                return $content;
            }

            switch (get_the_ID()){
                case jb_page_id('register'):
                    $content = '[jobboard_register_account]';
                    break;
                case jb_page_id('forgot-password'):
                    $content = '[jobboard_register_forgot_password]';
                    break;
            }

            return $content;
        }

        function validate_reset_password(){
            if(empty($_GET['action']) || empty($_GET['email']) || empty($_GET['key'])){
                return false;
            }

            if($_GET['action'] !== 'reset-password'){
                return false;
            }

            if(!$user = get_user_by('email', $_GET['email'])){
                return false;
            }

            $server_key = get_user_meta($user->ID, '_jobboard_forgot_key', true);
            $local_key  = $_GET['key'];

            if($server_key !== $local_key){
                return false;
            }

            $user->forgot_key = $server_key;

            return $user;
        }

        function set_custom_fields($fields){

            foreach ($fields as $k => $field) {

                if(empty($field['id'])){
                    continue;
                }

                if($field['id'] == 'user_pass' || $field['id'] == 'confirm_pass'){
                    continue;
                }

                if(!empty($_POST[$field['id']])) {
                    $fields[$k]['value'] = $_POST[$field['id']];
                }
            }

            return $fields;
        }

        function get_custom_fields(){
            $fields = array(
                'user_login' => array(
                    'id'            => 'user_login',
                    'type'          => 'text',
                    'title'         => esc_html__('User Name', 'jobboard-register'),
                    'subtitle'      => esc_html__('Enter your username.', 'jobboard-register' ),
                    'placeholder'   => esc_html__('User Name', 'jobboard-register'),
                    'require'       => true
                ),
                'user_email' => array(
                    'id'            => 'user_email',
                    'type'          => 'text',
                    'title'         => esc_html__('Email Address', 'jobboard-register'),
                    'subtitle'      => esc_html__('Enter your email', 'jobboard-register' ),
                    'placeholder'   => esc_html__('Email Address', 'jobboard-register'),
                    'input'         => 'email',
                    'require'       => true
                ),
                'user_pass' => array(
                    'id'            => 'user_pass',
                    'type'          => 'text',
                    'title'         => esc_html__('Password', 'jobboard-register'),
                    'subtitle'      => esc_html__('Enter your password.', 'jobboard' ),
                    'placeholder'   => esc_html__('Password', 'jobboard-register'),
                    'input'         => 'password',
                    'require'       => true
                ),
                'confirm_pass' => array(
                    'id'            => 'confirm_pass',
                    'type'          => 'text',
                    'title'         => esc_html__('Confirm Password', 'jobboard-register'),
                    'subtitle'      => esc_html__('Enter confirm password.', 'jobboard-register' ),
                    'placeholder'   => esc_html__('Confirm Password', 'jobboard-register'),
                    'input'         => 'password',
                    'require'       => true
                ),
                'first_name' => array(
                    'id'            => 'first_name',
                    'type'          => 'text',
                    'title'         => esc_html__('First Name', 'jobboard-register'),
                    'subtitle'      => esc_html__('Enter your first name', 'jobboard-register' ),
                    'placeholder'   => esc_html__('First Name', 'jobboard-register'),
                    'col'           => 6,
                    'require'       => true
                ),
                'last_name' => array(
                    'id'            => 'last_name',
                    'type'          => 'text',
                    'title'         => esc_html__('Last Name', 'jobboard-register'),
                    'subtitle'      => esc_html__('Enter your last name', 'jobboard-register' ),
                    'placeholder'   => esc_html__('Last Name', 'jobboard-register'),
                    'col'           => 6,
                    'require'       => true
                ),
                'user_type' => array(
                    'id'            => 'user_type',
                    'title'         => esc_html__('Account Type', 'jobboard-register' ),
                    'subtitle'      => esc_html__('Select account type.', 'jobboard-register' ),
                    'placeholder'   => esc_html__('Account Type','jobboard-register'),
                    'type'          => 'select',
                    'require'       => true,
                    'value'         => '',
                    'options'       => array(
                        'candidate' => esc_html__('Candidate', 'jobboard-register'),
                        'employer'  => esc_html__('Employer', 'jobboard-register'),
                    ),
                ),
                'job_specialisms' => array(
                    'id'            => 'job_specialisms',
                    'title'         => esc_html__('Specialisms', 'jobboard-register' ),
                    'subtitle'      => esc_html__('Select your specialisms.', 'jobboard-register' ),
                    'placeholder'   => esc_html__('Specialisms','jobboard-register'),
                    'type'          => 'select',
                    'multi'         => true,
                    'options'       => jb_get_specialism_options(),
                )
            );

            return apply_filters("jobboard-register-fields", $fields);
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/register/', $this->plugin_directory . 'templates/');
        }

        function get_template_actions(){
            $this->get_template("register-actions.php");
        }

        function get_template_button(){
            $this->get_template("register-button.php", array('register_page' => get_permalink(jb_get_option('page-register'))));
        }
    }
}

function jb_register(){
    return JB_Register::instance();
}

$GLOBALS['jobboard_register'] = jb_register();