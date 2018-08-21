<?php
/**
 * Add custom headding params
 * 
 * @author CMSSuperHeroes Team
 * @since Recruitment 1.0.9
 */

    vc_add_param("vc_custom_heading", array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Custom Font Weight", 'wp-recruitment'),
        "admin_label" => true,
        "param_name" => "custom_font_weight",
        "value" => array(
            "Default" => "fw-400",
            "Light" => "fw-300",
            "Medium" => "fw-500",
            "SemiBold" => "fw-600",
            "Bold" => "fw-700",
        ),
        'group' => 'CMS Customs'
    ));
    vc_add_param("vc_custom_heading", array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Custom Font Style", 'wp-recruitment'),
        "admin_label" => true,
        "param_name" => "custom_font_style",
        "value" => array(
            "Default" => "normal",
            "Italic" => "fs-i",
        ),
        'group' => 'CMS Customs'
    ));
    vc_add_param("vc_custom_heading", array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Heading Letter Spacing", 'wp-recruitment'),
        "admin_label" => true,
        "param_name" => "letter_spacing",
        "value" => array(
            '0' => '0em',
            '0.01em' => '0.01em',
            '0.015em' => '0.015em',
            '0.02em' => '0.02em',
            '0.025em' => '0.025em',
            '0.03em' => '0.03em',
            '0.035em' => '0.035em',
            '0.04em' => '0.04em',
            '0.045em' => '0.045em',
            '0.05em' => '0.05em',
            '0.055em' => '0.055em',
            '0.06em' => '0.06em',
            '0.065em' => '0.065em',
            '0.07em' => '0.07em',
            '0.075em' => '0.075em',
            '0.08em' => '0.08em',
            '0.085em' => '0.085em',
            '0.09em' => '0.09em',
            '0.095em' => '0.095em',
            '0.1em' => '0.1em',
            '0.15em' => '0.15em',
            '0.2em' => '0.2em',
            '0.3em' => '0.3em',
            '0.4em' => '0.4em',
            '0.5em' => '0.5em',
            '0.6em' => '0.6em',
            '0.7em' => '0.7em',
            '0.8em' => '0.8em',
            '0.9em' => '0.9em',
        ),
        'group' => 'CMS Customs'
    ));