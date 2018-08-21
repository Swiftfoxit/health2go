<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */
global $opt_theme_options;
?>
<footer id="colophon" class="site-footer cms-footer2 <?php if (isset($opt_theme_options['footer_custom_width_column']) && $opt_theme_options['footer_custom_width_column']) { echo 'width_column_change'; } ?>">
    <?php if ( is_active_sidebar( 'sidebar-footer-top-1' ) ) : ?>
        <div id="cms-footer-top">
            <div class="container">
                <div class="row">
                    <?php recruitment_footer_top(); ?>  
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div id="cms-footer-bottom">
        <div class="no-container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 text-center-xs text-center-sm">
                    <div class="cms-copyright">
                        <?php if(!empty($opt_theme_options['payment_logo']) && !empty($opt_theme_options['payment_logo'][0]['url'])) { ?>
                            <span class="cms-footer-payment">
                                <?php 
                                    $result = count($opt_theme_options['payment_logo']);
                                    for($i=0;$i<$result;$i++){ ?>
                                        <a href="<?php echo esc_url( $opt_theme_options['payment_logo'][$i]['url'] ); ?>">
                                            <img src="<?php echo esc_url( $opt_theme_options['payment_logo'][$i]['image'] ); ?>" alt="<?php echo esc_attr( $opt_theme_options['payment_logo'][$i]['title'] ); ?>" />
                                        </a>
                                    <?php }
                                ?>
                            </span>
                        <?php } ?>
                        <?php if(!empty($opt_theme_options['cms-copyright'])) {
                            echo wp_kses_post($opt_theme_options['cms-copyright']); 
                        } else { ?>
                            <div>© <?php echo date("Y"); ?>. Theme by <a href="https://themeforest.net/user/cmssuperheroes/portfolio"><?php echo esc_html('CMSSuperheroes', 'wp-recruitment'); ?></a></div>
                        <?php } ?>
                    </div>  
                </div>

                <?php if ( is_active_sidebar( 'footer_bottom_right' ) ) : ?>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 text-right text-center-xs text-center-sm">
                        <div class="cms-footer-bottom-col">
                            <?php dynamic_sidebar( 'footer_bottom_right' ); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</footer><!-- .site-footer -->