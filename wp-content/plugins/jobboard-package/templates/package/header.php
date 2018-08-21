<?php
/**
 * The Template display package header.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/package/header.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Package/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="package-heading dashboard-heading">
    <h3><?php esc_html_e('Membership', JB_PACKAGE_TEXT_DOMAIN); ?></h3>
    <span><?php esc_html_e('Select the package that’s right for you and choose a payment method below.', JB_PACKAGE_TEXT_DOMAIN); ?></span>
    <p><?php echo sprintf(esc_html__('or billing information %sContact Us%s', JB_PACKAGE_TEXT_DOMAIN), '<a href="'.esc_url($contact).'">', '</a>'); ?></p>
</div>
