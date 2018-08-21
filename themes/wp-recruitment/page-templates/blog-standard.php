<?php
/**
 * Template Name: Blog Standard
 *
 * @package CMSSuperHeroes
 * @subpackage Recruitment
 * @since Recruitment 1.0.9
 * @author CMSSuperHeroes Team
 */
$_get_sidebar = recruitment_blog_sidebar();
get_header(); ?>

<div id="section-blog-feature">
    <div class="container">
        <div class="row">
            <div class="<?php if(class_exists('JobBoard')) { echo 'col-lg-8 col-md-8 col-sm-6 col-xs-12'; } else { echo 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; } ?>">
                <?php recruitment_feature_post(); ?>  
            </div>
            <?php if(class_exists('JobBoard')) { ?>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <?php echo do_shortcode('[cms_grid col_xs="1" col_sm="2" col_md="2" col_lg="2" source="size:3|order_by:date|post_type:jobboard-post-jobs" cms_template="cms_grid--job.php"]'); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<section id="primary" class="container <?php echo esc_attr($_get_sidebar); ?>">
    <div class="row">
        <div id="content" class="<?php recruitment_blog_class(); ?>">
            <h3 class="blog-heading"><?php echo esc_html__('Latest News', 'wp-recruitment'); ?></h3>
            <main id="main" class="site-main" role="main">
                <?php global $wp_query, $paged;
            
                $wp_query->query('post_type=post&showposts='.get_option('posts_per_page').'&paged='.$paged);
                
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();

                        get_template_part( 'single-templates/content/content', get_post_format() );

                    endwhile; // end of the loop.

                    /* blog nav. */
                    recruitment_paging_nav();

                    /* reset custom postdata. */
                    wp_reset_postdata();
                    
                else :
                    /* content none. */
                    get_template_part( 'single-templates/content', 'none' );

                endif; ?>
                
            </main><!-- #content -->
        </div>
        <?php if($_get_sidebar != 'is-no-sidebar'):
            get_sidebar();
        endif; ?>
    </div>
</section><!-- #primary -->

<?php get_footer(); ?>