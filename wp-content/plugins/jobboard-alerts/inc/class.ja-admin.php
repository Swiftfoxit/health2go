<?php
/**
 * JobBoard Alerts Admin.
 *
 * Action/filter hooks used for JobBoard Alerts admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Alerts
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Alerts_Admin')) :
    class JB_Alerts_Admin{

        function __construct()
        {
            add_filter('jobboard_admin_endpoints', array($this, 'sections_endpoints'));
            add_filter('jobboard_admin_sections', array($this, 'sections_admin'));
        }

        function sections_admin($sections){
            $sections['alerts-settings'] = array(
                'title'     => esc_html__( 'Alerts & Newsletter', JB_ALEART_TEXT_DOMAIN ),
                'id'        => 'alerts-settings',
                'icon'      => 'dashicons dashicons-rss',
                'desc'      => esc_html__( 'Manager subscribe', JB_ALEART_TEXT_DOMAIN ),
                'fields'    => array(
                    array(
                        'id'            => 'alerts-cron-notice',
                        'type'          => 'info',
                        'style'         => 'warning',
                        'title'         => esc_html__( 'Important!', JB_ALEART_TEXT_DOMAIN ),
                        'desc'          => esc_html__( "Before enable 'Alerts Cron' you can add define('ALTERNATE_WP_CRON', true); to file wp-config.php, and your server support wp_mail() function.", JB_ALEART_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'            => 'alerts-cron',
                        'type'          => 'switch',
                        'title'         => esc_html__( 'Alerts Cron', JB_ALEART_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'For every job Approved, a cron job is created and email send to users.', JB_ALEART_TEXT_DOMAIN ),
                        'default'       => false,
                    ),
                    array(
                        'id'            => 'alerts-employer',
                        'type'          => 'switch',
                        'title'         => esc_html__( 'Alerts For Employer', JB_ALEART_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'For every job Approved, a cron job is created and email send to employers.', JB_ALEART_TEXT_DOMAIN ),
                        'default'       => false,
                        'required'      => array( 'alerts-cron', '=', '1' ),
                    ),
                    array(
                        'id'            => 'alerts-candidate',
                        'type'          => 'switch',
                        'title'         => esc_html__( 'Alerts For Candidate', JB_ALEART_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'For every job Approved, a cron job is created and email send to candidates.', JB_ALEART_TEXT_DOMAIN ),
                        'default'       => false,
                        'required'      => array( 'alerts-cron', '=', '1' ),
                    ),
                    array(
                        'id'            => 'alerts-emails',
                        'type'          => 'spinner',
                        'title'         => esc_html__( 'Emails / Seconds', JB_ALEART_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'The total number of emails will be sent in 30 seconds. On average, a server will handle 3 - 6 emails per second.', JB_ALEART_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'Emails', JB_ALEART_TEXT_DOMAIN),
                        'default'       => '30',
                        'min'           => '1',
                        'step'          => '5',
                        'max'           => '1000',
                        'required'      => array( 'alerts-cron', '=', '1' ),
                    ),
                    array(
                        'id'            => 'alerts-schedule',
                        'type'          => 'spinner',
                        'title'         => esc_html__( 'Schedule', JB_ALEART_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'Time interval for each email submission, optimized for sites with multiple users.', JB_ALEART_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'Seconds', JB_ALEART_TEXT_DOMAIN),
                        'default'       => '10',
                        'min'           => '5',
                        'step'          => '10',
                        'max'           => '1800',
                        'required'      => array( 'alerts-cron', '=', '1' ),
                    ),
                    array(
                        'id'            => 'alerts-email-section-start',
                        'type'          => 'section',
                        'title'         => esc_html__( 'Alerts Email', JB_ALEART_TEXT_DOMAIN ),
                        'indent'        => true,
                        'required'      => array( 'alerts-cron', '=', '1' ),
                    ),
                    array(
                        'id'            => 'alerts-email-from',
                        'type'          => 'text',
                        'title'         => esc_html__( 'From', JB_ALEART_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('name')
                    ),
                    array(
                        'id'            => 'alerts-email-reply',
                        'type'          => 'text',
                        'title'         => esc_html__( 'Reply', JB_ALEART_TEXT_DOMAIN ),
                        'placeholder'   => get_bloginfo('admin_email')
                    ),
                    array(
                        'id'            => 'alerts-email-template',
                        'type'          => 'info',
                        'style'         => 'info',
                        'title'         => esc_html__( 'Email Template.', JB_ALEART_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'This template can be overridden by copying it to yourtheme/jobboard/add-ons/alerts/emails/alerts.php.', JB_ALEART_TEXT_DOMAIN ),
                        'required'      => array( 'alerts-cron', '=', '1' ),
                    ),
                    array(
                        'id'            => 'alerts-email-section-end',
                        'type'          => 'section',
                        'indent'        => false,
                    ),
                )
            );

            return $sections;
        }

        function sections_endpoints($sections){
            $sections = array_merge($sections, array(
                array(
                    'id'       => 'section-alerts',
                    'type'     => 'section',
                    'title'    => esc_html__( 'Alerts & Notices', JB_ALEART_TEXT_DOMAIN ),
                    'indent'   => true
                ),
                array(
                    'id'       => 'endpoint-alerts',
                    'type'     => 'text',
                    'title'    => esc_html__( 'Job Alerts', JB_ALEART_TEXT_DOMAIN ),
                    'subtitle' => esc_html__( 'Endpoint for the employer & candidate → view job alerts page', JB_ALEART_TEXT_DOMAIN ),
                    'placeholder' => esc_html__('alerts', JB_ALEART_TEXT_DOMAIN)
                ),
                array(
                    'id'       => 'endpoint-notices',
                    'type'     => 'text',
                    'title'    => esc_html__( 'Manage Notifications', JB_ALEART_TEXT_DOMAIN ),
                    'subtitle' => esc_html__( 'Endpoint for the & employer candidate → view manage notifications page', JB_ALEART_TEXT_DOMAIN ),
                    'placeholder' => esc_html__('notices', JB_ALEART_TEXT_DOMAIN)
                )
            ));

            return $sections;
        }
    }
endif;

new JB_Alerts_Admin();