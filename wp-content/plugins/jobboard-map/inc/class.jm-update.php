<?php
/**
 * JobBoard Map Update.
 *
 * @class 		JB_Map_Update
 * @version		1.0.0
 * @package		JB_Map/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Map_Update')) :

    class JB_Map_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-map_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-map';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-map';
        }
    }

endif;

new JB_Map_Update();