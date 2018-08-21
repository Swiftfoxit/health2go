<?php
/**
 * @Class ReduxCustom.
 *
 * Custom fields for Redux-Framework.
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit();
}

// Don't duplicate me!
if (! class_exists('ReduxCustom')) {

    class ReduxCustom
    {
        public static $instance = null;
        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;
        public $extensions;
        public $extensions_url;

        public static function instance(){

            if(is_null(self::$instance)){
                self::$instance = new ReduxCustom();
                self::$instance->setup_globals();
                self::$instance->setup_actions();
            }

            return self::$instance;
        }

        private function setup_globals()
        {
            $this->file                 = __FILE__;
            $this->basename             = plugin_basename($this->file);
            $this->plugin_directory     = plugin_dir_path($this->file);
            $this->plugin_directory_uri = plugin_dir_url($this->file);
            $this->extensions           = plugin_dir_path($this->file) . 'extensions/';
            $this->extensions_url       = plugin_dir_url($this->file) . 'extensions/';
        }

        private function setup_actions(){
            add_action('redux/extensions/before', array($this, '_register_extensions'));
        }

        function _register_extensions($ReduxFramework){
            $path    = $this->extensions;
            $folders = scandir( $path, 1 );

            foreach ( $folders as $folder ) {
                if ( $folder === '.' or $folder === '..' or ! is_dir( $path . $folder ) ) {
                    continue;
                }
                $extension_class = 'ReduxFramework_Extension_' . $folder;

                if ( ! class_exists( $extension_class ) ) {
                    // In case you wanted override your override, hah.
                    $class_file = $path . $folder . '/extension_' . $folder . '.php';
                    $class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args['opt_name'] . '/' . $folder, $class_file );
                    if ( $class_file ) {
                        require_once( $class_file );
                    }
                }

                if ( ! isset( $ReduxFramework->extensions[ $folder ] ) ) {
                    $ReduxFramework->extensions[ $folder ] = new $extension_class( $ReduxFramework );
                }
            }
        }
    }
}

if (! function_exists('redux_custom')) {
    function redux_custom(){
        return ReduxCustom::instance();
    }
}

$GLOBALS['redux_custom'] = redux_custom();