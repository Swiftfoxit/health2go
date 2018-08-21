<?php
/**
 * Plugin Name: JobBoard Alpha Listing
 * Plugin URI: http://fsflex.com/
 * Description: Listing Alpha Beta for Locations, Specialisms, Tags, Candidates, Employers, ... add-on for JobBoard plugin.
 * Version: 1.0.1
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-alphabeta
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_ALPHABETA_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_AlphaBeta')) {
    class JB_AlphaBeta
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new JB_AlphaBeta();
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
            add_action( 'vc_before_init', array($this, 'vc'));
            add_filter( 'fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter( 'fsflex_update_plugin_jobboard-alphabeta_data', array($this, 'plugin_info'));
            add_shortcode( 'jobboard-alpha-beta-terms', array($this, 'terms'));
            add_shortcode( 'jobboard-alpha-beta-users', array($this, 'users'));
        }

        function get_chars(){
            return array(
                'a'     => esc_html__('A', JB_ALPHABETA_TEXT_DOMAIN),
                'b'     => esc_html__('B', JB_ALPHABETA_TEXT_DOMAIN),
                'c'     => esc_html__('C', JB_ALPHABETA_TEXT_DOMAIN),
                'd'     => esc_html__('D', JB_ALPHABETA_TEXT_DOMAIN),
                'e'     => esc_html__('E', JB_ALPHABETA_TEXT_DOMAIN),
                'f'     => esc_html__('F', JB_ALPHABETA_TEXT_DOMAIN),
                'g'     => esc_html__('G', JB_ALPHABETA_TEXT_DOMAIN),
                'h'     => esc_html__('H', JB_ALPHABETA_TEXT_DOMAIN),
                'i'     => esc_html__('I', JB_ALPHABETA_TEXT_DOMAIN),
                'j'     => esc_html__('J', JB_ALPHABETA_TEXT_DOMAIN),
                'k'     => esc_html__('K', JB_ALPHABETA_TEXT_DOMAIN),
                'l'     => esc_html__('L', JB_ALPHABETA_TEXT_DOMAIN),
                'm'     => esc_html__('M', JB_ALPHABETA_TEXT_DOMAIN),
                'n'     => esc_html__('N', JB_ALPHABETA_TEXT_DOMAIN),
                'o'     => esc_html__('O', JB_ALPHABETA_TEXT_DOMAIN),
                'p'     => esc_html__('P', JB_ALPHABETA_TEXT_DOMAIN),
                'q'     => esc_html__('Q', JB_ALPHABETA_TEXT_DOMAIN),
                'r'     => esc_html__('R', JB_ALPHABETA_TEXT_DOMAIN),
                's'     => esc_html__('S', JB_ALPHABETA_TEXT_DOMAIN),
                't'     => esc_html__('T', JB_ALPHABETA_TEXT_DOMAIN),
                'u'     => esc_html__('U', JB_ALPHABETA_TEXT_DOMAIN),
                'v'     => esc_html__('V', JB_ALPHABETA_TEXT_DOMAIN),
                'w'     => esc_html__('W', JB_ALPHABETA_TEXT_DOMAIN),
                'x'     => esc_html__('X', JB_ALPHABETA_TEXT_DOMAIN),
                'y'     => esc_html__('Y', JB_ALPHABETA_TEXT_DOMAIN),
                'z'     => esc_html__('Z', JB_ALPHABETA_TEXT_DOMAIN)
            );
        }

        function terms($atts = array(), $content = '')
        {
            $query = shortcode_atts(array(
                'taxonomy'      => 'jobboard-tax-specialisms',
                'hide_empty'    => false,
                'parent'        => '',
                'orderby'       => 'name',
                'order'         => 'ASC'
            ), $atts);

            if($query['parent'] == true){
                $query['parent'] = 0;
            }

            $this->load_scripts();

            ob_start();
            $this->get_template('terms-alphabeta.php', array(
                'chars' => $this->get_chars(),
                'terms' => get_terms($query)
            ));
            return ob_get_clean();
        }

        function users($atts = array(), $content = ''){
            $query = array(
                'role__in'      => array('jobboard_role_employer'),
                'count_total'   => true
            );

            $this->load_scripts();

            ob_start();
            $this->get_template('users-alphabeta.php', array(
                'chars' => $this->get_chars(),
                'users' => get_users($query),
            ));
            return ob_get_clean();
        }

        function load_scripts(){
            wp_enqueue_style('jobboard-alphabeta', $this->plugin_directory_uri . 'assets/css/jobboard-alphabeta.css');
        }

        function vc()
        {
            vc_map( array(
                "name"          => esc_html__( "Taxonomy Listing", "jobboard-alphabeta" ),
                "base"          => "jobboard-alpha-beta-terms",
                "category"      => esc_html__( "JobBoard", "jobboard-alphabeta"),
                "description"   => esc_html__( "Listing Alpha Beta", "jobboard-alphabeta"),
                "params"        => array(
                    array(
                        "heading"       => esc_html__( "Listing", "jobboard-alphabeta" ),
                        'description'   => esc_html__( 'Select Listing source.', JB_ALPHABETA_TEXT_DOMAIN ),
                        "type"          => "dropdown",
                        "param_name"    => "taxonomy",
                        "admin_label"   => true,
                        "value"         => array(
                            esc_html__( "Specialisms", "jobboard-alphabeta" )   => "jobboard-tax-specialisms",
                            esc_html__( "Locations", "jobboard-alphabeta" )     => "jobboard-tax-locations",
                            esc_html__( "Tags", "jobboard-alphabeta" )          => "jobboard-tax-tags",
                        )
                    ),
                    array(
                        "heading"       => esc_html__( "First Level", "jobboard-alphabeta" ),
                        'description'   => esc_html__( 'Show first level, default show all.', JB_ALPHABETA_TEXT_DOMAIN ),
                        "type"          => "checkbox",
                        "param_name"    => "parent"
                    ),
                    array(
                        "heading"       => esc_html__( "Hide Empty", "jobboard-alphabeta" ),
                        'description'   => esc_html__( 'Hide terms not assigned to any jobs.', JB_ALPHABETA_TEXT_DOMAIN ),
                        "type"          => "checkbox",
                        "param_name"    => "hide_empty",
                    )
                )
            ));

            vc_map( array(
                "name"          => esc_html__( "Company Listing", "jobboard-alphabeta" ),
                "base"          => "jobboard-alpha-beta-users",
                "category"      => esc_html__( "JobBoard", "jobboard-alphabeta"),
                "description"   => esc_html__( "Listing Alpha Beta", "jobboard-alphabeta"),
                "show_settings_on_create" => false
            ));
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/alphabeta/', $this->plugin_directory . 'templates/');
        }

        function add_update($slugs = array()){
            $slugs[] = 'jobboard-alphabeta';
            return $slugs;
        }

        function plugin_info(){
            return 'jobboard-alphabeta';
        }
    }
}

function jb_alphabeta(){
    return JB_AlphaBeta::instance();
}

$GLOBALS['jobboard_alphabeta'] = jb_alphabeta();