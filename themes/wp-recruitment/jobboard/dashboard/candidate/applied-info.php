<?php
/**
 * The Template for displaying applied info dashboard.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/dashboard/candidate/applied-info.php.
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

<div class="jb-account-applied">
	<div class="jb-field-heading">
	    <h3><?php esc_html_e('Application History', 'wp-recruitment'); ?></h3>
	    <span><?php echo sprintf(esc_html__('You have applied for %s jobs in the past 30 days.', 'wp-recruitment'), '<b>'.jb_candidate_count_applied().'</b>'); ?></span>
	    <a href="<?php echo esc_url(jb_page_endpoint_url('applied')); ?>"><?php esc_html_e('View application', 'wp-recruitment'); ?></a>
	</div>
</div>
