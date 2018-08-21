<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */
global $opt_theme_options;

?>
<div id="cshero-header-inner" class="header-1 custom-header4 header-top">
    <div id="cshero-header-wrapper">
        <div id="cshero-header" class="<?php recruitment_header_class('cshero-main-header'); ?>">
            <div class="no-container">
                <div class="row">
                    <div id="cshero-header-logo">
                        <?php recruitment_header_logo(); ?>
                    </div><!-- #site-logo -->
                    <div id="cshero-header-navigation" class="cshero-header-navigation">
                        <div class="cshero-header-navigation-inner clearfix">
                            <div id="cshero-header-navigation-primary">

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