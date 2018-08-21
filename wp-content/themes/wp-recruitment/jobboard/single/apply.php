<?php
/**
 * Simple job actions apply
 *
 * This template can be overridden by copying it to yourtheme/jobboard/single/actions/apply.php.
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
?>

<div class="job-gap">
	<button type="submit" class="action-apply btn btn-default btn-xlg md-trigger" data-modal="jobboard-modal-apply">
	    <i class="fa fa-search"></i>
	    <?php esc_html_e('Apply For This Job', 'wp-recruitment'); ?>
	</button>
</div>