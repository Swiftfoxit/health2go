<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rc_taxonomy_level' ) ) {
    class ReduxFramework_rc_taxonomy_level extends ReduxFramework {
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent           = $parent;
            $this->field            = $field;
            $this->value            = $value;
            $this->is_field         = $this->parent->extensions['rc_taxonomy_level']->is_field;
            $this->extension_dir    = redux_custom()->extensions . 'rc_taxonomy_level/';
            $this->extension_url    = redux_custom()->extensions_url . 'rc_taxonomy_level/';

            $defaults    = array(
                'taxonomy'      => 'category',
                'level'         => 2,
                'value'         => array(),
                'save'          => 'meta'
            );

            $placeholder = array();
            for ($i = 1; $i <= $this->field['level']; $i++){
                $placeholder[] = esc_html__('Level', 'redux-custom') . ' ' . $i;
            }

            $screen = get_current_screen();

            if(($screen->base == 'post') && $this->field['save'] == 'taxonomy'){
                $this->value = $this->parent->extensions['rc_taxonomy_level']->get_taxonomy_value(get_the_ID(), $this->field['taxonomy']);
            }

            $this->field = wp_parse_args( $this->field, $defaults );
        }

        public function render() {
            $_name = $this->field['name'] . $this->field['name_suffix'] . '[]';

            for ($i = 0; $i < $this->field['level']; $i++){

                $_terms = array();
                $_value = isset($this->value[$i]) ? $this->value[$i] : '';

                echo '<select id="' . $this->field['id'] . '-level-'.$i.'-select" data-level="'.($i + 1).'" data-placeholder="' . esc_attr($this->field['placeholder'][$i]) . '" name="'.esc_attr($_name).'" class="redux-select-item redux-wpl-taxonomy-level redux-wpl-taxonomy-level-'.$i.' ' . $this->field['class'] . '" data-taxonomy="'.esc_attr($this->field['taxonomy']).'">';
                echo '<option></option>';

                if(!empty($this->value[$i - 1])){

                    $_parent = get_term($this->value[$i - 1], $this->field['taxonomy'], OBJECT);

                    if(!empty($_parent->term_id)) {
                        $_terms = get_terms($this->field['taxonomy'], array(
                            'hide_empty'    => false,
                            'parent'      => $_parent->term_id,
                        ));
                    }
                } elseif ($i == 0){

                    $_terms = get_terms($this->field['taxonomy'], array(
                        'hide_empty'    => false,
                        'parent'        => 0
                    ));
                }

                foreach ($_terms as $k => $v) {
                    echo '<option value="' . $v->term_id . '"' . selected($_value, $v->term_id, false) . '>' . $v->name . '</option>';
                }

                echo '</select>';

                if(isset($this->field['save']) && $this->field['save'] == 'taxonomy'){
                    $_tax_input = "tax_input[{$this->field['taxonomy']}][]";
                    echo '<input type="hidden" name="'.esc_attr($_tax_input).'" value="'.esc_attr($_value).'">';
                }
            }
        }

        public function enqueue() {
            if (!wp_style_is('select2-css')) {
                wp_enqueue_style( 'select2-css' );
            }

            if (!wp_script_is ( 'wpl-taxonomy-level-js' )) {
                wp_enqueue_script(
                    'wpl-taxonomy-level-js',
                    $this->extension_url . 'rc_taxonomy_level/field_rc_taxonomy_level' . Redux_Functions::isMin() . '.js',
                    array( 'jquery', 'select2-js', 'redux-js' ),
                    time(),
                    true
                );
            }

            if (!wp_style_is ( 'wpl-taxonomy-level-css' )) {
                wp_enqueue_style(
                    'wpl-taxonomy-level-css',
                    $this->extension_url . 'rc_taxonomy_level/field_rc_taxonomy_level.css',
                    array(),
                    time(),
                    'all'
                );
            }
        }

        public function output() {
            if ( !$this->field['enqueue_frontend'] )
                return;
        }
    }
}