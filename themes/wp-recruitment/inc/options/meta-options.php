<?php
class WPLMetaPost_Theme{

    function __construct()
    {
        $this->add_meta();
        add_action('admin_head', array($this, 'add_scripts'));
        // Globals meta options.
        add_action('the_post', array( $this, '_set_global' ));
        add_action('wp', array( $this, '_set_global' ));
    }

    public function _set_global(){
        if(is_single() || is_page()) {
            $GLOBALS['opt_meta_options'] = $this->_get_values();
        }
    }

    public function _get_values(){
        global $post;

        $data = array();
        /* Is post. */
        if(empty($post->ID))
            return $data;

        /* Get redux meta data. */
        $_custom = get_post_custom($post->ID);

        foreach ($_custom as $key => $value){
            $data[$key] = maybe_unserialize($value[0]);
        }

        return $data;
    }

    function add_meta()
    {
        if(class_exists('ReduxMeta')){
            redux_meta()->post->add($this->args(), $this->sections_page(), '_page_main_options', esc_html__('Setting', 'wp-recruitment'), 'page');
            redux_meta()->post->add($this->args(array('open_expanded' => true)), $this->sections_post_sub(), '_page_post_sub_options', esc_html__('Setting', 'wp-recruitment'), 'post');
            redux_meta()->post->add($this->args(array('open_expanded' => true)), $this->sections_post(), '_page_post_format_options', esc_html__('Setting', 'wp-recruitment'), 'post');
            redux_meta()->post->add($this->args(), $this->sections_pricing(), '_pricing_options', esc_html__('Setting', 'wp-recruitment'), 'pricing');
            redux_meta()->post->add($this->args(), $this->sections_testimonial(), '_testimonial_options', esc_html__('Setting', 'wp-recruitment'), 'testimonial');
            redux_meta()->post->add($this->args(), $this->sections_team(), '_team_options', esc_html__('Setting', 'wp-recruitment'), 'team');
            redux_meta()->post->add($this->args(), $this->sections_client(), '_client_options', esc_html__('Setting', 'wp-recruitment'), 'client');
            redux_meta()->post->add($this->args(), $this->sections_gallery(), '_gallery_options', esc_html__('Setting', 'wp-recruitment'), 'gallery');
        } elseif (class_exists('WPLMetaPost')) {
            WPLMetaPost::add_meta($this->args(), $this->sections_page(), '_page_main_options', esc_html__('Setting', 'wp-recruitment'), 'page');
            WPLMetaPost::add_meta($this->args(array('open_expanded' => true)), $this->sections_post_sub(), '_page_post_sub_options', esc_html__('Setting', 'wp-recruitment'), 'post');
            WPLMetaPost::add_meta($this->args(array('open_expanded' => true)), $this->sections_post(), '_page_post_format_options', esc_html__('Setting', 'wp-recruitment'), 'post');
            WPLMetaPost::add_meta($this->args(), $this->sections_pricing(), '_pricing_options', esc_html__('Setting', 'wp-recruitment'), 'pricing');
            WPLMetaPost::add_meta($this->args(), $this->sections_testimonial(), '_testimonial_options', esc_html__('Setting', 'wp-recruitment'), 'testimonial');
            WPLMetaPost::add_meta($this->args(), $this->sections_team(), '_team_options', esc_html__('Setting', 'wp-recruitment'), 'team');
            WPLMetaPost::add_meta($this->args(), $this->sections_client(), '_client_options', esc_html__('Setting', 'wp-recruitment'), 'client');
            WPLMetaPost::add_meta($this->args(), $this->sections_gallery(), '_gallery_options', esc_html__('Setting', 'wp-recruitment'), 'gallery');
        }
    }

    function add_scripts() {
        wp_enqueue_style('metabox', get_template_directory_uri() . '/inc/options/css/metabox.css');
    }

    function get_nav_menu(){

        $menus = array(
            '' => esc_html__('Default', 'wp-recruitment')
        );

        $obj_menus = wp_get_nav_menus();

        foreach ($obj_menus as $obj_menu){
            $menus[$obj_menu->term_id] = $obj_menu->name;
        }

        return $menus;
    }

    function args($args = array()){
        $args = wp_parse_args($args, array(
            // TYPICAL -> Change these values as you need/desire
            'opt_name'             => 'opt_meta_options',
            // This is where your data is stored in the database and also becomes your global variable name.
            'display_name'         => '',
            // Name that appears at the top of your panel
            'display_version'      => '',
            // Version that appears at the top of your panel
            'menu_type'            => 'hidden',
            //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
            'allow_sub_menu'       => false,
            // Show the sections below the admin menu item or not
            'menu_title'           => '',
            'page_title'           => '',
            // You will need to generate a Google API key to use this feature.
            // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
            'google_api_key'       => '',
            // Set it you want google fonts to update weekly. A google_api_key value is required.
            'google_update_weekly' => true,
            // Must be defined to add google fonts to the typography module
            'async_typography'     => true,
            // Use a asynchronous font on the front end or font string
            //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
            'admin_bar'            => false,
            // Show the panel pages on the admin bar
            'admin_bar_icon'       => '',
            // Choose an icon for the admin bar menu
            'admin_bar_priority'   => 50,
            // Choose an priority for the admin bar menu
            'global_variable'      => '',
            // Set a different name for your global variable other than the opt_name
            'dev_mode'             => false,
            'forced_dev_mode_off'  => true,
            // Show the time the page took to load, etc
            'update_notice'        => false,
            // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
            'customizer'           => false,
            // Enable basic customizer support
            'open_expanded'     => false,                    // Allow you to start the panel in an expanded way initially.
            'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

            // OPTIONAL -> Give you extra features
            'page_priority'        => null,
            // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
            'page_parent'          => '',
            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
            'page_permissions'     => '',
            // Permissions needed to access the options panel.
            'menu_icon'            => '',
            // Specify a custom URL to an icon
            'last_tab'             => '',
            // Force your panel to always open to a specific tab (by id)
            'page_icon'            => '',
            // Icon displayed in the admin panel next to your menu_title
            'page_slug'            => '',
            // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
            'save_defaults'        => false,
            // On load save the defaults to DB before user clicks save or not
            'default_show'         => false,
            // If true, shows the default value next to each field that is not the default value.
            'default_mark'         => '',
            // What to print by the field's title if the value shown is default. Suggested: *
            'show_import_export'   => false,
            // Shows the Import/Export panel when not used as a field.
            'show_options_object'  => false,
            // CAREFUL -> These options are for advanced use only
            'transient_time'       => 60 * MINUTE_IN_SECONDS,
            'output'               => false,
            // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
            'output_tag'           => false,
            // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
            'footer_credit'     => false,
            // Disable the footer credit of Redux. Please leave if you can help it.

            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
            'database'             => '',
            // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
            'use_cdn'              => true,
            // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
        ));

        return $args;
    }

    function sections_page(){
        $sections = array(
            array(
                'title' => esc_html__('Header', 'wp-recruitment'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id'       => 'custom_header',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Custom Layout', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Yes',
                            '' => 'No',
                        ),
                        'default' => ''
                    ),
                    array(
                        'id' => 'header_layout',
                        'title' => esc_html__('Layouts', 'wp-recruitment'),
                        'subtitle' => esc_html__('Select a layout for header', 'wp-recruitment'),
                        'default' => '1',
                        'type' => 'image_select',
                        'options' => array(
                            '1' => get_template_directory_uri().'/assets/images/header/h1.jpg',
                            '2' => get_template_directory_uri().'/assets/images/header/h2.jpg',
                            '3' => get_template_directory_uri().'/assets/images/header/h3.jpg',
                            '4' => get_template_directory_uri().'/assets/images/header/h4.jpg',
                            '5' => get_template_directory_uri().'/assets/images/header/h5.jpg',
                        ),
                        'required' => array( 'custom_header', '=', '1' )
                    ),
                    array(
                        'id'       => 'header_menu',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Select Menu', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Apply header layout 1', 'wp-recruitment' ),
                        'options'  => $this->get_nav_menu(),
                        'default' => '',
                        'required' => array( 'custom_header', '=', '1' )
                    ),
                )
            ),
            array(
                'title' => esc_html__('Page Title', 'wp-recruitment'),
                'id' => 'tab-page-title-bc',
                'icon' => 'el el-map-marker',
                'fields' => array(
                    array(
                        'id'       => 'custom_page_title',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Custom Layout', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Yes',
                            '' => 'No',
                        ),
                        'default' => ''
                    ),
                    array(
                        'id' => 'page_title_layout',
                        'title' => esc_html__('Layouts', 'wp-recruitment'),
                        'subtitle' => esc_html__('select a layout for page title', 'wp-recruitment'),
                        'default' => '1',
                        'type' => 'image_select',
                        'options' => array(
                            '' => get_template_directory_uri().'/assets/images/pagetitle/p0.jpg',
                            '1' => get_template_directory_uri().'/assets/images/pagetitle/p1.jpg',

                        ),
                        'required' => array( 'custom_page_title', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Select Background Image', 'wp-recruitment'),
                        'id' => 'page_bg_page_title',
                        'type' => 'media',
                        'url' => false,
                        'default' => ''
                    ),
                    array(
                        'id' => 'page_title_text',
                        'type' => 'text',
                        'title' => esc_html__('Custom Title', 'wp-recruitment'),
                        'subtitle' => esc_html__('Custom current page title.', 'wp-recruitment'),
                    ),
                    array(
                        'id' => 'page_title_sub',
                        'type' => 'text',
                        'title' => esc_html__('Sub Title', 'wp-recruitment'),
                    ),
                    array(
                        'id'             => 'page_pagetitle_padding',
                        'type'           => 'spacing',
                        'right'   => false,
                        'left'    => false,
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => esc_html__('Padding', 'wp-recruitment'),
                        'default'            => array(
                            'padding-top'   => '',
                            'padding-bottom'   => '',
                            'units'          => 'px',
                        )
                    ),
                    array(
                        'id'       => 'page_pagetitle_align',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Text Align', 'wp-recruitment'),
                        'options' => array(
                            'left' => 'Left',
                            'center' => 'Center',
                            'right' => 'Right',
                        ),
                        'default' => 'center'
                    ),
                )
            ),
            array(
                'title' => esc_html__('Page', 'wp-recruitment'),
                'id' => 'tab-page-bc',
                'icon' => 'el el-photo',
                'fields' => array(
                    array(
                        'id'       => 'page_full_width',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Stretch Page and Content', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Yes',
                            '' => 'No',
                        ),
                        'default' => ''
                    ),
                    array(
                        'id' => 'content_background_color',
                        'type' => 'color',
                        'title' => esc_html__('Content Background Color', 'wp-recruitment'),
                        'default' => '',
                        'required' => array( 0 => 'page_full_width', 1 => '=', 2 => '1' ),
                    ),
                    array(
                        'id'       => 'enable_sidebar',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Custom Sidebar', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Show',
                            '' => 'Hide',
                        ),
                        'default' => ''
                    ),
                    array(
                        'id'       => 'page_sidebar',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Set sidebar for Page', 'wp-recruitment'),
                        'options' => array(
                            'left-sidebar' => 'Sidebar Left',
                            'no-sidebar' => 'No Sidebar',
                            'right-sidebar' => 'Sidebar Right'
                        ),
                        'default' => 'no-sidebar',
                        'required' => array( 'enable_sidebar', '=', '1' )
                    ),
                    array(
                        'id'             => 'page_content_padding',
                        'type'           => 'spacing',
                        'right'   => false,
                        'left'    => false,
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => esc_html__('Padding', 'wp-recruitment'),
                        'desc'           => esc_html__('Default: Top-90px, Bottom-90px', 'wp-recruitment'),
                        'default'            => array(
                            'padding-top'   => '',
                            'padding-bottom'   => '',
                            'units'          => 'px',
                        )
                    ),
                    array(
                        'id'       => 'page_call_to_action',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Custom Call To Action', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Yes',
                            '' => 'No',
                        ),
                        'default' => ''
                    ),
                    array(
                        'id'       => 'page_show_call_to_action',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Show Call To Action', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Yes',
                            '' => 'No',
                        ),
                        'default' => '',
                        'required' => array( 'page_call_to_action', '=', '1' )
                    ),
                    array(
                        'id' => 'page_cta_title',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'wp-recruitment'),
                        'required' => array( 'page_call_to_action', '=', '1' )
                    ),
                    array(
                        'id' => 'page_cta_decs',
                        'type' => 'text',
                        'title' => esc_html__('Description', 'wp-recruitment'),
                        'required' => array( 'page_call_to_action', '=', '1' )
                    ),
                    array(
                        'id' => 'page_cta_button_text',
                        'type' => 'text',
                        'title' => esc_html__('Button Text', 'wp-recruitment'),
                        'required' => array( 'page_call_to_action', '=', '1' )
                    ),
                    array(
                        'id' => 'page_cta_button_url',
                        'type' => 'text',
                        'title' => esc_html__('Button Url', 'wp-recruitment'),
                        'required' => array( 'page_call_to_action', '=', '1' )
                    ),
                )
            ),
            array(
                'title' => esc_html__('Footer', 'wp-recruitment'),
                'id' => 'tab-footer-bc',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id'       => 'custom_footer',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Custom', 'wp-recruitment'),
                        'options' => array(
                            '1' => 'Yes', 
                            '' => 'No',
                         ), 
                        'default' => ''
                    ),
                    array(
                        'id' => 'footer_layout',
                        'title' => esc_html__('Layouts', 'wp-recruitment'),
                        'subtitle' => esc_html__('Select a layout for Footer', 'wp-recruitment'),
                        'default' => '1',
                        'type' => 'image_select',
                        'options' => array(
                            '1' => get_template_directory_uri().'/assets/images/footer/f1.jpg',
                            '2' => get_template_directory_uri().'/assets/images/footer/f2.jpg',
                        ),
                        'required' => array( 'custom_footer', '=', '1' )
                    ),
                )
            ),
            array(
                'title' => esc_html__('One Page', 'wp-recruitment'),
                'id' => 'tab-one-page',
                'icon' => 'el el-download-alt',
                'fields' => array(
                    array(
                        'subtitle' => esc_html__('Enable one page mode for current page.', 'wp-recruitment'),
                        'id' => 'page_one_page',
                        'type' => 'switch',
                        'title' => esc_html__('Enable', 'wp-recruitment'),
                        'default' => false,
                    ),
                    array(
                        'id'            => 'page_one_page_speed',
                        'type'          => 'slider',
                        'title'         => esc_attr__( 'Speed', 'wp-recruitment' ),
                        'default'       => 1000,
                        'min'           => 500,
                        'step'          => 100,
                        'max'           => 5000,
                        'display_value' => 'text',
                        'required' => array('page_one_page', '=', 1)
                    ),
                )
            )
        );

        return $sections;
    }

    function sections_post(){
        $sections = array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id'       => 'opt-video-type',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Select Video Type', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Local video, Youtube, Vimeo', 'wp-recruitment' ),
                        'options'  => array(
                            'local' => esc_html__('Upload', 'wp-recruitment' ),
                            'youtube' => esc_html__('Youtube', 'wp-recruitment' ),
                            'vimeo' => esc_html__('Vimeo', 'wp-recruitment' ),
                        )
                    ),
                    array(
                        'id'       => 'otp-video-local',
                        'type'     => 'media',
                        'url'      => true,
                        'mode'       => false,
                        'title'    => esc_html__( 'Local Video', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Upload video media using the WordPress native uploader', 'wp-recruitment' ),
                        'required' => array( 'opt-video-type', '=', 'local' )
                    ),
                    array(
                        'id'       => 'opt-video-youtube',
                        'type'     => 'text',
                        'title'    => esc_html__('Youtube', 'wp-recruitment'),
                        'subtitle' => esc_html__('Load video from Youtube.', 'wp-recruitment'),
                        'placeholder' => esc_html__('https://youtu.be/iNJdPyoqt8U', 'wp-recruitment'),
                        'required' => array( 'opt-video-type', '=', 'youtube' )
                    ),
                    array(
                        'id'       => 'opt-video-vimeo',
                        'type'     => 'text',
                        'title'    => esc_html__('Vimeo', 'wp-recruitment'),
                        'subtitle' => esc_html__('Load video from Vimeo.', 'wp-recruitment'),
                        'placeholder' => esc_html__('https://vimeo.com/155673893', 'wp-recruitment'),
                        'required' => array( 'opt-video-type', '=', 'vimeo' )
                    ),
                    array(
                        'id'       => 'otp-video-thumb',
                        'type'     => 'media',
                        'url'      => true,
                        'mode'       => false,
                        'title'    => esc_html__( 'Video Thumb', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Upload thumb media using the WordPress native uploader', 'wp-recruitment' ),
                        'required' => array( 'opt-video-type', '=', 'local' )
                    ),
                    array(
                        'id'       => 'otp-audio',
                        'type'     => 'media',
                        'url'      => true,
                        'mode'       => false,
                        'title'    => esc_html__( 'Audio Media', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Upload audio media using the WordPress native uploader', 'wp-recruitment' ),
                    ),
                    array(
                        'id'       => 'opt-gallery',
                        'type'     => 'gallery',
                        'title'    => esc_html__( 'Add/Edit Gallery', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'wp-recruitment' ),
                    ),
                    array(
                        'id'       => 'opt-quote-title',
                        'type'     => 'text',
                        'title'    => esc_html__('Quote Title', 'wp-recruitment'),
                        'subtitle' => esc_html__('Quote title or quote name...', 'wp-recruitment'),
                    ),
                    array(
                        'id'       => 'opt-quote-content',
                        'type'     => 'textarea',
                        'title'    => esc_html__('Quote Content', 'wp-recruitment'),
                    ),
                )
            ),
        );

        return $sections;
    }

    function sections_post_sub(){

        $sections = array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id'       => 'post_subtitle',
                        'type'     => 'text',
                        'title'    => esc_html__('Sub Title', 'wp-recruitment'),
                    ),
                )
            ),
        );

        return $sections;
    }

    function sections_pricing(){
        $sections = array(
            array(
                'title' => esc_html__('General', 'wp-recruitment'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id' => 'pricing_unit',
                        'type' => 'text',
                        'title' => esc_html__('Currency Unit', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'pricing_price',
                        'type' => 'text',
                        'title' => esc_html__('Price', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'pricing_time',
                        'type' => 'text',
                        'title' => esc_html__('Time', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'pricing_btn_text',
                        'type' => 'text',
                        'title' => esc_html__('Button Text', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'pricing_btn_url',
                        'type' => 'text',
                        'title' => esc_html__('Button Link', 'wp-recruitment'),
                        'default' => '',
                    ),
                )
            ),
        );

        return $sections;
    }

    function sections_testimonial(){
        $sections = array(
            array(
                'title' => esc_html__('General', 'wp-recruitment'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id' => 'testimonial_position',
                        'type' => 'text',
                        'title' => esc_html__('Position', 'wp-recruitment'),
                        'default' => '',
                    ),
                )
            ),
        );

        return $sections;
    }

    function sections_team(){
        $sections = array(
            array(
                'title' => esc_html__('Social', 'wp-recruitment'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id' => 'team_icon1',
                        'type' => 'text',
                        'title' => esc_html__('Icon 1', 'wp-recruitment'),
                        'default' => '',
                        "description" => 'Please add class icon. Use the library <a href="http://zavoloklom.github.io/material-design-iconic-font/icons.html" target="_blank">Material Design Iconic</a>, <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank">FontAwesome</a>',
                    ),
                    array(
                        'id' => 'team_link1',
                        'type' => 'text',
                        'title' => esc_html__('Link 1', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'team_icon2',
                        'type' => 'text',
                        'title' => esc_html__('Icon 2', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'team_link2',
                        'type' => 'text',
                        'title' => esc_html__('Link 2', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'team_icon3',
                        'type' => 'text',
                        'title' => esc_html__('Icon 3', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'team_link3',
                        'type' => 'text',
                        'title' => esc_html__('Link 3', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'team_icon4',
                        'type' => 'text',
                        'title' => esc_html__('Icon 4', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'team_link4',
                        'type' => 'text',
                        'title' => esc_html__('Link 4', 'wp-recruitment'),
                        'default' => '',
                    ),
                )
            ),
        );

        return $sections;
    }

    function sections_client(){
        $sections = array(
            array(
                'title' => esc_html__('General', 'wp-recruitment'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id' => 'client_link',
                        'type' => 'text',
                        'title' => esc_html__('Link', 'wp-recruitment'),
                        'default' => '',
                    ),
                )
            ),
        );

        return $sections;
    }

    function sections_gallery(){
        $sections = array(
            array(
                'title' => esc_html__('General', 'wp-recruitment'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id' => 'gallery_subtitle',
                        'type' => 'text',
                        'title' => esc_html__('Sub Title', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id'       => 'size_gallery',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Crop Image', 'wp-recruitment'),
                        'subtitle' => esc_html__('Apply CMS Grid ( Layout Gallery )', 'wp-recruitment'),
                        'options' => array(
                            'gallery_meta' => 'Default (570x508)',
                            'crop-size2' => 'Size 2 (570x1016)',
                            'crop-size3' => 'Size 3 (1140x508)'
                        ),
                        'default' => 'crop-size1',
                    ),
                )
            ),
        );

        return $sections;
    }
}

new WPLMetaPost_Theme();