<?php
extract(shortcode_atts(array(
    'title_pricing' => 'Title Pricing',
    'currency_type'  => '$',
    'values_price'  => '50',
    'pricing_time' => 'Month',
    'description_pricing'  => '',
    'description_item'  => '',
    'text_button'  => 'Get Started Now',
    'link_button'  => '#',
    'main_color'  => '',
    'popular_item'  => 'no-popular',
    'popular_text'  => '',
), $atts));
$uqid = uniqid();
$link = vc_build_link($link_button);
$a_href = '';
$a_target = '';
if ( strlen( $link['url'] ) > 0 ) {
    $a_href = $link['url'];
    $a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
}
$pricing_lists = (array) vc_param_group_parse_atts($description_pricing);
    
?>
<div id="cms-pricing-<?php echo esc_attr($uqid);?>" class="cms-pricing-wrapper">
    <?php if(!empty($main_color)) : ?>
        <style type="text/css" scoped>
            #cms-pricing-<?php echo esc_attr($uqid);?> .pricingbox .pricing-header {
                border-top-color: <?php echo esc_attr($main_color); ?>;
            }
            #cms-pricing-<?php echo esc_attr($uqid);?> .pricingbox .pricing-header .pricing-popular {
                background-color: <?php echo esc_attr($main_color); ?>;
            }
            #cms-pricing-<?php echo esc_attr($uqid);?> .pricingbox .pricing-price {
                color: <?php echo esc_attr($main_color); ?>;
            }
            #cms-pricing-<?php echo esc_attr($uqid);?> .pricingbox .pricing-button a {
                border-color: <?php echo esc_attr($main_color); ?>;
                background-color: <?php echo esc_attr($main_color); ?>;
            }
        </style>
    <?php endif; ?>

    <div class="pricingbox <?php echo esc_attr( $popular_item ); ?>">

        <div class="pricing-header">
            <?php if($popular_item == 'is-popular') : ?>
                <?php if(!empty($popular_text)) { ?>
                    <span class="pricing-popular"><?php echo esc_attr( $popular_text ); ?></span>
                <?php } else { ?>
                    <span class="pricing-popular"><?php echo esc_html__('Popular', 'wp-recruitment'); ?></span>
                <?php } ?>
            <?php endif; ?>
            <?php if(!empty($title_pricing)) : ?>
                <h3 class="pricing-title title-normal"><?php echo esc_attr($title_pricing);?></h3> 
            <?php endif;?>
            <div class="pricing-meta">
	            <span class="pricing-currency"><?php echo esc_attr($currency_type); ?></span>
                <span class="pricing-price"><?php echo esc_attr($values_price); ?></span>
                <span class="pricing-time"><?php echo ' / '.esc_attr($pricing_time); ?></span>
            </div>
        </div>
        
        <?php if(!empty($pricing_lists)) : ?>
            <div class="pricing-body">
                <ul>
    	        	<?php
    	        		foreach ($pricing_lists as $key => $value) { 
                        $description_item = isset($value['description_item']) ? $value['description_item'] : '';
                        ?>
    	                	<li><?php echo esc_html($description_item); ?></li>
    			    <?php } ?>
                </ul>
            </div>
        <?php endif;?>

        <?php if(!empty($text_button)) : ?>
            <div class="pricing-button">
                <a href="<?php echo esc_url($a_href);?>" target="<?php echo esc_attr( $a_target ); ?>" class="btn btn-default-outline btn-circle"><?php echo esc_attr($text_button);?></a>
            </div>
        <?php endif; ?>
    </div>
</div>