<?php
/**
 * @Template: cms-jb-resources.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 20-Dec-17
 */

$jr_jobs = jb_get_option('page-resources');
$jr_url_jobs = get_permalink($jr_jobs);

?>
<div class="jobboard-resources-wrap jobboard-resources-shortcode">
    <?php if(!empty($atts['title'])) : ?>
        <div class="jr-heading">
            <h3><?php echo esc_attr( $atts['title'] ); ?></h3>
        </div>
    <?php endif; ?>
    <div class="jr-items">
        <?php foreach($atts['posts'] as $jr) :?>
            <?php
            $file_id = get_post_meta($jr->ID, '_resources_file', true);
            $file_size = filesize( get_attached_file( $file_id ));
            $file_size_layout = '';
            if($file_size >= 0 && $file_size < 1024){
                $file_size_layout = $file_size.'B';
            }
            if($file_size >= 1024 && $file_size < 1048576){
                $file_size_layout = number_format($file_size/1024,2).'KB';
            }

            if($file_size >= 1073741824){
                $file_size_layout = number_format($file_size/1024,2).'MB';
            }
            $array_file = explode('.', get_attached_file($file_id));
            $extension = end($array_file);

            ?>
            <div class="jr-item">
                <div class="jr-item-inner">
                    <div class="jr-holder">
                        <h3 class="jr-title"><i class="zmdi zmdi-file-text"></i><?php echo esc_attr($jr->post_title) ?></h3>
                        <span class="jr-file-size">.<?php echo esc_attr($extension)?> (<?php echo esc_attr($file_size_layout) ?>)</span>
                        <div class="jr-content"><?php echo esc_attr($jr->post_content) ?></div>
                    </div>
                    <form class="jr-action">
                        <input type="text" placeholder="<?php echo esc_html__('Email Address', 'wp-recruitment') ?>">
                        <button class="jr-button-download" data-id="<?php echo $jr->ID ?>"><?php echo esc_html__('Free Download', 'wp-recruitment')?></button>
                        <?php wp_nonce_field('jr_download', '_wp_nonce_jr_download'); ?>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="jr-view-all">
        <a class="btn" href="<?php echo esc_url($jr_url_jobs); ?>"><?php echo esc_html__('View All Resources', 'wp-recruitment')?></a>
    </div>
</div>