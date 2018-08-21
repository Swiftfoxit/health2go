<?php
/**
 * The Template for displaying basket widget content.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/basket-content.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Basket/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="widget-content widget-<?php echo esc_attr($dropdown); ?>">

    <?php if($header_text): ?>

    <div class="jobboard-widget-header basket-widget-header">
        <h4><?php echo esc_html($header_text); ?></h4>
    </div>

    <?php endif; ?>

    <?php do_action('jobboard_basket_widget_content_before'); ?>

    <ul class="jobboard-widget-content basket-widget-content">

        <?php do_action('jobboard_basket_widget_content', $basket); ?>

    </ul>

    <?php do_action('jobboard_basket_widget_content_after'); ?>

    <div class="jobboard-widget-footer basket-widget-footer">

    <?php do_action('jobboard_basket_widget_footer'); ?>

    </div>

</div>

