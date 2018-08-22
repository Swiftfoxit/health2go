<?php
/**
 * The Template for displaying geo location.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/map/fields/fields/geolocation.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div id="<?php echo esc_attr($id); ?>" class="field-geolocation">
    <input class="geolocation-search" name="<?php echo esc_attr($name); ?>[s]" value="<?php echo esc_attr($value['s']); ?>" type="search" placeholder="<?php esc_attr_e('Enter Address', 'wpl-meta-framework'); ?>">
    <div class="geolocation-map"></div>
    <input type="hidden" name="<?php echo esc_attr($name); ?>[lat]" value="<?php echo esc_attr($value['lat']); ?>" class="geolocation-lat"/>
    <input type="hidden" name="<?php echo esc_attr($name); ?>[lng]" value="<?php echo esc_attr($value['lng']); ?>" class="geolocation-lng"/>
    <input type="hidden" name="<?php echo esc_attr($name); ?>[zoom]" value="<?php echo esc_attr($value['zoom']); ?>" class="geolocation-zoom"/>
</div>
