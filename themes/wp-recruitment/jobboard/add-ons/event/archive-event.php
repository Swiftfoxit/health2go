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
global $opt_theme_options;
?>
<?php get_header( 'jobboard' ); ?>
<?php if ( have_posts() ) : ?>

	<?php do_action( 'jobboard_event_loop_before' ); ?>
	
	<?php if(!empty($opt_theme_options['event_title'])) : ?>
		<div class="jobboard-event-banner">
			<div class="jobboard-event-heading">
				<h3><?php echo esc_attr( $opt_theme_options['event_title'] ); ?></h3>
				<p><?php echo esc_html( $opt_theme_options['event_description'] ); ?></p>
			</div>
		</div>
	<?php endif; ?>

	<?php JB_Event()->get_template('filter-events.php') ?>

	<div class="jobboard-event-primary">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php JB_Event()->get_template( 'content-event.php' ); ?>

		<?php endwhile; ?>
	</div>


	<?php do_action( 'jobboard_event_loop_after' ); ?>

<?php else: ?>

	<?php jb_get_template_part( 'loop/not-found' ); ?>

<?php endif; ?>
<?php get_footer( 'jobboard' ); ?>
