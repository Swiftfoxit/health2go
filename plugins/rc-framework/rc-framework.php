<?php
/**
 * Plugin Name: RC Framework
 * Plugin URI: http://fsflex.com
 * Description:  Extended for Redux Framework, truly extensible meta options framework for WordPress themes and plugins.
 * Version: 1.1.1
 * Author: FSFlex
 * Author URI: http://fsflex.com
 * License: GPLv2 or later
 * Text Domain: rc-framework
 */
if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('RC_Framework')) :

    final class RC_Framework
    {
        static $_instance = null;

        public static function instance(){
            if (is_null(self::$_instance)) {
                self::$_instance = new RC_Framework();
                self::$_instance->setup_globals();
                self::$_instance->setup_actions();
                self::$_instance->includes();
            }

            return self::$_instance;
        }

        private function setup_globals()
        {
            $this->file                 = __FILE__;
            $this->basename             = plugin_basename($this->file);
            $this->plugin_directory     = plugin_dir_path($this->file);
            $this->plugin_directory_uri = plugin_dir_url($this->file);
        }

        private function setup_actions(){
            //add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );
            //add_action( 'admin_init', array($this, 'deactivate_plugin' ));
        }

        private function includes(){
            require_once $this->plugin_directory . 'ReduxCore/framework.php';
            require_once $this->plugin_directory . 'redux-meta/framework.php';
            require_once $this->plugin_directory . 'redux-custom/framework.php';
            require_once $this->plugin_directory . 'update/update-manager.php';
            //if(get_option('rc-framework-demo') == 'on') {
                //require_once $this->plugin_directory . 'sample/meta-sample-config.php';
                //require_once $this->plugin_directory . 'sample/taxonomy-sample-config.php';
            //}
        }

        public function scssphp(){
            if(class_exists('scssc')){
                return;
            }

            require_once $this->plugin_directory . 'scssphp/scss.inc.php';
        }

        public function plugin_metalinks( $links, $file ) {
            if ( strpos( $file, 'rc-framework.php' ) !== false && is_plugin_active( $file ) ) {

                $new_links = array();

                if ( ( is_multisite() && $this->plugin_network_activated ) || ! is_network_admin() || ! is_multisite() ) {

                    if(isset($_GET['rc-framework-demo'])){
                        update_option('rc-framework-demo', $_GET['rc-framework-demo']);
                    }

                    if ( get_option('rc-framework-demo') == 'on' ) {
                        $new_links[] .= '<span style="display: block; padding-top: 6px;"><a href="./plugins.php?rc-framework-demo=off" style="color: #bc0b0b;">' . __( 'Deactivate Demo Mode', 'rc_framework' ) . '</a></span>';
                    } else {
                        $new_links[] .= '<span style="display: block; padding-top: 6px;"><a href="./plugins.php?rc-framework-demo=on" style="color: #bc0b0b;">' . __( 'Activate Demo Mode', 'rc_framework' ) . '</a></span>';
                    }
                }

                $links = array_merge( $links, $new_links );
            }

            return $links;
        }

        function deactivate_plugin() {
            if ( is_plugin_active('wpl-meta-framework/wpl-meta-framework.php') ) {
                deactivate_plugins( 'wpl-meta-framework/wpl-meta-framework.php' );
            }
        }
    }

endif;

if (! function_exists('rc_framework')) {
    function rc_framework(){
        return RC_Framework::instance();
    }
}

$GLOBALS['rc_framework'] = rc_framework();