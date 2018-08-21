<?php
	$params = array(
		array(
			'type' => 'cms_template_img',
		    'param_name' => 'cms_template',
		    "shortcode" => "cms_counter_single",
		    "heading" => esc_html__("Shortcode Template",'wp-recruitment'),
		    "admin_label" => true,
		    "group" => esc_html__("Template", 'wp-recruitment'),
		),
		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Title Color",'wp-recruitment'),
			"param_name" => "title_color",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment')
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Font Size",'wp-recruitment'),
			"param_name" => "title_fontsize",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment'),
			"description" => esc_html__("Enter: ...px", 'wp-recruitment'),
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Margin",'wp-recruitment'),
			"param_name" => "title_margin",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment'),
			"description" => esc_html__("Enter: ...px", 'wp-recruitment'),
		),
		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Digit Color",'wp-recruitment'),
			"param_name" => "digit_color",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment')
		),	
		array(
			"type" => "textfield",
			"heading" => esc_html__("Digit Font Size",'wp-recruitment'),
			"param_name" => "digit_fontsize",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment'),
			"description" => esc_html__("Enter: ...px", 'wp-recruitment'),
		),
		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Icon Color",'wp-recruitment'),
			"param_name" => "icon_color",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment')
		),
		array(
            "type" => "attach_image",
            "heading" => esc_html__("Icon Image",'wp-recruitment'),
            "param_name" => "image_icon",
            "group" => esc_html__("Template", 'wp-recruitment'),
        ),
        array(
		    "type" => "dropdown",
		    "class" => "",
		    "heading" => esc_html__("Use Grouping", 'wp-recruitment'),
		    "param_name" => "use_grouping",
		    "value" => array(
		        'No' => '0',
		        'Yes' => '1',
		    ),   
		    "group" => esc_html__("Template", 'wp-recruitment'), 
		    'dependency' => array(
				'element' => 'cms_template',
				'value' => 'cms_counter_single--layout2.php',
			),
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Separator", 'wp-recruitment'),
			"param_name" => "separator",
			"value" => "",
			"group" => esc_html__("Template", 'wp-recruitment'),
			'dependency' => array(
				'element' => 'use_grouping',
				'value' => '1',
			),
		)
	);
	vc_remove_param( "cms_counter_single", "title" );
	vc_remove_param( "cms_counter_single", "description" );
	vc_remove_param( "cms_counter_single", "content_align" );
	vc_remove_param( "cms_counter_single", "prefix" );
?>