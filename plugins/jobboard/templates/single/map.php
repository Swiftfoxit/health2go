<?php
/**
 * @Template: map.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 18-Dec-17
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>
<div class="col-xs-12">
	<div class="job-single-map-wrap">
		<?php echo do_shortcode('[jobboard-shortcode-map-2][/jobboard-shortcode-map-2]'); ?>
		<div class="job-single-map-holder clearfix">
		    <div class="job-single-address"><?php echo get_post_meta(get_the_ID(), '_address', true) ?></div>
		    <a href="<?php echo esc_url('https://www.google.com/maps/place/' . get_post_meta(get_the_ID(), '_address', true)) ?>">
		    	<?php echo esc_html__('View on Maps',JB_TEXT_DOMAIN)?>
		   	</a>
		</div>
	</div>
</div>