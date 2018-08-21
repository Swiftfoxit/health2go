<?php
	$params = array(
        
	);
	vc_remove_param('cms_grid','cms_template');
	$cms_template_attribute = array(
		'type' => 'cms_template_img',
	    'param_name' => 'cms_template',
	    "shortcode" => "cms_grid",
	    "heading" => esc_html__("Shortcode Template","wp-recruitment"),
	    "admin_label" => true,
	    "group" => esc_html__("Template", "wp-recruitment"),
		);
	vc_add_param('cms_grid',$cms_template_attribute);
?>