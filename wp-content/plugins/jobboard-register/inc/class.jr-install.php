<?php
/**
 * JobBoard Register Install.
 *
 * @class 		JobBoard_Register_Install
 * @version		1.0.0
 * @package		JobBoard/Register/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JobBoard_Register_Install')) :

    class JobBoard_Register_Install{

        function install()
        {
            self::install_pages();
            // Trigger action
            do_action( 'jobboard-register-installed' );
        }

        private function install_pages(){
            $_pages = array(
                array(
                    'post_title'    => esc_html__('Register', JB_REGISTER_TEXT_DOMAIN),
                    'post_name'     => 'register',
                    'post_options'  => 'page-register'
                ),
                array(
                    'post_title'    => esc_html__('Forgot Password', JB_REGISTER_TEXT_DOMAIN),
                    'post_name'     => 'forgot-password',
                    'post_options'  => 'page-forgot-password'
                ),
            );

            foreach ($_pages as $page){
                $_p = get_page_by_path($page['post_name']);
                $_pid = '';

                if(isset($_p->ID)){
                    $_pid = $_p->ID;
                } else {
                    $_new_p = wp_insert_post(array(
                        'post_title'    => $page['post_title'],
                        'post_name'     => $page['post_name'],
                        'post_type'     => 'page',
                        'post_status'   => 'publish'
                    ), true);

                    if(!is_wp_error($_new_p)) $_pid = $_new_p;
                }

                if(class_exists('Redux') && $page['post_options']) {
                    Redux::setOption('jobboard_options', $page['post_options'], $_pid);
                }
            }
        }
    }
endif;