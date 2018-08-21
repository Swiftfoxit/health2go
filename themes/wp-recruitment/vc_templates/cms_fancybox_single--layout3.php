<?php 
    $box_bg_image = '';
    if (!empty($atts['bg_image'])) {
        $attachment_image = wp_get_attachment_image_src($atts['bg_image'], 'full');
        $box_bg_image = $attachment_image[0];
    }
    $style = isset($atts['style']) ? $atts['style'] : 'style1';
?>
<div class="cms-fancyboxes-wraper cms-fancyboxes-layout3 <?php echo esc_attr($style); ?> <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>" style="background-image: url(<?php echo esc_url($box_bg_image); ?>); ">
    <div class="cms-fancybox-item">
        
        <?php if($atts['title_item']):?>
            <h3 class="cms-fancybox-title">
                <?php echo apply_filters('the_title',$atts['title_item']);?>
                <a href="<?php echo esc_url($atts['button_link']);?>"><i class="fa fa-chevron-right"></i></a>
            </h3>
        <?php endif;?>
        
    </div>
</div>