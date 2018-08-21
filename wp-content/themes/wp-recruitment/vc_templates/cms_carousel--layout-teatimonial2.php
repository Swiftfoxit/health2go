<div class="cms-carousel cms-testimonial cms-testimonial-layout2 text-center <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>" data-center="true">
    <?php
    $posts = $atts['posts'];
    global $opt_meta_options;
    while($posts->have_posts()){
        $posts->the_post();
        ?>
        <div class="cms-carousel-item cms-testimonial-item">
            <div class="cms-testimonial-wrapper clearfix">
                <i>“</i>
                <h3 class="cms-testimonial-title"><?php the_title();?></h3>
                <span class="cms-testimonial-position"><?php echo esc_attr($opt_meta_options['testimonial_position']); ?></span>
                <div class="cms-testimonial-content"><?php the_content(); ?></div>
                <div class="cms-testimonial-image">
                    <?php 
                        if(has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium', false)):
                            $class = ' has-thumbnail';
                            $thumbnail = get_the_post_thumbnail(get_the_ID(),'medium');
                        else:
                            $class = ' no-image';
                            $thumbnail = '<img src="'.esc_url(get_template_directory_uri(). '/assets/images/no-image.jpg').'" alt="'.get_the_title().'" />';
                        endif;
                        echo ''.$thumbnail.'';
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>