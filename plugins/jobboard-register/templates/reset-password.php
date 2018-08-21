<?php
/**
 * The Template for displaying reset password form.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/forgot-password.php.
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

<form class="jobboard-form reset-password-form" method="POST">
    <label for="new_pass"><?php esc_attr_e('New Password', JB_REGISTER_TEXT_DOMAIN); ?></label>
    <input id="new_pass" type="password" name="new_pass" value="">

    <label for="confirm_pass"><?php esc_attr_e('Confirm Password', JB_REGISTER_TEXT_DOMAIN); ?></label>
    <input id="confirm_pass" type="password" name="confirm_pass" value="">

    <input id="update_pass" type="submit" value="<?php esc_attr_e('Update Password', JB_REGISTER_TEXT_DOMAIN); ?>">

    <?php wp_nonce_field( 'reset_password' ); ?>

    <input type="hidden" name="key" value="<?php echo esc_attr($user->forgot_key); ?>">
    <input type="hidden" name="email" value="<?php echo esc_attr($user->user_email); ?>">
    <input type="hidden" name="action" value="reset_password">
    <input type="hidden" name="form" value="jobboard-form">
</form>
