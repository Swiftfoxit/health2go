<?php 
    /* get categories */
        $taxo = 'category';
        $_category = array();
        if(!isset($atts['cat']) || $atts['cat']==''){
            $terms = get_terms($taxo);
            foreach ($terms as $cat){
                $_category[] = $cat->term_id;
            }
        } else {
            $_category  = explode(',', $atts['cat']);
        }
        $atts['categories'] = $_category;

        global $jobboard_meta;
?>

<?php if(class_exists('JobBoard')) { ?>
    <div class="cms-grid-wraper cms-grid-job <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
        <div class="<?php echo esc_attr($atts['grid_class']);?>">
            <?php

            $posts = $atts['posts'];
            $size = 'full';
            while($posts->have_posts()){
                $posts->the_post();
                $groups = array();
                $groups[] = '"all"';
                $job_featured = get_post_meta(get_the_ID(), '_featured', true);

                foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                    $groups[] = '"category-'.$category->slug.'"';
                }
                ?>
                <div class="<?php echo esc_attr($atts['item_class']);?>" data-groups='[<?php echo implode(',', $groups);?>]'>
                    
                    <div class="cms-grid-content">

                        <?php 
                            if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
                                $thumbnail = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID(), 'wp_envogue_lg-images') ); 
                            else:
                                $thumbnail = '' .get_template_directory_uri(). '/assets/images/no-image.jpg';
                            endif;
                        ?>
        
                        <div class="cms-grid-image" style="background-image: url(<?php echo esc_url($thumbnail); ?>);">
                            <?php if($job_featured == 1) { ?>
                                <span class="btn-job"><?php echo esc_html__('FEATURED', 'wp-recruitment'); ?></span>
                            <?php } ?>
                        </div>

                        <div class="cms-grid-meta">
                            <h3 class="cms-grid-title">
                                <?php
                                    $title = get_the_title();
                                    if(strlen($title) > 20){
                                        $title = substr($title, 0, 20) . '...';
                                    }
                                ?>
                                <a href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a>
                            </h3>
                            <div class="cms-grid-location"><?php echo jb_job_location_html(); ?></div>
                            <div class="cms-grid-salary"><?php jb_job_salary(); ?></div>
                        </div>
                    </div>
                    
                </div>
                <?php
            }
            ?>
        </div>
    </div>
<?php } ?>