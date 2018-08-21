<?php
/**
 * The Template for displaying alerts sections.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/alerts/alerts-sections.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Alerts/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="alerts-sections">
    <ul>
        <li class="sections<?php echo esc_attr($active); ?>">
            <div class="alerts-header">
                <span><?php esc_html_e('Add Job Alert', JB_ALEART_TEXT_DOMAIN); ?></span>
                <i class="fa fa-minus-circle minus"></i>
                <i class="fa fa-plus-circle plus"></i>
            </div>
            <div class="alerts-content">
                <?php do_action('jobboard_form_alerts_fields', $fields); ?>
                <div class="alerts-remove">
                    <i class="fa fa-trash-o" title="<?php esc_html__('Remove', JB_ALEART_TEXT_DOMAIN); ?>"></i>
                </div>
            </div>
        </li>
    </ul>
</div>
