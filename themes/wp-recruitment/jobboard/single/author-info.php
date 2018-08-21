<?php
/**
 * @Template: author-info.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 18-Dec-17
 */
if (!defined('ABSPATH')) {
    die();
}
$job_count = count_user_posts(get_the_author_meta('ID'), 'jobboard-post-jobs');
$avt = get_the_author_meta('user_avatar');
$job_count_layout = $job_count > 1 ? $job_count . ' ' . esc_html__('Active Positions', 'wp-recruitment') : $job_count . ' ' . esc_html__('Active Position', 'wp-recruitment');
global $opt_theme_options;
$page_archive_url = !empty(jb_get_option('page-jobs', '')) ? get_permalink(jb_get_option('page-jobs')) : "#";

if ($opt_theme_options['single_job_auth'] == 'show') : ?>
    <div class="col-xs-12">
        <div class="job-single-author clearfix">
            <div class="job-single-author-image">
                <img src="<?php echo !empty($avt['thumbnail']) ? esc_url($avt['thumbnail']) : '#' ?>" alt="">
            </div>
            <div class="job-single-author-holder">
                <h3><?php echo get_the_author() ?></h3>
                <span class="job-single-author-post"><?php echo esc_attr($job_count_layout) ?></span>
                <div class="job-single-author-meta">
                    <a href="<?php echo add_query_arg('employer_id', get_the_author_meta('ID'), $page_archive_url) ?>"><?php echo esc_html__('View More Jobs', 'wp-recruitment') ?></a>
                    <a href="<?php echo esc_url(get_the_author_meta('url')) ?>"
                       target="_blank"><?php echo esc_html__('Visit Website', 'wp-recruitment') ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>