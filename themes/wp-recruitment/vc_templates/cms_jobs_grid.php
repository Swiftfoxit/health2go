<?php if(class_exists('JobBoard')) {
    $i = 0 ; $num = $content->post_count - 1;
    $specialism = get_terms(array('taxonomy' => 'jobboard-tax-specialisms', 'hide_empty' => true, 'number' => 4));
    $html_id = cmsHtmlID('cms-recent-jobs');
    $atts['html_id'] = $html_id;
    ?>
    <div id="<?php echo esc_attr($atts['html_id']);?>" class="jb-carousel-wrap cms-recent-jobs <?php echo esc_html($atts['job_style']); ?> <?php echo esc_html($atts['custom_class']); ?>">
        <div class="jb-carousel-meta">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            <ul class="jb-carousel-filter">
                <li><a href="#"><?php echo esc_attr('All', 'wp-recruitment'); ?></a></li>
                <?php foreach ($specialism as $term ): ?>
                    <li>
                        <a href="#jobboard-tax-specialisms-<?php echo esc_attr($term->slug); ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div id="cms-jobs-wrapper">
            <div class="jb-jobs-body jobs-carousel">
                <?php while ($content->have_posts()) : $content->the_post(); ?>

                    <?php if($i % 4 == 0){ echo "<div class='jb-carousel-items'>"; } ?>

                    <?php 
                        if(class_exists('JobBoard')) {
                            jb_get_template_part( 'content', 'jobs' ); 
                        }
                    ?>

                    <?php if($i % 4 == 3 || $i == $num){ echo "</div>"; } ?>

                <?php $i++; endwhile; ?>

                <?php wp_reset_postdata(); ?>
            </div>
            <div class="jb-jobs-footer clearfix">
                <a class="btn view-all-job" href="<?php echo jb_page_permalink('jobs'); ?>"><?php echo esc_attr('All Jobs', 'wp-recruitment'); ?></a>
            </div>
        </div>
    </div>
<?php } ?>