<?php
add_filter('ef3-theme-options-opt-name', 'recruitment_set_demo_opt_name');

function recruitment_set_demo_opt_name(){
    return 'opt_theme_options';
}

add_filter('ef3-replace-content', 'recruitment_replace_content', 10 , 2);

function recruitment_replace_content($replaces, $attachment){
    return array(
        '/tax_query:/' => 'remove_query:',
        '/categories:/' => 'remove_query:',
    );
}

add_filter('ef3-replace-theme-options', 'recruitment_replace_theme_options');

function recruitment_replace_theme_options(){
    return array(
        'dev_mode' => 0,
    );
}

add_filter('ef3-enable-create-demo', 'recruitment_enable_create_demo');

function recruitment_enable_create_demo(){
    return false;
}

add_action('ef3-export-finish', 'recruitment_export_finish');

function recruitment_export_finish($part){
    ef3_options_export_file('jobboard_options', 'jobboard.json', $part);
}

add_action('ef3-import-finish', 'recruitment_import_finish', 10, 2);

function recruitment_import_finish($id, $part){
    ef3_options_import_file('jobboard_options', 'jobboard.json', $part);
    recruitment_update_geo_locations();
    recruitment_update_jobboard_options();
}

add_action('ef3-import-start', 'recruitment_delete_post');

function recruitment_delete_post(){
    $_pages = array(
        'candidates',
        'employers',
        'dashboard',
        'jobs',
        'register',
        'forgot-password',
        'sample-page'
    );

    foreach ($_pages as $v){
        $_p = get_page_by_path($v);

        if(isset($_p->ID)) {
            wp_delete_post($_p->ID);
        }
    }

    if($post_id = get_page_by_path('hello-world', OBJECT, 'post')){
        wp_delete_post($post_id);
    }
}

function recruitment_update_geo_locations(){
    $jobs = new WP_Query(array(
        'post_type' => 'jobboard-post-jobs',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => '_map',
                'value'   => '',
                'compare' => '!=',
            )
        )
    ));

    if(!empty($jobs->posts)){

        global $wpdb;

        foreach ($jobs as $job){
            $map = get_post_meta($job->ID, '_map', true);

            if(empty($map['lat']) || empty($map['lng'])){
                continue;
            }

            $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}jobboard_geolocation WHERE post_id = %d", $job->ID));
            $wpdb->replace($wpdb->prefix . 'jobboard_geolocation', array(
                'id'      => $id,
                'post_id' => $job->ID,
                'lat' => $map['lat'],
                'lng' => $map['lng'],
            ), array(
                '%d',
                '%d',
                '%f',
                '%f'
            ));
        }
    }
}

function recruitment_update_jobboard_options(){
    $_pages = array(
        'candidates',
        'employers',
        'dashboard',
        'jobs',
        'register',
        'forgot-password'
    );

    foreach ($_pages as $v){
        $_p = get_page_by_path($v);

        if(class_exists('Redux') && isset($_p->ID)) {
            Redux::setOption('jobboard_options', 'page-' . $v, $_p->ID);
        }
    }
}