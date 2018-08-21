<?php
/**
 * Plugin Name: JobBoard Alerts
 * Plugin URI: http://fsflex.com/
 * Description: JobBoard Alerts.
 * Version: 1.0.4
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-alerts
 */
if (!defined('ABSPATH')) {
    exit();
}
define('JB_ALEART_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (!class_exists('JB_Alerts')) {
    class JB_Alerts
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new JB_Alerts();
                self::$instance->setup_globals();
                self::$instance->init();

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

        private function init()
        {
            require_once $this->plugin_directory . 'inc/class.ja-install.php';
            register_activation_hook($this->file, array(new JobBoard_Alerts_Install, 'install'));
            register_deactivation_hook($this->file, array($this, 'deactivation'));
        }

        private function includes()
        {
            require_once $this->plugin_directory . 'inc/class.ja-admin.php';
            require_once $this->plugin_directory . 'inc/class.ja-update.php';
            if ($this->is_request('frontend')) {
                require_once $this->plugin_directory . 'inc/class.ja-formhandler.php';
                require_once $this->plugin_directory . 'inc/template-functions.php';
                require_once $this->plugin_directory . 'inc/template-hooks.php';
            }
        }

        private function actions()
        {
            add_action('delete_user', array($this, 'delete_user'));
            add_action('widgets_init', array($this, 'add_widgets'));
            add_action('save_post', array($this, 'save_post'), 10, 2);
            add_action('jobboard_endpoint_alerts_scripts', array($this, 'add_endpoint_scripts'));
            add_action('jobboard_alerts_save_post_event', array($this, 'save_event'));
            add_action('wp_ajax_jobboard_alerts_save_post_event', array($this, 'save_event_callback'));
            add_action('wp_ajax_nopriv_jobboard_alerts_save_post_event', array($this, 'save_event_callback'));

            add_filter('jobboard_query_endpoint_args', array($this, 'add_endpoint'));
            add_filter('jobboard_query_endpoint_alerts_title', array($this, 'add_endpoint_alerts_title'));
            add_filter('jobboard_query_endpoint_notices_title', array($this, 'add_endpoint_notices_title'));
            add_filter('jobboard_employer_navigation_args', array($this, 'add_employer_endpoint_menu'), 100);
            add_filter('jobboard_candidate_navigation_args', array($this, 'add_candidate_endpoint_menu'), 100);

            add_filter('cron_schedules', array($this, 'cron_minutes'));
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

        function deactivation()
        {
            wp_clear_scheduled_hook('jobboard_alerts_save_post_event');
            update_option('jobboard_alerts_user_page', 0);
            update_option('jobboard_alerts_post_id', '');
        }

        function delete_user($user_id)
        {
            global $wpdb;
            $wpdb->delete($wpdb->prefix . 'jobboard_interest',
                array('user_id' => $user_id),
                array('%d')
            );
        }

        function add_endpoint_scripts()
        {
            wp_enqueue_style('jobboard-alerts-css', $this->plugin_directory_uri . 'assets/css/jobboard-alerts.css');
            wp_enqueue_script('jobboard-alerts-js', $this->plugin_directory_uri . 'assets/js/jobboard-alerts.js', array('jquery'), time(), true);
        }

        function add_endpoint($endpoint)
        {

            $endpoint['alerts'] = jb_get_option('endpoint-alerts', 'alerts');
            $endpoint['notices'] = jb_get_option('endpoint-notices', 'notices');

            return $endpoint;
        }

        function add_endpoint_alerts_title()
        {
            return esc_html__('Job Alerts', JB_ALEART_TEXT_DOMAIN);
        }

        function add_endpoint_notices_title()
        {
            return esc_html__('Manage Notifications', JB_ALEART_TEXT_DOMAIN);
        }

        function add_employer_endpoint_menu($args)
        {
            if (!jb_get_option('alerts-employer', false))
                return $args;

            $args[] = array(
                'id'       => 'alerts',
                'endpoint' => jb_get_option('endpoint-alerts', 'alerts'),
                'title'    => $this->add_endpoint_alerts_title()
            );

            $args[] = array(
                'id'       => 'notices',
                'endpoint' => jb_get_option('endpoint-notices', 'notices'),
                'title'    => $this->add_endpoint_notices_title()
            );

            return $args;
        }

        function add_candidate_endpoint_menu($args)
        {
            if (!jb_get_option('alerts-candidate', false))
                return $args;

            $args[] = array(
                'id'       => 'alerts',
                'endpoint' => jb_get_option('endpoint-alerts', 'alerts'),
                'title'    => $this->add_endpoint_alerts_title()
            );

            $args[] = array(
                'id'       => 'notices',
                'endpoint' => jb_get_option('endpoint-notices', 'notices'),
                'title'    => $this->add_endpoint_notices_title()
            );

            return $args;
        }

        function add_widgets()
        {
            if (!class_exists('JB_Widget')) {
                require_once JB()->plugin_directory . 'abstracts/abstract-jb-widget.php';
            }

            require_once $this->plugin_directory . 'widgets/class-ja-widget-newsletter.php';

            register_widget('JB_Widget_Newsletter');
        }

        function save_post($post_id, $post)
        {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            if (!jb_get_option('alerts-cron')) {
                return $post_id;
            }

            if ($post->post_type != 'jobboard-post-jobs' || $post->post_status != 'publish') {
                return $post_id;
            }

            if ($sent = get_post_meta($post_id, '_jobboard_alerts_sent', true) == 1) {
                return $post_id;
            } elseif ($sent == '') {
                update_post_meta($post_id, '_jobboard_alerts_sent', 0);

                if (!wp_next_scheduled('jobboard_alerts_save_post_event')) {
                    $this->save_event_reset();
                    wp_schedule_event(time(), 'jobboard-alerts-minute-1', 'jobboard_alerts_save_post_event');
                }
            }
        }

        function save_event()
        {
            wp_remote_request(add_query_arg('action', 'jobboard_alerts_save_post_event', admin_url('admin-ajax.php')), array('timeout' => 0, 'httpversion' => '1.1'));
        }

        function save_event_callback()
        {

            $post_query = array(
                'post_type'      => 'jobboard-post-jobs',
                'post_status'    => 'publish',
                'meta_key'       => '_jobboard_alerts_sent',
                'meta_value'     => 0,
                'posts_per_page' => 1,
                'orderby'        => 'date',
                'order'          => 'ASC',
            );

            if ($current_id = get_option('jobboard_alerts_post_id', '') != '') {
                $post_query['ID'] = $current_id;
            }

            $posts = new WP_Query($post_query);

            if (!$posts->have_posts()) {
                wp_clear_scheduled_hook('jobboard_alerts_save_post_event');
                $this->save_event_reset();
                exit();
            }

            global $wpdb;

            $post = $posts->post;

            $types = wp_get_post_terms($post->ID, 'jobboard-tax-types', array('fields' => 'ids'));
            $locations = wp_get_post_terms($post->ID, 'jobboard-tax-locations', array('fields' => 'ids'));
            $specialisms = wp_get_post_terms($post->ID, 'jobboard-tax-specialisms', array('fields' => 'ids'));
            $tags = wp_get_post_terms($post->ID, 'jobboard-tax-tags', array('fields' => 'names'));

            $page = get_option('jobboard_alerts_user_page', 0);
            $limit = jb_get_option('alerts-emails', 30);
            $paged = $limit * $page;

            $query = "SELECT u.* FROM {$wpdb->users} AS u";
            $query .= " LEFT JOIN {$wpdb->prefix}jobboard_interest AS i ON u.ID = i.user_id";
            $query .= " LEFT JOIN {$wpdb->usermeta} AS mt ON u.ID = mt.user_id";
            $query .= " WHERE (mt.meta_key = '_jobboard_alert_interest' AND mt.meta_value = '1'";

            if (!is_wp_error($types) && !empty($types)) {
                $query .= " AND ({$this->like_query('i.types', $types)}i.types = '')";
            }

            if (!is_wp_error($locations) && !empty($locations)) {
                $query .= " AND ({$this->like_query('i.locations', $locations)}i.locations = '')";
            }

            if (!is_wp_error($specialisms) && !empty($specialisms)) {
                $query .= " AND ({$this->like_query('i.specialisms', $specialisms)}i.specialisms = '')";
            }

            if (!is_wp_error($tags) && !empty($tags)) {
                $query .= " AND ({$this->like_query('i.keywords', $tags)}i.keywords = '')";
            }

            $query .= " AND (i.types <> '' OR i.specialisms <> '' OR i.locations <> '' OR i.keywords <> ''))";
            $query .= " OR mt.meta_key = '_jobboard_alert_posted' AND mt.meta_value = '1'";
            $query .= " GROUP BY i.user_id";
            $query .= " LIMIT {$paged},{$limit}";

            $users = $wpdb->get_results($query, OBJECT);

            if (empty($users)) {
                update_post_meta($post->ID, '_jobboard_alerts_sent', 1);
                $this->save_event_reset();
                exit();
            }

            $from = jb_get_option('alerts-email-from', get_bloginfo('name'));
            $reply = jb_get_option('alerts-email-reply', get_bloginfo('admin_email'));
            $subject = $post->post_title;

            ob_start();

            $this->get_template('emails/alerts.php', array('post' => $post));

            $message = ob_get_clean();

            foreach ($users as $user) {
                $email = new JobBoard_Emails($user->user_email, $from, $reply, $subject, $message);
                $email->send();
            }

            update_option('jobboard_alerts_user_page', $page + 1);
            update_option('jobboard_alerts_post_id', $post->ID);

            exit();
        }

        function save_event_reset()
        {
            update_option('jobboard_alerts_user_page', 0);
            update_option('jobboard_alerts_post_id', '');
        }

        function like_query($column, $keys)
        {

            $query = '';

            foreach ($keys as $key) {
                $query .= $column . " LIKE '%\\\"{$key}\\\"%' OR ";
            }

            return $query;
        }

        function cron_minutes($array)
        {
            $array['jobboard-alerts-minute-1'] = array(
                'interval' => jb_get_option('alerts-schedule', 10),
                'display'  => esc_html__('Once a Minute', JB_ALEART_TEXT_DOMAIN),
            );
            return $array;
        }

        function get_template($template_name, $args = array())
        {
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/alerts/', $this->plugin_directory . 'templates/');
        }
    }
}

function jb_alerts()
{
    return JB_Alerts::instance();
}

$GLOBALS['jobboard_alerts'] = jb_alerts();