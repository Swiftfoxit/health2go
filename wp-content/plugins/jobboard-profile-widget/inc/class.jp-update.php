<?php
/**
 * JobBoard Profile Widget Update.
 *
 * @class 		JB_Profile_Widget_Update
 * @version		1.0.0
 * @package		JB_Profile_Widget/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Profile_Widget_Update')) :

    class JB_Profile_Widget_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-profile-widget_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-profile-widget';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-profile-widget';
        }
    }

endif;

new JB_Profile_Widget_Update();