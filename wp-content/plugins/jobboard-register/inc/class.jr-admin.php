<?php
/**
 * JobBoard Register Admin.
 *
 * Action/filter hooks used for JobBoard Map admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Register/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Register_Admin')) :
    class JB_Register_Admin{

        function __construct()
        {
            add_filter('jobboard_admin_sections', array($this, 'sections_admin'));
        }

        function sections_admin($sections){

            $sections['page-setting']['fields'] = array_merge($sections['page-setting']['fields'], array(
                array(
                    'id'       => 'page-register',
                    'type'     => 'select',
                    'data'     => 'pages',
                    'title'    => esc_html__( 'User Register', JB_REGISTER_TEXT_DOMAIN ),
                    'subtitle' => esc_html__( 'Page user register.', JB_REGISTER_TEXT_DOMAIN )
                ),
                array(
                    'id'       => 'page-forgot-password',
                    'type'     => 'select',
                    'data'     => 'pages',
                    'title'    => esc_html__( 'Forgot Password', JB_REGISTER_TEXT_DOMAIN ),
                    'subtitle' => esc_html__( 'Page forgot password.', JB_REGISTER_TEXT_DOMAIN )
                )
            ));

            $sections['register-settings'] = array(
                'title'            => esc_html__( 'Manager Register', JB_REGISTER_TEXT_DOMAIN ),
                'id'               => 'register-settings-tab',
                'icon'             => 'dashicons dashicons-admin-network',
            );

            $sections['register-candidate'] = array(
                'title'            => esc_html__( 'Candidates', JB_REGISTER_TEXT_DOMAIN ),
                'id'               => 'register-candidate-sub',
                'icon'             => 'dashicons dashicons-groups',
                'subsection'       => true,
                'fields'           => array(
                    array(
                        'id'            => 'register-candidate-active',
                        'type'          => 'switch',
                        'title'         => esc_html__( 'Activate', JB_REGISTER_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'Automatically activate the account after the user successfully registered.', JB_REGISTER_TEXT_DOMAIN ),
                        'default'       => false,
                    ),
                    array(
                        'id'            => 'register-candidate-login',
                        'type'          => 'switch',
                        'title'         => esc_html__( 'Login', JB_REGISTER_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'Automatically login after the user successfully registered.', JB_REGISTER_TEXT_DOMAIN ),
                        'default'       => true,
                    ),
                    array(
                        'id'            => 'register-candidate-email-start',
                        'type'          => 'section',
                        'title'         => esc_html__( 'Email', JB_REGISTER_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'Send email notice after user successfully registered.', JB_REGISTER_TEXT_DOMAIN ),
                        'indent'        => true
                    ),
                    array(
                        'id'            => 'register-candidate-email-from',
                        'type'          => 'text',
                        'title'         => esc_html__( 'From', JB_REGISTER_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('name')
                    ),
                    array(
                        'id'            => 'register-candidate-email-reply',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Reply', JB_REGISTER_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('admin_email')
                    ),
                    array(
                        'id'            => 'register-candidate-email-subject',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Subject', JB_REGISTER_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('description'),
                    ),
                    array(
                        'id'            => 'register-candidate-email-template-wellcome',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', JB_REGISTER_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/emails/candidate-welcome.php.', JB_REGISTER_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'            => 'register-candidate-email-template-active',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', JB_REGISTER_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/emails/candidate-active.php.', JB_REGISTER_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'            => 'register-candidate-email-end',
                        'type'          => 'section',
                        'indent'        => false,
                    ),
                )
            );

            $sections['register-employer'] = array(
                'title'            => esc_html__( 'Employers', JB_REGISTER_TEXT_DOMAIN ),
                'id'               => 'register-employer-sub',
                'icon'             => 'dashicons dashicons-businessman',
                'subsection'       => true,
                'fields'           => array(
                    array(
                        'id'       => 'register-employer-active',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Activate', JB_REGISTER_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Automatically activate the account after the user successfully registered.', JB_REGISTER_TEXT_DOMAIN ),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'register-employer-login',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Login', JB_REGISTER_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Automatically login after the user successfully registered.', JB_REGISTER_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'            => 'register-employer-email-start',
                        'type'          => 'section',
                        'title'         => esc_html__( 'Email', JB_REGISTER_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'Send email notice after user successfully registered.', JB_REGISTER_TEXT_DOMAIN ),
                        'indent'        => true
                    ),
                    array(
                        'id'            => 'register-employer-email-from',
                        'type'          => 'text',
                        'title'         => esc_html__( 'From', JB_REGISTER_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('name')
                    ),
                    array(
                        'id'            => 'register-employer-email-reply',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Reply', JB_REGISTER_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('admin_email')
                    ),
                    array(
                        'id'            => 'register-employer-email-subject',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Subject', JB_REGISTER_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('description'),
                    ),
                    array(
                        'id'            => 'register-employer-email-template-wellcome',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', JB_REGISTER_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/emails/employer-welcome.php.', JB_REGISTER_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'            => 'register-employer-email-template-active',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', JB_REGISTER_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/emails/employer-active.php.', JB_REGISTER_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'            => 'register-employer-email-end',
                        'type'          => 'section',
                        'indent'        => false,
                    ),
                )
            );

            $sections['register-forgot'] = array(
                'title'            => esc_html__( 'Forgot Password', JB_REGISTER_TEXT_DOMAIN ),
                'id'               => 'register-forgot-sub',
                'icon'             => 'dashicons dashicons-unlock',
                'subsection'       => true,
                'fields'           => array(
                    array(
                        'id'            => 'register-forgot-email-start',
                        'type'          => 'section',
                        'title'         => esc_html__( 'Email', 'jobboard-register' ),
                        'subtitle'      => esc_html__( 'Send email reset password to user.', 'jobboard-register' ),
                        'indent'        => true
                    ),
                    array(
                        'id'            => 'register-forgot-email-from',
                        'type'          => 'text',
                        'title'         => esc_html__( 'From', 'jobboard-register' ),
                        'placeholder'   => get_bloginfo('name')
                    ),
                    array(
                        'id'            => 'register-forgot-email-reply',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Reply', 'jobboard-register' ),
                        'placeholder'   => get_bloginfo('admin_email')
                    ),
                    array(
                        'id'            => 'register-forgot-email-subject',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Subject', 'jobboard-register' ),
                        'placeholder'   => get_bloginfo('description'),
                    ),
                    array(
                        'id'            => 'register-forgot-email-template-forgot',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', 'jobboard-register' ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/emails/forgot-password.php.', 'jobboard-register' ),
                    ),
                    array(
                        'id'            => 'register-forgot-email-end',
                        'type'          => 'section',
                        'indent'        => false,
                    )
                )
            );

            return $sections;
        }
    }
endif;

new JB_Register_Admin();