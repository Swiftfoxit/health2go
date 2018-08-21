<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */
global $opt_theme_options;
?>
<footer id="colophon" class="site-footer cms-footer1 <?php if (isset($opt_theme_options['footer_custom_width_column']) && $opt_theme_options['footer_custom_width_column']) { echo 'width_column_change'; } ?>">
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
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center-xs">
                    <div class="logo-footer">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php recruitment_logo_footer(); ?>" alt="Logo Footer" /></a>
                    </div>
                    <div class="cms-copyright">
                        <?php if(!empty($opt_theme_options['cms-copyright'])) {
                            echo wp_kses_post($opt_theme_options['cms-copyright']); 
                        } else { ?>
                            <div>© <?php echo date("Y"); ?>. Theme by <a href="https://themeforest.net/user/cmssuperheroes/portfolio"><?php echo esc_html('CMSSuperheroes', 'wp-recruitment'); ?></a></div>
                        <?php } ?>
                    </div>  
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right text-center-xs">
                    <div class="cms-footer-backtop">
                        <?php recruitment_back_to_top(); ?>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</footer><!-- .site-footer -->