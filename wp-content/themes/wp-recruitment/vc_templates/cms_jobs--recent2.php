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
    $html_id = cmsHtmlID('cms-recent-jobs');
    $atts['html_id'] = $html_id; ?>
    <div id="<?php echo esc_attr($atts['html_id']);?>" class="jb-carousel-wrap cms-recent-jobs2 <?php echo esc_html($atts['custom_class']); ?> <?php echo esc_html($atts['job_style']); ?> <?php if(!empty($atts['title'])) { echo "title-show"; } ?> clearfix">
        <?php if(!empty($atts['title'])) { ?>
            <div class="jb-carousel-meta2">
                <?php
                    $title_hd = $atts['title'];
                    if(strlen($title_hd) > 15){
                        $title_hd = substr($title_hd, 0, 15);
                    }
                ?>
                <h3><?php echo esc_html($title_hd); ?></h3>
            </div>
        <?php } ?>
        <div class="jobs-carousel-recent jobs-carousel" data-nav="<?php echo esc_attr($nav); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>" data-item-xs="<?php echo esc_attr($col_xs); ?>" data-item-sm="<?php echo esc_attr($col_sm); ?>" data-item-md="<?php echo esc_attr($col_md); ?>" data-item-lg="<?php echo esc_attr($col_lg); ?>" data-margin="<?php echo esc_attr($margin); ?>">
            <?php while ($content->have_posts()) : $content->the_post(); ?>

                <div class="items">
                    <div class="item-inner">
                        <?php
                            $title_item = get_the_title();
                            if(strlen($title_item) > 30){
                                $title_item = substr($title_item, 0, 30) . '...';
                            }
                        ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php echo esc_html($title_item); ?></a></h3>
                        <div class="jb-carousel-location"><?php echo jb_job_location_html(); ?></div>
                        <div class="jb-carousel-salary"><?php echo esc_html__('Salary: ', 'wp-recruitment'); ?><?php jb_job_the_salary(); ?></div>
                    </div>
                </div>

            <?php endwhile; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php } ?>