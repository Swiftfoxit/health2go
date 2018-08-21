<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_Extension_rc_taxonomy_level' ) ) {

    class ReduxFramework_Extension_rc_taxonomy_level {
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public static $version  = "4.0";
        public $is_field        = false;

        public function __construct( $parent ) {
            $this->parent       = $parent;
            $this->field_name   = 'rc_taxonomy_level';
            self::$theInstance  = $this;
            $this->is_field     = Redux_Helpers::isFieldInUse($parent, $this->field_name);

            add_action( "wp_ajax_rc_taxonomy_level", array($this, "get_taxonomy_level"));
            add_action( "wp_ajax_nopriv_rc_taxonomy_level", array($this, "get_taxonomy_level"));
            add_action( 'meta_rc_taxonomy_level_saved', array($this, 'save_taxonomy_level'), 10, 3);
            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
                &$this,
                'overload_field_path'
            ) );
        }

        public function get_taxonomy_level(){

            if(empty($_POST['parent']) || empty($_POST['taxonomy']))
                exit();

            $_parent = get_term($_POST['parent'], $_POST['taxonomy'], OBJECT);

            if(is_wp_error($_parent))
                exit();

            $_terms = get_terms($_POST['taxonomy'], array(
                'hide_empty'    => false,
                'parent'      => $_parent->term_id,
            ));

            echo '<option value></option>';

            foreach ( $_terms as $k => $v ) {
                echo '<option value="' . $v->term_id . '">' . $v->name . '</option>';
            }

            exit();
        }

        public function get_taxonomy_value($post_id, $taxonomy){
            $terms      = wp_get_post_terms($post_id, $taxonomy);
            $_terms     = array();
            $locations  = array();

            if(is_wp_error($terms)){
                return $locations;
            }

            jb_sort_terms($terms, $_terms);

            if(empty($_terms)){
                return $locations;
            }

            foreach ($_terms as $term){
                $locations[] = $term->term_id;
            }

            return $locations;
        }

        public function save_taxonomy_level($field, $post_id, $value){
            if(isset($field['save']) && $field['save'] == 'taxonomy' && !empty($field['taxonomy'])){
                wp_set_post_terms($post_id, $value, $field['taxonomy']);
            }
        }

        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }
    }
}
