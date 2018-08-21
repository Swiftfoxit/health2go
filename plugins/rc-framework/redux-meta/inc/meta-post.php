<?php
/**
 * @class ReduxMeta_Post
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('ReduxMeta_Post')) {
    class ReduxMeta_Post
    {
        public $posts = array();

        function __construct()
        {
            add_action( 'add_meta_boxes', array($this,'add_meta_box'));
        }

        function add($args = array(), $sections = array(), $id = '', $label = '', $post_type = 'page', $context = 'advanced', $priority = 'default'){
            if(!class_exists('ReduxFramework') || !$id){
                return;
            }

            $args['meta_id']        = $id;
            $args['meta_title']     = $label;
            $args['meta_context']   = $context;
            $args['meta_priority']  = $priority;

            if(isset($this->posts[$post_type])){
                $args      = wp_parse_args($args, $this->posts[$post_type]['args']);
                $sections  = array_merge($this->posts[$post_type]['sections'], $sections);
            } else {
                add_action( 'save_post_' . $post_type, array($this, 'save_meta'), 10, 2);
            }

            $this->posts[$post_type] = array(
                'args'      => $args,
                'sections'  => $sections
            );
        }

        function add_meta_box(){
            if(empty($this->posts)){
                return;
            }

            $opt_name = array();

            foreach ($this->posts as $type => $args){
                add_meta_box($args['args']['meta_id'], $args['args']['meta_title'], array( $this, 'generate_panel'), $type, $args['args']['meta_context'], $args['args']['meta_priority']);
                add_filter( "postbox_classes_{$type}_{$args['args']['meta_id']}", array( $this, 'add_meta_box_class' ) );

                if(!in_array($args['args']['opt_name'], $opt_name)){
                    add_action("redux/page/{$args['args']['opt_name']}/enqueue", array($this, 'panel_scripts'));
                    add_filter("redux/{$args['args']['opt_name']}/panel/templates_path", array($this, 'panel_template'));
                    add_filter("redux/options/{$args['args']['opt_name']}/options", array($this, 'get_values'));
                } else {
                    $opt_name[$args['args']['opt_name']] = $args['args']['opt_name'];
                }
            }
        }

        function add_meta_box_class($class){
            $class[] = 'redux-meta';
            return $class;
        }

        function panel_scripts() {
            wp_enqueue_style('redux-meta-post', redux_meta()->plugin_directory_uri . 'assets/css/meta-post.css', null, time(), 'all');
            wp_enqueue_script('redux-meta-post', redux_meta()->plugin_directory_uri . 'assets/js/meta-post.js', array( 'jquery' ), time(), true);
        }

        function panel_template(){
            return redux_meta()->templates;
        }

        function generate_panel($post){
            $GLOBALS['redux_notice_check'] = true;
            $this->redux = new ReduxFramework($this->posts[$post->post_type]['sections'], $this->posts[$post->post_type]['args']);
            $this->redux->_register_settings();
            $this->redux->_enqueue();
            $this->redux->generate_panel();
        }

        function save_meta($post_id, $post)
        {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            $opt_name = $this->get_opt($post->post_type);
            $fields   = $this->get_fields($post->post_type, $opt_name);
            if (empty($_POST[$opt_name]) || empty($fields)) {
                return;
            }

            $data = apply_filters( "rc_{$post->post_type}_{$opt_name}_data", (array)$_POST[$opt_name], $post_id, $fields);
            foreach ($data as $key => $value){
                if(!isset($fields[$key]))
                    continue;
                update_post_meta($post_id, $key, $value);
                do_action( "meta_{$fields[$key]['type']}_saved", $fields[$key], $post_id, $value);
            }

            do_action( "rc_{$post->post_type}_{$opt_name}_saved", $post_id, $data, $fields);
        }

        function get_opt($post_type){
            return !empty($this->posts[$post_type]['args']['opt_name']) ? (string)$this->posts[$post_type]['args']['opt_name'] : 'opt_meta_options';
        }

        function get_fields($post_type, $opt_name){
            $fields   = array();
            $sections = $this->get_sections($post_type, $opt_name);
            if(!empty($sections)) {
                foreach ($sections as $section){
                    if(empty($section['fields']))
                        continue;
                    foreach ($section['fields'] as $field){
                        if(empty($field['id']))
                            continue;
                        $fields[$field['id']] = $field;
                    }
                }
            }
            return apply_filters("rc_{$post_type}_{$opt_name}_fields", $fields);
        }

        function get_sections($post_type, $opt_name){
            $sections = !empty($this->posts[$post_type]['sections']) ? (array)$this->posts[$post_type]['sections'] : array();
            return apply_filters( "rc_{$post_type}_{$opt_name}_sections", $sections);
        }

        function get_values($post = ''){
            $post     = get_post($post);
            $data     = array();
            $opt_name = $this->get_opt($post->post_type);

            if(empty($post->ID)) {
                return $data;
            }

            $_custom = get_post_custom($post->ID);

            foreach ($_custom as $key => $value){
                $data[$key] = maybe_unserialize($value[0]);
            }

            return apply_filters( "rc_{$post->post_type}_{$opt_name}_values", $data, $post);
        }
    }
}