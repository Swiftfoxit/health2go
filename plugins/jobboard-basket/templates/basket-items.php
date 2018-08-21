<?php
/**
 * The Template for displaying basket widget items.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/basket-items.php.
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

<?php if($basket->have_posts()): ?>

    <?php while ($basket->have_posts()): $basket->the_post(); ?>

    <li>
        <div class="basket-items">
            <?php do_action('jobboard_basket_widget_loop'); ?>
        </div>
        <i class="basket-delete fa fa-times" data-id="<?php the_ID(); ?>"></i>
    </li>

    <?php endwhile; ?>

    <li class="basket-not-found" style="display: none;">

    <?php else: ?>

    <li class="basket-not-found">

    <?php endif; ?>

    <i class="fa fa-shopping-cart"></i>
    <p><?php esc_html_e('No Jobs!', JB_BASKET_TEXT_DOMAIN); ?></p>
</li>
