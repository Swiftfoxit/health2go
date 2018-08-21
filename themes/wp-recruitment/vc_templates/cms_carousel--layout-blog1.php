<?php
    $carousel_title = isset($atts['carousel_title']) ? $atts['carousel_title'] : '';
?>
<div class="cms-carousel-wrap">
    <?php if(!empty($carousel_title)) { ?>
        <h3><?php echo esc_attr($carousel_title); ?></h3>
    <?php } ?>
    <div class="cms-carousel cms-carousel-blog-layout1 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
        <?php
        $posts = $atts['posts'];
        while($posts->have_posts()){
            $posts->the_post();
            $size = 'recruitment_blog_size3';
            ?>
            <div class="cms-carousel-item">
                    <?php 
                        if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
                            $class = ' has-feature-img';
                            $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
                            $thumbnail = get_the_post_thumbnail(get_the_ID(),'recruitment_blog_size3');
                        else:
                            $class = ' no-feature-img';
                            $thumbnail_url[0] = get_template_directory_uri(). '/assets/images/no-image.jpg';
                            $thumbnail = '<img src="'.get_template_directory_uri(). '/assets/images/no-image.jpg" alt="'.get_the_title().'" />';
                        endif;
                    ?>
                    <div class="carousel-entry-blog <?php echo esc_attr($class); ?>">
                        <div class="entry-header">
                            <ul class="entry-details">
                                <?php if(has_category()): ?>
                                    <li class="detail-terms"><?php the_terms( get_the_ID(), 'category', '', ', ' ); ?></li>
                                <?php endif; ?> 
                                <li class="detail-date"><?php the_date(); ?></li>
                            </ul>
                            <div class="entry-feature">
                                <a href="<?php the_permalink(); ?>"><?php echo ''.$thumbnail.''; ?></a>
                            </div>
                        </div>
                        <div class="entry-body">
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="entry-content">
                                <?php echo substr(strip_tags(strip_shortcodes(get_the_content())), 0,118); ?>...
                            </div>
                            <div class="entry-readmore">
                                <a href="<?php the_permalink(); ?>"><?php echo esc_html__('Read more', 'wp-recruitment'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }
        ?>
    </div>
</div>