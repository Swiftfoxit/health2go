<?php
/**
 * The Template for displaying user loop salary.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/users/salary.php.
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

if(empty($salary)){
    return;
}
?>

<div class="loop-salary loop-table">
	<div class="loop-salary-inner">
		<span><?php echo esc_html($salary); ?></span>
	    <?php esc_html_e('Minimum Salary', 'wp-recruitment'); ?>
	</div>
</div>