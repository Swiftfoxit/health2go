<?php
/**
 * The Template for google map search form control.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/map/search-form.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Map/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="map-search-control<?php echo esc_attr($active); ?>">
    <div class="map-search-form">
        <div class="search-form">
            <input class="search-geolocation" type="text" value="" placeholder="<?php esc_html_e('Geo Location', 'wp-recruitment'); ?>">
            <input class="search-keyword" type="search" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_html_e('Keyword', 'wp-recruitment'); ?>">
            <button class="search-button btn btn-default">
                <i class="fa fa-search"></i>
                <i class="fa fa-spinner jobboard-loading" style="display: none"></i>
                <span><?php esc_html_e('Search', 'wp-recruitment'); ?></span>
            </button>
        </div>
    </div>
</div>
