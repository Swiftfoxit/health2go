<?php
extract(shortcode_atts(array(                                   
    'gettouch_content' => '',              
    'gettouch_icon' => '',          
), $atts));

$gettouch_icon_url = '';
if (!empty($atts['gettouch_icon'])) {
    $gettouch_icon = wp_get_attachment_image_src($atts['gettouch_icon'], 'full');
    $gettouch_icon_url = $gettouch_icon[0];
}

?>
<div class="cms-gettouch clearfix">
	<?php if(!empty($gettouch_icon_url)) : ?>
		<div class="cms-gettouch-icon">
			<img src="<?php echo esc_url($gettouch_icon_url); ?>" alt="" />
		</div>
	<?php endif; ?>
	<div class="cms-gettouch-content">
		<?php echo wp_kses_post( $gettouch_content ); ?>
	</div>
</div>