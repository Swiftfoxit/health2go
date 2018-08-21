<?php
	$params = array(
		array(
			'type' => 'cms_template_img',
		    'param_name' => 'cms_template',
		    "shortcode" => "cms_fancybox_single",
		    "heading" => esc_html__("Shortcode Template",'wp-recruitment'),
		    "admin_label" => true,
		    "group" => esc_html__("Template", 'wp-recruitment'),
		),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Icon - Background Color",'wp-recruitment'),
            "param_name" => "icon_bg_color",
            "value" => "",
            "group" => esc_html__("Template", 'wp-recruitment'),
            "template" => array(
                "cms_fancybox_single--layout2.php",
            ),
        ),
        array(
            "type" => "attach_image",
            "heading" => esc_html__("Background Image",'wp-recruitment'),
            "param_name" => "bg_image",
            "group" => esc_html__("Template", 'wp-recruitment'),
            "template" => array(
                "cms_fancybox_single--layout3.php",
            ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Style", 'wp-recruitment'),
            "param_name" => "style",
            "value" => array(
                "Style 1" => "style1",
                "Style 2" => "style2",
            ),
            'group' => 'Template',
            "template" => array(
                "cms_fancybox_single--layout3.php",
            ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Custom Icon -  Add Class Icon","wp-recruitment"),
            "param_name" => "icon_custom",
            "value" => "",
            "group" => esc_html__("Template", "wp-recruitment"),
            "description" => 'Please add class icon. Use the library <a href="http://zavoloklom.github.io/material-design-iconic-font/icons.html" target="_blank">Material Design Iconic</a>, <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_blank">FontAwesome</a>',
            "template" => array(
                "cms_fancybox_single--layout4.php",
                "cms_fancybox_single--layout5.php",
            ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Style", 'wp-recruitment'),
            "param_name" => "icon_style",
            "value" => array(
                "Style 1" => "icon-style1",
                "Style 2" => "icon-style2",
            ),
            'group' => 'Template',
            "template" => array(
                "cms_fancybox_single--layout4.php",
            ),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Content Align", 'wp-recruitment'),
            "param_name" => "box_align",
            "value" => array(
                "Left" => "content-left",
                "Right" => "content-right",
            ),
            'group' => 'Template',
            "template" => array(
                "cms_fancybox_single--layout4.php",
            ),
        ),
	);
    vc_remove_param( "cms_fancybox_single", "title" );
    vc_remove_param( "cms_fancybox_single", "description" );
    vc_remove_param( "cms_fancybox_single", "content_align" );
?>