<?php
/**
 * Plugin Name: JobBoard Protect
 * Plugin URI: http://fsflex.com/
 * Description: Anti-spam and security for JobBoard plugin.
 * Version: 1.0.5
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-protect
 */
if (!defined('ABSPATH')) {
    exit();
}

define('JB_PROTECTED_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (!class_exists('JB_Protect')) {
    class JB_Protect
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new JB_Protect();
                self::$instance->setup_globals();

                if (!function_exists('is_plugin_active')) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if (is_plugin_active('jobboard/jobboard.php')) {
                    self::$instance->includes();
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

        private function includes()
        {
            require_once $this->plugin_directory . 'inc/class.jp-admin.php';
            require_once $this->plugin_directory . 'inc/class.jp-update.php';
        }

        private function actions()
        {
            add_action('wp_enqueue_scripts', array($this, 'add_scripts'));
            add_action('jobboard_package_payment_actions', array($this, 'get_template_checkout'));
            add_action('jobboard_register_form', array($this, 'get_template_register'), 15);
            add_filter('jobboard_form_handler_validate_package_checkout', array($this, 'validate_captcha'));
        }

        function add_scripts()
        {
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', null, time(), false);
        }

        function validate_captcha()
        {

            $site_key = jb_get_option('re-captcha-site-key');
            $secret_key = jb_get_option('re-captcha-secret-key');

            if (!jb_get_option('protect-checkout', 0)) {
                return false;
            }

            if (!$site_key || !$secret_key) {
                jb_notice_add(esc_html__('Site key or Secret key is null.', JB_PROTECTED_TEXT_DOMAIN), 'error');
                return true;
            }

            if (empty($_POST['g-recaptcha-response'])) {
                jb_notice_add(esc_html__('You need to verify captcha before ordering.', JB_PROTECTED_TEXT_DOMAIN), 'error');
                return true;
            }

            $args = array(
                'method'  => 'POST',
                'timeout' => 15,
                'body'    => array(
                    'secret'   => $secret_key,
                    'response' => $_POST['g-recaptcha-response']
                ),
            );

            if (is_wp_error($remote = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', $args))) {
                jb_notice_add(sprintf(esc_html__('Error: %s'), $remote->get_error_message()), 'error');
                return true;
            }

            $data = json_decode($remote['body']);

            if ($data->success) {
                return false;
            } else {
                jb_notice_add(esc_html__('Captcha not correct.', JB_PROTECTED_TEXT_DOMAIN), 'error');
                return true;
            }

            jb_notice_add(esc_html__('Do not verify captcha.', JB_PROTECTED_TEXT_DOMAIN), 'error');
            return true;
        }

        function get_template($template_name, $args = array())
        {
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/protect/', $this->plugin_directory . 'templates/');
        }

        function get_template_checkout()
        {
            if (!jb_get_option('protect-checkout', 0)) {
                return;
            }

            $this->get_template_captcha();
        }

        function get_template_register()
        {
            if (!jb_get_option('protect-register', 0)) {
                return;
            }

            $this->get_template_captcha();
        }

        function get_template_captcha()
        {
            $this->get_template('recaptcha.php', array('key' => jb_get_option('re-captcha-site-key')));
        }
    }
}

function jb_protect()
{
    return JB_Protect::instance();
}

$GLOBALS['jobboard_protect'] = jb_protect();