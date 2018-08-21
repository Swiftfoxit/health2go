<?php
$col_lg = (isset($atts['col_lg'])) ? $atts['col_lg'] : '1';
$col_md = (isset($atts['col_md'])) ? $atts['col_md'] : '1';
$col_sm = (isset($atts['col_sm'])) ? $atts['col_sm'] : '1';
$col_xs = (isset($atts['col_xs'])) ? $atts['col_xs'] : '1';

$margin = (isset($atts['margin'])) ? $atts['margin'] : '30';
$loop = (isset($atts['loop'])) ? $atts['loop'] : '0';
$autoplay = (isset($atts['autoplay'])) ? $atts['autoplay'] : '0';
$nav = (isset($atts['nav'])) ? $atts['nav'] : '1';

$btn_view_all = (isset($atts['btn_view_all'])) ? $atts['btn_view_all'] : 'show';
$post_per_row = (isset($atts['post_per_row'])) ? $atts['post_per_row'] : '4';

if(class_exists('JobBoard')) {
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
            <div class="jb-jobs-body jobs-carousel"  data-nav="<?php echo esc_attr($nav); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>" data-item-xs="<?php echo esc_attr($col_xs); ?>" data-item-sm="<?php echo esc_attr($col_sm); ?>" data-item-md="<?php echo esc_attr($col_md); ?>" data-item-lg="<?php echo esc_attr($col_lg); ?>" data-margin="<?php echo esc_attr($margin); ?>">
                <?php while ($content->have_posts()) : $content->the_post(); ?>

                    <?php if($i % $post_per_row == 0){ echo "<div class='jb-carousel-items'>"; } ?>

                    <?php 
                        if(class_exists('JobBoard')) {
                            jb_get_template_part( 'content', 'jobs' ); 
                        }
                    ?>

                    <?php if($i % $post_per_row == ($post_per_row - 1) || $i == $num){ echo "</div>"; } ?>

                <?php $i++; endwhile; ?>

                <?php wp_reset_postdata(); ?>
            </div>

            <?php if($btn_view_all == 'show') : ?>
                <div class="jb-jobs-footer clearfix">
                    <a class="btn view-all-job" href="<?php echo jb_page_permalink('jobs'); ?>"><?php echo esc_attr('All Jobs', 'wp-recruitment'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>