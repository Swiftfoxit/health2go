<?php
/**
 * The Template for displaying newsletter form.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/alerts/newsletter-form.php.
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

<form class="jobboard-form newsletter-form" action="" method="post">
    <input type="email" name="email" class="email" value="" placeholder="<?php esc_html_e('Enter email address', JB_ALEART_TEXT_DOMAIN); ?>">
    <input type="submit" class="button" value="<?php esc_html_e('Submit', JB_ALEART_TEXT_DOMAIN); ?>">
    <?php wp_nonce_field( 'alerts_newsletter' ); ?>
    <input type="hidden" name="action" value="alerts_newsletter">
    <input type="hidden" name="form" value="jobboard-form">
</form>
