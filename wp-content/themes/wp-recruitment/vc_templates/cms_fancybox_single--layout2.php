<?php 
    $icon_name = "icon_" . $atts['icon_type'];
    $iconClass = isset($atts[$icon_name])?$atts[$icon_name]:'';
    $icon_bg_color = isset($atts['icon_bg_color']) ? $atts['icon_bg_color'] : '';
?>
<div class="cms-fancyboxes-wraper cms-fancyboxes-layout2  <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <div class="cms-fancybox-item">
        <?php 
            $image_url = '';
            if (!empty($atts['image'])) {
                $attachment_image = wp_get_attachment_image_src($atts['image'], 'full');
                $image_url = $attachment_image[0];
            }
        ?>
        
        <?php if($image_url):?>
            <div class="cms-fancybox-icon" style="background-color: <?php echo esc_attr($icon_bg_color); ?>">
                <img src="<?php echo esc_attr($image_url);?>" />
            </div>
        <?php else:?>
            <div class="cms-fancybox-icon" style="background-color: <?php echo esc_attr($icon_bg_color); ?>">
                <?php if( $iconClass ): ?>
                    <i class="<?php echo esc_attr($iconClass); ?>"></i>
                <?php endif; ?>
            </div>
        <?php endif;?>
        
        <div class="cms-fancybox-right">
            <?php if($atts['title_item']):?>
                <h3 class="cms-fancybox-title">
                    <?php echo apply_filters('the_title',$atts['title_item']);?>
                </h3>
            <?php endif;?>
            <?php if($atts['description_item']): ?>
                <div class="cms-fancybox-content">
                    <?php echo apply_filters('the_content',$atts['description_item']);?>
                </div>
            <?php endif; ?>

            <?php if($atts['button_text']!=''):?>
                <div class="cms-fancybox-button">
                    <?php
                    $class_btn = ($atts['button_type']=='button')?'btn btn-default btn-sm':'';
                    ?>
                    <a href="" class=""></a>
                    <div class="cms-button-wrapper style-btn-default">
                        <a class="<?php echo esc_attr($class_btn);?>" href="<?php echo esc_url($atts['button_link']);?>"><span class="btn-text"><?php echo esc_attr($atts['button_text']);?></span></a>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>