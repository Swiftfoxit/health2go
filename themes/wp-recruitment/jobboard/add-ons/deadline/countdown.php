<?php
/**
 * The Template for displaying countdown.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/deadline/countdown.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author     FOX
 * @package    JobBoard/Deadline/Templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<div class="loop-deadline">
    <i class="fa fa-lock"></i>
    <span class="deadline-clock" data-time="<?php echo esc_attr($time); ?>" data-expired="<?php esc_html_e('Expired', 'wp-recruitment'); ?>" title="<?php esc_html_e('Deadline', 'wp-recruitment') ?>"></span>
</div>
