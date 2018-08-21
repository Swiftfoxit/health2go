<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_Extension_rc_custom_fields' ) ) {

    class ReduxFramework_Extension_rc_custom_fields {

        // Protected vars
        public $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public static $version = "4.0";
        public $is_field = false;

        public function __construct( $parent ) {
            $this->parent = $parent;
            $this->field_name = 'rc_custom_fields';

            self::$theInstance = $this;

            $this->is_field = Redux_Helpers::isFieldInUse($parent, $this->field_name);

            add_filter( 'pre_update_option_' . $this->parent->args['opt_name'], array($this, 'render_options'), 10, 3);

            add_action( "wp_ajax_rc_cf_get_field", array($this, "ajax"));

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
                $this,
                'overload_field_path'
            ) ); // Adds the local field
        }

        public function ajax(){

            if(!empty($_POST['type']) && !empty($_POST['icon']) && !empty($_POST['id'])) {

                $args = array(
                    'id'        => $_POST['id'],
                    'type'      => $_POST['type'],
                    'title'     => esc_attr__('[none]', 'redux-custom'),
                    'icon'      => $_POST['icon'],
                    'index'     => isset($_POST['index']) ? $_POST['index'] : uniqid(),
                    'opt_name'  => $_POST['opt_name'],
                    'value'     => '',
                );

                $this->section($args);
            }

            exit();
        }

        public function section($args = array()){

            $_uniqid    = $args['id'] . '-' . $args['index'];
            $_col       = !empty($args['value']['col']) ? $args['value']['col'] : 12;

            ?>
            <li id="<?php echo esc_attr($_uniqid); ?>" data-col="<?php echo esc_attr($_col); ?>">
                <i class="<?php echo esc_attr($args['icon']); ?>"></i>
                <span class="cf-title"> <?php echo esc_html($args['title']); ?></span>
                <i class="el el-remove right actions"></i>
                <a href="#TB_inline?width=auto&height=550&inlineId=setting-<?php echo esc_attr($_uniqid); ?>" class="thickbox right actions">
                    <i class="el el-adjust-alt"></i>
                </a>
                <div id="setting-<?php echo esc_attr($_uniqid); ?>" style="display: none;">
                    <div class="setting-popup" data-id="<?php echo esc_attr($_uniqid); ?>">
                        <?php $this->field_table($args); ?>
                    </div>
                </div>
            </li>
            <?php
        }

        private function field_table($args){

            if(!empty($args['opt_name'])){
                $this->parent->args['opt_name'] = $args['opt_name'];
            }

            $name       = $this->parent->args['opt_name'] . '['.$args['id'] .']['.$args['index'].']';
            $value      = isset($args['value']) ? $args['value'] : array();

            $value      = wp_parse_args( $value, array(
                'id'            => '',
                'title'         => '',
                'subtitle'      => '',
                'desc'          => '',
                'default'       => '',
                'output'        => '',
                'required'      => '',
                'class'         => '',
                'validate'      => '',
                'readonly'      => '',
                'options'       => '',
                'multi'         => '',
                'sortable'      => '',
                'url'           => '',
                'preview'       => '',
                'mode'          => '',
                'data'          => '',
                'placeholder'   => '',
                'value'         => '',
            ));

            $inputs             = array();

            $inputs['id']       = array(
                'name'          => 'id',
                'type'          => 'text',
                'title'         => esc_html__('ID (*)', 'redux-custom'),
                'subtitle'      => esc_html__('Unique ID identifying the field. Must be different from all other field IDs.', 'redux-custom'),
                'placeholder'   => esc_html__('prefix-field-name', 'redux-custom'),
            );

            $inputs['title']    = array(
                'name'          => 'title',
                'type'          => 'text',
                'title'         => esc_html__('Title (*)', 'redux-custom'),
                'subtitle'      => esc_html__('Displays title of the option.', 'redux-custom'),
                'placeholder'   => esc_html__('Your Title', 'redux-custom'),
                'class'         => 'title'
            );

            $inputs['subtitle'] = array(
                'name'          => 'subtitle',
                'type'          => 'text',
                'title'         => esc_html__('Subtitle', 'redux-custom'),
                'subtitle'      => esc_html__('Subtitle display of the option, situated beneath the title.', 'redux-custom'),
            );

            $inputs['desc']     = array(
                'name'          => 'desc',
                'type'          => 'text',
                'title'         => esc_html__('Description', 'redux-custom'),
                'subtitle'      => esc_html__('Description of the option, appearing beneath the field control.', 'redux-custom'),
            );

            $setting_method     = 'get_settings_' . str_replace('-', '_', $args['type']);

            if(method_exists($this, $setting_method)) {
                $inputs = call_user_func(array($this, $setting_method), $inputs);
            }

            $inputs = apply_filters('redux/custom/' . $this->parent->args['opt_name'] . '/settings', $inputs, $args);

            $inputs['col'] = array(
                'name'          => 'col',
                'type'          => 'select',
                'title'         => esc_html__('Column', 'redux-custom'),
                'subtitle'      => esc_html__('Design column.', 'redux-custom'),
                'options'       => array(
                    12          => esc_html__('1/1', 'redux-custom'),
                    6           => esc_html__('1/2', 'redux-custom'),
                    4           => esc_html__('1/3', 'redux-custom'),
                    3           => esc_html__('1/4', 'redux-custom'),
                ),
                'class'         => 'setting-col'
            );
            $inputs['required'] = array(
                'name'          => 'required',
                'type'          => 'array',
                'title'         => esc_html__('Required', 'redux-custom'),
                'subtitle'      => esc_html__('Provide the parent, comparison operator, and value which affects the field’s visibility.', 'redux-custom'),
                'placeholder'   => esc_html__('id,operation,value', 'redux-custom'),
            );
            $inputs['class'] = array(
                'name'          => 'class',
                'type'          => 'text',
                'title'         => esc_html__('Class', 'redux-custom'),
                'subtitle'      => esc_html__('Appends any number of classes to the field’s class attribute.', 'redux-custom'),
            );

            ?>
            <table class="form-table">
                <tbody>
                    <?php foreach ($inputs as $v) {

                        /* get value. */
                        $default    = isset($v['default']) ? $v['default'] : '';
                        $_v         = !empty($value[$v['name']]) ? $value[$v['name']] : $default;

                        /* default value. */
                        $v = wp_parse_args($v,
                            array(
                                'title'         => '',
                                'value'         => $_v,
                                'subtitle'      => '',
                                'placeholder'   => '',
                                'desc'          => '',
                                'class'         => '',
                            )
                        );

                        $this->field_html($name, $v);
                    } ?>
                </tbody>
            </table>
            <input type="hidden" name="<?php echo esc_attr($name) ?>[type]" value="<?php echo esc_attr($args['type']); ?>">
            <?php
        }

        private function field_html($id, $args = array()){

            $id = $id . '['.$args['name'].']';

            ?>
            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php echo esc_html($args['title']); ?>
                        <span class="description"><?php echo esc_html($args['subtitle']); ?></span>
                    </div>
                </th>
                <td>
                    <fieldset class="wpl-cf-field-container wpl-cf-field">

                        <?php call_user_func(array($this, 'setting_' . $args['type']), $id, $args); ?>

                        <div class="description field-desc">
                            <?php echo esc_html($args['desc']); ?>
                        </div>
                    </fieldset>
                </td>
            </tr>
            <?php
        }

        private function setting_text($id, $args){

            echo '<input type="text" name="'.esc_attr($id).'" class="regular-text '.esc_attr($args['class']).'" value="'.esc_attr($args['value']).'" placeholder="'.esc_attr($args['placeholder']).'">';
        }

        private function setting_array($id, $args){

            if(is_array($args['value'])) {
                $args['value'] = implode('|', $args['value']);
            }

            echo '<input type="text" name="'.esc_attr($id).'" class="regular-text '.esc_attr($args['class']).'" value="'.esc_attr($args['value']).'" placeholder="'.esc_attr($args['placeholder']).'">';
        }

        private function setting_large_array($id, $args){

            if(is_array($args['value'])){

                $value = '';

                foreach ($args['value'] as $k => $v){
                    if($k == $v){
                        $value .= '&#10;' . $v;
                    } else {
                        $value .= '&#10;' . $k . '=>' . $v;
                    }
                }

            } else {
                $value = $args['value'];
            }

            echo '<textarea name="'.esc_attr($id).'" class="large-text wpl-cf-large-text '.esc_attr($args['class']).'" placeholder="'.esc_attr($args['placeholder']).'">'.esc_html($value).'</textarea>';
        }

        private function setting_select($id, $args){

            $multi = '';
            if(isset($args['multi']) && $args['multi']){
                $multi = ' multiple';
                $id = $id . '[]';
            }

            echo '<select class="wpl-cf-select '.esc_attr($args['class']).'" name="'.esc_attr($id).'"'.$multi.'>';
                    foreach ($args['options'] as $k => $v){
                        echo '<option value="'.esc_attr($k).'" '.$this->get_setting_selected( $args['value'], $k ).'>'.esc_attr($v).'</option>';
                    }
            echo '</select>';
        }

        private function get_setting_selected($selected, $current = ''){
            if((is_array($selected) && in_array($current, $selected)) || (!is_array($selected) && $current == $selected)){
                return ' selected';
            }
        }

        private function get_settings_heading($inputs){

            $inputs[] = array(
                'name'      => 'heading',
                'type'      => 'select',
                'title'     => esc_html__('Heading', 'redux-custom'),
                'subtitle'  => esc_html__('h1,h2,h3,h4,h5,h6', 'redux-custom'),
                'options'   => array(
                    'h1'    => esc_html__('H1', 'redux-custom'),
                    'h2'    => esc_html__('H2', 'redux-custom'),
                    'h3'    => esc_html__('H3', 'redux-custom'),
                    'h4'    => esc_html__('H4', 'redux-custom'),
                    'h5'    => esc_html__('H5', 'redux-custom'),
                    'h6'    => esc_html__('H6', 'redux-custom'),
                )
            );

            return $inputs;
        }

        private function get_settings_text($inputs){

            $inputs[] = array(
                'name'      => 'default',
                'type'      => 'text',
                'title'     => esc_html__('Default', 'redux-custom'),
                'subtitle'  => esc_html__('String that appears in the text input.', 'redux-custom'),
            );

            $inputs[] = array(
                'name'      => 'placeholder',
                'type'      => 'text',
                'title'     => esc_html__('Placeholder', 'redux-custom'),
                'subtitle'  => esc_html__('Text to display in the input when n value is present.', 'redux-custom'),
            );

            return apply_filters('redux/custom/' . $this->parent->args['opt_name'] . '/settings/text', $inputs);
        }

        private function get_settings_textarea($inputs){

            $inputs[] = array(
                'name'      => 'default',
                'type'      => 'text',
                'title'     => esc_html__('Default', 'redux-custom'),
                'subtitle'  => esc_html__('String that appears in the text input.', 'redux-custom'),
            );

            $inputs[] = array(
                'name'      => 'placeholder',
                'type'      => 'text',
                'title'     => esc_html__('Placeholder', 'redux-custom'),
                'subtitle'  => esc_html__('Text to display in the input when n value is present.', 'redux-custom'),
            );

            return apply_filters('redux/custom/' . $this->parent->args['opt_name'] . '/settings/textarea', $inputs);
        }

        private function get_settings_select($inputs){
            $inputs['placeholder'] = array(
                'name'      => 'placeholder',
                'type'      => 'text',
                'title'     => esc_html__('Placeholder', 'redux-custom'),
                'subtitle'  => esc_html__('Text to display in the input when n value is present.', 'redux-custom'),
            );

            $inputs['default'] = array(
                'name'              => 'default',
                'type'              => 'array',
                'title'             => esc_html__('Default', 'redux-custom'),
                'subtitle'          => esc_html__('Key value from the options array to set as default.', 'redux-custom'),
                'placeholder'       => esc_html__('A string or multiple default-1|default-2|default-3|... ', 'redux-custom')
            );

            $inputs['data'] = array(
                'name'      => 'data',
                'type'      => 'select',
                'title'     => esc_html__('Data', 'redux-custom'),
                'subtitle'  => esc_html__('Value to populate the selector with WordPress values.', 'redux-custom'),
                'options'   => array(
                    ''      => esc_html__('Custom', 'redux-custom'),
                    'categories'    => esc_html__('Categories', 'redux-custom'),
                    'menus'         => esc_html__('Menus', 'redux-custom'),
                    'menu_locations'=> esc_html__('Menu locations', 'redux-custom'),
                    'pages'         => esc_html__('Pages', 'redux-custom'),
                    'posts'         => esc_html__('Posts', 'redux-custom'),
                    'post_types'    => esc_html__('Post types', 'redux-custom'),
                    'tags'          => esc_html__('Tags', 'redux-custom'),
                    'taxonomies'    => esc_html__('Taxonomies', 'redux-custom'),
                    'roles'         => esc_html__('Roles', 'redux-custom'),
                    'sidebars'      => esc_html__('Sidebars', 'redux-custom'),
                    'capabilities'  => esc_html__('Capabilities', 'redux-custom'),
                )
            );

            $inputs['options'] = array(
                'name'          => 'options',
                'type'          => 'large_array',
                'title'         => esc_html__('Options', 'redux-custom'),
                'subtitle'      => esc_html__('Array of options in key pair format.  The key represents the ID of the option.  The value represents the text to appear in the selector.', 'redux-custom'),
                'placeholder'   => esc_html__('Option 1&#10;Option 2&#10;Option ...&#10;Value 1=>Text 1&#10;Value 2=>Text 2&#10;Value ...=>Text ...', 'redux-custom')
            );

            $inputs['multi'] = array(
                'name'      => 'multi',
                'type'      => 'select',
                'title'     => esc_html__('Multi', 'redux-custom'),
                'subtitle'  => esc_html__('Flag to set the multi-select variation of the field.', 'redux-custom'),
                'options'   => array(
                    false   => esc_html__('False', 'redux-custom'),
                    true    => esc_html__('True', 'redux-custom'),
                )
            );

            $inputs['sortable'] = array(
                'name'      => 'sortable',
                'type'      => 'select',
                'title'     => esc_html__('Sortable', 'redux-custom'),
                'subtitle'  => esc_html__('Flag to enable data sorting.', 'redux-custom'),
                'options'   => array(
                    false   => esc_html__('False', 'redux-custom'),
                    true    => esc_html__('True', 'redux-custom'),
                )
            );

            return $inputs;
        }

        private function get_settings_switch($inputs){

            $inputs['default'] = array(
                'name'      => 'default',
                'type'      => 'select',
                'title'     => esc_html__('Default', 'redux-custom'),
                'subtitle'  => esc_html__('Default value of the switch.', 'redux-custom'),
                'options'   => array(
                    true    => esc_html__('On', 'redux-custom'),
                    false   => esc_html__('Off', 'redux-custom'),
                )
            );

            return $inputs;
        }

        private function get_settings_color($inputs){

            $inputs['default'] = array(
                'name'      => 'default',
                'type'      => 'text',
                'title'     => esc_html__('Default', 'redux-custom'),
                'subtitle'  => esc_html__('Hex string representing the default color.', 'redux-custom'),
            );

            $inputs['output'] = array(
                'name'        => 'output',
                'type'        => 'array',
                'title'       => esc_html__('Output', 'redux-custom'),
                'subtitle'    => esc_html__('Array of CSS selectors to dynamically generate CSS.', 'redux-custom'),
                'placeholder' => esc_html__('class-1,class-2,id-1,id-2,...', 'redux-custom'),
            );

            return $inputs;
        }

        private function get_settings_color_rgba($inputs){
            $inputs['output'] = array(
                'name'        => 'output',
                'type'        => 'array',
                'title'       => esc_html__('Output', 'redux-custom'),
                'subtitle'    => esc_html__('Array of CSS selectors to dynamically generate CSS.', 'redux-custom'),
                'placeholder' => esc_html__('class-1,class-2,id-1,id-2,...', 'redux-custom'),
            );

            return $inputs;
        }

        private function get_settings_media($inputs){
            return apply_filters( 'redux/custom/' . $this->parent->args['opt_name'] . '/settings/media' , $inputs);
        }

        private function get_settings_ace_editor($inputs){

            $inputs['mode'] = array(
                'name'      => 'mode',
                'type'      => 'select',
                'title'     => esc_html__('Mode', 'redux-custom'),
                'subtitle'  => esc_html__('Sets the language mode of the editor.', 'redux-custom'),
                'options'   => array(
                    'css'       => esc_html__('css', 'redux-custom'),
                    'html'      => esc_html__('html', 'redux-custom'),
                    'javascript'=> esc_html__('javascript', 'redux-custom'),
                    'json'      => esc_html__('json', 'redux-custom'),
                    'less'      => esc_html__('less', 'redux-custom'),
                    'markdown'  => esc_html__('markdown', 'redux-custom'),
                    'mysql'     => esc_html__('mysql', 'redux-custom'),
                    'php'       => esc_html__('php', 'redux-custom'),
                    'plain_text'=> esc_html__('plain text', 'redux-custom'),
                    'sass'      => esc_html__('sass', 'redux-custom'),
                    'scss'      => esc_html__('scss', 'redux-custom'),
                    'text'      => esc_html__('text', 'redux-custom'),
                    'xml'       => esc_html__('xml', 'redux-custom'),
                )
            );

            return $inputs;
        }

        private function render_value($values)
        {
            if(empty($values))
                return $values;

            foreach ($values as $k => $field){

                if(empty($field['id'])){
                    unset($values[$k]);
                    continue;
                }

                /* default */
                if (!empty($field['default']) && !is_array($field['default'])) {
                    $_default = explode('|', $field['default']);
                    if (count($_default) > 1) {
                        $values[$k]['default'] = $_default;
                    }
                }
                /* options. */
                if (!empty($field['options']) && !is_array($field['options'])) {
                    $_options = explode(PHP_EOL, $field['options']);
                    $_new_options = array();
                    foreach ($_options as $_v) {
                        $__v = explode('=>', $_v);
                        if (count($__v) == 2) {
                            $_new_options[$__v[0]] = $__v[1];
                        } else {
                            $_new_options[$_v] = $_v;
                        }
                    }
                    $values[$k]['options'] = $_new_options;
                }
            }

            return $values;
        }

        public function render_options($value, $old_value, $option){

            if(empty($this->parent->sections))
                return $value;

            foreach ($this->parent->sections as $section){

                if(empty($section['fields']))
                    continue;

                foreach ($section['fields'] as $field){

                    if($field['type'] == 'rc_custom_fields' && isset($value[$field['id']])){
                        $value[$field['id']] = $this->render_value($value[$field['id']]);
                    }
                }
            }

            return $value;
        }

        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }
    }
}