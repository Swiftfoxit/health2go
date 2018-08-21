<?php
/**
 * The Template for displaying email template.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/register/emails/employer-active.php
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

<h3><?php esc_html_e('Welcome New Employer!', JB_REGISTER_TEXT_DOMAIN); ?></h3>
<p><?php esc_html_e('Your account has been created, you can click the following link to activate your account.', JB_REGISTER_TEXT_DOMAIN); ?></p>
<p><a href="<?php echo esc_url($employer->active_url); ?>"><?php echo esc_url($employer->active_url); ?></a></p>
<table style="text-align: left;font-style: italic;">
    <tbody>
    <tr>
        <th><?php esc_html_e('Name', JB_REGISTER_TEXT_DOMAIN); ?></th>
        <td><?php echo sprintf('%s %s', $employer->first_name, $employer->last_name); ?></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Email', JB_REGISTER_TEXT_DOMAIN); ?></th>
        <td><?php echo esc_html($employer->user_email); ?></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Account', JB_REGISTER_TEXT_DOMAIN); ?></th>
        <td><?php esc_html_e('Employer', JB_REGISTER_TEXT_DOMAIN); ?></td>
    </tr>
    </tbody>
</table>