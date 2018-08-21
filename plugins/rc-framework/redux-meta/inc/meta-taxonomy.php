<?php
/**
 * @class ReduxMeta_Taxonomy
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('ReduxMeta_Taxonomy')) {
    class ReduxMeta_Taxonomy{

        public $taxonomies = array();

        function __construct()
        {
            add_action( 'admin_init', array($this,'add_form_fields'));
            add_action( 'created_term', array( $this, 'save_meta' ), 10, 3 );
            add_action( 'edit_term', array( $this, 'save_meta' ), 10, 3 );
        }

        function add($args = array(), $sections = array(), $taxonomy = '', $label = ''){
            if(!class_exists('ReduxFramework') || !$taxonomy){
                return;
            }

            $args['meta_title'] = $label;

            if(isset($this->taxonomies[$taxonomy])){
                $args      = wp_parse_args($args, $this->taxonomies[$taxonomy]['args']);
                $sections  = array_merge($this->taxonomies[$taxonomy]['sections'], $sections);
            }

            $this->taxonomies[$taxonomy] = array(
                'args' => $args,
                'sections' => $sections
            );
        }

        function add_form_fields(){
            if(empty($this->taxonomies)){
                return;
            }

            $opt_name = array();

            foreach ($this->taxonomies as $tax => $args){
                add_action("{$tax}_add_form_fields",   array($this, 'add_taxonomy_fields'));
                add_action("{$tax}_edit_form_fields",  array($this, 'edit_taxonomy_fields' ), 10, 2 );

                if(!in_array($args['args']['opt_name'], $opt_name)){
                    add_action("redux/page/{$args['args']['opt_name']}/enqueue", array($this, 'panel_scripts'));
                    add_filter("redux/{$args['args']['opt_name']}/panel/templates_path", array($this, 'panel_template'));
                    add_filter("redux/options/{$args['args']['opt_name']}/options", array($this, 'get_values'));
                } else {
                    $opt_name[$args['args']['opt_name']] = $args['args']['opt_name'];
                }
            }
        }

        function add_taxonomy_fields($taxonomy){
            echo '<div class="form-field term-rc-custom-wrap">';
            $this->generate_panel($taxonomy);
            echo '</div>';
        }

        function edit_taxonomy_fields($tag, $taxonomy){
            echo '<tr class="form-field term-rc-custom-wrap">';
            echo '<th scope="row"><label>'.esc_html($this->taxonomies[$taxonomy]['args']['meta_title']).'</label></th>';
            echo '<td><div>';
            $this->generate_panel($taxonomy);
            echo '</div></td>';
            echo '</tr>';
        }

        function panel_scripts() {
            wp_enqueue_style('redux-meta-taxonomy', redux_meta()->plugin_directory_uri . 'assets/css/meta-taxonomy.css', null, time(), 'all');
        }

        function panel_template(){
            return redux_meta()->templates;
        }

        function generate_panel($taxonomy){
            $GLOBALS['redux_notice_check'] = true;
            $redux = new ReduxFramework($this->taxonomies[$taxonomy]['sections'], $this->taxonomies[$taxonomy]['args']);
            $redux->_register_settings();
            $redux->_enqueue();
            $redux->generate_panel();
        }

        function save_meta($term_id, $tt_id, $taxonomy){

            if(!isset($this->taxonomies[$taxonomy]['args']['opt_name'])){
                return;
            }

            $opt_name = $this->taxonomies[$taxonomy]['args']['opt_name'];

            if (empty($_POST[$opt_name])) {
                return;
            }

            foreach($_POST[$opt_name] as $key => $value){
                update_term_meta($term_id, $key, $value);
            }
        }

        function get_values($term_id = ''){
            global $tag;

            $data = array();

            if(!$term_id && $tag){
                $term_id = $tag->term_id;
            }

            if(empty($term_id)) {
                return $data;
            }

            $_custom = get_term_meta($term_id);

            if(empty($_custom)) {
                return $data;
            }

            foreach ($_custom as $key => $value){
                $data[$key] = maybe_unserialize($value[0]);
            }

            return $data;
        }
    }
}