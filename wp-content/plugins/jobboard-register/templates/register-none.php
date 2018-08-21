<?php
/**
 * The Template for displaying register none.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/register-none.php.
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

<div class="jobboard-inline-notices notice-error">
    <h3><?php esc_html_e('Notice !', JB_REGISTER_TEXT_DOMAIN); ?></h3>
    <p><?php esc_html_e('Sorry register new account not available in the at this time.', JB_REGISTER_TEXT_DOMAIN); ?></p>
</div>
