<?php
/**
 * JobBoard Basket Update.
 *
 * @class 		JB_Basket_Update
 * @version		1.0.0
 * @package		JB_Basket/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Basket_Update')) :

    class JB_Basket_Update{

        function __construct()
        {
            add_filter('fsflex_update_plugin_check_list', array($this, 'add_update'));
            add_filter('fsflex_update_plugin_jobboard-basket_data', array($this, 'get_info'));
        }

        function add_update($slugs = array()){

            $slugs[] = 'jobboard-basket';

            return $slugs;
        }

        function get_info(){
            return 'jobboard-basket';
        }
    }

endif;

new JB_Basket_Update();