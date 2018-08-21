<div class="cms-carousel cms-carousel-blog-layout2 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <?php
    $posts = $atts['posts'];
    while($posts->have_posts()){
        $posts->the_post();
        $size = 'full';
        ?>
        <div class="cms-carousel-item">
                <?php 
                    if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
                        $class = ' has-feature-img';
                        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
                        $thumbnail = get_the_post_thumbnail(get_the_ID(),'full');
                    else:
                        $class = ' no-feature-img';
                        $thumbnail_url[0] = get_template_directory_uri(). '/assets/images/no-image.jpg';
                        $thumbnail = '<img src="'.get_template_directory_uri(). '/assets/images/no-image.jpg" alt="'.get_the_title().'" />';
                    endif;
                ?>
                <div class="cms-carousel-inner col-same <?php echo esc_attr($class); ?>" style="background-image: url(<?php echo esc_url($thumbnail_url[0]); ?>); ">
                    <?php if(has_category()): ?>
                        <div class="detail-terms"><?php the_terms( get_the_ID(), 'category', '', ', ' ); ?></div>
                    <?php endif; ?> 
                    <div class="entry-body">
                        <div class="entry-time">
                            <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>
                        </div>
                        <h3 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="entry-readmore">
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html__('Read post', 'wp-recruitment'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
    ?>
</div>