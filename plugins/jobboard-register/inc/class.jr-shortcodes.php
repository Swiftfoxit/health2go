<?php
/**
 * JB_Register_Shortcodes class
 *
 * @class       JB_Register_Shortcodes
 * @version     1.0.0
 * @package     JobBoard/Register
 * @category    Class
 * @author      FOX
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!class_exists('JB_Register_Shortcodes')):

    class JB_Register_Shortcodes {

        function __construct() {
            add_shortcode('jobboard_register_account', array($this, 'shortcodes_register') );
            add_shortcode('jobboard_register_forgot_password', array($this, 'shortcodes_forgot_password') );
            add_action( 'vc_before_init', array($this, 'vc_shortcode'));
        }

        public static function shortcodes_register($args, $content = ''){
            global $jobboard_register;

            $fields = $jobboard_register->get_custom_fields();
            if(!$jobboard_register->registed){
                $fields = $jobboard_register->set_custom_fields($fields);
            }

            ob_start();

            if($jobboard_register->registed) {
                jb_register()->get_template('registered.php', array('email' => $_POST['user_email']));
            } else {
                jb_register()->get_template('register-form.php', array('args' => $args, 'fields' => $fields));
            }

            return apply_filters('jobboard_register_form_html', ob_get_clean());
        }

        function shortcodes_forgot_password(){

            ob_start();

            if($user = jb_register()->validate_reset_password()){
                jb_register()->get_template('reset-password.php', array('user' => $user));
            } else {
                jb_register()->get_template('forgot-password.php');
            }

            return apply_filters('jobboard_forgot_password_html', ob_get_clean());
        }

        function vc_shortcode(){
            vc_map( array(
                "name"          => esc_html__( "Register Form", "jobboard-register" ),
                "base"          => "jobboard_register_account",
                "category"      => esc_html__( "JobBoard", "jobboard-register"),
                "description"   => esc_html__('Register form for JobBoard', JB_REGISTER_TEXT_DOMAIN),
                "show_settings_on_create" => false
            ));
            vc_map( array(
                "name"          => esc_html__( "Forgot Password", "jobboard-register" ),
                "base"          => "jobboard_register_forgot_password",
                "category"      => esc_html__( "JobBoard", "jobboard-register"),
                "description"   => esc_html__('Forgot password form for JobBoard', JB_REGISTER_TEXT_DOMAIN),
                "show_settings_on_create" => false
            ));
        }
    }

endif;

new JB_Register_Shortcodes();