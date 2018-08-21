<?php

function jb_search_form($atts){
    jb_search()->get_template('search-form.php',array('atts'=>$atts));
}

function jb_search_form_before($atts){
    $count = 1;

    if($atts['search_type'] == 'show'){
        $count++;
    }

     if($atts['search_specialism'] == 'show'){
        $count++;
    }

     if($atts['search_locations'] == 'show'){
        $count++;
    }

    jb_search()->get_template('search-before.php', array('count' => $count));
}

function jb_search_form_keywords(){
    jb_search()->get_template('keywords.php');
}

function jb_search_form_types($atts){
    if($atts['search_type'] == 'hidden'){
        return;
    }
    $value = !empty($_GET['type']) ? $_GET['type'] : '';
    $types = jb_get_taxonomy_options(array('taxonomy' => 'jobboard-tax-types', 'hide_empty' => true));

    jb_search()->get_template('types.php', array('atts'=>$atts,'value' => $value, 'types' => $types));
}

function jb_search_form_specialisms($atts){

    if($atts['search_specialism'] == 'hidden'){
        return;
    }

    $value       = !empty($_GET['specialism']) ? $_GET['specialism'] : '';
    $specialisms = jb_get_taxonomy_options(array('taxonomy' => 'jobboard-tax-specialisms', 'hide_empty' => true));

    jb_search()->get_template('specialisms.php', array('value' => $value, 'specialisms' => $specialisms));
}

function jb_search_form_locations($atts){

    if($atts['search_locations'] == 'hidden'){
        return;
    }

    $value      = !empty($_GET['location']) ? $_GET['location'] : '';
    $locations  = jb_get_taxonomy_options(array('taxonomy' => 'jobboard-tax-locations', 'parent' => 0, 'hide_empty' => true));

    jb_search()->get_template('locations.php', array('value' => $value, 'locations' => $locations));
}

function jb_search_form_actions(){
    jb_search()->get_template('search-actions.php');
}

function jb_search_form_after(){
    jb_search()->get_template('search-after.php');
}