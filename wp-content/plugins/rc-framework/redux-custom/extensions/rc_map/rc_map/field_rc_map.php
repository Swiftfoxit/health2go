<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rc_map' ) ) {

    /**
     * Main ReduxFramework_rc_images_size class
     *
     * @since       1.0.0
     */
    class ReduxFramework_rc_map extends ReduxFramework {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent           = $parent;
            $this->field            = $field;
            $this->value            = $value;
            $this->is_field         = $this->parent->extensions['rc_map']->is_field;

            $this->extension_dir    = redux_custom()->extensions . 'rc_map/';
            $this->extension_url    = redux_custom()->extensions_url . 'rc_map/';

            // Set default args for this field to avoid bad indexes. Change this to anything you use.

            $defaults       = array();

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

            $s      = isset($this->value['s']) ? $this->value['s'] : '';
            $lat    = isset($this->value['lat']) ? $this->value['lat'] : '';
            $lng    = isset($this->value['lng']) ? $this->value['lng'] : '';
            $zoom   = isset($this->value['zoom']) ? $this->value['zoom'] : 1;

            echo '<input class="rc_map_search" name="' . $this->field['name'] . $this->field['name_suffix'] . '[s]" value="' . esc_attr( $s ) . '" type="search" placeholder="' . esc_attr__('Enter Address', 'redux-custom') . '">';
            echo '<div class="rc_map_content"></div>';

            echo '<input type="hidden" name="' . $this->field['name'] . $this->field['name_suffix'] . '[lat]" value="' . esc_attr( $lat ) . '" class="rc_map_location rc_map_lat ' . $this->field['class'] . '"/>';
            echo '<input type="hidden" name="' . $this->field['name'] . $this->field['name_suffix'] . '[lng]" value="' . esc_attr( $lng ) . '" class="rc_map_location rc_map_lng ' . $this->field['class'] . '"/>';
            echo '<input type="hidden" name="' . $this->field['name'] . $this->field['name_suffix'] . '[zoom]" value="' . esc_attr( $zoom ) . '" class="rc_map_location rc_map_zoom ' . $this->field['class'] . '"/>';
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

            if (!wp_script_is ( 'google-map' )) {
                $api = apply_filters('rc_map_api', '');
                $src = 'https://maps.googleapis.com/maps/api/js?libraries=places';
                $src = $api ? add_query_arg('key', $api, $src) : $src;
                wp_enqueue_script('google-map', $src, array(), time(), true);
            }

            if (!wp_script_is ( 'rc-map-js' )) {
                wp_enqueue_script(
                    'rc-map-js',
                    $this->extension_url . 'rc_map/field_rc_map' . Redux_Functions::isMin() . '.js',
                    array('google-map'),
                    time(),
                    true
                );
            }

            if (!wp_style_is ( 'rc-map-css' )) {
                wp_enqueue_style(
                    'rc-map-css',
                    $this->extension_url . 'rc_map/field_rc_map.css',
                    array(),
                    time(),
                    'all'
                );
            }
        }
    }
}