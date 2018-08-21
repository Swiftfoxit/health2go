<?php
/**
 * Plugin Name: EF3 Import And Export
 * Plugin URI: http://cmssuperheroes.com/
 * Description: EF3-Framework auto create demo data package for developer, auto import demo data for clients. After import you can deactivate or remove plugin.
 * Version: 1.1.6
 * Author: FOX
 * Author URI: http://cmssuperheroes.com/
 * License: GPLv2 or later
 * Text Domain: ef3-import-and-export
 */
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('EF3_Import_Export')) :

    /**
     * Main Class
     *
     * @class EF3_Import_Export
     *
     * @version 1.0.0
     */
    final class EF3_Import_Export
    {

        /* single instance of the class */
        public $file = '';

        public $basename = '';

        /* base plugin_dir. */
        public $plugin_dir = '';
        public $plugin_url = '';

        /* base acess folder. */
        public $acess_dir = '';
        public $acess_url = '';

        public $theme_dir = '';
        public $theme_url = '';

        /**
         * Main EF3_Import_Export Instance
         *
         * Ensures only one instance of EF3_Import_Export is loaded or can be loaded.
         *
         * @since 1.0.0
         * @static
         *
         * @see EF3_Import_Export()
         * @return EF3_Import_Export - Main instance
         */
        public static function instance()
        {
            static $_instance = null;

            /* Check php ver. */
            if(!version_compare(PHP_VERSION, '5.3', '>=')){
                add_action( 'admin_notices', array(new EF3_Import_Export(),'admin_notice_error'));
                return;
            }

            if (is_null($_instance)) {

                $_instance = new EF3_Import_Export();

                // globals.
                $_instance->setup_globals();

                // includes.
                $_instance->includes();

                // actions.
                $_instance->setup_actions();
            }

            return $_instance;
        }

        /**
         * globals value.
         *
         * @package EF3_Import_Export
         * @global path + uri.
         */
        private function setup_globals()
        {
            $this->file = __FILE__;

            /* base name. */
            $this->basename = plugin_basename($this->file);

            /* base plugin. */
            $this->plugin_dir = plugin_dir_path($this->file);
            $this->plugin_url = plugin_dir_url($this->file);

            /* base assets. */
            $this->acess_dir = trailingslashit($this->plugin_dir . 'assets');
            $this->acess_url = trailingslashit($this->plugin_url . 'assets');

            $this->theme_dir = trailingslashit(get_template_directory() . '/inc/demo-data') ;
            $this->theme_url = trailingslashit(get_template_directory_uri() . '/inc/demo-data') ;
        }

        /**
         * Setup all actions + filter.
         *
         * @package EF3_Import_Export
         * @version 1.0.0
         */
        private function setup_actions()
        {
            add_action('admin_menu', array($this, 'add_admin_page'));

            add_action('extension_import_export_before', array($this, 'get_option_layout'));

            add_action('wp_ajax_ef3_export', array($this, 'ajax_export'));

            add_action('wp_ajax_ef3_import', array($this, 'ajax_inport'));

            add_action('wp_ajax_ef3_download', array($this, 'ajax_download'));
        }

        /**
         * include files.
         *
         * @package EF3_Import_Export
         * @version 1.0.0
         */
        private function includes()
        {
            global $wp_filesystem;

            /* add WP_Filesystem. */
            if ( !class_exists('WP_Filesystem') ) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                WP_Filesystem();
            }

            /* dropbox */
            require_once $this->plugin_dir . 'plugins/dropbox/dropbox.php';

            require_once $this->plugin_dir . 'plugins/start/start.php';

            /* content export. */
            require_once $this->plugin_dir . 'plugins/content/export.php';
            /* content import. */
            require_once $this->plugin_dir . 'plugins/content/import.php';

            /* media export. */
            require_once $this->plugin_dir . 'plugins/media/media.php';

            /* widget import. */
            require_once $this->plugin_dir . 'plugins/widget/import.php';
            /* widget export. */
            require_once $this->plugin_dir . 'plugins/widget/export.php';

            /* setting. */
            require_once $this->plugin_dir . 'plugins/setting/reduxframework.php';

            /* ctp ui. */
            require_once $this->plugin_dir . 'plugins/ctp-ui/ctp-ui.php';

            /* revslider export. */
            require_once $this->plugin_dir . 'plugins/revslider/export.php';
            /* revslider import. */
            require_once $this->plugin_dir . 'plugins/revslider/import.php';

            /* options */
            require_once $this->plugin_dir . 'plugins/options/options.php';

            /* download demo. */
            require_once $this->plugin_dir . 'plugins/download/download.php';

            /* reset data. */
            require_once $this->plugin_dir . 'plugins/reset/wordpress-reset.php';

            /* clear data */
            require_once $this->plugin_dir . 'plugins/clear/clear-tmp.php';

            /* git */
            require_once $this->plugin_dir . 'plugins/git/git.php';

        }

        /**
         * admin page.
         */
        function add_admin_page(){

            if(is_dir($this->theme_dir) || $this->export_demo_mode())
                add_submenu_page('tools.php', esc_attr__('Install Demo', 'ef3-import-and-export'), esc_attr__('Install Demo', 'ef3-import-and-export'), 'manage_options', 'ef3-import-and-export', array($this, 'get_admin_page_html'));
        }

        function get_admin_page_html(){
            echo '<div id="ef3-admin-demo-page">';
            $this->get_option_layout();
            echo '</div>';
        }

        function admin_notice_error(){
            ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo sprintf(esc_html__('PHP V%s (It was too backward and incompatible with many plugins.) We recommend you upgrade to a newer version. You can contact your hosting or search "How to Change your PHP Version in cPanel".', 'ef3-import-and-export'), PHP_VERSION); ?></p>
            </div>
            <?php
        }

        /**
         * html options
         */
        function get_option_layout(){

            wp_enqueue_style('ef3-import-and-export', $this->acess_url . 'ef3-import-and-export.css');
            wp_enqueue_script('ef3-import-and-export' , $this->acess_url . 'ef3-import-and-export.js');

            do_action('ef3-option-before');

            require_once $this->plugin_dir . 'templates/options-template.php';
        }

        /**
         * scan demo data folder
         * @return array|bool demo folders
         */
        function get_all_demo_folder(){

            if(!is_dir($this->theme_dir))
                return false;

            $files = scandir($this->theme_dir, 1);

            return array_diff($files, array('..', '.', 'attachment'));
        }

        /**
         * on or off export functions.
         * @return mixed|void
         */
        function export_demo_mode(){
            return apply_filters('ef3-enable-create-demo', false);
        }

        function ajax_inport(){

            if(empty($_REQUEST['id']) && empty($_REQUEST['import'])) exit();

            $response = $options = array();

            /* get demo dir. */
            $folder_dir = trailingslashit($this->theme_dir . $_REQUEST['id']);

            /* get options. */
            if(!file_exists($folder_dir . 'options.json')) exit();

            $options = json_decode(file_get_contents($folder_dir . 'options.json'), true);
            $options['folder'] = $folder_dir;

            set_time_limit(0);

            switch ($_REQUEST['import']) {
                case 'start':
                    ob_start();
                    do_action('ef3-import-start', $_REQUEST['id'], $folder_dir);
                    ef3_import_start($folder_dir);
                    $response = ob_get_clean();
                    break;
                /* import media. */
                case 'attachment':
                    $response = ef3_media_import($options);
                    break;
                /* import widgets. */
                case 'widgets':
                    $response = ef3_widgets_process_import_file($folder_dir);
                    break;
                /* import theme setting. */
                case 'settings':
                    $response = ef3_setting_import($folder_dir . 'setting.json');
                    break;
                /* import options */
                case 'options':
                    $response = ef3_options_import($options);
                    break;
                /* import post type */
                case 'ctp_ui':
                    $response = ef3_ctp_ui_import($folder_dir);
                    break;
                /* import content */
                case 'content':
                    $response = ef3_content_import($options);
                    break;
                /* revslider import */
                case 'revslider':
                    $response = ef3_revslider_import($folder_dir);
                    break;
                case 'clear':
                    $response = ef3_clear_tmp();
                    break;
                case 'finish':
                    do_action('ef3-import-finish', $_REQUEST['id'], $folder_dir);
                    /* set demo id installed. */
                    update_option('ef3-current-demo-installed', $_REQUEST['id']);
                    break;
            }

            do_action('ef3-demo-'.$_REQUEST['id'].'-'.$_REQUEST['import'].'-after');

            exit($response);
        }

        function ajax_export(){

            if(empty($_REQUEST['id']) || empty($_REQUEST['export']))
                exit();

            $response = array();

            $folder_name = sanitize_title($_REQUEST['id']);
            $export_action = $_REQUEST['export'];

            /* get demo dir. */
            $folder_dir = $this->process_demo_folder($folder_name);

            /* screenshot */
            $this->process_demo_thumb($folder_name);

            switch ($export_action) {
                case 'start':
                    ob_start();
                    do_action('ef3-export-finish', $folder_dir);
                    ef3_export_start($folder_dir);
                    $response = ob_get_clean();
                    break;
                /* export widgets. */
                case 'attachment':
                    $response = ef3_media_export($folder_dir);
                    break;
                /* export widgets. */
                case 'widgets':
                    $response = ef3_widgets_save_export_file($folder_dir);
                    break;
                /* export theme setting. */
                case 'settings':
                    $response = ef3_setting_export($folder_dir . 'setting.json');
                    break;
                /* export options */
                case 'options':
                    $response = ef3_options_export($folder_dir . 'options.json');
                    break;
                /* custom post type */
                case 'ctp_ui':
                    $response = ef3_ctp_ui_export($folder_dir);
                    break;
                /* export content */
                case 'content':
                    $response = ef3_content_export($folder_dir);
                    break;
                /* revslider export */
                case 'revslider':
                    $response = ef3_revslider_export($folder_dir);
                    break;
                /* syn to git. */
                case 'git':
                    $response = ef3_git_shell();
                    break;
                /* clear tmp. */
                case 'clear':
                    do_action('ef3-export-finish', $folder_dir);
                    $response = ef3_clear_tmp();
                    break;
            }

            do_action('ef3-export', $export_action, $folder_dir);

            exit(json_encode($response));
        }

        /**
         * download demo data.
         */
        function ajax_download(){

            $zip_file = ef3_download_demo_zip();

            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=demo-data.zip");
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($zip_file);

            @unlink($zip_file); //delete file after sending it to user

            exit();
        }

        /**
         * check and create folder.
         *
         * @param $folder_name
         * @return string folder dir
         */
        private function process_demo_folder($folder_name){

            if(!is_dir($this->theme_dir . $folder_name))
                wp_mkdir_p($this->theme_dir . $folder_name);

            return trailingslashit($this->theme_dir . $folder_name);
        }

        /*
         * auto copy screenshot from theme.
         */
        private function process_demo_thumb($folder_name){

            if(is_file($this->theme_dir . $folder_name . '/screenshot.png'))
                return;

            if(!is_file(get_template_directory() . '/screenshot.png'))
                return;

            copy(get_template_directory() . '/screenshot.png' , $this->theme_dir . $folder_name . '/screenshot.png');
        }
    }
endif;

/**
 * Returns the main instance of EF3_Import_Export() to prevent the need to use globals.
 *
 * @since 1.0
 * @return EF3_Import_Export
 */
if (!function_exists('ef3_import_export')) {

    function ef3_import_export()
    {
        return EF3_Import_Export::instance();
    }
}

if (defined('EF3_IMPORT_EXPORT_LATE_LOAD')) {
    add_action('plugins_loaded', 'ef3_import_export', (int)EF3_IMPORT_EXPORT_LATE_LOAD);
} else {
    ef3_import_export();
}