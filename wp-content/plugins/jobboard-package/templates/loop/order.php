<?php
/**
 * The Template display order.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/loop/order.php.
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

<strong class="loop-order"><?php echo sprintf(esc_html__('#%s', JB_PACKAGE_TEXT_DOMAIN), $order); ?></strong>
