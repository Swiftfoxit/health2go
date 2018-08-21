<?php
/**
 * Loop job title
 *
 * This template can be overridden by copying it to yourtheme/jobboard/loop/title.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author     FOX
 * @package    JobBoard/Templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
global $opt_theme_options;
?>

<div class="loop-title">
    <?php
    $layout = !empty($opt_theme_options['job_layout']) ? 'grid' : 'list' ;
    $value  = !empty($_GET['layout']) ? $_GET['layout'] : $layout ;
    ?>
    <?php if($value == 'grid'): ?>
        <div class="job-grid-feature-image" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>)"></div>
    <?php endif; ?>
    <h2 class="entry-title">
        <a href="<?php the_permalink(); ?>" rel="bookmark">
            <?php the_title(); ?>
        </a>
        <?php if(!empty($status)): ?>
            <span class="status status-<?php echo esc_attr($status['id']) ?>">
            <?php echo esc_html($status['name']); ?>
        </span>
        <?php endif; ?>
    </h2>
</div>
