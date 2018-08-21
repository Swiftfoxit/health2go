<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/jobboard/global/wrapper-start.php.
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
<?php if(is_jb_profile()): ?>
	<div class="jb-user-container"><div class="user-container">
<?php else: ?>
	<div class="container job-container"><div class="row">
<?php endif; ?>
