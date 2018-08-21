<?php
/**
 * Newsletter form for VC
 * Require Newsletter plugin to be installed
 * 
 * @package Autosmart
 * @since   Autosmart 1.0
 */

$forms = array_filter( (array) get_option( 'newsletter_forms', array() ) );

$forms_list = array(
    esc_html__( 'Default Form', 'wp-recruitment' ) => 'default'
);

if ( $forms )
{
    $index = 1;
    foreach ( $forms as $key => $form )
    {
        $forms_list[ sprintf( esc_html__( 'Form %s', 'wp-recruitment' ), $index ) ] = $key;
        $index ++;
    }
}

vc_map(array(
    "name" => 'CMS Newsletter',
    "base" => "cms_newsletter",
    "icon" => "cs_icon_for_vc",
    'description' => esc_html__( 'Newsletter Form', 'wp-recruitment' ),
    "category" => esc_html__('CmsSuperheroes Shortcodes', 'wp-recruitment'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => esc_html__( 'Title', "wp-recruitment" ),
            "param_name" => "title",
            "value" => '',
        ),
        array(
            "type" => "textarea",
            "heading" => esc_html__( 'Description', "wp-recruitment" ),
            "param_name" => "description",
            "value" => '',
        ), 
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Newsletter Form', 'wp-recruitment' ),
            'value'       => $forms_list,
            'admin_label' => true,
            'param_name'  => 'form'
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => esc_html__("Layout", "wp-recruitment"),
            "admin_label" => true,
            "param_name" => "layout",
            "value" => array(
                "Layout 1" => "layout1",
                "Layout 2" => "layout2",
            ),
        ),
    )
));

class WPBakeryShortCode_cms_newsletter extends CmsShortCode
{

    protected function content($atts, $content = null)
    {
        return parent::content($atts, $content);
    }
}

?>