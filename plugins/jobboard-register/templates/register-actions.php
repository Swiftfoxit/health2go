<?php
/**
 * The Template for displaying register form actions.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/register-actions.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Register/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="form-actions register-actions">
    <?php wp_nonce_field( 'register_account' ); ?>
    <input type="submit" class="button" value="<?php esc_html_e('Register Now', JB_REGISTER_TEXT_DOMAIN); ?>">
    <input type="hidden" name="action" value="register_account">
    <input type="hidden" name="form" value="jobboard-form">
</div>
