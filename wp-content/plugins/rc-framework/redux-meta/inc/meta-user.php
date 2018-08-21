<?php
/**
 * @class ReduxMeta_User
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('ReduxMeta_User')) {
    class ReduxMeta_User
    {
        function __construct()
        {
            add_action( 'admin_post_add_foobar', array($this, 'save_meta'));
        }

        function add($args = array(), $sections = array()){
            $user_id = $this->get_user_id();
            $this->save_meta($user_id, $args['opt_name']);

            add_action( "redux/page/{$args['opt_name']}/enqueue", array($this, 'panel_scripts'));
            add_filter( "redux/{$args['opt_name']}/panel/templates_path", array($this, 'panel_template'));
            add_filter( "redux/options/{$args['opt_name']}/options", array($this, 'get_values'));

            $GLOBALS['redux_notice_check']  = true;
            $args['open_expanded']          = true;

            $this->redux = new ReduxFramework($sections, $args);
            $this->redux->_register_settings();
            $this->redux->_enqueue();
            $this->redux->generate_panel();
            wp_nonce_field('update-user_' . $user_id);
            ?>
            <input type="hidden" name="action" value="rc-profile-update"/>
            <input type="hidden" name="checkuser_id" value="<?php echo get_current_user_id(); ?>" />
            <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($user_id); ?>" />
            <?php submit_button(esc_html__('Update Profile', 'rc-framework')); ?>
            <?php
        }

        function panel_scripts(){
            wp_enqueue_style('redux-meta-user', redux_meta()->plugin_directory_uri . 'assets/css/meta-user.css', null, time(), 'all');
        }

        function panel_template(){
            return redux_meta()->templates;
        }

        function save_meta($user_id, $opt_name){
            if(!current_user_can('edit_user', $user_id) || !isset($_REQUEST['action']) || (isset($_REQUEST['action']) && $_REQUEST['action'] != 'rc-profile-update')){
                return;
            }

            if(empty($_POST[$opt_name])){
                return;
            }

            foreach ($_POST[$opt_name] as $key => $value){
                update_user_meta($user_id, $key, $value);
            }
        }

        function get_user_id(){
            $user_id = (int) get_current_user_id();
            if ( ! empty( $_GET['user_id'] ) ) {
                $user_id = (int) $_GET['user_id'];
            }
            return $user_id;
        }

        function get_values($data = array()){
            $user_id = $this->get_user_id();
            if(!$user = get_user_to_edit($user_id)){
                return $data;
            }

            $meta = get_user_meta($user->ID);
            foreach ($meta as $key => $value){
                $data[$key] = maybe_unserialize($value[0]);
            }

            return $data;
        }
    }
}