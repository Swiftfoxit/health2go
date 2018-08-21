<?php 
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name])?$atts[$icon_name]:'';
    $icon_color = isset($atts['icon_color']) ? $atts['icon_color'] : '';
    $digit_color = isset($atts['digit_color']) ? $atts['digit_color'] : '';
    $title_color = isset($atts['title_color']) ? $atts['title_color'] : '';

    $title_fontsize = isset($atts['title_fontsize']) ? $atts['title_fontsize'] : '';
    $title_margin = isset($atts['title_margin']) ? $atts['title_margin'] : '';
    $digit_fontsize = isset($atts['digit_fontsize']) ? $atts['digit_fontsize'] : '';

    $use_grouping = isset($atts['use_grouping']) ? $atts['use_grouping'] : '0';
    $separator = isset($atts['separator']) ? $atts['separator'] : '';
    $suffix = isset($atts['suffix']) ? $atts['suffix'] : '';
?>
<div class="cms-counter-wraper cms-counter-layout2 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">

    <div class="cms-counter-body clearfix">
        <?php 
            $image_icon_url = '';
            if (!empty($atts['image_icon'])) {
                $attachment_image = wp_get_attachment_image_src($atts['image_icon'], 'full');
                $image_icon_url = $attachment_image[0];
            }
        ?>

        <?php if($image_icon_url):?>
            <div class="cms-counter-image-icon">
                <img src="<?php echo esc_attr($image_icon_url);?>" alt="" />
            </div>
        <?php else:?>
            <?php if(!empty($iconClass)) { ?>
                <div class="cms-counter-icon"><i class="<?php echo esc_attr($iconClass); ?>" style="color: <?php echo esc_attr($icon_color)?>;"></i></div>
            <?php } ?>
        <?php endif;?>

        <div class="cms-counter-content">
            <div style="font-size: <?php echo esc_attr($digit_fontsize); ?>; color: <?php echo esc_attr($digit_color)?>" id="counter_<?php echo esc_attr($atts['html_id']);?>" class="cms-counter ft-lr <?php echo esc_attr(strtolower($atts['type']));?>" data-type="<?php echo esc_attr(strtolower($atts['type']));?>" data-digit="<?php echo esc_attr($atts['digit']);?>" data-grouping="<?php echo esc_attr($use_grouping); ?>" data-separator="<?php echo esc_attr($separator); ?>" data-suffix="<?php echo esc_attr($suffix);?>">
            </div>
            <?php if($atts['c_title']):?>
                <span class="cms-counter-title" style="font-size: <?php echo esc_attr($title_fontsize); ?>; margin: <?php echo esc_attr($title_margin); ?>; color: <?php echo esc_attr($title_color)?>">
                    <?php echo apply_filters('the_title',$atts['c_title']);?>
                </span>
            <?php endif;?>
        </div>
    </div>
</div>