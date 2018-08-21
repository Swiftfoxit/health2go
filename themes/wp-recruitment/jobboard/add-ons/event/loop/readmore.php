<?php
/**
 * The Template for displaying loop job readmore.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/loop/readmore.php.
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
?>

<div class="loop-readmore col-lg-3 col-md-4 col-sm-12 col-xs-12 text-right text-left-sm text-left-xs">
    <a class="btn" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Event', 'wp-recruitment' ); ?></a>
</div>