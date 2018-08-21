<?php
extract(shortcode_atts(array(         
    'title' => '',       
    'description' => '',            
    'box_bg_color' => '',                          
), $atts));
?>
<div class="cms-job-cv col-same" style="background-color:<?php echo esc_attr($box_bg_color); ?>">
    <h3><?php echo esc_attr( $title ); ?></h3>
    <div class="cms-job-cv-content"><?php echo wp_kses_post( $description ); ?></div>
    <a style="color:<?php echo esc_attr($box_bg_color); ?>" class="btn" href="<?php echo esc_url(home_url()); ?>/dashboard/profile/"><?php echo esc_html__('Upload your CV', 'wp-recruitment'); ?></a>
</div>