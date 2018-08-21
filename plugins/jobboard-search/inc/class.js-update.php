<?php
/**
 * JobBoard Search Update.
 *
 * @class 		JB_Search_Update
 * @version		1.0.0
 * @package		JB_Search/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Search_Update')) :

    class JB_Search_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-search_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-search';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-search';
        }
    }

endif;

new JB_Search_Update();