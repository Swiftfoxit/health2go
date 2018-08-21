<?php
/**
 * The Template for displaying widgets jobs.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/widgets/widget-jobs.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (!$jobs->have_posts()){
    return;
}
?>

<div class="widget-content">
    <ul>

        <?php while ( $jobs->have_posts() ) : $jobs->the_post(); ?>

            <li>
                
                <div class="loop-holder clearfix">
                    <a class="loop-title left" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php if (!$hide_salary): ?>
                        <div class="loop-salary right"><?php jb_job_the_salary(); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="loop-meta clearfix">
                    <?php if (!$hide_location): ?>
                        <div class="loop-locations left"><?php jb_job_the_locations(); ?></div>
                    <?php endif; ?>
                    <div class="loop-type right"><?php jb_job_the_types(); ?></div>
                </div>

                <div class="loop-content"><?php echo wp_trim_words(strip_tags(strip_shortcodes(get_the_content())),18); ?></div>
                
            </li>

        <?php endwhile; ?>

        <li><a href="#"><?php echo esc_html__('View All Jobs', 'wp-recruitment'); ?></a></li>

    </ul>
</div>