<?php 
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name])?$atts[$icon_name]:'';
    $image_icon_url = '';
    if (!empty($atts['image_icon'])) {
        $attachment_image = wp_get_attachment_image_src($atts['image_icon'], 'full');
        $image_icon_url = $attachment_image[0];
    }
?>
<div class="cms-progress-wraper cms-progress-layout1 custom-layout3 <?php if($iconClass || $image_icon_url){ echo 'icon-active'; } ?> <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <?php
        $item_title     = $atts['item_title'];
        $show_value     = ($atts['show_value']=='true')?true:false;
        $value          = $atts['value'];
        $value_suffix   = $atts['value_suffix'];
        $bg_color       = $atts['bg_color'];
        $color          = $atts['color'];
        ?>
        <div class="cms-progress-inner">
            <?php if($image_icon_url):?>
                <div class="cms-progress-icon">
                    <img src="<?php echo esc_attr($image_icon_url);?>" alt="" />
                </div>
            <?php elseif($iconClass):?>
                <div class="cms-progress-icon">
                    <i class="<?php echo esc_attr($iconClass);?>"></i>
                </div> 
            <?php endif;?>

            <div class="cms-progress-content">
                <?php if($item_title):?>
                    <h3 class="cms-progress-title ft-nvsb">
                        <?php echo apply_filters('the_title',$item_title);?>
                    </h3>
                <?php endif;?>
                <div class="cms-progress progress">
                    <div id="item-<?php echo esc_attr($atts['html_id']); ?>" class="progress-bar" role="progressbar" data-valuetransitiongoal="<?php echo esc_attr($value); ?>" style="background-color:<?php echo esc_attr($color);?>;">
                        <span class="progress-couter">
                            <?php if($show_value): ?>
                                <?php echo esc_attr($value.$value_suffix);?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
</div>