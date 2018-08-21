<?php
/**
 * JobBoard Admin.
 *
 * @class        JobBoard_Admin
 * @version        1.0.0
 * @package        JobBoard/Classes
 * @category    Class
 * @author        FOX
 */

if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('JobBoard_Admin')) :

    class JobBoard_Admin
    {

        function __construct()
        {
            add_action('init', array($this, 'admin_settings'));
            add_action("redux/options/jobboard_options/saved", array($this, 'saving_permalink'));
            add_filter('redux/jobboard_options/field/custom/candidate-custom-fields/types', array($this, 'custom_remove_fields'));
            add_filter('redux/jobboard_options/field/custom/employer-custom-fields/types', array($this, 'custom_remove_fields'));
            add_filter('redux/custom/jobboard_options/settings', array($this, 'custom_setting'));
            add_filter('redux/custom/jobboard_options/settings/text', array($this, 'custom_setting_text'));
            add_filter('redux/custom/jobboard_options/settings/media', array($this, 'custom_setting_media'));

            add_filter('jobboard_candidate_profile_fields', array($this, 'fields_candidate_social'), 10);
            add_filter('jobboard_candidate_profile_fields', array($this, 'fields_change_password'), 50);
            add_filter('jobboard_employer_profile_fields', array($this, 'fields_employer_social'), 10);

            //add profile video
            add_action('jobboard_profile_updated', array($this, 'update_profile_video'));
            add_filter('jobboard_admin_profile_sections', array($this, 'add_fields_video'));
            add_filter('jobboard_candidate_profile_fields', array($this, 'fields_video'), 8);
            add_filter('jobboard_employer_profile_fields', array($this, 'fields_video'), 8);

            add_filter('jobboard_employer_profile_fields', array($this, 'fields_change_password'), 50);
            add_filter('jobboard_profile_custom_fields', array($this, 'fields_employer_social'));
            add_filter('jobboard_profile_custom_fields', array($this, 'fields_change_password'));
        }

        function admin_settings()
        {
            if (!class_exists('Redux'))
                return;

            $redux = new Redux();

            $redux::setArgs('jobboard_options', $this->args());
            $redux::setSections('jobboard_options', $this->sections());
        }

        function saving_permalink()
        {
            JB()->query->init_query_vars();
            JB()->query->add_endpoints();
            flush_rewrite_rules();
        }

        function args()
        {

            if (!function_exists('get_plugin_data')) {
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            $plugin = get_plugin_data(JB()->file, array('Name' => 'Plugin Name', 'Version' => 'Version'));

            $args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'jobboard_options',
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $plugin['Name'],
                // Name that appears at the top of your panel
                'display_version' => $plugin['Version'],
                // Version that appears at the top of your panel
                'menu_type' => 'submenu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => false,
                // Show the sections below the admin menu item or not
                'menu_title' => __('Settings', JB_TEXT_DOMAIN),
                'page_title' => __('Settings', JB_TEXT_DOMAIN),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography' => true,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar' => false,
                // Show the panel pages on the admin bar
                'admin_bar_icon' => '',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable' => '',
                // Set a different name for your global variable other than the opt_name
                'dev_mode' => false,
                'forced_dev_mode_off' => false,
                // Show the time the page took to load, etc
                'update_notice' => true,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer' => false,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority' => null,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'edit.php?post_type=jobboard-post-jobs',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_jobboard_options',
                // Permissions needed to access the options panel.
                'menu_icon' => '',
                // Specify a custom URL to an icon
                'last_tab' => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon' => '',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '',
                // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
                'save_defaults' => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show' => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit' => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'use_cdn' => true,
                // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
                'templates_path' => JB()->plugin_directory . '/inc/templates/redux/'
            );

            return apply_filters('jobboard_admin_args', $args);
        }

        private function sections()
        {
            $sections = array(
                'general-setting' => array(
                    'title' => esc_html__('General', JB_TEXT_DOMAIN),
                    'id' => 'general',
                    'icon' => 'dashicons-before dashicons-admin-settings',
                    'fields' => array(
                        array(
                            'id' => 'posts-per-page',
                            'type' => 'slider',
                            'title' => esc_html__('Jobs Listing', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Number of jobs to show per page.', JB_TEXT_DOMAIN),
                            'default' => 12,
                            'min' => 0,
                            'step' => 1,
                            'max' => 100,
                            'display_value' => 'text'
                        ),
                        array(
                            'id' => 'author-per-page',
                            'type' => 'slider',
                            'title' => esc_html__('Companies & Candidates Listing', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Number of Companies or Candidates to show per page.', JB_TEXT_DOMAIN),
                            'default' => 12,
                            'min' => 0,
                            'step' => 1,
                            'max' => 100,
                            'display_value' => 'text'
                        ),
                        array(
                            'id' => 'dashboard-per-page',
                            'type' => 'slider',
                            'title' => esc_html__('Dashboard Listing', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Number of Items in Dashboard to show per page.', JB_TEXT_DOMAIN),
                            'default' => 12,
                            'min' => 0,
                            'step' => 1,
                            'max' => 100,
                            'display_value' => 'text'
                        ),
                        array(
                            'id' => 'default-currency',
                            'type' => 'select',
                            'title' => esc_html__('Default Currency', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('This sets default currency.', JB_TEXT_DOMAIN),
                            'default' => 'USD',
                            'options' => jb_get_currencies_options(),
                        ),
                        array(
                            'id' => 'currency-position',
                            'type' => 'select',
                            'title' => esc_html__('Currency Position', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('This sets the number of decimal points shown in displayed prices.', JB_TEXT_DOMAIN),
                            'default' => 'left',
                            'options' => array(
                                'left' => esc_html__('Left ($99.99)', JB_TEXT_DOMAIN),
                                'right' => esc_html__('Right (99.99$)', JB_TEXT_DOMAIN),
                                'left_space' => esc_html__('Left with space ($ 99.99)', JB_TEXT_DOMAIN),
                                'right_space' => esc_html__('Right with space (99.99 $)', JB_TEXT_DOMAIN),
                            ),
                        ),
                        array(
                            'id' => 'font-awesome',
                            'type' => 'switch',
                            'title' => esc_html__('Load Awesome Font', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('If your theme or other plugins support Awesome Font we recommend turn off options.', JB_TEXT_DOMAIN),
                            'default' => true,
                        ),
                        array(
                            'id' => 'add-job-require-login',
                            'type' => 'switch',
                            'title' => esc_html__('User Login Add Job', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Require user login when add job to basket.', JB_TEXT_DOMAIN),
                            'default' => false,
                        ),
                    )
                ),
                'page-setting' => array(
                    'title' => esc_html__('Pages', JB_TEXT_DOMAIN),
                    'id' => 'page-setting',
                    'icon' => 'dashicons dashicons-admin-page',
                    'desc' => esc_html__('This page is set in pages template drop down.', JB_TEXT_DOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'page-jobs',
                            'type' => 'select',
                            'data' => 'pages',
                            'title' => esc_html__('Jobs', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Page for Job listing.', JB_TEXT_DOMAIN),
                            'desc' => esc_html__('(search, archive, taxonomy, location, tags)', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'page-employers',
                            'type' => 'select',
                            'data' => 'pages',
                            'title' => esc_html__('Employer Listing', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Page for Employer listing.', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'page-candidates',
                            'type' => 'select',
                            'data' => 'pages',
                            'title' => esc_html__('Candidate Listing', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Page for Candidate listing.', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'page-dashboard',
                            'type' => 'select',
                            'data' => 'pages',
                            'title' => esc_html__('Account Dashboard', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Page for User Dashboard.', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'page-list-specialisms',
                            'type' => 'select',
                            'data' => 'pages',
                            'title' => esc_html__('Specialisms List', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Page for Specialisms List.', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'page-locations',
                            'type' => 'select',
                            'data' => 'pages',
                            'title' => esc_html__('Locations', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Page for Location List.', JB_TEXT_DOMAIN),
                        )
                    )
                ),
                'dashboard-setting' => array(
                    'title' => esc_html__('Dashboard', JB_TEXT_DOMAIN),
                    'id' => 'dashboard-setting',
                    'icon' => 'dashicons dashicons-performance'
                ),
                'employer-custom-fields' => array(
                    'title' => esc_html__('Employers', JB_TEXT_DOMAIN),
                    'id' => 'custom-fields-employer',
                    'icon' => 'dashicons dashicons-businessman',
                    'desc' => esc_html__('Employer profile form.', JB_TEXT_DOMAIN),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'employer-custom-fields',
                            'type' => 'rc_custom_fields',
                            'title' => esc_html__('Profile Fields', JB_TEXT_DOMAIN),
                            'default' => $this->default_profile_employer()
                        ),
                        array(
                            'id' => 'employer-social-fields',
                            'type' => 'rc_custom_fields',
                            'title' => esc_html__('Social Network', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Add social network for Employer profile.', JB_TEXT_DOMAIN),
                            'support' => array('text', 'heading'),
                            'default' => $this->default_social()
                        )
                    )
                ),
                'candidate-custom-fields' => array(
                    'title' => esc_html__('Candidates', JB_TEXT_DOMAIN),
                    'id' => 'custom-fields-candidate',
                    'icon' => 'dashicons dashicons-groups',
                    'desc' => esc_html__('Candidates profile form.', JB_TEXT_DOMAIN),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'candidate-custom-fields',
                            'type' => 'rc_custom_fields',
                            'title' => esc_html__('Profile Fields', JB_TEXT_DOMAIN),
                            'default' => $this->default_profile_candidate()
                        ),
                        array(
                            'id' => 'candidate-social-fields',
                            'type' => 'rc_custom_fields',
                            'title' => esc_html__('Social Network', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Add social network for Candidate profile.', JB_TEXT_DOMAIN),
                            'support' => array('text', 'heading'),
                            'default' => $this->default_social()
                        )
                    )
                ),
                'account-endpoints' => array(
                    'title' => esc_html__('Endpoints', JB_TEXT_DOMAIN),
                    'id' => 'account-endpoints',
                    'icon' => 'dashicons dashicons-admin-links',
                    'desc' => esc_html__('Endpoints are appended to your page URLs to handle specific actions on the accounts pages. They should be unique and can be left blank to disable the endpoint.', JB_TEXT_DOMAIN),
                    'subsection' => true,
                    'fields' => $this->default_endpoints()
                ),
                'seo-optimization' => array(
                    'title' => esc_html__('SEO Optimization', JB_TEXT_DOMAIN),
                    'id' => 'seo-optimization',
                    'icon' => 'dashicons dashicons-chart-area',
                    'fields' => array(
                        array(
                            'id' => 'post-job-slug',
                            'type' => 'text',
                            'title' => esc_html__('Job Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for "Job" post type.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('job', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'taxonomy-type-slug',
                            'type' => 'text',
                            'title' => esc_html__('Type Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for "Type" taxonomy.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('type', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'taxonomy-specialism-slug',
                            'type' => 'text',
                            'title' => esc_html__('Specialism Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for "Specialism" taxonomy.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('specialism', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'taxonomy-location-slug',
                            'type' => 'text',
                            'title' => esc_html__('Location Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for "Location" taxonomy.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('location', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'taxonomy-tag-slug',
                            'type' => 'text',
                            'title' => esc_html__('Tag Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for job tag.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('job-tag', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'profile-employer-slug',
                            'type' => 'text',
                            'title' => esc_html__('Employer Profile Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for employer profile.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('employer', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'profile-candidate-slug',
                            'type' => 'text',
                            'title' => esc_html__('Candidate Profile Slug', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Custom base slug for candidate profile.', JB_TEXT_DOMAIN),
                            'placeholder' => esc_html__('candidate', JB_TEXT_DOMAIN),
                        )
                    )
                ),
                'email-setting' => array(
                    'title' => esc_html__('Email Config', JB_TEXT_DOMAIN),
                    'id' => 'email-setting',
                    'icon' => 'dashicons dashicons-email-alt',
                    'fields' => array(
                        array(
                            'id' => 'email-function',
                            'type' => 'button_set',
                            'title' => esc_html__('Email Function', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Select a email function, php mail or smtp server.', JB_TEXT_DOMAIN),
                            'options' => array(
                                'php' => esc_html__('PHP Mail', JB_TEXT_DOMAIN),
                                'smtp' => esc_html__('SMTP', JB_TEXT_DOMAIN)
                            ),
                            'default' => 'php'
                        ),
                        array(
                            'id' => 'email-php-notice',
                            'type' => 'info',
                            'style' => 'warning',
                            'title' => esc_html__('Warning!', JB_TEXT_DOMAIN),
                            'desc' => esc_html__('Make sure your server support php mail() function, Using php mail() will be send email fastest..', JB_TEXT_DOMAIN),
                            'required' => array('email-function', '=', 'php')
                        ),
                        array(
                            'id' => 'email-smtp-notice',
                            'type' => 'info',
                            'style' => 'warning',
                            'title' => esc_html__('Warning!', JB_TEXT_DOMAIN),
                            'desc' => esc_html__('The SMTP service will slow down the send email process, you should consider before use.', JB_TEXT_DOMAIN),
                            'required' => array('email-function', '=', 'smtp')
                        ),
                        array(
                            'id' => 'email-smtp-port',
                            'type' => 'text',
                            'title' => esc_html__('SMTP Port', JB_TEXT_DOMAIN),
                            'required' => array('email-function', '=', 'smtp')
                        ),
                        array(
                            'id' => 'email-smtp-host',
                            'type' => 'text',
                            'title' => esc_html__('SMTP Host', JB_TEXT_DOMAIN),
                            'required' => array('email-function', '=', 'smtp')
                        ),
                        array(
                            'id' => 'email-smtp-secure',
                            'type' => 'radio',
                            'title' => esc_html__('Encryption', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('TLS is not the same STARTTLS. For most servers SSL is the recommended option.', JB_TEXT_DOMAIN),
                            'options' => array(
                                '' => esc_html__('No Encryption', JB_TEXT_DOMAIN),
                                'ssl' => esc_html__('SSL Encryption', JB_TEXT_DOMAIN),
                                'tls' => esc_html__('TLS Encryption', JB_TEXT_DOMAIN)
                            ),
                            'default' => '',
                            'required' => array('email-function', '=', 'smtp')
                        ),
                        array(
                            'id' => 'email-smtp-auth',
                            'type' => 'switch',
                            'title' => esc_html__('Authentication', JB_TEXT_DOMAIN),
                            'subtitle' => esc_html__('If you set to no, the values bellow are ignored.', JB_TEXT_DOMAIN),
                            'default' => true,
                            'required' => array('email-function', '=', 'smtp')
                        ),
                        array(
                            'id' => 'email-smtp-login',
                            'type' => 'password',
                            'username' => true,
                            'title' => esc_html__('User/Password', JB_TEXT_DOMAIN),
                            'placeholder' => array(
                                'username' => esc_html__('Username', JB_TEXT_DOMAIN),
                                'password' => esc_html__('Password', JB_TEXT_DOMAIN),
                            ),
                            'required' => array('email-function', '=', 'smtp')
                        )
                    )
                ),
                'email-applied' => array(
                    'title' => esc_html__('Applied', JB_TEXT_DOMAIN),
                    'id' => 'email-applied',
                    'icon' => 'dashicons dashicons-yes',
                    'desc' => esc_html__('Send email after Candidate applied a job.', JB_TEXT_DOMAIN),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'email-applied-candidate-section-start',
                            'type' => 'section',
                            'title' => esc_html__('Candidate', JB_TEXT_DOMAIN),
                            'indent' => true
                        ),
                        array(
                            'id' => 'email-applied-candidate-from',
                            'type' => 'text',
                            'title' => esc_html__('From', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('name')
                        ),
                        array(
                            'id' => 'email-applied-candidate-reply',
                            'type' => 'text',
                            'title' => esc_html__('Reply', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('admin_email')
                        ),
                        array(
                            'id' => 'email-applied-candidate-subject',
                            'type' => 'text',
                            'title' => esc_html__('Subject', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('description'),
                        ),
                        array(
                            'id' => 'email-applied-candidate-template',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Email Template.', JB_TEXT_DOMAIN),
                            'desc' => esc_html__('This template can be overridden by copying it to yourtheme/jobboard/emails/candidate-applied.php.', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'email-applied-candidate-section-end',
                            'type' => 'section',
                            'indent' => false,
                        ),
                        array(
                            'id' => 'email-applied-employer-section-start',
                            'type' => 'section',
                            'title' => esc_html__('Employer', JB_TEXT_DOMAIN),
                            'indent' => true
                        ),
                        array(
                            'id' => 'email-applied-employer-from',
                            'type' => 'text',
                            'title' => esc_html__('From', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('name')
                        ),
                        array(
                            'id' => 'email-applied-employer-reply',
                            'type' => 'text',
                            'title' => esc_html__('Reply', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('admin_email')
                        ),
                        array(
                            'id' => 'email-applied-employer-subject',
                            'type' => 'text',
                            'title' => esc_html__('Subject', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('description'),
                        ),
                        array(
                            'id' => 'email-applied-employer-template',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Email Template.', JB_TEXT_DOMAIN),
                            'desc' => esc_html__('This template can be overridden by copying it to yourtheme/jobboard/emails/employer-applied.php.', JB_TEXT_DOMAIN),
                        ),
                        array(
                            'id' => 'email-applied-employer-section-end',
                            'type' => 'section',
                            'indent' => false,
                        )
                    )
                ),
                'email-application' => array(
                    'title' => esc_html__('Application', JB_TEXT_DOMAIN),
                    'id' => 'email-application',
                    'icon' => 'dashicons dashicons-id',
                    'desc' => esc_html__('Send email after Employer approval or reject a application.', JB_TEXT_DOMAIN),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'email-application-from',
                            'type' => 'text',
                            'title' => esc_html__('From', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('name')
                        ),
                        array(
                            'id' => 'email-application-reply',
                            'type' => 'text',
                            'title' => esc_html__('Reply', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('admin_email')
                        ),
                        array(
                            'id' => 'email-application-subject',
                            'type' => 'text',
                            'title' => esc_html__('Subject', JB_TEXT_DOMAIN),
                            'placeholder' => get_bloginfo('description'),
                        ),
                        array(
                            'id' => 'email-application-template',
                            'type' => 'info',
                            'style' => 'info',
                            'title' => esc_html__('Email Template.', JB_TEXT_DOMAIN),
                            'desc' => esc_html__('This template can be overridden by copying it to yourtheme/jobboard/emails/application.php.', JB_TEXT_DOMAIN),
                        )
                    )
                ),
                'search-settings' => array(
                    'title' => esc_html__('Search', JB_TEXT_DOMAIN),
                    'id' => 'search-settings',
                    'icon' => 'dashicons dashicons-search',
                    'desc' => esc_html__('Search settings.', JB_TEXT_DOMAIN),
                ),
            );

            return apply_filters('jobboard_admin_sections', $sections);
        }

        function default_profile()
        {
            return apply_filters('jobboard_admin_profile', array(
                10 => array(
                    'id' => 'profile-heading',
                    'title' => esc_html__('Your Profile', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Edit and update your profile.', JB_TEXT_DOMAIN),
                    'type' => 'heading',
                    'heading' => 'h3'
                ),
                20 => array(
                    'id' => 'user_email',
                    'title' => esc_html__('Email', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your email', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'input' => 'email',
                    'require' => 1,
                    'placeholder' => esc_html__('your-email@your-domain.com', JB_TEXT_DOMAIN)
                ),
                30 => array(
                    'id' => 'first_name',
                    'title' => esc_html__('First Name', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your first name', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'require' => 1,
                    'col' => 6,
                    'placeholder' => esc_html__('First Name', JB_TEXT_DOMAIN)
                ),
                40 => array(
                    'id' => 'last_name',
                    'title' => esc_html__('Last name', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your last name', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'require' => 1,
                    'col' => 6,
                    'placeholder' => esc_html__('Last name', JB_TEXT_DOMAIN)
                ),
                50 => array(
                    'id' => 'address-1',
                    'title' => esc_html__('Address 1', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your address 1', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'require' => 1,
                    'placeholder' => esc_html__('Address 1', JB_TEXT_DOMAIN)
                ),
                60 => array(
                    'id' => 'address-2',
                    'title' => esc_html__('Address 2', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your address 2', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'placeholder' => esc_html__('Address 2', JB_TEXT_DOMAIN)
                ),
                70 => array(
                    'id' => 'user_city',
                    'title' => esc_html__('City', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your city', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'placeholder' => esc_html__('City', JB_TEXT_DOMAIN)
                ),
                80 => array(
                    'id' => 'user_country',
                    'title' => esc_html__('Country', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your country', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'placeholder' => esc_html__('Country', JB_TEXT_DOMAIN)
                ),
                90 => array(
                    'id' => 'user_phone',
                    'title' => esc_html__('Phone', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your phone number', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'input' => 'tel',
                    'placeholder' => esc_html__('+1 646 4706923', JB_TEXT_DOMAIN)
                ),
                100 => array(
                    'id' => 'url',
                    'title' => esc_html__('Website', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your website.', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'input' => 'url',
                    'placeholder' => esc_html__('https://www.your-website.com', JB_TEXT_DOMAIN)
                ),
                110 => array(
                    'id' => 'description',
                    'title' => esc_html__('About', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your description.', JB_TEXT_DOMAIN),
                    'type' => 'textarea',
                    'placeholder' => esc_html__('Share information to fill out your profile. This may be shown publicly.', JB_TEXT_DOMAIN)
                ),
                120 => array(
                    'id' => 'image-heading',
                    'title' => esc_html__('Profile Image', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Upload your image profile.', JB_TEXT_DOMAIN),
                    'type' => 'heading',
                    'heading' => 'h3'
                ),
                130 => array(
                    'id' => 'user_avatar',
                    'title' => esc_html__('Avatar Image', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Upload your avatar image.', JB_TEXT_DOMAIN),
                    'type' => 'media',
                    'input' => 'image',
                    'types' => 'jpg,png',
                    'size' => 200,
                    'col' => 6,
                ),
                140 => array(
                    'id' => 'user_cover',
                    'title' => esc_html__('Cover Photo', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Upload your cover photo.', JB_TEXT_DOMAIN),
                    'type' => 'media',
                    'input' => 'image',
                    'types' => 'jpg,png',
                    'size' => 1024,
                    'col' => 6,
                ),
            ));
        }

        function default_profile_employer()
        {
            $fields = $this->default_profile();
            $fields[111] = array(
                'id' => 'job-heading',
                'title' => esc_html__('Recruitment Info', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('To Candidates easily find you, you need to complete the recruitment information.', JB_TEXT_DOMAIN),
                'type' => 'heading',
                'heading' => 'h3'
            );
            $fields[112] = array(
                'id' => 'job_specialisms',
                'title' => esc_html__('Specialisms', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Select your specialisms.', JB_TEXT_DOMAIN),
                'placeholder' => esc_html__('Specialisms', JB_TEXT_DOMAIN),
                'type' => 'select',
                'multi' => true
            );
            $fields[113] = array(
                'id' => 'job_vacancies',
                'title' => esc_html__('Vacancies', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Enter your vacancies.', JB_TEXT_DOMAIN),
                'placeholder' => esc_html__('Vacancies', JB_TEXT_DOMAIN),
                'type' => 'text',
                'input' => 'number'
            );
            $fields = apply_filters('jobboard_admin_profile_employer', $fields);
            ksort($fields);
            return $fields;
        }

        function default_profile_candidate()
        {
            $fields = $this->default_profile();

            $fields[41] = array(
                'id' => 'user_sex',
                'title' => esc_html__('Sex', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Select your sex.', JB_TEXT_DOMAIN),
                'placeholder' => esc_html__('Your sex', JB_TEXT_DOMAIN),
                'type' => 'select',
                'options' => array(
                    'female' => esc_html__('Female', JB_TEXT_DOMAIN),
                    'male' => esc_html__('Male', JB_TEXT_DOMAIN),
                    'none' => esc_html__('Do not want to say', JB_TEXT_DOMAIN),
                ),
                'require' => 1,
                'col' => 6,
            );
            $fields[42] = array(
                'id' => 'user_birthday',
                'title' => esc_html__('Birthday', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Select your birthday (mm/dd/yyyy).', JB_TEXT_DOMAIN),
                'placeholder' => esc_html__('10/05/1990', JB_TEXT_DOMAIN),
                'type' => 'text',
                'input' => 'date',
                'require' => 1,
                'col' => 6,
            );
            $fields[111] = array(
                'id' => 'job-heading',
                'title' => esc_html__('Suggest jobs', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('We will suggest work according to your wishes.', JB_TEXT_DOMAIN),
                'type' => 'heading',
                'heading' => 'h3'
            );
            $fields[112] = array(
                'id' => 'job_specialisms',
                'title' => esc_html__('Jobs Interests', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Select your interests.', JB_TEXT_DOMAIN),
                'placeholder' => esc_html__('Jobs Interests', JB_TEXT_DOMAIN),
                'type' => 'select',
                'multi' => true
            );
            $fields[113] = array(
                'id' => 'job_salary',
                'title' => esc_html__('Minimum Salary', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Set minimum salary ($).', JB_TEXT_DOMAIN),
                'placeholder' => esc_html__('Minimum Salary', JB_TEXT_DOMAIN),
                'type' => 'text',
                'input' => 'number'
            );
            $fields[150] = array(
                'id' => 'cv-heading',
                'title' => esc_html__('CV', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Upload your cv file ( .pdf, .docx, .doc, .rtf ).', JB_TEXT_DOMAIN),
                'type' => 'heading',
                'heading' => 'h3'
            );
            $fields[160] = array(
                'id' => 'cv',
                'title' => esc_html__('CV File', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Upload your CV file.', JB_TEXT_DOMAIN),
                'type' => 'media',
                'types' => 'pdf,docx,doc,rtf',
                'require' => 1,
                'size' => 1000
            );
            $fields = apply_filters('jobboard_admin_profile_candidate', $fields);
            ksort($fields);
            return $fields;
        }

        function default_social()
        {
            return apply_filters('jobboard_admin_social', array(
                array(
                    'id' => 'social-heading',
                    'title' => esc_html__('Social Network', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your social network url.', JB_TEXT_DOMAIN),
                    'type' => 'heading',
                    'heading' => 'h3',
                ),
                array(
                    'id' => 'social-facebook',
                    'title' => esc_html__('Facebook URL', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your Facebook url', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'col' => 6,
                    'class' => 'fa fa-facebook-square',
                    'placeholder' => esc_html__('https://www.facebook.com/', JB_TEXT_DOMAIN),
                ),
                array(
                    'id' => 'social-twitter',
                    'title' => esc_html__('Twitter URL', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your Twitter url', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'col' => 6,
                    'class' => 'fa fa-twitter-square',
                    'placeholder' => esc_html__('https://twitter.com/', JB_TEXT_DOMAIN),
                ),
                array(
                    'id' => 'social-plus',
                    'title' => esc_html__('Google+ URL', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your Google+ url', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'col' => 6,
                    'class' => 'fa fa-google-plus-square',
                    'placeholder' => esc_html__('https://plus.google.com/', JB_TEXT_DOMAIN),
                ),
                array(
                    'id' => 'social-linkedin',
                    'title' => esc_html__('Linkedin URL', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Enter your Linkedin url', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'col' => 6,
                    'class' => 'fa fa-linkedin-square',
                    'placeholder' => esc_html__('https://www.linkedin.com/', JB_TEXT_DOMAIN),
                )
            ));
        }

        function default_endpoints()
        {

            $employer = apply_filters('jobboard_admin_endpoints_employer', array(
                array(
                    'id' => 'section-employer',
                    'type' => 'section',
                    'title' => esc_html__('Employer', JB_TEXT_DOMAIN),
                    'indent' => true
                ),
                array(
                    'id' => 'endpoint-jobs',
                    'type' => 'text',
                    'title' => esc_html__('Applications History', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Endpoint for the employer → view manage jobs page', JB_TEXT_DOMAIN),
                    'placeholder' => esc_html__('jobs', JB_TEXT_DOMAIN),
                ),
                array(
                    'id' => 'endpoint-new-job',
                    'type' => 'text',
                    'title' => esc_html__('Post New ', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Endpoint for the employer → view post a new job page', JB_TEXT_DOMAIN),
                    'placeholder' => esc_html__('new-job', JB_TEXT_DOMAIN),
                )
            ));

            $candidate = apply_filters('jobboard_admin_endpoints_candidate', array(
                array(
                    'id' => 'section-candidate',
                    'type' => 'section',
                    'title' => esc_html__('Candidate', JB_TEXT_DOMAIN),
                    'indent' => true
                ),
                array(
                    'id' => 'endpoint-applied',
                    'type' => 'text',
                    'title' => esc_html__('Applications History', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Endpoint for the candidate → view applications history page', JB_TEXT_DOMAIN),
                    'placeholder' => esc_html__('applied', JB_TEXT_DOMAIN)
                ),
                array(
                    'id' => 'endpoint-profile',
                    'type' => 'text',
                    'title' => esc_html__('Manage Profile', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__('Endpoint for the candidate → view manager profile page', JB_TEXT_DOMAIN),
                    'placeholder' => esc_html__('profile', JB_TEXT_DOMAIN)
                )
            ));

            return apply_filters('jobboard_admin_endpoints', array_merge($candidate, $employer));
        }

        function custom_setting($fields = array())
        {

            $fields['require'] = array(
                'name' => 'require',
                'type' => 'select',
                'title' => esc_html__('Require', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Front-end require field (*).', JB_TEXT_DOMAIN),
                'options' => array(
                    false => esc_html__('No', JB_TEXT_DOMAIN),
                    true => esc_html__('Yes', JB_TEXT_DOMAIN),
                )
            );

            $fields['require_notice'] = array(
                'name' => 'notice',
                'type' => 'text',
                'title' => esc_html__('Require Notice', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Front-end field validate notice.', JB_TEXT_DOMAIN),
            );

            return $fields;
        }

        function custom_setting_text($fields)
        {

            $fields['input'] = array(
                'name' => 'input',
                'type' => 'select',
                'title' => esc_html__('Type', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Front-end text, number, email, password...', JB_TEXT_DOMAIN),
                'options' => array(
                    'text' => esc_html__('Text', JB_TEXT_DOMAIN),
                    'number' => esc_html__('Number', JB_TEXT_DOMAIN),
                    'email' => esc_html__('Email', JB_TEXT_DOMAIN),
                    'password' => esc_html__('Password', JB_TEXT_DOMAIN),
                    'search' => esc_html__('Search', JB_TEXT_DOMAIN),
                    'tel' => esc_html__('Tel', JB_TEXT_DOMAIN),
                    'url' => esc_html__('Url', JB_TEXT_DOMAIN),
                    'time' => esc_html__('Time', JB_TEXT_DOMAIN),
                    'date' => esc_html__('Date', JB_TEXT_DOMAIN),
                    'datetime' => esc_html__('Datetime', JB_TEXT_DOMAIN),
                )
            );

            return $fields;
        }

        function custom_setting_media($fields = array())
        {

            $fields['input'] = array(
                'name' => 'input',
                'type' => 'select',
                'title' => esc_html__('Type', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Front-end file or image.', JB_TEXT_DOMAIN),
                'options' => array(
                    'file' => esc_html__('File', JB_TEXT_DOMAIN),
                    'image' => esc_html__('Image', JB_TEXT_DOMAIN)
                )
            );

            $fields['require-types'] = array(
                'name' => 'types',
                'type' => 'text',
                'title' => esc_html__('Upload Types', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Front-end allow upload file types.', JB_TEXT_DOMAIN),
                'placeholder' => 'jpg,png,pdf,... or image/jpeg,image/png,application/pdf,...'
            );

            $fields['require-dimension'] = array(
                'name' => 'size',
                'type' => 'text',
                'title' => esc_html__('Upload Size (Kb)', JB_TEXT_DOMAIN),
                'subtitle' => esc_html__('Front-end maximum upload size.', JB_TEXT_DOMAIN),
                'default' => 1000,
                'placeholder' => 1000
            );

            return $fields;
        }

        function custom_remove_fields($fields)
        {

            unset($fields['switch']);
            unset($fields['color']);
            unset($fields['color-rgba']);
            unset($fields['gallery']);
            unset($fields['ace-editor']);

            return $fields;
        }

        function fields_candidate_social($fields)
        {
            $social = jb_get_option('candidate-social-fields');

            if (!$social) {
                return $fields;
            }

            return array_merge($fields, $social);
        }

        function update_profile_video()
        {
            if (isset($_POST['user_video']) && wp_http_validate_url($_POST['user_video']))
                update_user_meta(get_current_user_id(), 'user_video', $_POST['user_video']);
        }

        function add_fields_video($sections)
        {
            $sections[17] = array(
                'id' => 'user_video',
                'title' => esc_html__('Video', 'jobboard-map'),
                'subtitle' => esc_html__('Enter your video url.', 'jobboard-map'),
                'type' => 'text'
            );
            return $sections;
        }

        public function fields_video($fields)
        {

            $video = array(
                'video-profile' => array(
                    'id' => 'profile-video',
                    'title' => esc_html__('Profile Video', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__("Add your video.", JB_TEXT_DOMAIN),
                    'type' => 'heading',
                    'heading' => 'h3'
                ),
                'user_video' => array(
                    'id' => 'user_video',
                    'title' => esc_attr__('Enter Video Url', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'input' => 'url',
                    'placeholder' => esc_attr__('Enter Video Url', JB_TEXT_DOMAIN)
                ),
                'video-frame' => array(
                    'id' => 'user_video',
                    'type' => 'video',
                    'value' => '',
                ),
            );
            return array_merge($fields, $video);
        }

        function fields_employer_social($fields)
        {
            $social = jb_get_option('employer-social-fields');

            if (!$social) {
                return $fields;
            }

            return array_merge($fields, $social);
        }

        function fields_change_password($fields)
        {

            $pass = array(
                'change-pass-heading' => array(
                    'id' => 'change-pass-heading',
                    'title' => esc_html__('Change Password', JB_TEXT_DOMAIN),
                    'subtitle' => esc_html__("Leave blank if you'd like your password to remain the same.", JB_TEXT_DOMAIN),
                    'type' => 'heading',
                    'heading' => 'h3'
                ),
                'new-password' => array(
                    'id' => 'new-password',
                    'title' => esc_attr__('New Password', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'input' => 'password',
                    'placeholder' => esc_attr__('New Password', JB_TEXT_DOMAIN)
                ),
                'confirm-password' => array(
                    'id' => 'confirm-password',
                    'title' => esc_html__('Confirm Password', JB_TEXT_DOMAIN),
                    'type' => 'text',
                    'input' => 'password',
                    'placeholder' => esc_html__('Confirm Password', JB_TEXT_DOMAIN)
                ),
            );

            return array_merge($fields, $pass);
        }
    }

endif;