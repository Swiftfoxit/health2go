<?php
/**
 * The Template for displaying basket button on archive page.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/buttons/single-add.php.
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

<button class="btn btn-default btn-xlg basket-add" data-id="<?php the_ID(); ?>">
    <i class="fa fa-shopping-cart cart"></i>
    <i class="fa fa-spinner jobboard-loading" style="display: none"></i>
    <span class="add"><?php esc_attr_e('Add To Job Basket', 'wp-recruitment'); ?></span>
    <span class="added" style="display: none"><?php esc_attr_e('Ready In Job Basket', 'wp-recruitment'); ?></span>
</button>
