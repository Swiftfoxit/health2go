<?php
$col_lg = (isset($atts['col_lg'])) ? $atts['col_lg'] : '4';
$col_md = (isset($atts['col_md'])) ? $atts['col_md'] : '3';
$col_sm = (isset($atts['col_sm'])) ? $atts['col_sm'] : '2';
$col_xs = (isset($atts['col_xs'])) ? $atts['col_xs'] : '1';

$margin = (isset($atts['margin'])) ? $atts['margin'] : '30';
$loop = (isset($atts['loop'])) ? $atts['loop'] : '0';
$autoplay = (isset($atts['autoplay'])) ? $atts['autoplay'] : '0';
$nav = (isset($atts['nav'])) ? $atts['nav'] : '1';

$layout_type = (isset($atts['layout_type'])) ? $atts['layout_type'] : 'carousel';
$view_all = (isset($atts['view_all'])) ? $atts['view_all'] : 'show';
$location_child = (isset($atts['location_child'])) ? $atts['location_child'] : 'hidden';
$location_child_limit = (isset($atts['location_child_limit'])) ? $atts['location_child_limit'] : '8';

$col_lg_class = 12 / $col_lg;
$col_md_class = 12 / $col_md;
$col_sm_class = 12 / $col_sm;
$col_xs_class = 12 / $col_xs;

if (function_exists('jb_get_locations')) {
    $locations = jb_get_locations($atts['country'], $atts['count'], false);
    $limit = $atts['count'] === 'all' ? count($locations) : $atts['count'];
    if ($layout_type == 'carousel') { ?>
        <div class="cms-locations-wrap">
            <div class="cms-locations-listing jobs-carousel" data-nav="<?php echo esc_attr($nav); ?>"
                 data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>"
                 data-item-xs="<?php echo esc_attr($col_xs); ?>" data-item-sm="<?php echo esc_attr($col_sm); ?>"
                 data-item-md="<?php echo esc_attr($col_md); ?>" data-item-lg="<?php echo esc_attr($col_lg); ?>"
                 data-margin="<?php echo esc_attr($margin); ?>">
                <?php if (isset($locations)):
                    ?>
                    <?php for ($i = 0; $i < $limit; $i++):
                    if (!empty($locations[$i])) {
                        $key = $locations[$i];
                        $thumbnail_url = jb_get_location_thum($key);
                        ?>
                        <div class="cms-location-item">
                            <div class="cms-location-parent overlay-gradient">
                                <div class="cms-location-image"
                                     style="background-image: url(<?php echo esc_url($thumbnail_url); ?>);"></div>
                                <a class="cms-location-link"
                                   href="<?php echo get_term_link($key, 'jobboard-tax-locations'); ?>"></a>
                                <div class="cms-location-holder">
                                    <a class="cms-location-more"
                                       href="<?php echo get_term_link($key, 'jobboard-tax-locations'); ?>"><?php esc_html_e('View Jobs', 'wp-recruitment'); ?></a>
                                    <h2 class="cms-location-title"><?php echo get_term($key)->name; ?></h2>
                                </div>
                            </div>
                            <?php
                            if ($atts['country'] === '0'):
                                if($location_child == 'show') : ?>
                                    <div class="cms-child-locations">
                                        <div class="jb-location-flag" style="background-image: url(<?php echo esc_url(get_term_meta($key, '_flag', true)['url']) ?>);"></div>
                                        <div class="jb-location-holder">
                                            <h4><?php echo esc_html__('Jobs in', 'wp-recruitment') . ' ' . esc_attr(get_term($key, 'jobboard-tax-locations')->name) ?></h4>
                                            <div class="jb-locations-list">
                                                <?php
                                                $child_term = jb_get_locations($key, intval($location_child_limit), false);
                                                foreach ($child_term as $term_child) {
                                                    $link = get_term_link($term_child, 'jobboard-tax-locations');
                                                    ?>
                                                        <a href="<?php echo esc_url($link) ?>"
                                                           rel="tag"><?php echo esc_html(get_term($term_child)->name) ?></a>
                                                    <?php
                                                }
                                                ?>
                                                <a class="jb-location-child-more" href="<?php echo add_query_arg('country', $key, get_permalink(jb_get_option('page-locations', 0))) ?>" rel="tag"><?php echo esc_html__('More Locations', 'wp-recruitment') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                <?php endfor; ?>
                <?php endif; ?>
            </div>
            <?php if($view_all == 'show') : ?>
                <div class="view-all-locations">
                    <a class="btn"
                       href="<?php echo get_permalink(jb_get_option('page-locations', 0)) ?>"><?php echo esc_html__('View All Locations', 'wp-recruitment') ?></a>
                </div>
            <?php endif; ?>
        </div>
    <?php } else { ?>
        <div class="cms-locations-wrap">
            <div class="cms-locations-listing jobs-grid">
                <?php if (isset($locations)):
                    ?>
                    <?php for ($i = 0; $i < $limit; $i++):
                    if (!empty($locations[$i])) {
                        $key = $locations[$i];
                        $thumbnail_url = jb_get_location_thum($key);
                        ?>
                        <div class="col-lg-<?php echo esc_attr($col_lg_class); ?> col-md-<?php echo esc_attr($col_md_class); ?> col-sm-<?php echo esc_attr($col_sm_class); ?> col-xs-<?php echo esc_attr($col_xs_class); ?>">
                            <div class="cms-location-item">
                                <div class="cms-location-parent overlay-gradient">
                                    <div class="cms-location-image"
                                         style="background-image: url(<?php echo esc_url($thumbnail_url); ?>);"></div>
                                    <a class="cms-location-link"
                                       href="<?php echo get_term_link($key, 'jobboard-tax-locations'); ?>"></a>
                                    <div class="cms-location-holder">
                                        <a class="cms-location-more"
                                           href="<?php echo get_term_link($key, 'jobboard-tax-locations'); ?>"><?php esc_html_e('View Jobs', 'wp-recruitment'); ?></a>
                                        <h2 class="cms-location-title"><?php echo get_term($key)->name; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php endfor; ?>
                <?php endif; ?>
            </div>
            <?php if($view_all == 'show') : ?>
                <div class="view-all-locations">
                    <a class="btn"
                       href="<?php echo get_permalink(jb_get_option('page-locations', 0)) ?>"><?php echo esc_html__('View All Locations', 'wp-recruitment') ?></a>
                </div>
            <?php endif; ?>
        </div>
    <?php } ?>
<?php } ?>