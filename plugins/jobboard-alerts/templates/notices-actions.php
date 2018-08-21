<?php
/**
 * The Template for displaying notices form actions.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/alerts/endpoint-notices-actions.php.
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

<div class="form-actions notices-actions">

    <?php do_action('jobboard_form_notices_actions'); ?>

    <?php wp_nonce_field( 'alerts_notices' ); ?>

    <input type="submit" class="button" name="alerts_notices" value="<?php esc_attr_e('Apply Changes', JB_ALEART_TEXT_DOMAIN); ?>">
    <input type="hidden" name="action" value="alerts_notices">
    <input type="hidden" name="form" value="jobboard-form">
</div>
