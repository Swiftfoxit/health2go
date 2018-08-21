<?php
$page = isset($atts['page']) ? $atts['page'] : '';
$link = vc_build_link($page);
$a_href = '';
$a_target = '';
if ( strlen( $link['url'] ) > 0 ) {
    $a_href = $link['url'];
    $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
}
switch ($atts['specialism_layout']) {
    case "layout1": ?>
        <div class="jb-specialism-list <?php echo esc_html($atts['job_style']); ?> <?php echo esc_html($atts['custom_class']); ?> clearfix">
            <?php if(!empty($content)): ?>

                <?php foreach ($content as $term): ?>

                <div class="jb-specialism-item">
                    <div class="jb-specialism-inner clearfix" data-toggle="tooltip" title="<?php echo esc_html($term->name);?>">
                        <div class="jb-specialism-left">
                            <span>
                                <?php

                                $term_link = get_term_link($term->term_id, 'jobboard-tax-specialisms');
                                $term_icon = get_term_meta($term->term_id, '_icon', true);
                                $term_image = get_term_meta($term->term_id, '_image', true);
                                $term_media = get_term_meta($term->term_id, '_media', true);

                                if($term_media && $term_icon){
                                    echo '<i class="'.esc_attr($term_icon).'"></i>';
                                } elseif (!$term_media && isset($term_image['url'])){
                                    echo '<img src="'.esc_url($term_image['url']).'" alt="' . esc_html($term->name) . '">';
                                } elseif(strlen($term->name) <= 2){
                                    echo esc_html($term->name);
                                } else {
                                    echo substr($term->name, 0, 1);
                                }

                                ?>
                            </span>
                        </div>
                        <div class="jb-specialism-right">
                            <h6><?php echo esc_html($atts['length'] && strlen($term->name) > (int)$atts['length'] ? substr($term->name, 0 , (int)$atts['length']) . '...' : $term->name);?></h6>
                            <a class="jb-specialism-position" href="<?php echo esc_url($term_link); ?>"><?php echo sprintf(esc_html__('%s Positions', 'wp-recruitment'), $term->count); ?></a>
                            <a class="jb-specialism-more" href="<?php echo esc_url($term_link); ?>"><?php esc_html_e('View', 'wp-recruitment'); ?></a>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p><?php esc_html_e('Specialism not found!', 'wp-recruitment'); ?></p>

            <?php endif; ?>
        </div>

        <?php if(!empty($a_href)) : ?>
            <div class="jb-specialism-button text-center">
                <a href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>"><i class="zmdi zmdi-chevron-down"></i><?php echo esc_html($page_url['title'] ? $page_url['title'] : esc_html__('All Sectors', 'wp-recruitment')); ?><i class="zmdi zmdi-chevron-down"></i></a>
            </div>
        <?php endif; ?>
        <?php break;
    case "layout2": ?>
        <div class="jb-specialism-list2 col-same <?php echo esc_html($atts['custom_class']); ?> clearfix">
            <?php if(!empty($atts['title_text'])) : ?>
                <h3><?php echo esc_attr( $atts['title_text'] ); ?></h3>
            <?php endif; ?>
            <?php if(!empty($content)): ?>
                <ul class="row">
                    <?php foreach ($content as $term): $term_link = get_term_link($term->term_id, 'jobboard-tax-specialisms'); ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><a class="jb-specialism-more" href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($atts['length'] && strlen($term->name) > (int)$atts['length'] ? substr($term->name, 0 , (int)$atts['length']) . '...' : $term->name);?></a></li>
                    <?php endforeach; ?>
                    <?php if(!empty($a_href)) : ?>
                        <li class="specialism-more col-lg-4 col-md-4 col-sm-6 col-xs-12"><a href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>"><?php echo esc_html__('More +', 'wp-recruitment'); ?></a></li>
                    <?php endif; ?>
                </ul>
            <?php else: ?>
                <p><?php esc_html_e('Specialism not found!', 'wp-recruitment'); ?></p>
            <?php endif; ?>
        </div>
        <?php  break;
    case "layout3": ?>
        <?php if(!empty($content)): ?>
            <div class="jb-specialism-wrap">
                <div class="jb-specialism-list3 box-modern <?php echo esc_html($atts['custom_class']); ?> clearfix">
                    <?php if(!empty($atts['title_text'])) : ?>
                        <h3><?php echo esc_attr( $atts['title_text'] ); ?></h3>
                    <?php endif; ?>
                    
                    <div class="jb-specialism-inner row">
                        <div class="jb-specialism-items col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <?php foreach ($content as $term): $term_link = get_term_link($term->term_id, 'jobboard-tax-specialisms'); ?>
                                <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($atts['length'] && strlen($term->name) > (int)$atts['length'] ? substr($term->name, 0 , (int)$atts['length']) . '...' : $term->name);?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="jb-specialism-view-all col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <?php if(!empty($a_href)) : ?>
                               <a class="btn btn-md2" href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>"><?php echo esc_html__('View All Sectors', 'wp-recruitment'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php  break;
    case "layout4": ?>
        <?php if(!empty($content)): ?>
            <div class="jb-specialism-wrap">
                <div class="jb-specialism-list4 <?php echo esc_html($atts['custom_class']); ?> clearfix">
                    <?php if(!empty($atts['title_text'])) : ?>
                        <h3><?php echo esc_attr( $atts['title_text'] ); ?></h3>
                    <?php endif; ?>
                    
                    <div class="jb-specialism-inner">
                        <div class="jb-specialism-items">
                            <?php foreach ($content as $term): $term_link = get_term_link($term->term_id, 'jobboard-tax-specialisms'); ?>
                                <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($atts['length'] && strlen($term->name) > (int)$atts['length'] ? substr($term->name, 0 , (int)$atts['length']) . '...' : $term->name);?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="jb-specialism-view-all">
                            <?php if(!empty($a_href)) : ?>
                               <a class="btn" href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>"><?php echo esc_html__('View All Sectors', 'wp-recruitment'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php break;
    case "layout5": ?>
        <?php if(!empty($content)): ?>
            <div class="jb-specialism-wrap box-modern">
                <div class="jb-specialism-list5 <?php echo esc_html($atts['custom_class']); ?> clearfix">
                    <?php if(!empty($atts['title_text'])) : ?>
                        <h3><?php echo esc_attr( $atts['title_text'] ); ?></h3>
                    <?php endif; ?>
                    
                    <div class="jb-specialism-inner">
                        <div class="jb-specialism-items">
                            <?php foreach ($content as $term): $term_link = get_term_link($term->term_id, 'jobboard-tax-specialisms'); ?>
                                <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($atts['length'] && strlen($term->name) > (int)$atts['length'] ? substr($term->name, 0 , (int)$atts['length']) . '...' : $term->name);?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="jb-specialism-view-all">
                            <?php if(!empty($a_href)) : ?>
                               <a class="btn" href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>"><?php echo esc_html__('View All Sectors', 'wp-recruitment'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php break;
} ?>