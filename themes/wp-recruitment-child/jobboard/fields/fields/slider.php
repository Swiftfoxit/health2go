<?php
/**
 * The Template for displaying input text.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/fields/fields/text.php.
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
wp_enqueue_style('bootstrap-slider');
wp_enqueue_script('bootstrap-slider');
?>

<input id="<?php echo esc_attr($id); ?>" type="text" class="<?php echo esc_attr($type); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" data-slider-id='ex1Slider'>

<script>
  jQuery( function($) {
    $( "#<?php echo esc_attr($id); ?>" ).slider({
	formatter: function(value) {
		return 'Current value: ' + value;
	}
});

  } );
  </script>