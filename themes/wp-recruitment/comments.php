<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php comments_number(__('Comments',"wp-recruitment"),__('1 Comments',"wp-recruitment"),__('Comments (%)',"wp-recruitment")); ?>
		</h2>

		<?php recruitment_comment_nav(); ?>

		<ol class="comment-list">
			<?php wp_list_comments( 'type=comment&callback=recruitment_custom_list_comment' ); ?>
		</ol><!-- .comment-list -->

		<?php recruitment_comment_nav(); ?>

	<?php endif; // have_comments() ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'wp-recruitment' ); ?></p>
	<?php endif; ?>

	<?php 
		$args = array(
				'id_form'           => 'commentform',
				'id_submit'         => 'submit',
				'title_reply'       => esc_html__( 'leave a Comment',"wp-recruitment"),
				'title_reply_to'    => esc_html__( 'leave a Comment To ',"wp-recruitment") . '%s',
				'cancel_reply_link' => esc_html__( 'Cancel Comment',"wp-recruitment"),
				'label_submit'      => esc_html__( 'Submit Comment',"wp-recruitment"),
				'comment_notes_before' => '',
				'fields' => apply_filters( 'comment_form_default_fields', array(

						'author' =>
						'<div class="row"><div class="comment-form-author col-lg-4 col-md-4 col-sm-12 col-xs-12">'.
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
						'" size="30" placeholder="'.esc_html__('Your Name', "wp-recruitment").'"/></div>',

						'email' =>
						'<div class="comment-form-email col-lg-4 col-md-4 col-sm-12 col-xs-12">'.
						'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
						'" size="30" placeholder="'.esc_html__('Email', "wp-recruitment").'"/></div>',

						'website' =>
						'<div class="comment-form-website col-lg-4 col-md-4 col-sm-12 col-xs-12">'.
						'<input id="website" name="website" type="text" value="" size="30" placeholder="'.esc_html__('Website', "wp-recruitment").'"/></div></div>',
				)
				),
				'comment_field' =>  '<div class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="'.esc_html__('Comment', "wp-recruitment").'" aria-required="true">' .
				'</textarea></div>',
		);
		comment_form($args); 
	?>

</div><!-- .comments-area -->
