<?php
/**
 * The Template for displaying archive basket applied button.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/buttons/loop-applied.php.
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

<a href="<?php echo esc_url($app_url); ?>" class="button basket-applied">
    <i class="fa fa-check-square-o"></i>
    <span class="added"><?php esc_attr_e('Applied', JB_BASKET_TEXT_DOMAIN); ?></span>
</a>

