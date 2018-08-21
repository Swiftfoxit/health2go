<?php

function recruitment_job_sidebar()
{
    if ((is_jb_jobs() && (is_active_sidebar('job') || is_active_sidebar('job2'))) || (is_jb_account_listing() && (is_active_sidebar('jobboard-sidebar-employers') || is_active_sidebar('jobboard-sidebar-candidates') || is_active_sidebar('job')))) {
        ?>
        <div id="sidebar" class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
            <div id="widget-area" class="widget-area sidebar-job" role="complementary">
                <?php if (is_active_sidebar('job3')) { ?>
                    <div class="sidebar-job-default">
                        <?php dynamic_sidebar('job3'); ?>
                    </div>
                <?php } ?>

                <?php if (is_jb_jobs() && is_active_sidebar('job2')) { ?>
                    <div class="sidebar-job-border">
                        <?php dynamic_sidebar('job2'); ?>
                    </div>
                <?php } elseif (is_jb_employer_listing() && is_active_sidebar('jobboard-sidebar-employers')) { ?>
                    <div class="sidebar-job-border">
                        <?php dynamic_sidebar('jobboard-sidebar-employers'); ?>
                    </div>
                <?php } elseif (is_jb_candidate_listing() && is_active_sidebar('jobboard-sidebar-candidates')) { ?>
                    <div class="sidebar-job-border">
                        <?php dynamic_sidebar('jobboard-sidebar-candidates'); ?>
                    </div>
                <?php } ?>

                <?php if (is_active_sidebar('job')) { ?>
                    <div class="sidebar-job-default">
                        <?php dynamic_sidebar('job'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}


function recruitment_job_sidebar_class()
{
    global $opt_theme_options;
    $layout = '';
    if (!empty($opt_theme_options['job_layout']) && $opt_theme_options['job_layout'] == 'list') {
        $layout = 'jb-layout-list';
    } else {
        $layout = 'jb-layout-grid';
    }
    $layout = !empty($_GET['layout']) ? 'jb-layout-' . $_GET['layout'] : $layout;
    if ((is_jb_jobs() && (is_active_sidebar('job') || is_active_sidebar('job2'))) || (is_jb_account_listing() && (is_active_sidebar('jobboard-sidebar-employers') || is_active_sidebar('jobboard-sidebar-candidates') || is_active_sidebar('job')))) {
        echo '<div id="content" class="col-lg-9 col-md-8 col-sm-7 col-xs-12 sidebar-active ' . esc_attr($layout) . '">';
    } else {
        echo '<div id="content" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 job-full-width ' . esc_attr($layout) . '">';
    }
}

function recruitment_job_result_count()
{
    global $opt_theme_options;
    $layout = $opt_theme_options['job_layout'];
    $layout = !empty($_GET['layout']) ? $_GET['layout'] : $layout;
    $page_jobs = jb_get_option('page-jobs');
    $link_back_all_jobs = get_permalink($page_jobs);
    $count_job = $GLOBALS['wp_query']->found_posts;
    $count_layout = $count_job > 1 ? $count_job . ' ' . esc_html__('Results Found', 'wp-recruitment') : $count_job . ' ' . esc_html__('Result Found', 'wp-recruitment');
    if ($layout === 'grid' && empty($_REQUEST['employer_id'])) {
        ?>
        <div class="jb-grid-head">
            <a href="<?php echo esc_url($link_back_all_jobs) ?>"><?php echo esc_html__('Back to All Jobs', 'wp-recruitment') ?></a>
            <h3><?php echo esc_attr($count_layout) ?></h3>
        </div>
        <?php
    }
    if (!empty($_REQUEST['employer_id'])) {
        $employer = get_user_by('ID', $_REQUEST['employer_id']);
        $name_emp = !empty($employer->display_name) ? $employer->display_name : '';
        ?>
        <div class="jb-grid-head">
            <a href="<?php echo esc_url($link_back_all_jobs) ?>"><?php echo esc_html__('Back to All Jobs', 'wp-recruitment') ?></a>
            <h3><?php echo esc_html__('All Jobs by', 'wp-recruitment') . ' ' . esc_attr($name_emp) ?></h3>
        </div>
        <?php
    }
}

function jb_template_catalog_layout()
{
    global $opt_theme_options;

    $layout = '';
    if (!empty($opt_theme_options['job_layout']) && $opt_theme_options['job_layout'] == 'list') {
        $layout = 'list';
    } else {
        $layout = 'grid';
    }

    $value = !empty($_GET['layout']) ? $_GET['layout'] : $layout;

    echo '<span class="jb-layout-grid jb-layout"><input id="jb-layout-grid" type="radio" name="layout" value="grid" ' . checked($value, 'grid', false) . '/><i class="zmdi zmdi-view-module"></i><label for="jb-layout-grid">' . esc_html__('Grid', 'wp-recruitment') . '</label></span>';
    echo '<span class="jb-layout-list jb-layout"><input id="jb-layout-list" type="radio" name="layout" value="list" ' . checked($value, 'list', false) . '/><i class="zmdi zmdi-view-list"></i><label for="jb-layout-list">' . esc_html__('List', 'wp-recruitment') . '</label></span>';

}

/** Loop**************************************************************/

function recruitment_show_jobs_page_title()
{
    return false;
}

function recruitment_loop_page_content()
{
    global $jobboard_options;

    if (!class_exists('JB_Map')) {
        return;
    }

    if (function_exists('is_jb') && is_jb() && !is_jb_job() && $jobboard_options['page-jobs']) {

        $post = get_post($jobboard_options['page-jobs']);
        echo '<div class="job-vc">';
        echo apply_filters('the_content', $post->post_content);
        echo '</div>';
    }
}

function recruitment_loop_type()
{
    echo '<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 job-price">';
    echo '<i class="fa fa-money"></i>' . jb_job_get_salary();
    echo jb_job_get_type('', '', '<i class="fa fa-clock-o"></i>');
    echo '</div>';
}

function recruitment_loop_actions_view_more()
{
    echo '<a class="job-more btn" href="' . get_permalink() . '">' . esc_html__('View More', 'wp-recruitment') . '</a>';
}

/** Single **************************************************************/

function recruitment_single_job_start()
{
    echo '<div id="sg-job-content" class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><div class="row">';
}

function recruitment_job_actions()
{
    echo '<div class="col-xs-12">';

    jb_template_job_actions();

    echo '</div>';
}

function recruitment_single_job_end()
{
    echo '</div></div>';
}

function recruitment_single_job_page_title($bg)
{
    global $opt_theme_options;
    ?>
    <div id="cms-page-title" class="page-title <?php echo esc_attr($opt_theme_options['job_single_feature']) ?>"
         style="background-image: url(<?php echo esc_url($bg); ?>)">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div>
                        <span class="job-sub-title"><?php esc_html_e('Job Title', 'wp-recruitment'); ?></span>
                        <h1><?php the_title(); ?></h1>
                    </div>
                    <ul>
                        <li><?php printf(esc_html__('%1$sPosition:%2$s %3$s', 'wp-recruitment'), '<span>', '</span>', jb_job_get_type()); ?></li>
                        <li><?php printf(esc_html__('%1$sSalary:%2$s %3$s', 'wp-recruitment'), '<span>', '</span>', jb_job_get_salary()); ?></li>
                        <li><?php printf(esc_html__('%1$sLocation:%2$s %3$s', 'wp-recruitment'), '<span>', '</span>', jb_job_location_html()); ?></li>
                        <li><?php printf('<span>' . esc_html__('Job ID:', 'wp-recruitment') . '</span> %05d', get_the_ID()); ?></li>
                        <li><?php printf(esc_html__('%1$sApplications:%2$s %3$s', 'wp-recruitment'), '<span>', '</span>', jb_job_count_applied()); ?></li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right text-left-sm text-left-xs">
                    <div class="job-social-shared">
                        <span><?php esc_html_e('Share This Job', 'wp-recruitment'); ?></span>
                        <?php recruitment_get_socials_share_job(); ?>
                    </div>
                    <div class="job-button-apply">
                        <?php jb_template_job_actions(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="job-skills">
        <div class="no-container">
            <?php echo get_the_term_list('', 'jobboard-tax-specialisms', esc_html__('Required Skills: ', 'wp-recruitment')); ?>
        </div>
    </div>
    <?php
}

function recruitment_single_job_sidebar()
{
    echo '<div id="sg-job-sidebar" class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';

    ?>
    <?php if (is_active_sidebar('job-single')) {
    dynamic_sidebar('job-single');
}
    echo '</div>';
}

function recruitment_job_loop_summary_start()
{
    echo '<div class="job-summary">';
}

function recruitment_job_loop_summary_end()
{
    echo '</div>';
}

function recruitment_job_loop_type()
{
    echo '<div class="job-price"><i class="fa fa-money"></i>' . jb_job_get_salary() . '';
    echo '' . jb_job_get_type('', '', '<i class="fa fa-clock-o"></i>') . '</div>';
}

function recruitment_user_skills()
{
    ?>
    <div class="job-skills">
        <div class="no-container">
            <?php esc_html_e('Job Types Offered:', 'wp-recruitment'); ?>
            <?php jb_account_the_specialisms(); ?>
        </div>
    </div>
    <?php
}

function recruitment_user_class_args($args = array())
{
    $args[] = 'jobboard-post-jobs';
    $args[] = 'clearfix';
    return $args;
}

function recruitment_user_grid_before()
{
    global $opt_theme_options;

    if ((!isset($_GET['layout']) && !empty($opt_theme_options['job_layout'])) || (isset($_GET['layout']) && $_GET['layout'] == 'grid')) {
        echo '<div class="user-grid">';
    }
}

function recruitment_user_grid_after()
{
    global $opt_theme_options;

    if ((!isset($_GET['layout']) && !empty($opt_theme_options['job_layout'])) || (isset($_GET['layout']) && $_GET['layout'] == 'grid')) {
        echo '</div>';
    }
}

function recruitment_job_join_us()
{
    global $opt_theme_options;
    if (!empty($opt_theme_options['show_newsletter_form'])) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="job-newsletter box-modern row">
                <div class="job-newsletter-heading col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <h3><?php echo esc_attr($opt_theme_options['newsletter_title']); ?></h3>
                    <p><?php echo esc_attr($opt_theme_options['newsletter_desc']); ?></p>
                </div>
                <div class="job-newsletter-form col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?php
                        if (class_exists('Newsletter')) {
                            echo NewsletterSubscription::instance()->get_subscription_form_html5();
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php endif;
}

function recruitment_template_job_tag()
{
    global $opt_theme_options; ?>
    <?php if ($opt_theme_options['single_job_tag'] == 'show') : ?>
    <div class="col-xs-12">
        <div class="job-single-tag">
            <?php $tag_ids = wp_get_object_terms(get_the_ID(), 'jobboard-tax-tags', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names')); ?>
            <?php
            echo '<b>' . esc_html__('Tags:', 'wp-recruitment') . '</b>' . ' ' . implode(', ', $tag_ids);
            ?>
        </div>
    </div>
<?php endif; ?>
<?php }