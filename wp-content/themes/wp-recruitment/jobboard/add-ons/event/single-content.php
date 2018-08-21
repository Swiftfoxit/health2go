<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 11:22 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="jobboard-event-wrap">
	<div class="container">
		<article id="loop-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="jobboard-event-primary">
				<?php do_action( 'jobboard_event_single_summary_before' ); ?>

				<?php do_action( 'jobboard_event_single_summary' ); ?>

				<?php do_action( 'jobboard_event_single_summary_after' ); ?>
			</div>
		</article>
	</div>
</div>