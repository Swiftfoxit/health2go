<?php
/**
 * The Template for displaying email template.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/alerts/emails/alerts.php.
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

<h3><?php esc_html_e('Job Alert', JB_ALEART_TEXT_DOMAIN); ?></h3>
<p><?php esc_html_e('A job of interest is posted', JB_ALEART_TEXT_DOMAIN); ?></p>
<table style="text-align: left;font-style: italic;">
    <tbody>
    <tr>
        <th><?php esc_html_e('Title', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td><a href="<?php echo get_permalink($post->ID); ?>"><?php echo esc_html($post->post_title); ?></a></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Salary', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Type', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Specialism', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Location', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td></td>
    </tr>
    <tr>
        <th><?php esc_html_e('By', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td></td>
    </tr>
    <tr>
        <th><?php esc_html_e('Description', JB_ALEART_TEXT_DOMAIN); ?></th>
        <td></td>
    </tr>
    </tbody>
</table>