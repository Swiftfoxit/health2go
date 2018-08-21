<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rc_icons' ) ) {

    /**
     * Main ReduxFramework_import_export class
     *
     * @since       1.0.0
     */
    class ReduxFramework_rc_icons extends ReduxFramework {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent   = $parent;
            $this->field    = $field;
            $this->value    = $value;
            $this->is_field = $this->parent->extensions['rc_icons']->is_field;

            $this->extension_dir = redux_custom()->extensions . 'rc_icons/';
            $this->extension_url = redux_custom()->extensions_url . 'rc_icons/';

            // Set default args for this field to avoid bad indexes. Change this to anything you use.

            $defaults       = array(
                'fonts'     => array()
            );

            $this->field    = wp_parse_args( $this->field, $defaults );
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            $readonly       = ( isset( $this->field['readonly'] ) && $this->field['readonly']) ? ' readonly="readonly"' : '';
            $placeholder    = ( isset( $this->field['placeholder'] ) && ! is_array( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_attr( $this->field['placeholder'] ) . '" ' : '';
            $autocomplete   = ( isset($this->field['autocomplete']) && $this->field['autocomplete'] == false) ? ' autocomplete="off"' : '';
            $icon           = $this->value ? $this->value : 'dashicons dashicons-external';

            echo '<input type="text" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" ' . $placeholder . 'value="' . esc_attr( $this->value ) . '" class="regular-text wpl-redux-icon ' . $this->field['class'] . '"' . $readonly . $autocomplete . ' />';
            echo '<a type="button" href="#TB_inline?width=auto&height=550&inlineId=wpl-redux-fonts" title="'.esc_attr__('Select Icon', 'redux-custom').'" class="thickbox button wpl-redux-icon-button"><i class="'.esc_attr($icon).'"></i></a>';

            if(!empty($this->field['fonts'])) {
                echo '<div id="wpl-redux-fonts" class="wpl-redux-fonts" style="display: none">';
                echo '<input id="wpl-redux-icon-search" type="text" placeholder="' . esc_attr__('Search...', 'redux-custom') . '">';
                foreach ($this->field['fonts'] as $font) {
                    if (!empty($font['class'])) {
                        echo '<span class="wpl-redux-font-title">' . $font['name'] . '</span>';
                        echo '<ul id="' . $font['id'] . '" class="wpl-redux-font">';
                        foreach ($font['class'] as $class) {
                            echo '<li title="' . esc_attr($class) . '"><i class="' . esc_attr($class) . '"></i></li>';
                        }
                        echo '</ul>';
                    }
                }
                echo '</div>';
            }
        }

        /**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            /* load fonts */
            if(isset($this->field['fonts'])){
                foreach ($this->field['fonts'] as $font) {
                    if (!wp_script_is($font['id'])) {
                        wp_enqueue_style($font['id'], $font['file'], array(), time(), 'all');
                    }
                }
            }

            if (!wp_script_is ( 'rc-icons-js' )) {
                wp_enqueue_script(
                    'rc-icons-js',
                    $this->extension_url . 'rc_icons/field_rc_icons' . Redux_Functions::isMin() . '.js',
                    array( 'jquery', 'redux-js' ),
                    time(),
                    true
                );
            }

            if (!wp_style_is ( 'rc-icons-css' )) {
                wp_enqueue_style(
                    'rc-icons-css',
                    $this->extension_url . 'rc_icons/field_rc_icons.css',
                    array(),
                    time(),
                    'all'
                );
            }
        }
    }
}