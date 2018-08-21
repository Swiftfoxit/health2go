<?php
/**
 * Plugin Name: JobBoard Social Login
 * Plugin URI: http://fsflex.com/
 * Description: Applied job with social for JobBoard plugin (Facebook, Google ...).
 * Version: 1.0.4
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-social-login
 */
if (!defined('ABSPATH')) {
    exit();
}

define('JB_SOCIAL_LOGIN_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (!class_exists('JB_Social_Login')) {
    class JB_Social_Login
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new JB_Social_Login();
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
            require_once $this->plugin_directory . 'inc/class.js-admin.php';
            require_once $this->plugin_directory . 'inc/class.js-update.php';
        }

        private function actions()
        {
            add_action('init', array($this, 'init'));
        }

        function init()
        {

            if (!is_user_logged_in()) {
                add_action('wp_enqueue_scripts', array($this, 'add_scripts'));
                add_action('wp_ajax_jb_social_login', array($this, 'ajax_login'));
                add_action('wp_ajax_nopriv_jb_social_login', array($this, 'ajax_login'));
                add_action('jobboard_social_login_content', array($this, 'get_template_social'));
                add_action('jobboard_form_login_after', array($this, 'get_template_content'), 100);
                add_filter('script_loader_tag', array($this, 'add_async_attribute'), 10, 2);
            }

            add_filter('jobboard_user_avatar_url', array($this, 'get_avatar_url'), 10, 3);
            add_filter('plugin_action_links_' . $this->basename, array($this, 'plugin_setting'));
        }

        function add_scripts()
        {
            $jb_social_login = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'debug'   => defined('WP_DEBUG') ? WP_DEBUG : false
            );

            if (jb_get_option('social-login-facebook')) {
                wp_enqueue_script('facebook-jssdk', 'https://connect.facebook.net/en_US/sdk.js', null, time(), false);
                $jb_social_login['facebook'] = jb_get_option('social-login-facebook-id');
            }

            if (jb_get_option('social-login-google')) {
                wp_enqueue_script('google-api', 'https://apis.google.com/js/api.js', null, time(), false);
                $jb_social_login['google'] = jb_get_option('social-login-google-id');
            }

            if (jb_get_option('social-login-linkedin')) {
                wp_enqueue_script('linkedin-api', 'http://platform.linkedin.com/in.js?async=true', null, time(), false);
                $jb_social_login['linkedin'] = jb_get_option('social-login-linkedin-id');
            }

            wp_register_script('jobboard-social-login-js', $this->plugin_directory_uri . 'assets/js/jobboard-social-login.js', array('jquery'), time(), true);
            wp_localize_script('jobboard-social-login-js', 'jb_social_login', $jb_social_login);
            wp_enqueue_script('jobboard-social-login-js');
        }

        function add_async_attribute($tag, $handle)
        {
            if (!in_array($handle, array('facebook-jssdk', 'google-api')))
                return $tag;

            return str_replace(' src', ' async defer src', $tag);

            return $tag;
        }

        function is_request($type)
        {
            switch ($type) {
                case 'admin' :
                    return is_admin();
                case 'ajax' :
                    return defined('DOING_AJAX');
                case 'cron' :
                    return defined('DOING_CRON');
                case 'frontend' :
                    return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
            }
        }

        function ajax_login()
        {

            /* if profile null. */
            if (empty($_POST['profile'])) {
                exit();
            }

            $profile = $_POST['profile'];

            /* no email auto set email. */
            if (empty($profile['user_email']) && $domain = $this->get_domain()) {
                $profile['user_email'] = $profile['user_login'] . '@' . $domain;
            }

            $userdata = array(
                'nickname'      => sanitize_text_field($profile['display_name']),
                'user_nicename' => sanitize_text_field($profile['display_name']),
                'first_name'    => sanitize_text_field($profile['first_name']),
                'last_name'     => sanitize_text_field($profile['last_name']),
                'role'          => 'jobboard_role_candidate',
            );

            $user = null;

            /* if email does not exists. */
            if (!email_exists($profile['user_email'])) {

                $userdata['user_login'] = $userdata['user_email'] = sanitize_email($profile['user_email']);
                $userdata['user_pass'] = wp_generate_password(8, false);

                /* register new user. */
                if (!is_wp_error($user_id = wp_insert_user($userdata))) {

                    $user = get_user_by('ID', $user_id);
                    $user->data->user_pass = $userdata['user_pass'];

                    update_user_meta($user->ID, 'user_image', $profile['user_image']);

                    if (isset($profile['user_meta'])) {
                        $this->update_user_meta($user->ID, $profile['user_meta']);
                    }

                    $this->send_email($user);
                }
            } /* update user info. */
            else {
                $user = get_user_by('email', $profile['user_email']);
                $user_data = get_userdata($user->ID);
                $userdata['ID'] = $user->ID;

                foreach ($userdata as $k => $meta) {
                    if ($user_data->$k != $meta) {
                        wp_update_user($userdata);
                        break;
                    }
                }
            }

            /* login no pass. */
            $this->login($user);

            exit();
        }

        function update_user_meta($user_id, $data)
        {
            if (isset($user_id) && is_array($data)) {
                foreach ($data as $key => $value) {
                    update_user_meta($user_id, $key, $value);
                }
            }
        }

        function send_email($user)
        {
            $to = $user->user_email;
            $from = jb_get_option('social-login-email-from', get_bloginfo('name'));
            $reply = jb_get_option('social-login-email-reply', get_bloginfo('admin_email'));
            $subject = jb_get_option('social-login-email-subject', get_bloginfo('description'));

            ob_start();

            $this->get_template('emails/new-connect.php', array('user' => $user));

            $message = ob_get_clean();
            $email = new JobBoard_Emails($to, $from, $reply, $subject, $message);

            $email->send();
        }

        function login($user)
        {

            clean_user_cache($user->ID);
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true, false);
            update_user_caches($user);

            update_user_meta($user->ID, 'last_login', current_time('mysql'));
            do_action('wp_login', $user->user_login, $user);
        }

        function get_avatar_url($url, $user_id, $attachment)
        {

            if (!empty($attachment['id'])) {
                return $url;
            }

            if ($image_url = get_user_meta($user_id, 'user_image', true)) {
                $url = $image_url;
            }

            return $url;
        }

        function get_domain()
        {
            $sURL = site_url();
            $asParts = wp_parse_url($sURL);

            if (empty($asParts['host'])) {
                return false;
            }

            return $asParts['host'];
        }

        function get_template($template_name, $args = array())
        {
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/social-login/', $this->plugin_directory . 'templates/');
        }

        function get_template_content()
        {
            $this->get_template('content-social.php');
        }

        function get_template_social()
        {
            if (jb_get_option('social-login-facebook')) {
                $notice = !jb_get_option('social-login-facebook-id') ? esc_html__('Facebook App ID not found.', JB_SOCIAL_LOGIN_TEXT_DOMAIN) : '';
                $this->get_template('facebook.php', array('notice' => $notice));
            }

            if (jb_get_option('social-login-google')) {
                $notice = !jb_get_option('social-login-google-id') ? esc_html__('Google Client ID not found.', JB_SOCIAL_LOGIN_TEXT_DOMAIN) : '';
                $this->get_template('google.php', array('notice' => $notice));
            }

            if (jb_get_option('social-login-linkedin')) {
                $notice = !jb_get_option('social-login-linkedin-id') ? esc_html__('Linkedin Client ID not found.', JB_SOCIAL_LOGIN_TEXT_DOMAIN) : '';
                $this->get_template('linkedin.php', array('notice' => $notice));
            }
        }

        function plugin_setting($links)
        {
            $action_links = array(
                'settings' => '<a href="' . admin_url('edit.php?post_type=jobboard-post-jobs&page=JobBoard') . '" title="' . esc_attr(esc_html__('Social login settings', JB_SOCIAL_LOGIN_TEXT_DOMAIN)) . '">' . esc_html__('Settings', JB_SOCIAL_LOGIN_TEXT_DOMAIN) . '</a>',
            );
            return array_merge($action_links, $links);
        }
    }
}

function jb_social_login()
{
    return JB_Social_Login::instance();
}

$GLOBALS['jobboard_social_login'] = jb_social_login();