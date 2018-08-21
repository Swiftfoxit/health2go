<?php
/**
 * JB Social Login Update.
 *
 * @class 		JB_Social_Login_Update
 * @version		1.0.0
 * @package		JB_Social_Login/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Social_Login_Update')) :

    class JB_Social_Login_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-social-login_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-social-login';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-social-login';
        }
    }

endif;

new JB_Social_Login_Update();