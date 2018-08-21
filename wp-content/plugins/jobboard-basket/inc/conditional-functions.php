<?php
/**
 * JobBoard Basket Conditional Functions
 *
 * @author      FOX
 * @category    Core
 * @package     JobBoard/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function in_jb_basket(){
    global $post;

    if (is_jb_candidate() && isset($post->app_status) && $post->app_status == 'basket'){
        return true;
    } elseif (!empty($_COOKIE['jobboard-basket']) && is_array($_COOKIE['jobboard-basket']) && in_array($post->ID, $_COOKIE['jobboard-basket'])) {
        return true;
    } else {
        return false;
    }
}