<?php
/**
 * The Template for displaying user registered.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/registered.php.
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

<div class="jobboard-inline-notices notice-success">
    <h3><?php echo sprintf(esc_html__('Hi %s%s%s', JB_REGISTER_TEXT_DOMAIN), '<cite>', $email, '</cite>'); ?></h3>
    <p><?php esc_html_e('Your account has been created, you can check your email and click the following link to activate your account.', JB_REGISTER_TEXT_DOMAIN); ?></p>
</div>
