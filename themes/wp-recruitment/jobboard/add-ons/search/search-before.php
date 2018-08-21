<?php
/**
 * The Template for displaying before search form.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/search/search-before.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Search/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $jobboard_options;
$class = $count == 4 ? ' search-type-active' : '';
?>

<div class="search-content clearfix input-<?php echo esc_attr($count); ?><?php echo esc_attr($class); ?>">
