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
<article id="loop-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'jobboard_event_loop_item_summary_before' ); ?>

	<?php do_action( 'jobboard_event_loop_item_summary' ); ?>

	<?php do_action( 'jobboard_event_loop_item_summary_after' ); ?>

</article>