<?php
/**
 * The Template for displaying all single posts
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 */
$_get_post_sidebar = recruitment_single_post_sidebar();
get_header(); ?>

<div id="primary" class="container <?php echo esc_attr($_get_post_sidebar); ?>">
    <div class="row">
        <div id="content" class="<?php recruitment_single_post_class(); ?>">
            <main id="main" class="site-main" role="main">
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();
                    ?>
                     <div class="single-post-inner">
                        <?php 
                            // Include the single content template.
                            get_template_part( 'single-templates/single/content', get_post_format() );

                            // If comments are open or we have at least one comment, load up the comment template.
                            recruitment_post_comment();
                        ?>
                    </div>
                    <?php 
                    
                endwhile;
                ?>

            </main>
        </div><!-- #main -->
        <?php if($_get_post_sidebar != 'is-no-sidebar'):
            get_sidebar();
        endif; ?>
    </div>
</div><!-- #primary -->
<?php get_footer(); ?>