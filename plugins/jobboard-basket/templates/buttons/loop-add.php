<?php
/**
 * The Template for displaying archive basket add button.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/buttons/loop-add.php.
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

<button class="button basket-add" data-id="<?php the_ID(); ?>">
    <i class="fa fa-shopping-cart cart"></i>
    <i class="fa fa-spinner jobboard-loading" style="display: none"></i>
    <span class="add"><?php esc_attr_e('Add', JB_BASKET_TEXT_DOMAIN); ?></span>
    <span class="added" style="display: none"><?php esc_attr_e('Added', JB_BASKET_TEXT_DOMAIN); ?></span>
</button>

