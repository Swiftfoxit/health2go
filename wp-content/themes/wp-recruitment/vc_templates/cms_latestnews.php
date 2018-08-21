<?php
extract(shortcode_atts(array(
    'title' => '',
    'posts_limit' => '7',
    'post_featured' => '',
    'view_all_text' => '',
    'view_all_url' => '',
), $atts));
$html_id = cmsHtmlID('cms-latest-news');
$atts['html_id'] = $html_id;

$link = vc_build_link($view_all_url);
$a_href = '';
if ( strlen( $link['url'] ) > 0 ) {
    $a_href = $link['url'];
}
?>
<div id="<?php echo esc_attr($atts['html_id']);?>" class="cms-latest-news">
    <div class="cms-latestnews-inner">
        <?php if(!empty($title)) : ?>
            <div class="cms-latestnews-heading">
                <h3>
                    <?php echo wp_kses_post($title); ?>
                </h3>
            </div>
        <?php endif; ?>
        <?php
            $sticky = '';
            if($post_featured == '1') {
                $sticky = get_option( 'sticky_posts' );
            } else {
                $sticky = '';
            }
            $args = array(
                'posts_per_page' => intval($posts_limit),
                'post_type' => 'post',
                'post_status' => 'publish',
                'post__in'  => $sticky,
                'orderby' => 'date',
                'order' => 'DESC',
            );
            $posts_new = get_posts($args);
            if (!empty($posts_new)) { ?>
                <div class="cms-latestnews-content clearfix">
                    <?php foreach ($posts_new as $post): ?>
                        <div class="cms-latestnews-item box-effect-item">
                            <?php if(has_post_thumbnail($post->ID)) {
                                $thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'full') );
                                $class = 'has-thumbnail';
                            } else {
                                $thumbnail = '' .get_template_directory_uri(). '/assets/images/no-image.jpg';
                                $class = 'no-thumbnail';
                            } ?>
                            <div class="cms-latestnews-item-inner">
                                <div class="cms-latestnews-image overlay-gradient" style="background-image: url(<?php echo esc_url($thumbnail); ?>)"></div>
                                <a class="cms-latestnews-more" title="<?php echo get_the_title($post->ID); ?>" href="<?php echo get_permalink($post->ID); ?>"></a>
                                <div class="cms-latestnews-holder">
                                    <div class="cms-latestnews-author">
                                        <?php echo esc_html__('by ', 'wp-recruitment'); ?></span><?php the_author_posts_link(); ?>
                                    </div>
                                    <h3 class="cms-latestnews-title">
                                        <?php echo get_the_title($post->ID); ?>
                                    </h3>
                                    <div class="cms-latestnews-date"><?php echo esc_html__('Posted ', 'wp-recruitment'); ?><?php echo get_the_date(); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php
            } else { ?>
                <span class="notfound"><?php echo esc_html__('No post found!', 'wp-recruitment'); ?></span>
            <?php } ?>
        <?php if(!empty($view_all_text)) : ?>
            <div class="cms-latestnews-viewall text-center">
                <a class="btn btn-modern" href="<?php echo esc_url($a_href);?>"><?php echo esc_attr($view_all_text); ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>