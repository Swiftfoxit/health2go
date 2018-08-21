<?php
$col_lg = (isset($atts['col_lg'])) ? $atts['col_lg'] : '1';
$col_md = (isset($atts['col_md'])) ? $atts['col_md'] : '2';
$col_sm = (isset($atts['col_sm'])) ? $atts['col_sm'] : '3';
$col_xs = (isset($atts['col_xs'])) ? $atts['col_xs'] : '4';

$margin = (isset($atts['margin'])) ? $atts['margin'] : '30';
$loop = (isset($atts['loop'])) ? $atts['loop'] : '0';
$autoplay = (isset($atts['autoplay'])) ? $atts['autoplay'] : '0';
$nav = (isset($atts['nav'])) ? $atts['nav'] : '1';

$client_items = (array) vc_param_group_parse_atts($atts['client']);
?>

<div class="cms-client-wrapper jobs-carousel" data-nav="<?php echo esc_attr($nav); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-loop="<?php echo esc_attr($loop); ?>" data-item-xs="<?php echo esc_attr($col_xs); ?>" data-item-sm="<?php echo esc_attr($col_sm); ?>" data-item-md="<?php echo esc_attr($col_md); ?>" data-item-lg="<?php echo esc_attr($col_lg); ?>" data-margin="<?php echo esc_attr($margin); ?>">
    <?php if(!empty($client_items)) : ?>
        <?php
            foreach ($client_items as $key => $value) { 
            $image_url = '';
            if (!empty($value['image'])) {
                $attachment_image = wp_get_attachment_image_src($value['image'], 'full');
                $image_url = $attachment_image[0];
            }
            $link = vc_build_link($value['link']);
            $a_href = '';
            $a_target = '';
            if ( strlen( $link['url'] ) > 0 ) {
                $a_href = $link['url'];
                $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
            }
            ?>
                <div class="cms-client-item"><a href="<?php echo esc_url($a_href);?>" target="<?php echo esc_attr( $a_target ); ?>"><img src="<?php echo esc_url($image_url); ?>" /></a></div>
        <?php } ?>
    <?php endif;?>
</div>