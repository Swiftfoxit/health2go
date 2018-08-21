<?php

function ef3_clear_tmp(){

    $upload_dir = wp_upload_dir();

    ef3_delete_directory($upload_dir['basedir'] . '/attachment-tmp');
    ef3_delete_directory($upload_dir['basedir'] . '/ef3_demo');
}

function ef3_delete_directory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir) || is_link($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!ef3_delete_directory($dir . "/" . $item, false)) {
            chmod($dir . "/" . $item, 0777);
            if (!ef3_delete_directory($dir . "/" . $item, false)) return false;
        };
    }
    return rmdir($dir);
}