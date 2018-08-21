<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rc_images_size' ) ) {

    /**
     * Main ReduxFramework_rc_images_size class
     *
     * @since       1.0.0
     */
    class ReduxFramework_rc_images_size extends ReduxFramework {

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
            $this->is_field         = $this->parent->extensions['rc_images_size']->is_field;

            $this->extension_dir    = redux_custom()->extensions . 'rc_images_size/';
            $this->extension_url    = redux_custom()->extensions_url . 'rc_images_size/';

            // Set default args for this field to avoid bad indexes. Change this to anything you use.

            $defaults       = array(
                'default'   => array(
                    array(
                        'title' => esc_html__('Thumbnails', 'redux-custom'),
                        'name'  => 'wpl-thumbnail',
                        'size'  => array(300,300),
                        'crop'  => true,
                    )
                )
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
            echo '<ul class="wpl-crop-images">';

            foreach ($this->field['default'] as $value){

                if(empty($value['name'])) {
                    continue;
                }

                if(!empty($value['title'])){
                    echo '<li class="title">'.esc_html($value['title']).'</li>';
                }

                $height = !empty($this->value[$value['name']]['height']) ? $this->value[$value['name']]['height'] : (isset($value['size'][1]) ? $value['size'][1] : '');
                $width  = !empty($this->value[$value['name']]['width']) ? $this->value[$value['name']]['width'] : (isset($value['size'][0]) ? $value['size'][0] : '');

                $crop   = isset($this->value[$value['name']]['crop']) ? $this->value[$value['name']]['crop'] : (isset($value['crop']) ? $value['crop'] : true) ;

                echo '<li>';

                echo '<input type="text" name="' . $this->field['name'] . $this->field['name_suffix'] . '['.$value['name'].'][width]" class="crop-width" value="'.esc_attr($width).'" placeholder="'.esc_html__('width', 'redux-custom').'">';
                esc_html_e(' × ', 'redux-custom');
                echo '<input type="text" name="' . $this->field['name'] . $this->field['name_suffix'] . '['.$value['name'].'][height]" class="crop-height" value="'.esc_attr($height).'" placeholder="'.esc_html__('height', 'redux-custom').'">';
                esc_html_e('px', 'redux-custom');
                echo '<label for="'.$this->field['id'].'-'.$value['name'].'-crop" class="crop">';
                echo '<input id="'.$this->field['id'].'-'.$value['name'].'-crop" type="checkbox" name="' . $this->field['name'] . $this->field['name_suffix'] . '['.$value['name'].'][crop]" '.($crop ? 'checked="checked"' : '').'>';
                echo esc_html__('Hard Crop?', 'redux-custom') . '</label>';

                echo '</li>';
            }

            echo '</ul>';
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
            if (!wp_style_is ( 'rc-images-size-css' )) {
                wp_enqueue_style(
                    'rc-images-size-css',
                    $this->extension_url . 'rc_images_size/field_rc_images_size.css',
                    array(),
                    time(),
                    'all'
                );
            }
        }
    }
}