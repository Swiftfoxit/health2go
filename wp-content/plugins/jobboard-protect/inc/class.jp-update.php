<?php
/**
 * JobBoard Protect Update.
 *
 * @class 		JB_Protect_Update
 * @version		1.0.0
 * @package		JB_Protect/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Protect_Update')) :

    class JB_Protect_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-protect_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-protect';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-protect';
        }
    }

endif;

new JB_Protect_Update();