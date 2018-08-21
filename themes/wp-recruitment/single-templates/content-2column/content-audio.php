<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php recruitment_post_audio(); ?>

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>

		<div class="entry-meta">

			<?php recruitment_post_detail(); ?>

		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->


	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_excerpt();

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wp-recruitment' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More:', 'wp-recruitment') ?></a>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
