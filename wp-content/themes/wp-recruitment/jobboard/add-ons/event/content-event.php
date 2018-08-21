<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 11:22 AM
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$post_terms = wp_get_post_terms(get_the_ID(), 'jobboard-event-type', array('fields' => 'ids'));
$term_ids = !empty($post_terms) ? implode(',', $post_terms) : '';
?>
<article id="loop-<?php the_ID(); ?>" <?php post_class(); ?> data-jbfilter="<?php echo esc_attr($term_ids) ?>">

    <?php do_action('jobboard_event_loop_item_summary_before'); ?>

    <?php do_action('jobboard_event_loop_item_summary'); ?>

    <?php do_action('jobboard_event_loop_item_summary_after'); ?>

</article>