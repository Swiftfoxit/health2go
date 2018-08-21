<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 * @author CMSSuperHeroes Team
 */

$_get_page_sidebar = recruitment_page_sidebar();
get_header(); ?>

<div id="primary" class="<?php cms_page_full_width(); ?> <?php echo esc_attr($_get_page_sidebar); ?>">
    <div class="row">
        <div id="content" class="<?php recruitment_page_class(); ?>">
            <main id="main" class="site-main">

                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'single-templates/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    // End the loop.
                endwhile;
                ?>

            </main><!-- .site-main -->
        </div>
        <?php if($_get_page_sidebar != 'is-no-sidebar'):
            get_sidebar();
        endif; ?>
    </div>
</div><!-- .content-area -->

<?php get_footer(); ?>