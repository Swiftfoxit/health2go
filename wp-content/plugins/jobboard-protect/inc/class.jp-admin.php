<?php
/**
 * JobBoard Protect Admin.
 *
 * Action/filter hooks used for JobBoard Protect admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Protect/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Protect_Admin')) :
    class JB_Protect_Admin{

        function __construct()
        {
            add_filter('jobboard_admin_sections', array($this, 'sections_admin'));
        }

        function sections_admin($sections){
            $sections[] = array(
                'title'            => esc_html__( 'Security & Spam', JB_PROTECTED_TEXT_DOMAIN ),
                'id'               => 'security-setting',
                'icon'             => 'dashicons dashicons-shield',
                'desc'             => sprintf(esc_html__( 'reCAPTCHA you can get Site Key and Secret Key %1$sHere →%2$s', JB_PROTECTED_TEXT_DOMAIN), '<a href="https://www.google.com/recaptcha/intro/comingsoon/invisiblebeta.html" target="_blank">', '</a>'),
                'fields'           => array(
                    array(
                        'id'       => 're-captcha-site-key',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Site Key', JB_PROTECTED_TEXT_DOMAIN ),
                        'placeholder' => esc_html__('6LcDtg8UAAAAAOGzbFtgv8bPBFIN-5GpMpT4bRNJ', JB_PROTECTED_TEXT_DOMAIN),
                    ),
                    array(
                        'id'       => 're-captcha-secret-key',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Secret Key', JB_PROTECTED_TEXT_DOMAIN ),
                        'placeholder' => esc_html__('6LcDtg8UAAAAABkJ1DR91hUpRLQHFw94xLeaoIr4', JB_PROTECTED_TEXT_DOMAIN),
                    ),
                    array(
                        'id'       => 'protect-checkout',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Protect Checkout', JB_PROTECTED_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enable captcha for checkout form.', JB_PROTECTED_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'protect-register',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Protect Register', JB_PROTECTED_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Enable captcha for register form', JB_PROTECTED_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                )
            );

            return $sections;
        }
    }
endif;

new JB_Protect_Admin();