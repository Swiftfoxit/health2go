<?php
extract(shortcode_atts(array(         
    'hd_title' => '',       
    'title_color' => '',            
    'hd_title_font_size' => '',            
    'content_align' => 'left',              
    'hd_line' => 'top',              
    'hd_description' => '',              
), $atts));

$html_id = cmsHtmlID('cms-heading');
$atts['html_id'] = $html_id;
?>
<div id="<?php echo esc_attr($atts['html_id']);?>" class="cms-heading-wrapper cms-heading-default line-<?php echo esc_attr($hd_line); ?> text-<?php echo esc_attr($content_align); ?>">
    <div class="cms-heading-inner">
        <div class="cms-heading-content">
            <h3 class="title" style="font-size:<?php echo esc_attr($hd_title_font_size); ?>;color:<?php echo esc_attr($title_color); ?>;"><?php echo wp_kses_post($hd_title); ?></h3>
            <div class="desc"><?php echo wp_kses_post($hd_description); ?></div>
        </div>
    </div>
</div>