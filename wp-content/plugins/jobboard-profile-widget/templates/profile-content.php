<?php
/**
 * The Template for displaying profile widget content.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/profile/profile-content.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Profile/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="widget-content widget-<?php echo esc_attr($dropdown); ?>">
    <div class="jobboard-widget-content">

    <?php do_action('jobboard_profile_widget_content'); ?>

    </div>
</div>