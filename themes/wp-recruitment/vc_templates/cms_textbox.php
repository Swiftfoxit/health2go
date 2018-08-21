<?php
extract(shortcode_atts(array(                                   
    'title' => '',         
    'image' => '',         
    'tb_content' => '',         
    'link' => '',         
    'layout' => 'layout1',         
    'button_text' => '',         
    'box_bg_color' => '',         
    'box_padding' => '',         
    'content_padding' => '',         
    'icon' => '',         
), $atts));

$icon_url = '';
if (!empty($icon)) {
    $attachment_icon = wp_get_attachment_image_src($icon, 'full');
    $icon_url = $attachment_icon[0];
}

$image_url = '';
$image_add = '';
if (!empty($image)) {
    $attachment_image = wp_get_attachment_image_src($image, 'full');
    $image_url = $attachment_image[0];
    $image_add = 'image-add';
}

$tp_link = vc_build_link($link);
$a_href = '';
$a_target = '';
if ( strlen( $tp_link['url'] ) > 0 ) {
    $a_href = $tp_link['url'];
    $a_target = strlen( $tp_link['target'] ) > 0 ? $tp_link['target'] : '_self';
}

?>
<?php if($layout == 'layout1') { ?>
    <div class="cms-textbox clearfix">
    	<a href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>"></a>
    	<?php if($image_url) : ?>
            <div class="cms-textbox-image" style="background-image: url(<?php echo esc_url($image_url); ?>);"></div>
    	<?php endif; ?>
    	<div class="cms-textbox-content col-same">
    		<h3><?php echo esc_attr( $title ); ?></h3>
    		<p><?php echo wp_kses_post( $tb_content ); ?></p>
    	</div>
    </div>
<?php } else { ?>
    <div class="cms-job-cv cms-job-textbox col-same <?php echo esc_attr( $image_add ); ?>" style="padding:<?php echo esc_attr( $box_padding ); ?>; background-image: url(<?php echo esc_url($image_url); ?>);">
        <div class="cms-job-cv-overlay" style="background-color:<?php echo esc_attr($box_bg_color); ?>"></div>
        <?php if($icon_url) : ?>
            <div class="cms-job-cv-icon">
                <img src="<?php echo esc_url($icon_url); ?>" alt="" />
            </div>
        <?php endif; ?>
        <div class="cms-job-cv-holder">
            <h3><?php echo esc_attr( $title ); ?></h3>
            <div class="cms-job-cv-content" style="margin:<?php echo esc_attr( $content_padding ); ?>"><?php echo wp_kses_post( $tb_content ); ?></div>
            <a style="color:<?php echo esc_attr($box_bg_color); ?>" class="btn" href="<?php echo esc_url($a_href);?>" target="<?php  echo esc_attr($a_target); ?>" ><?php echo esc_attr( $button_text ); ?></a>
        </div>
    </div>
<?php } ?>