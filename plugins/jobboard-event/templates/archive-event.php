<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 9:58 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php get_header( 'jobboard' ); ?>
<?php if ( have_posts() ) : ?>

	<?php do_action( 'jobboard_event_loop_before' ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php JB_Event()->get_template( 'content-event.php' ); ?>

	<?php endwhile; ?>

	<?php do_action( 'jobboard_event_loop_after' ); ?>

<?php else: ?>

	<?php jb_get_template_part( 'loop/not-found' ); ?>

<?php endif; ?>
<?php get_footer( 'jobboard' ); ?>
