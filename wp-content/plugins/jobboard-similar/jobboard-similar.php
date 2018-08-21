<?php
/**
 * Plugin Name: JobBoard Similar
 * Plugin URI: http://fsflex.com/
 * Description: Similar behavior analysis and give a list similar jobs.
 * Version: 1.0.3
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-similar
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_SIMILAR_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_Similar')) {
    class JB_Similar
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_Similar();
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
            require_once $this->plugin_directory . 'inc/class.js-admin.php';
            require_once $this->plugin_directory . 'inc/class.js-update.php';
        }

        private function actions(){
            add_action('wp', array($this, 'set_cookie'));
            add_action('jobboard_search_form', array($this, 'template_similar_keywords'), 110);
            add_filter('jobboard_widget_jobs_types', array($this, 'add_widget_options'));
            add_filter('jobboard_widget_jobs_query_similar_args', array($this, 'similar'));
        }

        function similar($query){
            global $post;

            $type = $location = $specialism = false;

            if(is_jb_job()){
                $query['post__not_in'] = array($post->ID);
            }

            if(!empty($_COOKIE['jobboard-similar'])) {
                $query['tax_query']['relation'] = 'AND';
                $type = $this->get_terms('jobboard-tax-types', 2);
                $location = $this->get_terms('jobboard-tax-locations', 2);
                $specialism = $this->get_terms('jobboard-tax-specialisms', 5);
            }

            if($type) {
                $query['tax_query'][] = array(
                    'taxonomy' => 'jobboard-tax-types',
                    'field' => 'term_id',
                    'terms' => $type,
                );
            }

            if($location) {
                $query['tax_query'][] = array(
                    'taxonomy' => 'jobboard-tax-locations',
                    'field' => 'term_id',
                    'terms' => $location
                );
            }

            if($specialism) {
                $query['tax_query'][] = array(
                    'taxonomy' => 'jobboard-tax-specialisms',
                    'field' => 'term_id',
                    'terms' => $specialism,
                );
            }

            return $query;
        }

        function add_widget_options($type){
            $type['similar'] = esc_html__('Similar', JB_SIMILAR_TEXT_DOMAIN);
            return $type;
        }

        function set_cookie(){
            global $post;

            $current_id = isset($_COOKIE['jobboard-similar']['id']) ? $_COOKIE['jobboard-similar']['id'] : 0 ;

            if(is_jb_job() && $current_id != $post->ID){

                $this->set_terms_priority('jobboard-tax-types');
                $this->set_terms_priority('jobboard-tax-specialisms');
                $this->set_terms_priority('jobboard-tax-locations');

                setcookie("jobboard-similar[id]", $post->ID, $this->get_expire(15), '/');

            } elseif (is_jb_taxonomy()){

                $term = get_queried_object();

                $this->set_term_priority($term);

            } elseif (is_jb_search() && have_posts()){
                $s          = strtolower($_GET['s']);
                $s_length   = strlen($_GET['s']);

                if($s_length < 2 || $s_length > 50){
                    return;
                }

                setcookie("jobboard-similar[s][{$s}]", $s, $this->get_expire(1), '/');

                $search = get_option('jobboard-similar-keywords');

                if(empty($search)){
                    $search = array();
                }

                if(isset($search[$s])){
                    $search[$s] = $search[$s] + 1;
                } elseif(count($search) > 50) {
                    array_splice($search, 30 , count($search));
                    array_splice($search, 0 , 5);
                    $search[$s] = 1;
                } else {
                    $search[$s] = 1;
                }

                arsort($search);

                update_option('jobboard-similar-keywords', $search);
            }
        }

        function get_expire($days = 15){
            return time() + 86400 * $days;
        }

        function set_terms_priority($taxonomy){
            global $post;

            $terms  = get_the_terms( $post->ID, $taxonomy );

            if(!$terms || is_wp_error($terms)){
                return;
            }

            foreach ($terms as $term){

                $this->set_term_priority($term);
            }
        }

        function set_term_priority($term){

            $priority = isset($_COOKIE['jobboard-similar'][$term->taxonomy][$term->term_id]) ? (int)$_COOKIE['jobboard-similar'][$term->taxonomy][$term->term_id] + 1 : 1;

            setcookie("jobboard-similar[{$term->taxonomy}][{$term->term_id}]", $priority, $this->get_expire(15), '/');
        }

        function get_terms($taxonomy, $level = 1){

            if(empty($_COOKIE['jobboard-similar'][$taxonomy])){
                return false;
            }

            $cookie_ids = $_COOKIE['jobboard-similar'][$taxonomy];

            if(count($cookie_ids) < $level){
                return false;
            }

            $term_ids   = array();

            arsort($cookie_ids); $i = 0;

            foreach ($cookie_ids as $id => $priority){

                if($i < $level){
                    $term_ids[] = $id;
                }

                $i++;
            }

            return $term_ids;
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/similar/', $this->plugin_directory . 'templates/');
        }

        function template_similar_keywords(){

            if(!jb_get_option('search-similar')){
                return;
            }

            $keywords = get_option('jobboard-similar-keywords');

            if(is_array($keywords) && count($keywords) > 5) {
                $keywords = array_slice($keywords, 0, 5);
            }

            $this->get_template('similar-search.php', array('keywords' => $keywords));
        }
    }
}

function jb_similar(){
    return JB_Similar::instance();
}

$GLOBALS['jobboard_similar'] = jb_similar();