<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */

get_header(); ?>

<div id="primary" class="container">
	<main id="main" class="site-main" role="main">

		<section class="error-404 text-center">
			<div class="heading-404"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/image-404.png" alt=""/></div>
			<div class="page-content">
				<?php esc_html_e( 'It looks like something went wrong. The page cannot be found.', 'wp-recruitment' ); ?>
				<div class="home-404">
					<?php esc_html_e('Return','wp-recruitment'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home','wp-recruitment'); ?></a>
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
