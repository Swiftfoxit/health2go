<?php

function ef3_export_start($part){
    $css = get_template_directory() . '/assets/css/static.css';

    if(file_exists($css)){
        copy($css, $part . 'static.css');
    }
}

function ef3_import_start($part){
    $css = get_template_directory() . '/assets/css/static.css';

    if(file_exists($part . 'static.css')){
        copy($part . 'static.css', $css);
    }
}