<?php
/**
 * JB Package Update.
 *
 * @class 		JB_Package_Update
 * @version		1.0.0
 * @package		JB_Package/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Package_Update')) :

    class JB_Package_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-package_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-package';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-package';
        }
    }

endif;

new JB_Package_Update();