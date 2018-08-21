<?php
/**
 * @Template: class.enqueue-scripts.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 07-Dec-17
 */
if (!defined('ABSPATH')) {
    exit();
}
if (!class_exists('JB_Enqueue_Scripts')) {
    class JB_Enqueue_Scripts
    {
        public function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'je_enqueue_scripts'));
        }

        function je_enqueue_scripts()
        {
            if (is_post_type_archive('jb-events') || is_singular('jb-events')) {
                wp_enqueue_script('jb-events.js', JB_Event()->plugin_directory_uri . 'assets/jb-events.js', '', 'all', true);
                wp_enqueue_style('jb-events.css', JB_Event()->plugin_directory_uri . 'assets/jb-events.css');
                $params = array(
                    'ajax_url' => admin_url('admin-ajax.php')
                );
                wp_localize_script('jb-events.js', 'data_ajax', $params);
            }
        }
    }

    new JB_Enqueue_Scripts();
}