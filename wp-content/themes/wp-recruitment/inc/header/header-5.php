<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */
global $opt_theme_options;
$j_register = jb_get_option('page-register');
$j_register_url = get_permalink($j_register);

$j_dashboard = jb_get_option('page-dashboard');
$j_dashboard_url = get_permalink($j_dashboard);

?>
<div id="cshero-header-inner" class="header-1 custom-header5">
    <div id="cshero-header-wrapper">
        <div id="cshero-header" class="<?php recruitment_header_class('cshero-main-header'); ?>">
            <div class="no-container">
                <div class="row cms-flex">
                    <div id="cshero-header-logo" class="cms-flex-col">
                        <?php recruitment_header_logo(); ?>
                    </div><!-- #site-logo -->
                    <div id="cshero-header-navigation" class="cshero-header-navigation cms-flex-col">
                        <?php if (class_exists('JobBoard')) { ?>
                            <div class="cshero-header-navigation-top hidden-sm hidden-xs">
                                <?php
                                if (function_exists('jb_get_lastest_job')):
                                    ?>
                                    <div class="job-recent hidden-md">
                                        <span><?php echo esc_html__('Recent Jobs:', 'wp-recruitment'); ?></span>
                                        <ul>
                                            <?php

                                            foreach (jb_get_lastest_job() as $post_new):
                                                ?>
                                                <li>
                                                    <a href="<?php echo get_permalink($post_new->ID) ?>"><?php echo esc_attr($post_new->post_title) ?></a>
                                                </li>
                                            <?php
                                            endforeach;
                                            ?>
                                        </ul>
                                    </div>
                                <?php
                                endif;
                                ?>
                                <div class="job-user-meta">
                                    <?php if (!is_user_logged_in()) { ?>
                                        <a href="<?php echo esc_url($j_register_url); ?>"><?php echo esc_html__('Create Account', 'wp-recruitment'); ?></a>
                                        <a href="<?php echo esc_url($j_dashboard_url); ?>"><?php echo esc_html__('Candidate Login', 'wp-recruitment'); ?></a>
                                        <a href="<?php echo esc_url($j_dashboard_url); ?>"><?php echo esc_html__('Employer Login', 'wp-recruitment'); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="cshero-header-navigation-inner clearfix">
                            <div id="cshero-header-navigation-primary">

                                <nav id="site-navigation" class="main-navigation cms-flex-col">
                                    <?php recruitment_header_navigation_primary(); ?>
                                </nav><!-- #site-navigation -->
                                <?php if (is_user_logged_in()) { ?>
                                    <div class="cshero-navigation-right">
                                        <?php if(is_active_sidebar('nav')): ?>
                                            <?php dynamic_sidebar('nav'); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="cshero-navigation-right cms-flex-col hidden-sm hidden-xs">
                                        <form role="search" method="get" class="jb-job-search hidden-md"
                                              action="<?php echo esc_url(home_url('/')); ?>">
                                            <div>
                                                <input type="search" id="jb-job-search-field" class="search-field"
                                                       placeholder="<?php echo esc_attr_x('Search for Jobs', 'placeholder', 'wp-recruitment'); ?>"
                                                       value="<?php echo get_search_query(); ?>" name="s"
                                                       title="<?php echo esc_attr_x('Search for:', 'label', 'wp-recruitment'); ?>"/>
                                                <input type="submit"
                                                       value="<?php echo esc_attr_x('Search', 'submit button', 'wp-recruitment'); ?>"/>
                                                <input type="hidden" name="post_type" value="jobboard-post-jobs"/>
                                            </div>
                                        </form>
                                        <?php if (!empty($opt_theme_options['view_job_text'])) : ?>
                                            <div class="view-job">
                                                <a class="btn"
                                                   href="<?php echo esc_url($opt_theme_options['view_job_url']); ?>"><?php echo esc_attr($opt_theme_options['view_job_text']); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                 <?php } ?>
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