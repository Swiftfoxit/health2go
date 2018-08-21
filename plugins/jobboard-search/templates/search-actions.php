<?php
/**
 * The Template for displaying search actions.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/search/search-actions.php.
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
?>

<button type="submit" class="button"><?php esc_html_e('Search', JB_SEARCH_TEXT_DOMAIN); ?></button>
<input type="hidden" name="post_type" value="jobboard-post-jobs" />
