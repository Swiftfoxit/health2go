<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package CMSSuperHeroes
 * @since Recruitment 1.0.9
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php recruitment_post_quote(); ?>
	</header><!-- .entry-header -->


	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_content( sprintf(esc_html__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'wp-recruitment' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		) );

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wp-recruitment' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->
	<footer class="entry-footer clearfix">
		<div class="single-post-tags left">
			<?php the_tags('<span>Tags</span>',''); ?>
		</div> 
		<div class="single-post-socia right">
			<?php recruitment_get_socials_share(); ?>
		</div>
	</footer>

</article><!-- #post-## -->