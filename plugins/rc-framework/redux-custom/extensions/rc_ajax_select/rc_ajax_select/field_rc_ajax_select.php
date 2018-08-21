<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rc_ajax_select' ) ) {
    class ReduxFramework_rc_ajax_select extends ReduxFramework {
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent           = $parent;
            $this->field            = $field;
            $this->value            = $value;
            $this->is_field         = $this->parent->extensions['rc_ajax_select']->is_field;
            $this->extension_dir    = redux_custom()->extensions . 'rc_ajax_select/';
            $this->extension_url    = redux_custom()->extensions_url . 'rc_ajax_select/';
            $this->field            = wp_parse_args( $this->field, array(
                'source'        => 'post',
                'source-type'   => 'post',
                'save'          => 'meta',//save actions "meta/user/taxonomy"
                'placeholder'   => esc_html__( 'Enter a keyword.', 'redux-framework' )
            ));

            $screen = get_current_screen();

            if(($screen->base == 'post' || $screen->base == 'page') && $this->field['save'] == 'user'){
                global $post;
                $this->value = $post->post_author;
                $this->field['name'] = 'post_author_override';
            }
        }

        public function render() {
            $multi = ( isset( $this->field['multi'] ) && $this->field['multi'] ) ? ' multiple="multiple"' : "";
            $width = !empty( $this->field['width'] ) ? ' style="' . $this->field['width'] . '"' : ' style="width: 40%;"';

            echo '<select ' . $multi . ' id="' . $this->field['id'] . '-select" data-source="' . $this->field['source'] . '" data-type="' . $this->field['source-type'] . '" data-placeholder="' . $this->field['placeholder'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" class="redux-ajax-select-item ' . $this->field['class'] . '"' . $width . ' rows="6">';

            if(!is_array($this->value)) {
                echo '<option value="' . esc_attr($this->value) . '" selected="selected">' . esc_html($this->value_name($this->value)) . '</option>';
            } elseif (is_array($this->value) && !empty($this->value)){
                foreach ($this->value as $v){
                    echo '<option value="' . esc_attr($v) . '" selected="selected">' . esc_html($this->value_name($v)) . '</option>';
                }
            } else {
                echo '<option></option>';
            }

            echo '</select>';
        }

        public function value_name($name = ''){
            switch ($this->field['source-type']) {
                case 'user':
                    if($user = get_userdata($this->value)){
                        $name = $user->display_name;
                    }
                    break;
                case 'post':
                    $name = get_the_title($this->value);
                    break;
                case 'taxonomy':
                    $term = get_term($this->value, $this->field['source']);
                    if(is_wp_error($term) && $term){
                        $name = $term->name;
                    }
                    break;
            }
            return $name;
        }

        public function enqueue() {
            if (!wp_style_is ( 'selectize-css' )) {
                wp_enqueue_style(
                    'selectize-css',
                    $this->extension_url . 'rc_ajax_select/selectize.css',
                    array(),
                    '0.12.4',
                    'all'
                );
            }

            if (!wp_script_is ( 'selectize-js' )) {
                wp_enqueue_script(
                    'selectize-js',
                    $this->extension_url . 'rc_ajax_select/selectize.min.js',
                    array( 'jquery' ),
                    '0.12.4',
                    true
                );
            }

            if (!wp_script_is ( 'rc_ajax_select-js' )) {
                wp_enqueue_script(
                    'rc_ajax_select-js',
                    $this->extension_url . 'rc_ajax_select/field_rc_ajax_select' . Redux_Functions::isMin() . '.js',
                    array( 'jquery', 'redux-js' ),
                    time(),
                    true
                );
            }
        }
    }
}