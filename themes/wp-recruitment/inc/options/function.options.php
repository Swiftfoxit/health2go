<?php
class WPLThemeOptions{

    public $custom_font_1 = array();

    function __construct()
    {
        $this->admin_page();
    }

    function admin_page(){

        if(!class_exists('Redux'))
            return;

        $redux = new Redux();

        /* Extra Font. */
        $custom_font_1 = $redux::getOption('opt_theme_options', 'google-font-selector-1');
        $this->custom_font_1 = !empty($custom_font_1) ? explode(',', $custom_font_1) : array();

        $redux::setArgs('opt_theme_options', $this->args());
        $redux::setSections('opt_theme_options', $this->sections());
    }

    function args(){
        $args = array(
            // TYPICAL -> Change these values as you need/desire
            'opt_name'             => 'opt_theme_options',
            // This is where your data is stored in the database and also becomes your global variable name.
            'display_name'         => esc_html__('Theme Options', 'wp-recruitment'),
            // Name that appears at the top of your panel
            'display_version'      => '1.0.0',
            // Version that appears at the top of your panel
            'menu_type'            => 'menu',
            //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
            'allow_sub_menu'       => true,
            // Show the sections below the admin menu item or not
            'menu_title'           => esc_html__('Theme Options', 'wp-recruitment'),
            'page_title'           => esc_html__('Theme Options', 'wp-recruitment'),
            // You will need to generate a Google API key to use this feature.
            // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
            'google_api_key'       => '',
            // Set it you want google fonts to update weekly. A google_api_key value is required.
            'google_update_weekly' => false,
            // Must be defined to add google fonts to the typography module
            'async_typography'     => true,
            // Use a asynchronous font on the front end or font string
            //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
            'admin_bar'            => true,
            // Show the panel pages on the admin bar
            'admin_bar_icon'       => 'dashicons-portfolio',
            // Choose an icon for the admin bar menu
            'admin_bar_priority'   => 50,
            // Choose an priority for the admin bar menu
            'global_variable'      => '',
            // Set a different name for your global variable other than the opt_name
            'dev_mode'             => true,
            'forced_dev_mode_off'  => true,
            // Show the time the page took to load, etc
            'update_notice'        => true,
            // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
            'customizer'           => true,
            // Enable basic customizer support
            //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
            //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

            // OPTIONAL -> Give you extra features
            'page_priority'        => null,
            // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
            'page_parent'          => 'themes.php',
            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
            'page_permissions'     => 'manage_options',
            // Permissions needed to access the options panel.
            'menu_icon'            => '',
            // Specify a custom URL to an icon
            'last_tab'             => '',
            // Force your panel to always open to a specific tab (by id)
            'page_icon'            => 'icon-themes',
            // Icon displayed in the admin panel next to your menu_title
            'page_slug'            => '',
            // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
            'save_defaults'        => true,
            // On load save the defaults to DB before user clicks save or not
            'default_show'         => false,
            // If true, shows the default value next to each field that is not the default value.
            'default_mark'         => '',
            // What to print by the field's title if the value shown is default. Suggested: *
            'show_import_export'   => true,
            // Shows the Import/Export panel when not used as a field.

            // CAREFUL -> These options are for advanced use only
            'transient_time'       => 60 * MINUTE_IN_SECONDS,
            'output'               => true,
            // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
            'output_tag'           => true,
            // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
            // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
            'database'             => '',
            // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
            'use_cdn'              => true,
            // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
            'templates_path'       => get_template_directory() . '/inc/templates/redux/'
        );

        return $args;
    }

    function sections(){
        $sections = array(
            array(
                'title' => esc_html__('Header', 'wp-recruitment'),
                'icon' => 'el-icon-credit-card',
                'fields' => array(
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
                        )
                    ),
                    array(
                        'id' => 'view_job_text',
                        'type' => 'text',
                        'title' => esc_html__('View Jobs: Butotn Text', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'view_job_url',
                        'type' => 'text',
                        'title' => esc_html__('View Jobs: Butotn Link', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'subtitle' => esc_html__('enable sticky mode for menu.', 'wp-recruitment'),
                        'id' => 'menu_sticky',
                        'type' => 'switch',
                        'title' => esc_html__('Sticky Header', 'wp-recruitment'),
                        'default' => false,
                    ),
                    array(
                        'subtitle'          => esc_html__('Enable mega menu.', 'wp-recruitment'),
                        'id'                => 'mega_menu',
                        'type'              => 'switch',
                        'title'             => esc_html__('Mega Menu', 'wp-recruitment'),
                        'default'           => false,
                    ),   
                )
            ),
            array(
                'title' => esc_html__('Top Bar', 'wp-recruitment'),
                'icon' => 'el el-resize-vertical',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'top_bar_phone',
                        'type' => 'text',
                        'title' => esc_html__('Phone', 'wp-recruitment'),
                        'default' => '+44 (0)1254 302034',
                    ),
                    array(
                        'id' => 'top_bar_email',
                        'type' => 'text',
                        'title' => esc_html__('Email', 'wp-recruitment'),
                        'default' => 'enquiries@recruitment.com',
                    ),
                    array(
                        'id'      => 'top_bar_social',
                        'type'    => 'sorter',
                        'title'   => 'Social',
                        'desc'    => 'Choose which social networks are displayed and edit where they link to.',
                        'options' => array(
                            'enabled'  => array(
                                'facebook'  => 'Facebook',
                                'twitter'   => 'Twitter',
                                'linkedin'  => 'inkedin',
                                'google'    => 'Google',
                            ),
                            'disabled' => array(
                                'skype'     => 'Skype',
                                'youtube'   => 'Youtube',
                                'vimeo'     => 'Vimeo',
                                'tumblr'    => 'Tumblr',
                                'rss'       => 'RSS',
                                'pinterest' => 'Pinterest',
                                'instagram' => 'Instagram',
                                'yelp'      => 'Yelp'
                            )
                        ),
                    ),
                    array(
                        'subtitle' => esc_html__('Set text color for Top Bar.', 'wp-recruitment'),
                        'id' => 'top_bar_text_color',
                        'type' => 'color',
                        'title' => esc_html__('Text Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('#cms-theme #cshero-header-inner.header-top #cshero-header-top, #cms-theme #cshero-header-inner.header-top #cshero-header-top .contact-top i'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color for Top Bar.', 'wp-recruitment'),
                        'id' => 'top_bar_link_color',
                        'type' => 'color',
                        'title' => esc_html__('Link Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('#cms-theme #cshero-header-inner.header-top #cshero-header-top a'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color hover for Top Bar.', 'wp-recruitment'),
                        'id' => 'top_bar_link_hover_color',
                        'type' => 'color',
                        'title' => esc_html__('Link Color Hover', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('#cms-theme #cshero-header-inner.header-top #cshero-header-top a:hover'),
                    ),
                    array(
                        'id'       => 'top_bar_color',
                        'type'     => 'background',
                        'background-image' => false,
                        'background-position' => false,
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'title'    => esc_html__( 'Bacgkround Color', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Set background color for Top Bar.', 'wp-recruitment' ),
                        'output'   => array('#cms-theme #cshero-header-inner.header-top #cshero-header-top'),
                    ),
                )
            ),
            array(
                'title' => esc_html__('Logo', 'wp-recruitment'),
                'icon' => 'el-icon-picture',
                'subsection' => true,
                'fields' => array(
                    array(
                        'title' => esc_html__('Default Logo', 'wp-recruitment'),
                        'subtitle' => esc_html__('Select an image file for your logo.', 'wp-recruitment'),
                        'id' => 'main_logo',
                        'type' => 'media',
                        'url' => false,
                        'default' => array(
                            'url'=>get_template_directory_uri().'/assets/images/logo.png'
                        )
                    ),

                    array(
                        'id'       => 'main_logo_height',
                        'type'     => 'dimensions',
                        'units'    => array('px'),
                        'title'    => esc_html__('Logo (Width/Height)', 'wp-recruitment'),
                        'default'  => array(
                            'Width'   => '',
                            'Height'  => ''
                        ),
                        'output' => array('#cshero-header-inner.header-1 #cshero-header #cshero-header-logo a img'),
                    ),
                )
            ),
            array(
                'title' => esc_html__('Page Title', 'wp-recruitment'),
                'icon' => 'el-icon-map-marker',
                'fields' => array(
                    array(
                        'id' => 'page_title_layout',
                        'title' => esc_html__('Layouts', 'wp-recruitment'),
                        'subtitle' => esc_html__('Select a layout for page title', 'wp-recruitment'),
                        'default' => '1',
                        'type' => 'image_select',
                        'options' => array(
                            '1' => get_template_directory_uri().'/assets/images/pagetitle/p1.jpg',
                        )
                    ),
                    array(
                        'title' => esc_html__('Select Background Image', 'wp-recruitment'),
                        'id' => 'bg_page_title',
                        'type' => 'media',
                        'url' => false,
                        'default' => array(
                            'url'=>get_template_directory_uri().'/assets/images/bg-page-title.jpg'
                        )
                    ),
                    array(
                        'id' => 'page_title_color',
                        'type' => 'color',
                        'title' => esc_html__('Text Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('#cms-theme #cms-page-title .cms-page-title-inner h1'),
                    ),
                    array(
                        'id'       => 'page_title_align',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Text Align', 'wp-recruitment'),
                        'options' => array(
                            'left' => 'Left',
                            'center' => 'Center',
                            'right' => 'Right',
                        ),
                        'default' => 'center'
                    )
                )
            ),
            array(
                'title' => esc_html__('Body', 'wp-recruitment'),
                'icon' => 'el el-website',
                'fields' => array(
                    array(
                        'id'             => 'content_padding',
                        'type'           => 'spacing',
                        'output'         => array('body #cms-content'),
                        'right'   => false,
                        'left'    => false,
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => esc_html__('Content Padding', 'wp-recruitment'),
                        'desc'           => esc_html__('Default: Top-90px, Bottom-90px', 'wp-recruitment'),
                        'default'            => array(
                            'padding-top'   => '',
                            'padding-bottom'   => '',
                            'units'          => 'px',
                        )
                    ),
                    array(
                        'id' => 'page_loadding',
                        'type' => 'switch',
                        'title' => esc_html__('Page Loadding', 'wp-recruitment'),
                        'default' => false,
                    ),
                    array(
                        'id' => 'show_call_to_action',
                        'type' => 'switch',
                        'title' => esc_html__('Show Call To Action', 'wp-recruitment'),
                        'default' => false,
                    ),
                    array(
                        'id' => 'cta_title',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'wp-recruitment'),
                        'default' => '',
                        'required' => array( 0 => 'show_call_to_action', 1 => '=', 2 => '1' ),
                    ),
                    array(
                        'id' => 'cta_desc',
                        'type' => 'text',
                        'title' => esc_html__('Description', 'wp-recruitment'),
                        'default' => '',
                        'required' => array( 0 => 'show_call_to_action', 1 => '=', 2 => '1' ),
                    ),
                    array(
                        'id' => 'cta_button_text',
                        'type' => 'text',
                        'title' => esc_html__('Button Text', 'wp-recruitment'),
                        'default' => '',
                        'required' => array( 0 => 'show_call_to_action', 1 => '=', 2 => '1' ),
                    ),
                    array(
                        'id' => 'cta_button_url',
                        'type' => 'text',
                        'title' => esc_html__('Button Url', 'wp-recruitment'),
                        'default' => '',
                        'required' => array( 0 => 'show_call_to_action', 1 => '=', 2 => '1' ),
                    ),
                )
            ),
            array(
                'title' => esc_html__('Footer', 'wp-recruitment'),
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'id' => 'footer_layout',
                        'title' => esc_html__('Layouts', 'wp-recruitment'),
                        'subtitle' => esc_html__('select a layout for footer', 'wp-recruitment'),
                        'default' => '1',
                        'type' => 'image_select',
                        'options' => array(
                            '1' => get_template_directory_uri().'/assets/images/footer/f1.jpg',
                            '2' => get_template_directory_uri().'/assets/images/footer/f2.jpg',
                        )
                    ),
                    array(
                        'title' => esc_html__('Select Logo', 'wp-recruitment'),
                        'subtitle' => esc_html__('Select an image file for your logo footer', 'wp-recruitment'),
                        'id' => 'logo_footer',
                        'type' => 'media',
                        'url' => false,
                        'default' => array(
                            'url'=>get_template_directory_uri().'/assets/images/logo-footer.png'
                        ),
                        'required' => array( 0 => 'footer_layout', 1 => '=', 2 => '1' ),
                    ),
                    array(
                        'title' => esc_html__('Payment Methods', 'wp-recruitment'),
                        'type'  => 'section',
                        'id' => 'payment_start',
                        'indent' => true,
                    ),
                    array(
                        'title' => esc_html__('Image List', 'wp-recruitment'),
                        'type'  => 'slides',
                        'id' => 'payment_logo',
                    ),
                    array(
                        'title' => esc_html__('Footer Top', 'wp-recruitment'),
                        'type'  => 'section',
                        'id' => 'footer_top_start',
                        'indent' => true
                    ),
                    array(
                        'id'       => 'footer-top-column',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Column', 'wp-recruitment'),
                        'options' => array(
                            '2' => '2 Columns',
                            '3' => '3 Columns',
                            '4' => '4 Columns',
                            '5' => '5 Columns',
                        ),
                        'default' => '4'
                    ),
                    array(
                        'id'       => 'footer_top_bgcolor',
                        'type'     => 'background',
                        'background-image' => false,
                        'background-position' => false,
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'title'    => esc_html__( 'Bacgkround Color', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Set background color for Footer Top.', 'wp-recruitment' ),
                        'output'   => array('#cms-theme #cms-footer-top'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set title heading color', 'wp-recruitment'),
                        'id' => 'footer_heading_color',
                        'type' => 'color',
                        'title' => esc_html__('Heading Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-top .wg-title'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set text color', 'wp-recruitment'),
                        'id' => 'footer_text_color',
                        'type' => 'color',
                        'title' => esc_html__('Text Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-top'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color', 'wp-recruitment'),
                        'id' => 'footer_link_color',
                        'type' => 'color',
                        'title' => esc_html__('Link Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-top a, #cms-footer-top ul.menu li a'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color hover', 'wp-recruitment'),
                        'id' => 'footer_link_color_hover',
                        'type' => 'color',
                        'title' => esc_html__('Link Color Hover', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-top a:hover, #cms-footer-top ul.menu li a:hover'),
                    ),
                    array(
                        'title' => esc_html__('Footer Bottom', 'wp-recruitment'),
                        'type'  => 'section',
                        'id' => 'footer_bottom_start',
                        'indent' => true
                    ),
                    array(
                        'id'=>'cms-copyright',
                        'type' => 'textarea',
                        'title' => esc_html__('Copyright', 'wp-recruitment'),
                        'validate' => 'html_custom',
                        'default' => '',
                        'allowed_html' => array(
                            'a' => array(
                                'href' => array(),
                                'title' => array()
                            ),
                            'br' => array(),
                            'em' => array(),
                            'strong' => array(),
                            'span' => array(),
                        )
                    ),
                    array(
                        'id'       => 'footer_bottom_bgcolor',
                        'type'     => 'background',
                        'background-image' => false,
                        'background-position' => false,
                        'background-repeat' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'title'    => esc_html__( 'Bacgkround Color', 'wp-recruitment' ),
                        'subtitle' => esc_html__( 'Set background color for Footer Bottom.', 'wp-recruitment' ),
                        'output'   => array('#cms-theme #cms-footer-bottom'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set text color', 'wp-recruitment'),
                        'id' => 'footer_bottom_text_color',
                        'type' => 'color',
                        'title' => esc_html__('Text Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-bottom'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color', 'wp-recruitment'),
                        'id' => 'footer_bottom_link_color',
                        'type' => 'color',
                        'title' => esc_html__('Link Color', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-bottom a, #cms-footer-bottom #back_to_top .go_up'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color hover', 'wp-recruitment'),
                        'id' => 'footer_bottom_link_color_hover',
                        'type' => 'color',
                        'title' => esc_html__('Link Color Hover', 'wp-recruitment'),
                        'default' => '',
                        'output'  => array('body #cms-footer-bottom a:hover, #cms-footer-bottom #back_to_top .go_up:hover'),
                    ),
                    array(
                        'id' => 'footer_button_back_to_top',
                        'title' => esc_html__('Back To Top', 'wp-recruitment'),
                        'subtitle' => esc_html__('Enable button back to top.', 'wp-recruitment'),
                        'type' => 'switch',
                        'default' => true,
                    ),
                    array(
                        'id' => 'footer_custom_width_column',
                        'title' => esc_html__('Width Column Change', 'wp-recruitment'),
                        'subtitle' => esc_html__('Apply 4 column Footer Top', 'wp-recruitment'),
                        'type' => 'switch',
                        'default' => true,
                    ),
                )
            ),
            array(
                'title' => esc_html__('Styling', 'wp-recruitment'),
                'icon' => 'el-icon-adjust',
                'fields' => array(
                    array(
                        'subtitle' => esc_html__('Set primary color.', 'wp-recruitment'),
                        'id' => 'primary_color',
                        'type' => 'color',
                        'title' => esc_html__('Primary Color', 'wp-recruitment'),
                        'default' => '#4e007a'
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color.', 'wp-recruitment'),
                        'id' => 'link_color',
                        'type' => 'color',
                        'title' => esc_html__('Link Color', 'wp-recruitment'),
                        'default' => '#4e007a',
                        'output'  => array('a'),
                    ),
                    array(
                        'subtitle' => esc_html__('Set link color hover.', 'wp-recruitment'),
                        'id' => 'link_color_hover',
                        'type' => 'color',
                        'title' => esc_html__('Link Color Hover', 'wp-recruitment'),
                        'default' => '#000000',
                        'output'  => array('a:hover'),
                    ),
                )
            ),
            array(
                'title' => esc_html__('Typography', 'wp-recruitment'),
                'icon' => 'el-icon-text-width',
                'fields' => array(
                    array(
                        'id' => 'font_body',
                        'type' => 'typography',
                        'title' => esc_html__('Body Font', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'output'  => array('body#cms-theme'),
                        'units' => 'px',
                        'text-align' => false,
                        'subtitle' => esc_html__('Typography option with each property can be called individually.', 'wp-recruitment'),
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                    array(
                        'id' => 'font_menu',
                        'type' => 'typography',
                        'title' => esc_html__('Menu Font', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'color' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'font-size' => false,
                        'line-height' => false,
                        'text-align' => false,
                        'output'  => array('.cshero-header-navigation .main-navigation .menu-main-menu li a, #cshero-header-inner #cshero-header .cshero-navigation-right span'),
                        'units' => 'px',
                        'subtitle' => esc_html__('Set menu font.', 'wp-recruitment'),
                        'default' => array(
                            'font-family' => '',
                            'google' => true,
                        )
                    ),
                    array(
                        'id' => 'font_h1',
                        'type' => 'typography',
                        'title' => esc_html__('H1', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'text-align' => false,
                        'output'  => array('body h1'),
                        'units' => 'px',
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                    array(
                        'id' => 'font_h2',
                        'type' => 'typography',
                        'title' => esc_html__('H2', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'text-align' => false,
                        'output'  => array('body h2'),
                        'units' => 'px',
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                    array(
                        'id' => 'font_h3',
                        'type' => 'typography',
                        'title' => esc_html__('H3', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'text-align' => false,
                        'output'  => array('body h3'),
                        'units' => 'px',
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                    array(
                        'id' => 'font_h4',
                        'type' => 'typography',
                        'title' => esc_html__('H4', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'text-align' => false,
                        'output'  => array('body h4'),
                        'units' => 'px',
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                    array(
                        'id' => 'font_h5',
                        'type' => 'typography',
                        'title' => esc_html__('H5', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'text-align' => false,
                        'output'  => array('body h5'),
                        'units' => 'px',
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                    array(
                        'id' => 'font_h6',
                        'type' => 'typography',
                        'title' => esc_html__('H6', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => false,
                        'all_styles' => true,
                        'text-align' => false,
                        'output'  => array('body h6'),
                        'units' => 'px',
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                        )
                    ),
                )
            ),
            array(
                'title' => esc_html__('Extra Fonts', 'wp-recruitment'),
                'icon' => 'el el-fontsize',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'google-font-1',
                        'type' => 'typography',
                        'title' => esc_html__('Custom Font', 'wp-recruitment'),
                        'google' => true,
                        'font-backup' => true,
                        'all_styles' => true,
                        'output'  =>  $this->custom_font_1,
                        'units' => 'px',
                        'subtitle' => esc_html__('Typography option with each property can be called individually.', 'wp-recruitment'),
                        'default' => array(
                            'color' => '',
                            'font-style' => '',
                            'font-weight' => '',
                            'font-family' => '',
                            'google' => true,
                            'font-size' => '',
                            'line-height' => '',
                            'text-align' => ''
                        )
                    ),
                    array(
                        'id' => 'google-font-selector-1',
                        'type' => 'textarea',
                        'title' => esc_html__('Selector 1', 'wp-recruitment'),
                        'subtitle' => esc_html__('add html tags ID or class (body,a,.class,#id)', 'wp-recruitment'),
                        'validate' => 'no_html',
                        'default' => '',
                    )
                )
            ),
            array(
                'title' => esc_html__('Blog', 'wp-recruitment'),
                'icon' => 'el el-blogger',
                'subsection' => false,
                'fields' => array(
                    array(
                        'id'       => 'blog_sidebar',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Set sidebar for Blog', 'wp-recruitment'),
                        'options' => array(
                            'left-sidebar' => 'Sidebar Left',
                            'no-sidebar' => 'No Sidebar',
                            'right-sidebar' => 'Sidebar Right'
                        ),
                        'default' => 'right-sidebar'
                    )
                )
            ),
            array(
                'title' => esc_html__('Single Post', 'wp-recruitment'),
                'icon' => 'el el-file-edit',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id'       => 'single_sidebar',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Set sidebar for Single Post', 'wp-recruitment'),
                        'options' => array(
                            'left-sidebar' => 'Sidebar Left',
                            'no-sidebar' => 'No Sidebar',
                            'right-sidebar' => 'Sidebar Right'
                        ),
                        'default' => 'right-sidebar'
                    ),
                    array(
                        'id'       => 'post_comment',
                        'type'     => 'button_set',
                        'title' => esc_html__('Show/Hide Comment', 'wp-recruitment'),
                        'options' => array(
                            'show' => 'Show', 
                            'hide' => 'Hide',
                         ), 
                        'default' => 'show'
                    ),
                )
            ),
            array(
                'title' => esc_html__('JobBoard', 'wp-recruitment'),
                'icon' => 'el el-opensource',
                'subsection' => false,
                'fields' => array(
                    array(
                        'id'       => 'job_listing_style',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Job Listing Style', 'wp-recruitment'),
                        'options' => array(
                            'job-listing-classic' => 'Classic',
                            'job-listing-modern' => 'Modern',
                        ),
                        'default' => 'job-listing-classic'
                    ),
                    array(
                        'id' => 'job_layout',
                        'type' => 'button_set',
                        'title' => esc_html__('Job Listing Layout', 'wp-recruitment'),
                        'options' => array(
                            'list' => 'List',
                            'grid' => 'Grid',
                        ),
                        'default' => 'list'
                    ),
                    array(
                        'title' => esc_html__('Newsletter', 'wp-recruitment'),
                        'type'  => 'section',
                        'id' => 'newsletter',
                        'indent' => true,
                    ),
                    array(
                        'id' => 'show_newsletter_form',
                        'type' => 'switch',
                        'title' => esc_html__('Show Newsletter Form', 'wp-recruitment'),
                        'default' => false,
                    ),
                    array(
                        'id' => 'newsletter_title',
                        'type' => 'text',
                        'title' => esc_html__('Newsletter Title', 'wp-recruitment'),
                        'default' => 'Join Our Free Newsletter',
                        'required' => array( 0 => 'show_newsletter_form', 1 => '=', 2 => '1' ),
                    ),
                    array(
                        'id' => 'newsletter_desc',
                        'type' => 'textarea',
                        'title' => esc_html__('Newsletter Description', 'wp-recruitment'),
                        'default' => 'Duis imperdiet ut velit eu bibendum. Curabitur ut ante placerat diam mattis ultrices eget at odio. Etiam et tortor eget neque facilisis dignissim. Maecenas eleifend condimentum efficitur.',
                        'required' => array( 0 => 'show_newsletter_form', 1 => '=', 2 => '1' ),
                    ),
                )
            ),
            array(
                'title' => esc_html__('Single JobBoard', 'wp-recruitment'),
                'icon' => 'el el-briefcase',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'single_job_layout',
                        'type' => 'button_set',
                        'title' => esc_html__('Single Job Layout', 'wp-recruitment'),
                        'options' => array(
                            'single-job-classic' => 'Classic',
                            'single-job-modern' => 'Modern',
                        ),
                        'default' => 'single-job-classic'
                    ),
                    array(
                        'id'       => 'job_single_feature',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Page Title Single Job', 'wp-recruitment'),
                        'options' => array(
                            'default' => 'Default Background Page Title',
                            'job-feature' => 'Feature Image',
                        ),
                        'default' => 'default'
                    ),
                    array(
                        'id' => 'single_job_auth',
                        'type' => 'button_set',
                        'title' => esc_html__('Author', 'wp-recruitment'),
                        'options' => array(
                            'hidden' => 'Hidden',
                            'show' => 'Show',
                        ),
                        'default' => 'show'
                    ),
                    array(
                        'id' => 'single_job_map',
                        'type' => 'button_set',
                        'title' => esc_html__('Map', 'wp-recruitment'),
                        'options' => array(
                            'hidden' => 'Hidden',
                            'show' => 'Show',
                        ),
                        'default' => 'show'
                    ),
                    array(
                        'id' => 'single_job_tag',
                        'type' => 'button_set',
                        'title' => esc_html__('Tags', 'wp-recruitment'),
                        'options' => array(
                            'hidden' => 'Hidden',
                            'show' => 'Show',
                        ),
                        'default' => 'show'
                    ),
                )
            ),
            array(
                'title' => esc_html__('Event', 'wp-recruitment'),
                'icon' => 'el el-calendar',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id'       => 'event_style',
                        'type'     => 'button_set',
                        'title'    => esc_html__('Event Style', 'wp-recruitment'),
                        'options' => array(
                            'event-classic' => 'Classic',
                            'event-modern' => 'Modern',
                        ),
                        'default' => 'event-classic'
                    ),
                    array(
                        'title' => esc_html__('Event Banner', 'wp-recruitment'),
                        'id' => 'event_banner',
                        'type' => 'media',
                        'url' => false,
                        'default' => '',
                    ),
                    array(
                        'id' => 'event_title',
                        'type' => 'text',
                        'title' => esc_html__('Archive: Title', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'event_description',
                        'type' => 'textarea',
                        'title' => esc_html__('Archive: Decription', 'wp-recruitment'),
                        'default' => '',
                    ),
                )
            ),
            array(
                'title' => esc_html__('Resources', 'wp-recruitment'),
                'icon' => 'el el-file',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'resources_title',
                        'type' => 'text',
                        'title' => esc_html__('Archive: Title', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'resources_description',
                        'type' => 'textarea',
                        'title' => esc_html__('Archive: Decription', 'wp-recruitment'),
                        'default' => '',
                    ),
                )
            ),
            array(
                'title' => esc_html__('Optimal Core', 'wp-recruitment'),
                'icon' => 'el-icon-idea',
                'fields' => array(
                    array(
                        'subtitle' => esc_html__('no minimize , generate css over time...', 'wp-recruitment'),
                        'id' => 'dev_mode',
                        'type' => 'switch',
                        'title' => esc_html__('Dev Mode (not recommended)', 'wp-recruitment'),
                        'default' => false
                    )
                )
            ),
            array(
                'title' => esc_html__('Social Media', 'wp-recruitment'),
                'icon' => 'el el-twitter',
                'subsection' => false,
                'fields' => array(
                    array(
                        'id' => 'social_facebook_url',
                        'type' => 'text',
                        'title' => esc_html__('Facebook URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_twitter_url',
                        'type' => 'text',
                        'title' => esc_html__('Twitter URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_inkedin_url',
                        'type' => 'text',
                        'title' => esc_html__('Inkedin URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_rss_url',
                        'type' => 'text',
                        'title' => esc_html__('Rss URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_instagram_url',
                        'type' => 'text',
                        'title' => esc_html__('Instagram URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_google_url',
                        'type' => 'text',
                        'title' => esc_html__('Google URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_skype_url',
                        'type' => 'text',
                        'title' => esc_html__('Skype URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_pinterest_url',
                        'type' => 'text',
                        'title' => esc_html__('Pinterest URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_vimeo_url',
                        'type' => 'text',
                        'title' => esc_html__('Vimeo URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_youtube_url',
                        'type' => 'text',
                        'title' => esc_html__('Youtube URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_yelp_url',
                        'type' => 'text',
                        'title' => esc_html__('Yelp URL', 'wp-recruitment'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'social_tumblr_url',
                        'type' => 'text',
                        'title' => esc_html__('Tumblr URL', 'wp-recruitment'),
                        'default' => '',
                    ),

                )
            ),
            array(
                'title' => esc_html__('Custom CSS', 'wp-recruitment'),
                'icon' => 'el el-icon-bulb',
                'subsection' => false,
                'fields' => array(
                    array(
                        'id' => 'custom_css',
                        'type' => 'ace_editor',
                        'title' => esc_html__('CSS Code', 'wp-recruitment'),
                        'subtitle' => esc_html__('Paste your CSS code here.', 'wp-recruitment'),
                        'mode' => 'css',
                        'theme' => 'monokai',
                        'theme' => 'chrome',
                        'options' => array( 'minLines' => 20, 'maxLines' => 60 )
                    )

                )
            )
        );

        return $sections;
    }
}

new WPLThemeOptions();