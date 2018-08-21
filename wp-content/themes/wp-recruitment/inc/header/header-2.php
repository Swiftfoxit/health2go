<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */

?>
<div id="cshero-header-inner" class="header-2">
    <div id="cshero-header-wrapper">
        <div id="cshero-header" class="<?php recruitment_header_class('cshero-main-header'); ?>">
            <div class="no-container">
                <div class="row">
                    <div id="cshero-header-logo">

                        <?php recruitment_header_logo(); ?>

                    </div><!-- #site-logo -->

                    <div id="cshero-header-navigation" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 cshero-header-navigation">
                        <div class="cshero-header-navigation-inner clearfix">
                            <div class="main-navigation">
                                <div id="cshero-header-navigation-left">
                                    <nav id="site-navigation-left">

                                        <?php recruitment_header_navigation_left(); ?>

                                    </nav><!-- #site-navigation -->
                                </div>

                                <div id="cshero-header-navigation-right">
                                    <nav id="site-navigation-right">

                                        <?php recruitment_header_navigation_right(); ?>

                                    </nav><!-- #site-navigation -->
                                </div>
                            </div>
                        </div>
                        <div class="cshero-navigation-right">
                            <?php if(is_active_sidebar('nav-center')): ?>
                                <?php dynamic_sidebar('nav-center'); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="cshero-menu-mobile">
                        <i class="open-menu zmdi zmdi-menu"></i>
                        <i class="open-user zmdi zmdi-account"></i>
                        <i class="open-cart-job zmdi zmdi-shopping-cart"></i>
                    </div><!-- #menu-mobile -->
                </div>
            </div>
        </div><!-- #site-navigation -->
    </div>
</div>