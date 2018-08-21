<?php
/**
 * The Template for displaying search similar keywords.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/search/similar-keywords.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Similar/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(empty($keywords)){
    return;
}
?>

<div class="similar-search">
    <span><?php esc_html_e('Quick Searches:', JB_SIMILAR_TEXT_DOMAIN);?></span>
    <ul>
        <?php foreach ($keywords as $key => $priority): ?>

            <li class="priority-<?php echo esc_attr($priority); ?>"><a href="<?php echo esc_url(add_query_arg(array('s' => $key, 'post_type' => 'jobboard-post-jobs'), home_url( '/'  ))); ?>"><?php echo esc_html(ucfirst($key)); ?></a></li>

        <?php endforeach; ?>
    </ul>
</div>
