<?php
	$params = array(
		array(
            "type" => "attach_image",
            "heading" => esc_html__("Icon Image",'wp-recruitment'),
            "param_name" => "image_icon",
            "group" => esc_html__("Progress Bar Settings", 'wp-recruitment'),
        ),
	);

	vc_remove_param('cms_progressbar','cms_template');
	$cms_template_attribute = array(
		'type' => 'cms_template_img',
	    'param_name' => 'cms_template',
	    "shortcode" => "cms_progressbar",
	    "heading" => esc_html__("Shortcode Template","wp-recruitment"),
	    "admin_label" => true,
	    "group" => esc_html__("Template", "wp-recruitment"),
		);
	vc_add_param('cms_progressbar',$cms_template_attribute);
	vc_remove_param( "cms_progressbar", "width" );
	vc_remove_param( "cms_progressbar", "height" );
	vc_remove_param( "cms_progressbar", "border_radius" );
	vc_remove_param( "cms_progressbar", "striped" );
	vc_remove_param( "cms_progressbar", "bg_color" );
	vc_remove_param( "cms_progressbar", "mode" );
?>