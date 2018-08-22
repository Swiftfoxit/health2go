<?php
/**
 * The Template for google map marker info window.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/map/info-window.php.
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

<div class="map-marker-info">
    <?php do_action('jobboard_map_marker_info'); ?>
</div>
