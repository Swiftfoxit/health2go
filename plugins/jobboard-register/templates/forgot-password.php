<?php
/**
 * The Template for displaying forgot password form.
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

<form class="jobboard-form forgot-password-form" method="POST">
    <label for="user_email"><?php esc_html_e('Forgot your password?', JB_REGISTER_TEXT_DOMAIN); ?></label>
    <input id="user_email" type="email" value="" name="email" placeholder="<?php esc_attr_e('Email Address', JB_REGISTER_TEXT_DOMAIN) ?>">
    <input type="submit" value="<?php esc_attr_e('Send Me', JB_REGISTER_TEXT_DOMAIN); ?>">
    <?php wp_nonce_field( 'forgot_password' ); ?>
    <input type="hidden" name="action" value="forgot_password">
    <input type="hidden" name="form" value="jobboard-form">
</form>
