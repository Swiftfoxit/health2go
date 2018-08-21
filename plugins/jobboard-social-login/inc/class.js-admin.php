<?php
/**
 * JobBoard Social Admin.
 *
 * Action/filter hooks used for JobBoard social admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Social/Login/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Social_Login_Admin')) :
    class JB_Social_Login_Admin{

        function __construct()
        {
            add_filter('jobboard_admin_sections', array($this, 'sections_admin'));
        }

        function sections_admin($sections){

            $sections['social-login'] = array(
                'title'            => esc_html__( 'Social Connect', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                'id'               => 'social-login',
                'icon'             => 'dashicons dashicons-share',
                'fields'           => array(
                    array(
                        'id'       => 'social-login-facebook',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Facebook', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enable Facebook login.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'social-login-facebook-id',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Facebook App ID', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enter your facebook app ID.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'desc'     => sprintf(esc_html__( 'Register and configure an app : %1$sHere →%2$s', JB_SOCIAL_LOGIN_TEXT_DOMAIN), '<a href="https://developers.facebook.com/docs/apps/register" target="_blank">', '</a>'),
                        'placeholder' => esc_html__('123230190550786', JB_SOCIAL_LOGIN_TEXT_DOMAIN),
                        'required' => array( 'social-login-facebook', '=', true ),
                    ),
                    array(
                        'id'       => 'social-login-google',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Google', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enable Google login.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'social-login-google-id',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Google Client ID', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enter your client ID.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'desc'     => sprintf(esc_html__( 'Creating a Google API Console project and client ID : %1$sHere →%2$s', JB_SOCIAL_LOGIN_TEXT_DOMAIN), '<a href="https://developers.google.com/identity/sign-in/web/devconsole-project" target="_blank">', '</a>'),
                        'placeholder' => esc_html__('874159553790-reau1th488s38uteh59beu20xib9mkho.apps.googleusercontent.com', JB_SOCIAL_LOGIN_TEXT_DOMAIN),
                        'required' => array( 'social-login-google', '=', true ),
                    ),
                    array(
                        'id'       => 'social-login-linkedin',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Linkedin', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enable Linkedin login.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'social-login-linkedin-id',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Linkedin Client ID', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enter your client ID.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'desc'     => sprintf(esc_html__( 'Creating a Linkedin API Console project and client ID : %1$sHere →%2$s', JB_SOCIAL_LOGIN_TEXT_DOMAIN), '<a href="https://developer.linkedin.com/docs/oauth2" target="_blank">', '</a>'),
                        'placeholder' => esc_html__('812c5r65yg7ck6', JB_SOCIAL_LOGIN_TEXT_DOMAIN),
                        'required' => array( 'social-login-linkedin', '=', true ),
                    )
                )
            );

            $sections['social-login-email'] = array(
                'title'            => esc_html__( 'Email Notice', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                'desc'             => esc_html__( 'Send email to user after connect by social network.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                'id'               => 'social-login-email',
                'icon'             => 'dashicons dashicons-email',
                'subsection'       => true,
                'fields'           => array(
                    array(
                        'id'            => 'social-login-email-from',
                        'type'          => 'text',
                        'title'         => esc_html__( 'From', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('name')
                    ),
                    array(
                        'id'            => 'social-login-email-reply',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Reply', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('admin_email')
                    ),
                    array(
                        'id'            => 'social-login-email-subject',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Subject', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('description'),
                    ),
                    array(
                        'id'            => 'social-login-email-template',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/social-login/emails/new-connect.php.', JB_SOCIAL_LOGIN_TEXT_DOMAIN ),
                    )
                )
            );

            return $sections;
        }
    }
endif;

new JB_Social_Login_Admin();