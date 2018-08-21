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
	<div class="entry-blog">
		<header class="entry-header">

			<?php recruitment_post_thumbnail_2column(); ?>
			<?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>

		</header><!-- .entry-header -->


		<div class="entry-content">
			<div class="entry-meta">
				
				<?php recruitment_post_detail(); ?>

			</div><!-- .entry-meta -->
			
			<div class="entry-content-text">
				<?php echo wp_trim_words(strip_tags(strip_shortcodes(get_the_content())),20); ?>...
			</div>

			<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'wp-recruitment' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<a class="view-post" href="<?php the_permalink(); ?>"><?php esc_html_e('View Post', 'wp-recruitment') ?><i class="zmdi zmdi-long-arrow-right"></i></a>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-## -->
