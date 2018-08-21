<?php
/**
 * Description
 *
 * This template can be overridden by copying it to yourtheme/jobboard/single/description.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="col-xs-12">
    <div class="job-content">
        <div class="entry-content">

            <h2 class="job-heading">
                <?php echo sprintf(esc_html__('Job %1$sDescription%2$s', 'wp-recruitment'), '<span>', '</span>'); ?>
            </h2>

            <?php the_content(); ?>

        </div>
    </div>
</div>
