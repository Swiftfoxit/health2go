<?php
/**
 * The Template display package pricing table.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/pricing.php.
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

<div class="package-pricing">

    <?php if(have_posts()): ?>

    <ul class="table-items-<?php echo jb_package_get_table_count(); ?> clearfix">

        <?php while (have_posts() ) : the_post(); ?>

            <li class="<?php jb_package_the_pricing_table_class(); ?>">

            <?php do_action('jobboard_package_pricing_table'); ?>

            </li>

        <?php endwhile; // end of the loop. ?>

    </ul>

    <?php else: ?>

    <?php endif; ?>

</div>
