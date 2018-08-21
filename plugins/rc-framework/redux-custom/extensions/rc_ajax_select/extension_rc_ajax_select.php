<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_Extension_rc_ajax_select' ) ) {

    class ReduxFramework_Extension_rc_ajax_select {
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public static $version = "4.0";
        public $is_field = false;

        public function __construct( $parent ) {
            $this->parent       = $parent;
            $this->field_name   = 'rc_ajax_select';
            self::$theInstance  = $this;
            $this->is_field     = Redux_Helpers::isFieldInUse($parent, $this->field_name);

            add_action( "wp_ajax_rc_ajax_select", array($this, "ajax_search"));
            add_action( 'mata_rc_ajax_select_saved', array($this, 'save_ajax_select'), 10, 3);
            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
                $this,
                'overload_field_path'
            ) );
        }

        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }

        public function ajax_search(){

            $data = array();

            if(!empty($_POST['q']) && !empty($_POST['source']) && !empty($_POST['type'])){

                if($_POST['type'] == 'user'){
                    $data = $this->query_user($_POST['q']);
                } elseif ($_POST['type'] == 'post') {
                    $data = $this->query_post($_POST['q'], $_POST['source']);
                } elseif ($_POST['type'] == 'taxonomy') {
                    $data = $this->query_taxonomy($_POST['q'], $_POST['source']);
                }
            }

            wp_send_json($data);
        }

        public function save_ajax_select($field, $post_id, $value){
            if(isset($field['save']) && $field['save'] == 'taxonomy' && !empty($field['taxonomy'])){
                wp_set_post_terms($post_id, $value, $field['taxonomy']);
            }
        }

        public function query_user($q){

            $_users = array();

            $query = array(
                'search'            => "*$q*",
                'search_columns'    => array('ID', 'user_login', 'user_nicename', 'user_email'),
                'number'            => 5
            );

            $users = new WP_User_Query($query);

            if(empty($users->results)){
                return $_users;
            }

            foreach ($users->results as $user){
                $_users[] = array(
                    'id'    => $user->data->ID,
                    'title' => $user->data->display_name,
                    'email' => $user->data->user_email,
                    'login' => $user->data->user_login,
                );
            }

            return $_users;
        }

        public function query_post($q, $post_type = 'post'){

            $_posts = array();

            $query = array(
                'post_type'         => $post_type,
                'posts_per_page'    => 5,
                's'                 => $q
            );

            $posts = new WP_Query($query);

            if(empty($posts->posts)){
                return $_posts;
            }

            foreach ($posts->posts as $post){
                $_posts[] = array(
                    'id'    => $post->ID,
                    'title' => $post->post_title
                );
            }

            return $_posts;
        }

        public function query_taxonomy($q, $taxonomy = 'category'){
            $_terms = array();

            $query = array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'number'     => 5,
                'search'     => $q
            );

            $terms = new WP_Term_Query($query);

            if(empty($terms->terms)){
                return $_terms;
            }

            foreach ($terms->terms as $terms){
                $_terms[] = array(
                    'id'    => $terms->term_id,
                    'title' => $terms->name
                );
            }

            return $_terms;
        }
    }
}
