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

<div class="jb-s-item">
	<label class="label-hidden"><?php echo esc_html__('Search', 'wp-recruitment')?></label>
	<div class="jb-input-wrap">
		<button type="submit" class="jb-s-submit btn btn-default">
		    <i class="fa fa-search"></i>
		    <?php esc_html_e('Search', 'wp-recruitment'); ?>
		</button>
		<input type="hidden" name="post_type" value="jobboard-post-jobs" />
	</div>
</div>
