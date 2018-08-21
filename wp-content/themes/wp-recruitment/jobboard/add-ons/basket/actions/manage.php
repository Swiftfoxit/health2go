<?php
/**
 * The Template for displaying basket widget namager button .
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/actions/manage.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Basket/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<a href="<?php echo esc_url($basket_url); ?>" class="button basket-manager">
    <i class="fa fa-shopping-cart"></i><?php esc_html_e('Basket', 'wp-recruitment'); ?>
</a>
