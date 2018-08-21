<?php
/**
 * @class ReduxMeta.
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit();
}

// Don't duplicate me!
if (! class_exists('ReduxMeta')) {
    class ReduxMeta
    {
        public static $instance = null;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;
        public $templates;

        public $post;
        public $taxonomy;
        public $user;

        public static function instance(){

            if(is_null(self::$instance)){
                self::$instance = new ReduxMeta();
                self::$instance->setup_globals();
                if(!class_exists('ReduxFramework')) {
                    self::$instance->error();
                } else {
                    self::$instance->actions();
                    self::$instance->includes();
                }
            }

            return self::$instance;
        }

        private function actions(){
            add_action('wp_head', array($this, 'enqueue_output'), 200);
        }

        private function error(){
            add_action('admin_notices', array($this, 'admin_notice_error'));
        }

        private function includes(){
            require_once $this->plugin_directory . '/inc/meta-post.php';
            require_once $this->plugin_directory . '/inc/meta-taxonomy.php';
            require_once $this->plugin_directory . '/inc/meta-user.php';
            $this->post     = new ReduxMeta_Post;
            $this->taxonomy = new ReduxMeta_Taxonomy;
            $this->user     = new ReduxMeta_User;
        }

        private function setup_globals()
        {
            $this->file                 = __FILE__;
            $this->basename             = plugin_basename($this->file);
            $this->plugin_directory     = plugin_dir_path($this->file);
            $this->plugin_directory_uri = plugin_dir_url($this->file);
            $this->templates            = plugin_dir_path($this->file) . '/templates/panel/';
        }

        public function admin_notice_error(){
            $class      = 'notice notice-error';
            $redux      = 'https://wordpress.org/plugins/redux-framework/';
            $logo       = $this->plugin_directory_uri . '/assets/images/logo.png';
            $message    = esc_html__('Redux Framework not found, you can include ReduxCore or install plugin.', 'redux-meta' );
            $link       = esc_html__('Redux Framework', 'redux-meta');
            printf( '<div class="%1$s"><p><img src="%2$s" height="20px">%3$s <a href="%4$s">%5$s</a></p></div>', $class, $logo, $message, $redux, $link);
        }

        public function enqueue_output(){
            if((is_single() || is_page()) && $post_type = get_post_type()){

                if(empty($this->post->posts[$post_type])){
                    return;
                }

                $post = $this->post->posts[$post_type];

                if(!$post['args']['output']){
                    return;
                }

                $this->output($post_type, $post['sections'], $this->post->get_values());

            } elseif (is_tax() || is_category()){

                $queried_object = get_queried_object();

                if(empty($this->taxonomy->taxonomies[$queried_object->taxonomy])){
                    return;
                }

                $taxonomy = $this->taxonomy->taxonomies[$queried_object->taxonomy];

                if(!$taxonomy['args']['output']){
                    return;
                }

                $this->output($queried_object->taxonomy, $taxonomy['sections'], $this->taxonomy->get_values($queried_object->term_id));
            }
        }

        public function output($id, $sections, $options){
            $redux              = new ReduxFramework();
            $redux->sections    = $sections;
            $redux->options     = $options;

            $redux->_enqueue_output();

            if(!$redux->outputCSS){
                return;
            }

            echo '<style type="text/css" id="' . $id . '-dynamic-css">' . $redux->outputCSS . '</style>';
        }
    }
}

if (! function_exists('redux_meta')) {
    function redux_meta(){
        return ReduxMeta::instance();
    }
}

$GLOBALS['redux_meta'] = redux_meta();