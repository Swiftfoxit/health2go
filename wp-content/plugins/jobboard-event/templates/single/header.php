<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 2:45 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<header class="entry-header">
    <div class="event-type"><?php echo je_single_event_get_type()->name; ?></div>
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <div class="entry-meta">
		<?php do_action( 'jobboard_event_single_header_meta' ); ?>
    </div>
</header>
