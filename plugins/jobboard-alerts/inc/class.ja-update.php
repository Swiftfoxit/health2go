<?php
/**
 * JobBoard Alerts Update.
 *
 * @class 		JB_Alerts_Update
 * @version		1.0.0
 * @package		JB_Alerts/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Alerts_Update')) :

    class JB_Alerts_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-alerts_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-alerts';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-alerts';
        }
    }

endif;

new JB_Alerts_Update();