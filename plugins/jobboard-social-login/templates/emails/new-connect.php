<?php
/**
 * The Template for displaying email template.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/social-login/emails/new-connect.php
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

<h3><?php esc_html_e('Welcome New Candidate!', JB_SOCIAL_LOGIN_TEXT_DOMAIN); ?></h3>
<p><?php esc_html_e('You are connected with us by social networks, this is your user name and password.', JB_SOCIAL_LOGIN_TEXT_DOMAIN); ?></p>
<table style="text-align: left;font-style: italic;">
    <tbody>
    <tr>
        <th><?php esc_html_e('Name', JB_SOCIAL_LOGIN_TEXT_DOMAIN); ?></th>
        <td><?php echo esc_html($user->display_name); ?></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Email', JB_SOCIAL_LOGIN_TEXT_DOMAIN); ?></th>
        <td><?php echo esc_html($user->user_email); ?></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Password', JB_SOCIAL_LOGIN_TEXT_DOMAIN); ?></th>
        <td><?php echo esc_html($user->user_pass); ?></td>
    </tr>
    </tbody>
</table>