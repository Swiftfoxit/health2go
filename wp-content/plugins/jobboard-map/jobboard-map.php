<?php
/**
 * Plugin Name: JobBoard Map
 * Plugin URI: http://fsflex.com/
 * Description: Jobs location listing and geo-location search.
 * Version: 1.0.7
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-map
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_MAP_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_Map')) {
    class JB_Map
    {
        public static $instance;
        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public $query;
        public $user_map = false;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_Map();
                self::$instance->setup_globals();
                self::$instance->init();

                if ( ! function_exists( 'is_plugin_active' ) ) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if(is_plugin_active('jobboard/jobboard.php')){
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
            require_once $this->plugin_directory . 'inc/class.jm-install.php';
            register_activation_hook($this->file, array(new JB_Map_Install(), 'install'));
        }

        private function includes(){
            require_once $this->plugin_directory . 'inc/class.jm-query.php';
            require_once $this->plugin_directory . 'inc/class.jm-admin.php';
            require_once $this->plugin_directory . 'inc/class.jm-shortcodes.php';
            require_once $this->plugin_directory . 'inc/class.jm-update.php';

            $this->query = new JB_Map_Query();
        }

        private function actions(){
            add_action( 'wp_enqueue_scripts', array($this, 'add_scripts'));
            add_action( 'delete_user',  array($this, 'delete_user') );
            add_action( 'jobboard_endpoint_new_scripts', array($this, 'add_map_scripts'));
            add_action( 'jobboard_endpoint_new_scripts', array($this, 'add_endpoint_scripts'));
            add_action( 'jobboard_endpoint_profile_scripts', array($this, 'add_map_scripts'));
            add_action( 'jobboard_endpoint_profile_scripts', array($this, 'add_endpoint_scripts'));
            add_action( 'jobboard_profile_updated', array($this, 'profile_updated'), 10, 2);
            add_action( 'jobboard_migrated', array(new JB_Map_Install(), 'install'));
            add_action( 'save_post_jobboard-post-jobs', array($this, 'save_post'));

            add_action( 'jobboard_user_after_content', array($this, 'get_template_user_map'));

            add_action( 'wp_ajax_jb_map_search', array( $this, 'ajax_search' ));
            add_action( 'wp_ajax_nopriv_jb_map_search', array( $this, 'ajax_search' ));

            add_action( 'jobboard_map_marker_info', 'jb_template_job_loop_summary_title', 10);
            add_action( 'jobboard_map_marker_info', 'jb_template_job_loop_summary_salary', 20);
            add_action( 'jobboard_map_marker_info', 'jb_template_job_loop_summary_type', 30);

            add_filter( 'jobboard_add_job_fields', array($this, 'add_map_field'));
            add_filter( 'jobboard_field_geolocation_template_part', array($this, 'get_template_template_part'));
            add_filter( 'jobboard_field_geolocation_default_path', array($this, 'get_template_default_path'));
        }

        function add_scripts(){
            wp_enqueue_style( 'jobboard-map', $this->plugin_directory_uri . 'assets/css/jobboard-map.css');

            if(!is_jb_profile()){
                return;
            }

            global $jobboard_account;

            $map = get_user_meta($jobboard_account->ID, 'map', true);

            if(empty($map['lat']) || empty($map['lng'])){
                return;
            }

            $this->user_map = true;

            $center = array(
                'lat' => (float)$map['lat'],
                'lng' => (float)$map['lng']
            );

            $map = array(
                'zoom'              => 15,
                'center'            => (object)$center,
                'styles'            => $this->get_style(),
                'scrollwheel'       => false,
                'zoomControl'       => true,
                'mapTypeControl'    => false,
                'scaleControl'      => false,
                'streetViewControl' => false,
                'rotateControl'     => false,
                'fullscreenControl' => true,
            );

            $this->add_map_scripts();
            wp_enqueue_script('jobboard-map-user', $this->plugin_directory_uri . 'assets/js/user-map.js', array('google-map'), time(), true);
            wp_localize_script( 'jobboard-map-user', 'jobboard_map_user', array(
                'setting'   => $map,
                'info'      => $this->get_template_user_info(),
                'marker'    => jb_get_option('map-marker', array('url' => ''))
            ));
            wp_enqueue_script( 'jobboard-map-user');
        }

        function add_map_scripts(){
            $googleapis = 'https://maps.googleapis.com/maps/api/js?libraries=places';
            if(jb_get_option('map-api')){
                $googleapis = add_query_arg('key', jb_get_option('map-api'), $googleapis);
            }

            /* load scripts. */
            wp_enqueue_script( 'google-map', $googleapis, array(), time(), true);
            wp_enqueue_script( 'markerclusterer', $this->plugin_directory_uri . 'assets/js/markerclusterer.js', array('google-map'), time(), true);
        }

        function add_jobs_scripts(){
            $default    = jb_get_option('map-default', array('zoom' => 2));
            $marker     = jb_get_option('map-marker', array('url' => ''));
            $jb_map     = apply_filters('jb/map/options', array(
                'map' => array(
                    'zoom'              => (int)$default['zoom'],
                    'center'            => (object)$this->get_center(),
                    'styles'            => $this->get_style(),
                    'scrollwheel'       => false,
                    'zoomControl'       => (boolean)jb_get_option('map-control-zoom', true),
                    'mapTypeControl'    => (boolean)jb_get_option('map-control-maptype', false),
                    'scaleControl'      => (boolean)jb_get_option('map-control-scale', false),
                    'streetViewControl' => (boolean)jb_get_option('map-control-streetview', false),
                    'rotateControl'     => (boolean)jb_get_option('map-control-rotate', false),
                    'fullscreenControl' => (boolean)jb_get_option('map-control-fullscreen', true),
                ),
                'args'                  => array(
                    'icon'              => $marker['url'],
                    'icons'             => $this->get_icons(),
                    'markers'           => $this->get_markers(),
                    'templateControls'  => $this->get_template_controls(),
                    'templateSearch'    => $this->get_template_search(),
                    'ajaxUrl'           => admin_url('admin-ajax.php'),
                    'searchControl'     => (boolean)jb_get_option('map-search-control'),
                    'geoLocation'       => $this->get_geo(),
                )
            ));
            wp_register_script( 'jobboard-map', $this->plugin_directory_uri . 'assets/js/jobboard-map.js', array('jquery', 'google-map', 'markerclusterer'), time(), true);
            wp_localize_script( 'jobboard-map', 'jb_map', $jb_map);
            wp_enqueue_script( 'jobboard-map');
        }

        public function add_event_script(){
	        $default    = jb_get_option('map-default', array('zoom' => 2));
	        $marker     = jb_get_option('map-marker', array('url' => ''));
	        $jb_map     = apply_filters('jb/map/options', array(
		        'map' => array(
			        'zoom'              => (int)$default['zoom'],
			        'center'            => (object)$this->get_center(),
			        'styles'            => $this->get_style(),
			        'scrollwheel'       => false,
			        'zoomControl'       => (boolean)jb_get_option('map-control-zoom', true),
			        'mapTypeControl'    => (boolean)jb_get_option('map-control-maptype', false),
			        'scaleControl'      => (boolean)jb_get_option('map-control-scale', false),
			        'streetViewControl' => (boolean)jb_get_option('map-control-streetview', false),
			        'rotateControl'     => (boolean)jb_get_option('map-control-rotate', false),
			        'fullscreenControl' => (boolean)jb_get_option('map-control-fullscreen', true),
		        ),
		        'args'                  => array(
			        'icon'              => $marker['url'],
			        'icons'             => $this->get_icons(),
			        'markers'           => $this->get_single_markers(),
			        'templateControls'  => $this->get_template_controls(),
			        'templateSearch'    => $this->get_template_search(),
			        'ajaxUrl'           => admin_url('admin-ajax.php'),
			        'searchControl'     => (boolean)jb_get_option('map-search-control'),
			        'geoLocation'       => $this->get_geo(),
		        )
	        ));
	        wp_register_script( 'jobboard-single-map', $this->plugin_directory_uri . 'assets/js/jobboard-single-map.js', array('jquery', 'google-map', 'markerclusterer'), time(), true);
	        wp_localize_script( 'jobboard-single-map', 'jb_single_map', $jb_map);
	        wp_enqueue_script( 'jobboard-single-map');
//	        var_dump($this->get_markers());
        }

        function add_endpoint_scripts(){
            wp_enqueue_style( 'jobboard-map-geo-locations', $this->plugin_directory_uri . 'assets/css/geo-locations.css');
            wp_enqueue_script( 'jobboard-map-geo-locations', $this->plugin_directory_uri . 'assets/js/geo-locations.js', array('google-map'), time(), true);
        }

        function add_map_field($fields){
            $values = array(
                's'     => '',
                'lat'   => '',
                'lng'   => '',
                'zoom'  => '',
            );

            if(!empty($_POST['_map'])){
                $values = $_POST['_map'];
            }

            $fields[] = array(
                'id'         => 'map-heading',
                'title'      => esc_html__('Job Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Candidates easily find job around their area, please add a location.', JB_MAP_TEXT_DOMAIN ),
                'type'       => 'heading',
                'heading'    => 'h3'
            );

            $fields[] = array (
                'id'         => '_map',
                'title'      => esc_html__('Geo Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Search a location or right mouse click on map.', JB_MAP_TEXT_DOMAIN ),
                'type'       => 'geolocation',
                'value'      => $values
            );

            return $fields;
        }

        function profile_updated($user_id, $user_meta){
            if(empty($user_meta['map']['lat']) || empty($user_meta['map']['lng'])){
                return;
            }

            $lat = $user_meta['map']['lat'];
            $lng = $user_meta['map']['lng'];

            $this->query->update_user_location($user_id, $lat, $lng);
        }

        function save_post($post_id){
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if(isset($_POST['jobboard_meta']['_map'])){
                $map = $_POST['jobboard_meta']['_map'];
            } elseif (isset($_POST['_map'])){
                $map = $_POST['_map'];
            } else {
                return;
            }

            $lat = $map['lat'];
            $lng = $map['lng'];

            if($lat && $lng) {
                $this->query->update_geo_location($post_id, $lat, $lng);
            }
        }

        function delete_user($user_id){
            global $wpdb;
            if(is_jb_account($user_id)) {
                $wpdb->delete($wpdb->prefix . 'jobboard_userlocation',
                    array('user_id' => $user_id),
                    array('%d')
                );
            }
        }

        function ajax_search(){

            header('Content-Type: application/json');

            $markers = array();

            if(!empty($_POST['options'])) {
                $markers = $this->query->archive_map($_POST['options']);
            }

            exit(json_encode($markers));
        }

        function get_center(){

            $default = jb_get_option('map-default', array(
                'lat'=> 52.268160656613134,
                'lng' => -1.8281292915344238
            ));

            return array(
                'lat' => (float)$default['lat'],
                'lng' => (float)$default['lng']
            );
        }

        function get_icons(){
            $icons_dir = 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m';
            if(jb_get_option('map-marker-group')){
                $path      = JB()->template_path() . 'add-ons/map/markers/';
                $icons_dir = trailingslashit(get_template_directory_uri() . '/' . $path . jb_get_option('map-marker-group'));
            }
            return apply_filters('jobboard_map_markers_dir', $icons_dir);
        }

        function get_single_markers(){
	        global $post;
	        $_map = get_post_meta($post->ID, '_map', true);
	        $markers = array();
	        if($_map){
		        $markers[$post->ID]['lat']  = (float)$_map['lat'];
		        $markers[$post->ID]['lng']  = (float)$_map['lng'];
		        $markers[$post->ID]['info'] = apply_filters('jb/map/info/window', jb_map()->get_template_info_window(), $post);
	        }
	        return $markers;
        }

        function get_markers(){
            $markers = array();

            if(function_exists('is_jb') && is_jb() && have_posts()){
                while (have_posts()){ the_post();
                    global $post;
                    $_map = get_post_meta($post->ID, '_map', true);

                    if($_map){
                        $markers[$post->ID]['lat']  = (float)$_map['lat'];
                        $markers[$post->ID]['lng']  = (float)$_map['lng'];
                        $markers[$post->ID]['info'] = apply_filters('jb/map/info/window', jb_map()->get_template_info_window(), $post);
                    }
                }
            } else {
                $markers = $this->query->archive_map();
            }

            return $markers;
        }

        function get_geo(){

            if(function_exists('is_jb_jobs') && is_jb_jobs()){
                return (boolean)jb_get_option('map-geolocation', true);
            }

            return false;
        }

        function get_style(){
            $json   = array();
            $style  = jb_get_option('map-style', 'standard');
            $custom = jb_get_option('map-style-custom');

            if($style != 'standard' && $style != 'custom'){

                global $wp_filesystem;

                if (empty($wp_filesystem)) {
                    require_once (ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }

                if($style_json = $wp_filesystem->get_contents(jb_map()->plugin_directory . 'assets/json/' . $style . '.json')){
                    $json = json_decode($style_json);
                }

            } elseif ($style == 'custom' && $custom){
                $json = json_decode($custom);
            }

            return apply_filters('jb/map/style', $json, $style);
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, $this->get_template_template_part(), $this->get_template_default_path());
        }

        function get_template_controls(){
            $tabs = array(
                'map'    => esc_html__('Map', JB_MAP_TEXT_DOMAIN),
                'search' => esc_html__('Search', JB_MAP_TEXT_DOMAIN)
            );

            $active = jb_get_option('map-search-active') ? 'search' : 'map';

            ob_start();

            $this->get_template('controls.php', array('tabs' => $tabs, 'active' => $active));

            return ob_get_clean();
        }

        function get_template_search(){
            $active = jb_get_option('map-search-active') ? ' active' : '';
            ob_start();
            $this->get_template('search-form.php', array('active' => $active));
            return ob_get_clean();
        }

        function get_template_info_window(){

            ob_start();

            $this->get_template('info-window.php');

            return ob_get_clean();
        }

        function get_template_default_path(){
            return $this->plugin_directory . 'templates/';
        }

        function get_template_template_part(){
            return JB()->template_path() . 'add-ons/map/';
        }

        function get_template_user_map(){
            if($this->user_map !== true){
                return;
            }

            $this->get_template('map.php');
        }

        function get_template_user_info(){
            ob_start();
            $this->get_template('user-info.php');
            return ob_get_clean();
        }

        function get_template_markers(){

        }
    }
}

function jb_map(){
    return JB_Map::instance();
}

$GLOBALS['jobboard_map'] = jb_map();