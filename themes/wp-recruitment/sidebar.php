<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */
?>
<?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
	<div id="sidebar" class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
		<div id="widget-area" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'main-sidebar' ); ?>
		</div><!-- .widget-area -->
	</div><!-- #sidebar -->
<?php endif; ?>

