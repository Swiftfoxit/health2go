<?php
/**
 * JB Similar Update.
 *
 * @class 		JB_Similar_Update
 * @version		1.0.0
 * @package		JB_Similar/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Similar_Update')) :

    class JB_Similar_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-similar_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-similar';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-similar';
        }
    }

endif;

new JB_Similar_Update();