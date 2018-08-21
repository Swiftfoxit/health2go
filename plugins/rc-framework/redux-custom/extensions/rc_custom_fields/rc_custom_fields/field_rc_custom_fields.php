<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rc_custom_fields' ) ) {

    /**
     * Main ReduxFramework_import_export class
     *
     * @since       1.0.0
     */
    class ReduxFramework_rc_custom_fields extends ReduxFramework {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent       = $parent;
            $this->field        = $field;
            $this->value        = $value;
            $this->is_field     = $this->parent->extensions['rc_custom_fields']->is_field;

            $this->extension_dir= redux_custom()->extensions . 'rc_custom_fields/';
            $this->extension_url= redux_custom()->extensions_url . 'rc_custom_fields/';

            $this->types        = apply_filters("redux/{$this->parent->args['opt_name']}/field/custom/types", array(
                'text'          => 'dashicons dashicons-edit',
                'select'        => 'dashicons dashicons-list-view',
                'switch'        => 'dashicons dashicons-update',
                'color'         => 'dashicons dashicons-admin-customizer',
                'color-rgba'    => 'dashicons dashicons-dashboard',
                'media'         => 'dashicons dashicons-admin-media',
                'gallery'       => 'dashicons dashicons-format-gallery',
                'textarea'      => 'dashicons dashicons-welcome-write-blog',
                'editor'        => 'dashicons dashicons-editor-paste-word',
                'ace-editor'    => 'dashicons dashicons-editor-paste-text',
                'heading'       => 'dashicons dashicons-editor-textcolor',
            ));

            $this->types        = apply_filters("redux/{$this->parent->args['opt_name']}/field/custom/{$this->field['id']}/types", $this->types);

            // support fields.
            if(!empty($this->field['support'])){

                $support_fields = array();

                foreach ($this->field['support'] as $support){

                    if(isset($this->types[$support])){
                        $support_fields[$support] = $this->types[$support];
                    }
                }

                $this->types    = $support_fields;
            }

            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults           = array();
            $this->field        = wp_parse_args( $this->field, $defaults );
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
            ?>
            <div id="<?php echo esc_attr($this->field['id']); ?>" class="wpl-custom-field">
                <div class="wpl-cf-types">
                    <ul>
                        <li><?php esc_html_e('Click to add :', 'redux-custom'); ?></li>
                        <li>
                            <?php foreach ($this->types as $k => $v): ?>

                                <i class="<?php echo esc_attr($v); ?>" title="<?php echo esc_attr($k); ?>" data-icon="<?php echo esc_attr($v); ?>" data-type="<?php echo esc_attr($k); ?>"></i>

                            <?php endforeach; ?>
                        </li>
                    </ul>
                </div>
                <div class="wpl-cf-content">
                    <ul>
                        <?php $this->get_fields_from_value($this->value); ?>
                    </ul>
                </div>
            </div>
            <?php
        }

        public function get_fields_from_value($values){

            if(empty($values))
                return;

            $i = 0;

            foreach ($values as $v){

                $args = array(
                    'id'    => $this->field['id'],
                    'type'  => $v['type'],
                    'title' => $v['title'],
                    'icon'  => $this->types[$v['type']],
                    'index' => $i,
                    'value' => $v,
                );

                $this->parent->extensions['rc_custom_fields']->section($args);

                $i++;
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

            if (!wp_script_is ( 'wpl-custom-fields-js' )) {
                wp_register_script(
                    'wpl-custom-fields-js',
                    $this->extension_url . 'rc_custom_fields/field_rc_custom_fields' . Redux_Functions::isMin() . '.js',
                    array( 'jquery', 'redux-js' ,'jquery-ui-sortable' ),
                    time(),
                    true
                );

                wp_localize_script('wpl-custom-fields-js', 'rc_custom_fields', array('opt_name' => $this->parent->args['opt_name']));
                wp_enqueue_script('wpl-custom-fields-js');
            }

            if (!wp_style_is ( 'wpl-custom-fields-css' )) {
                wp_enqueue_style(
                    'wpl-custom-fields-css',
                    $this->extension_url . 'rc_custom_fields/field_rc_custom_fields.css',
                    array(),
                    time(),
                    'all'
                );
            }
        }
    }
}