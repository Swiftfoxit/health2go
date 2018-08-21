<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'ReduxFramework_rc_business_hours' ) ) {
    class ReduxFramework_rc_business_hours {
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent        = $parent;
            $this->field         = $field;
            $this->value         = $value;
            $this->extension_dir = redux_custom()->extensions . 'rc_business_hours/';
            $this->extension_url = redux_custom()->extensions_url . 'rc_business_hours/';
        }

        function render() {
            ?>
            <ul class="regular-business-hours <?php echo esc_attr($this->field['class']); ?>" data-name="<?php echo esc_attr($this->field['name'] . $this->field['name_suffix']); ?>">
                <?php if(!empty($this->value) && is_array($this->value)): ?>
                    <?php foreach ($this->value as $weekday => $hours): ?>
                        <li>
                            <span class="weekday"><?php echo esc_html($weekday); ?></span>
                            <span class="hour-open"><?php echo esc_html($hours['open']); ?></span>
                            <span class="sp">-</span>
                            <span class="hour-close"><?php echo esc_html($hours['close']); ?></span>
                            <a class="remove-hour" href="javascript:void(0)"><span class="dashicons dashicons-no-alt"></span></a>
                            <input type="hidden" name="<?php echo esc_attr($this->field['name'] . $this->field['name_suffix']); ?>[<?php echo esc_attr($weekday); ?>][open]" value="<?php echo esc_attr($hours['open']); ?>">
                            <input type="hidden" name="<?php echo esc_attr($this->field['name'] . $this->field['name_suffix']); ?>[<?php echo esc_attr($weekday); ?>][close]" value="<?php echo esc_attr($hours['close']); ?>">
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li>
                    <select class="redux-select-item weekday">
                        <option value="<?php esc_attr_e('Monday', 'rc-framework') ?>"><?php esc_html_e('Monday', 'rc-framework') ?></option>
                        <option value="<?php esc_attr_e('Tuesday', 'rc-framework') ?>"><?php esc_html_e('Tuesday', 'rc-framework') ?></option>
                        <option value="<?php esc_attr_e('Wednesday', 'rc-framework') ?>"><?php esc_html_e('Wednesday', 'rc-framework') ?></option>
                        <option value="<?php esc_attr_e('Thursday', 'rc-framework') ?>"><?php esc_html_e('Thursday', 'rc-framework') ?></option>
                        <option value="<?php esc_attr_e('Friday', 'rc-framework') ?>"><?php esc_html_e('Friday', 'rc-framework') ?></option>
                        <option value="<?php esc_attr_e('Saturday', 'rc-framework') ?>"><?php esc_html_e('Saturday', 'rc-framework') ?></option>
                        <option value="<?php esc_attr_e('Sunday', 'rc-framework') ?>"><?php esc_html_e('Sunday', 'rc-framework') ?></option>
                    </select>
                    <select class="redux-select-item hour-open">
                        <?php $this->the_times('07:00'); ?>
                    </select>
                    <select class="redux-select-item hour-close">
                        <?php $this->the_times('11:00'); ?>
                    </select>
                    <button type="button" class="button button-primary add"><?php esc_html_e('Add Hours', 'rc-framework'); ?></button>
                </li>
            </ul>
            <?php
        }

        public function enqueue() {
            if (!wp_script_is ( 'rc-business-hours-js' )) {
                wp_enqueue_script(
                    'rc-business-hours-js',
                    $this->extension_url . 'rc_business_hours/field_rc_business_hours' . Redux_Functions::isMin() . '.js',
                    array( 'jquery', 'redux-js' ),
                    time(),
                    true
                );
            }

            if (!wp_style_is ( 'rc-business-hours-css' )) {
                wp_enqueue_style(
                    'rc-business-hours-css',
                    $this->extension_url . 'rc_business_hours/field_rc_business_hours.css',
                    array(),
                    time(),
                    'all'
                );
            }
        }

        function the_times($default = '19:00', $interval = '+30 minutes'){
            $current    = strtotime( '00:00' );
            $end        = strtotime( '23:59' );

            while( $current <= $end ) {
                $time    = date( 'H:i', $current );
                echo '<option value="' . esc_attr($time) . '"' . selected($time, $default, false) . '>' . date( 'h.i A', $current ) .'</option>';
                $current = strtotime( $interval, $current );
            }
        }
    }
}