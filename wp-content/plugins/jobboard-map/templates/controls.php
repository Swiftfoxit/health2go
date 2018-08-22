<?php
/**
 * The Template for google map tabs controls.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/map/controls.php.
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

<div class="map-tabs-control">
    <?php foreach ($tabs as $id => $tab): ?>
        <button class="tabs-<?php echo esc_attr($id); ?><?php echo $active == $id ? ' active' : ''; ?>">
            <?php echo esc_html($tab); ?>
        </button>
    <?php endforeach; ?>
</div>
