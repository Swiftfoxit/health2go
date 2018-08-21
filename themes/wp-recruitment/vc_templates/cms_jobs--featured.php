<?php 
$col_lg = (isset($atts['col_lg'])) ? $atts['col_lg'] : '1';
$col_md = (isset($atts['col_md'])) ? $atts['col_md'] : '1';
$col_sm = (isset($atts['col_sm'])) ? $atts['col_sm'] : '1';
$col_xs = (isset($atts['col_xs'])) ? $atts['col_xs'] : '1';

$margin = (isset($atts['margin'])) ? $atts['margin'] : '30';
$loop = (isset($atts['loop'])) ? $atts['loop'] : '0';
$autoplay = (isset($atts['autoplay'])) ? $atts['autoplay'] : '0';
$nav = (isset($atts['nav'])) ? $atts['nav'] : '1';

if(class_exists('JobBoard')) { 
    $html_id = cmsHtmlID('cms-feature-jobs');
    $atts['html_id'] = $html_id; ?>
    <div id="<?php echo esc_attr($atts['html_id']);?>" class="jb-carousel-wrap cms-feature-jobs <?php echo esc_html($atts['job_style']); ?> <?php echo esc_html($atts['custom_class']); ?>">
        <?php if(!empty($atts['title'])) { ?>
            <div class="jb-carousel-meta">
                <h3><?php echo esc_html($atts['title']); ?></h3>
            </div>
        <?php } ?>
        <div class="jobs-carousel" data-nav="<?php echo esc_attr($nav); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>" data-item-xs="<?php echo esc_attr($col_xs); ?>" data-item-sm="<?php echo esc_attr($col_sm); ?>" data-item-md="<?php echo esc_attr($col_md); ?>" data-item-lg="<?php echo esc_attr($col_lg); ?>" data-margin="<?php echo esc_attr($margin); ?>">
            <?php while ($content->have_posts()) : $content->the_post(); ?>
                <div class="items">
                    <div class="item-inner">
                        <div class="jb-carousel-image" style="background-image: url(<?php the_post_thumbnail_url('medium'); ?>);">
                            <span><?php echo esc_attr('Featured', 'wp-recruitment'); ?></span>
                        </div>
                        <div class="jb-carousel-middle">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="jb-carousel-middle-inner">
                                <div class="jb-carousel-location"><?php echo jb_job_location_html(); ?></div>
                                <div class="jb-carousel-jobtype"><?php echo jb_job_get_type(); ?></div>
                                <div class="jb-carousel-salary"><?php jb_job_the_salary(); ?></div>
                            </div>
                        </div>
                        <div class="jb-carousel-content">
                            <?php echo substr(strip_shortcodes(strip_tags(get_the_content())), 0,120); ?>...
                        </div>
                    </div>
                    <a class="btn btn-default" href="<?php the_permalink(); ?>"><?php esc_html_e('View More', 'wp-recruitment') ?></a>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php } ?>