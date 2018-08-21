<?php
extract(shortcode_atts(array(                                   
    'process_description1' => '',              
    'process_description2' => '',       
    'process_description3' => '',             
    'icon_fontawesome1' => '',       
    'icon_fontawesome2' => '',       
    'icon_fontawesome3' => '',             
    'process_icon_image1' => '',       
    'process_icon_image2' => '',       
    'process_icon_image3' => '',          
), $atts));

$image_url1 = '';
if (!empty($atts['process_icon_image1'])) {
    $attachment_image1 = wp_get_attachment_image_src($atts['process_icon_image1'], 'full');
    $image_url1 = $attachment_image1[0];
}
$image_url2 = '';
if (!empty($atts['process_icon_image2'])) {
    $attachment_image2 = wp_get_attachment_image_src($atts['process_icon_image2'], 'full');
    $image_url2 = $attachment_image2[0];
}
$image_url3 = '';
if (!empty($atts['process_icon_image3'])) {
    $attachment_image3 = wp_get_attachment_image_src($atts['process_icon_image3'], 'full');
    $image_url3 = $attachment_image3[0];
}

?>
<div class="cms-process cms-process-layout1 clearfix">
	<ul class="cms-process-list">
		<?php if(!empty($process_description1)) { ?>
			<li class="cms-process-item clearfix">
	        	<div class="cms-process-icon">
	        		<?php if($image_url1):?>
	        			<img src="<?php echo esc_url($image_url1);?>" alt="" />
	        		<?php else:?>
	        			<i class="<?php echo esc_attr($icon_fontawesome1);?>"></i>
	        		<?php endif;?>
	        	</div>
	        	<div class="cms-process-content">
	        		<?php echo wp_kses_post($process_description1); ?>
	        	</div>
	        </li>
        <?php } if(!empty($process_description2)) { ?>
	        <li class="cms-process-item clearfix">
	        	<div class="cms-process-icon">
	        		<?php if($image_url2):?>
	        			<img src="<?php echo esc_url($image_url2);?>" alt="" />
	        		<?php else:?>
	        			<i class="<?php echo esc_attr($icon_fontawesome2);?>"></i>
	        		<?php endif;?>
	        	</div>
	        	<div class="cms-process-content">
	        		<?php echo wp_kses_post($process_description2); ?>
	        	</div>
	        </li>
	    <?php } if(!empty($process_description3)) { ?>
	        <li class="cms-process-item clearfix">
	        	<div class="cms-process-icon">
	        		<?php if($image_url3):?>
	        			<img src="<?php echo esc_url($image_url3);?>" alt="" />
	        		<?php else:?>
	        			<i class="<?php echo esc_attr($icon_fontawesome3);?>"></i>
	        		<?php endif;?>
	        	</div>
	        	<div class="cms-process-content">
	        		<?php echo wp_kses_post($process_description3); ?>
	        	</div>
	        </li>
	    <?php } ?>
	</ul>
</div>