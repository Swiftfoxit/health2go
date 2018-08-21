<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 9:58 AM
 */
/**
 * The template for displaying job content in the single-job.php template.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/content-single.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        FOX
 * @package    JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php get_header( 'jobboard' ); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php JB_Event()->get_template( 'single-content.php' ); ?>

<?php endwhile; ?>

<?php do_action( 'jobboard_single_after_content' ); ?>

<?php get_footer( 'jobboard' ); ?>