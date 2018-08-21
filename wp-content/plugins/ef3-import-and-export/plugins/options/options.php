<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 4/4/2016
 * Time: 4:21 PM
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

function ef3_options_import($options){
    foreach ($options as $key => $option){
        switch ($key){
            case 'home':
                ef3_options_set_home_page($option);
                break;
            case 'menus':
                ef3_options_set_menus($option);
                break;
            case 'wp-options':
                ef3_options_set_wp_options($option);
                break;
        }
    }
}

function ef3_options_export($file){
    global $wp_filesystem;

    $upload_dir = wp_upload_dir();

    $options = array();

    /* default. */
    $options['home'] = ef3_options_get_home_page();
    $options['menus'] = ef3_options_get_menus();
    $options['opt-name'] = ef3_setting_get_opt_name();
    $options['export'] = !empty($_POST['types']) ? $_POST['types'] : array() ;

    /* wp options */
    $options['wp-options'] = ef3_options_get_wp_options(apply_filters('ef3-options-wp-options', array()));

    /* attachment */
    if(file_exists($upload_dir['basedir'] . '/attachment-tmp.zip'))
        $options['attachment'] = $upload_dir['baseurl'] . '/attachment-tmp.zip';

    $wp_filesystem->put_contents($file, json_encode($options), FS_CHMOD_FILE);
}

function ef3_options_get_home_page(){

    $home_id = get_option('page_on_front');

    if(!$home_id)
        return null;

    $page = new WP_Query(array('post_type' => 'page', 'posts_per_page' => 1, 'page_id' => $home_id));

    if(!$page->post)
        return null;

    return $page->post->post_name;
}

function ef3_options_get_menus(){

    $theme_locations = get_nav_menu_locations();

    if(empty($theme_locations))
        return null;

    foreach ($theme_locations as $key => $id){
        $menu_object = wp_get_nav_menu_object( $id );
        $theme_locations[$key] = $menu_object->slug;
    }

    return $theme_locations;
}

function ef3_options_get_wp_options($options = array()){
    if(empty($options))
        return $options;

    $_options = array();

    foreach ($options as $key){
        $_options[$key] = get_option($key);
    }

    return $_options;
}

function ef3_options_set_home_page($slug){

    $page = new WP_Query(array('post_type' => 'page', 'posts_per_page' => 1, 'name' => $slug));

    if(!$page->post)
        return null;

    update_option('show_on_front', 'page');
    update_option('page_on_front', $page->post->ID);
}

function ef3_options_set_menus($menus){

    if(empty($menus))
        return;

    $new_setting = array();

    foreach ($menus as $key => $menu){
        
        $_menu = get_term_by('slug', $menu, 'nav_menu');

        $new_setting[$key] = $_menu->term_id;
    }

    set_theme_mod('nav_menu_locations', $new_setting);
}

function ef3_options_set_wp_options($options = array()){
    if(empty($options))
        return;

    foreach ($options as $key => $value){
        update_option($key, $value);
    }
}

function ef3_options_export_file($options, $file, $part){
    global $wp_filesystem;

    $file_contents = get_option($options);

    if(!$file_contents)
        return;

    $file_contents = json_encode($file_contents);

    $wp_filesystem->put_contents($part . $file, $file_contents);
}

function ef3_options_import_file($options, $file, $part){
    // File exists?
    if (file_exists($part . $file)){
        // Get file contents and decode
        $data = file_get_contents($part . $file);
        $data = json_decode($data, true);
        update_option($options, $data);
    }
}