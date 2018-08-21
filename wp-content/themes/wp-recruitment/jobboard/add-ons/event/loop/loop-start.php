<?php
/**
 * The Template for displaying loop start
 *
 * This template can be overridden by copying it to yourtheme/jobboard/loop/loop-start.php.
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
global $opt_theme_options;
$event_style = '';
$event_style = (isset($_GET['event-style'])) ? trim($_GET['event-style']) : $opt_theme_options['event_style'];
$event_class = '';
if ($event_style == 'event-classic') {
    $event_class = 'event-classic';
} else {
	$event_class = 'event-modern';
}

?>

<div class="jobboard-archive-loop jobboard-event-wrap <?php echo esc_attr( $event_style ); ?>">
	<div class="container">
