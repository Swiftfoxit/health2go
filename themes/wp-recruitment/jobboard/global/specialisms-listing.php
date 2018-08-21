<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/jobboard/global/pagination.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        FOX
 * @package    JobBoard/Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wp_query;

if ($wp_query->max_num_pages <= 1) {
    return;
}
$link = get_permalink(jb_get_option('page-list-specialisms'));
$link =  urlencode($link);
echo do_shortcode('[cms_specialism_list page="url:' .$link . '" show="custom" number="18" specialism_layout="layout3" title_text="' . esc_html__('Browse More Jobs by Sector', 'wp-recruitment') . '"]');
?>

