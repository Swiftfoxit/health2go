<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */
global $opt_theme_options;

?>
<div id="cshero-header-inner" class="header-1 header-top">
    <?php if ( class_exists( 'RC_Framework' ) ) { ?>
        <div id="cshero-header-top" class="hidden-xs header-top-dark">
            <div class="no-container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 text-center-sm text-center-xs">
                        <ul class="contact-top">
                            <?php if(!empty($opt_theme_options['top_bar_phone'])) { ?>
                                <li><a href="tel:<?php echo esc_attr($opt_theme_options['top_bar_phone']); ?>"><i class="fa fa-phone-square"></i><?php echo esc_attr($opt_theme_options['top_bar_phone']); ?></a></li>
                            <?php } ?>
                            <?php if(!empty($opt_theme_options['top_bar_email'])) { ?>
                                <li><a href="mailto:<?php echo esc_attr($opt_theme_options['top_bar_email']); ?>"><i class="fa fa-envelope"></i><?php echo esc_attr($opt_theme_options['top_bar_email']); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-center-sm text-center-xs text-right">
                        <?php recruitment_top_social(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div id="cshero-header-wrapper">
        <div id="cshero-header" class="<?php recruitment_header_class('cshero-main-header'); ?>">
            <div class="no-container">
                <div class="row">
                    <div id="cshero-header-logo">
                        <?php recruitment_header_logo(); ?>
                    </div><!-- #site-logo -->
                    <div id="cshero-header-navigation">
                        <div class="cshero-header-navigation-inner clearfix">
                            <div id="cshero-header-navigation-primary" class="cshero-header-navigation">

                                <div class="cshero-navigation-right">
                                    <?php if(is_active_sidebar('nav')): ?>
                                        <?php dynamic_sidebar('nav'); ?>
                                    <?php endif; ?>
                                </div>

                                <nav id="site-navigation" class="main-navigation">
                                    <?php recruitment_header_navigation_primary(); ?>
                                </nav><!-- #site-navigation -->

                            </div>
                        </div>
                    </div>
                    <div id="cshero-menu-mobile">
                        <i class="open-menu zmdi zmdi-menu"></i>
                        <i class="open-user zmdi zmdi-account"></i>
                        <i class="open-cart-job zmdi zmdi-shopping-cart"></i>
                    </div><!-- #menu-mobile -->
                </div>
            </div>
        </div>
    </div>
</div>