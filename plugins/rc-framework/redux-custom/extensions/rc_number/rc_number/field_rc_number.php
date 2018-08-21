<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'ReduxFramework_rc_number' ) ) {
    class ReduxFramework_rc_number {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since ReduxFramework 1.0.0
         */
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field  = $field;
            $this->value  = $value;
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since ReduxFramework 1.0.0
         */
        function render() {
            //if (isset($this->field['text_hint']) && is_array($this->field['text_hint'])) {
            $qtip_title = isset( $this->field['text_hint']['title'] ) ? 'qtip-title="' . $this->field['text_hint']['title'] . '" ' : '';
            $qtip_text  = isset( $this->field['text_hint']['content'] ) ? 'qtip-content="' . $this->field['text_hint']['content'] . '" ' : '';
            //}

            $readonly       = ( isset( $this->field['readonly'] ) && $this->field['readonly']) ? ' readonly="readonly"' : '';
            $autocomplete   = ( isset($this->field['autocomplete']) && $this->field['autocomplete'] == false) ? ' autocomplete="off"' : ''; 
            $min            = ( isset($this->field['min'])) ? ' min="' . $this->field['min'] . '"' : '';
            $max            = ( isset($this->field['max'])) ? ' max="' . $this->field['max'] . '"' : '';
            $step           = ( isset($this->field['step'])) ? ' step="' . $this->field['step'] . '"' : '';

            $placeholder = ( isset( $this->field['placeholder'] ) && ! is_array( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_attr( $this->field['placeholder'] ) . '" ' : '';
            echo '<input '. $min . $max . $step . $qtip_title . $qtip_text . 'type="number" id="' . $this->field['id'] . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" ' . $placeholder . 'value="' . esc_attr( $this->value ) . '" class="regular-text regular-number ' . $this->field['class'] . '"' . $readonly . $autocomplete .' />';
        }
    }
}