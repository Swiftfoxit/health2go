<?php
/**
 * JobBoard Register Update.
 *
 * @class 		JB_Register_Update
 * @version		1.0.0
 * @package		JB_Register/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Register_Update')) :

    class JB_Register_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-register_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-register';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-register';
        }
    }

endif;

new JB_Register_Update();