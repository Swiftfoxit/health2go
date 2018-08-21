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
}
$address = !empty(get_post_meta(get_the_ID(), '_address', true)) ? get_post_meta(get_the_ID(), '_address', true) : jb_job_location_html();
$location = !empty(get_post_meta(get_the_ID(), '_address', true)) ? get_post_meta(get_the_ID(), '_address', true) : jb_job_location_text();

global $opt_theme_options;
if ($opt_theme_options['single_job_map'] == 'show') : ?>
    <div class="col-xs-12">
        <div class="job-single-map-wrap">
            <?php echo do_shortcode('[jobboard-shortcode-map-2][/jobboard-shortcode-map-2]'); ?>
            <div class="job-single-map-holder clearfix">
                <div class="job-single-address"><?php echo wp_kses($address,wp_kses_allowed_html()) ?></div>
                <a class="job-single-view-map" href="<?php echo esc_url('https://www.google.com/maps/place/' . $location) ?>">
                    <?php echo esc_html__('View on Maps', 'wp-recruitment') ?>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>