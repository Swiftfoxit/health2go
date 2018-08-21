<?php
/**
 * remove_action
 */
remove_action('jobboard_main_before_content',              'jb_template_breadcrumb');
remove_action('jobboard_single_before_content',            'jb_template_breadcrumb');
remove_action('jobboard_user_before_content',              'jb_template_breadcrumb');
remove_action('jobboard_users_before_content',             'jb_template_breadcrumb');
remove_action('jobboard_sidebar',                          'jb_template_sidebar');
remove_action('jobboard_sidebar_users',                    'jb_template_sidebar_employers');
remove_action('jobboard_sidebar_users',                    'jb_template_sidebar_candidates');
remove_action('jobboard_sidebar_single',                   'jb_template_sidebar_single');
remove_action('jobboard_sidebar_user',                     'jb_template_sidebar_profile');
remove_action('jobboard_archive_actions',                  'jb_template_catalog_showing');
remove_action('jobboard_archive_actions',                  'jb_template_catalog_orderby', 20);
/**
 * add_action
 */
add_action('jobboard_main_before_content',                  'recruitment_job_sidebar', 6);
add_action('jobboard_main_before_content',                  'recruitment_job_sidebar_class', 7);
add_action('jobboard_main_before_content',                  'recruitment_job_result_count', 8);
add_action('jobboard_users_before_content',                 'recruitment_job_sidebar', 6);
add_action('jobboard_users_before_content',                 'recruitment_job_sidebar_class', 7);
add_action('jobboard_main_after_content',                   function(){ echo '</div>'; }, 6);
add_action('jobboard_users_after_content',                  function(){ echo '</div>'; }, 6);
add_action('jobboard_main_after_content',                  'recruitment_job_join_us', 8);
add_action('jobboard_archive_actions',                      'jb_template_catalog_orderby', 10);
add_action('jobboard_archive_actions',                      'jb_template_catalog_layout', 20);
add_action('jobboard_archive_actions',                      'jb_template_catalog_showing', 30);
add_action('jobboard_archive_actions',                      'jb_template_job_loop_pagination', 40);

/** Loop *******************************************/
remove_action('jobboard_loop_item_summary_before',     'jb_template_job_loop_thumbnail', 10 );
remove_action('jobboard_loop_meta',                    'jb_template_job_loop_summary_type', 10);
remove_action('jobboard_loop_meta',                    'jb_template_job_loop_summary_date', 20);
remove_action('jobboard_loop_meta',                    'jb_template_job_loop_summary_author', 30);
remove_action('jobboard_loop_meta',                    'jb_template_job_loop_summary_specialism', 40);
remove_action('jobboard_loop_item_summary',            'jb_template_job_loop_summary_excerpt', 30 );
remove_action('jobboard_loop_actions',                 'jb_template_job_loop_actions_readmore', 10);

add_filter('jobboard_archive_show_title',                   'recruitment_show_jobs_page_title');
add_action('jobboard_loop_item_summary_after',              'recruitment_loop_type', 5);
add_action('jobboard_loop_actions',                         'recruitment_loop_actions_view_more', 20);

/** Single *****************************************/
remove_action('jobboard_single_summary_before',         'jb_template_job_image', 10);
remove_action('jobboard_single_summary',                'jb_template_job_summary_start', 5);
remove_action('jobboard_single_summary',                'jb_template_job_type', 10);
remove_action('jobboard_single_summary',                'jb_template_job_title', 20);
remove_action('jobboard_single_summary',                'jb_template_job_meta', 30);
remove_action('jobboard_single_summary',                'jb_template_job_actions', 40);
remove_action('jobboard_single_summary',                'jb_template_job_summary_end', 50);
add_action('jobboard_single_summary_after',             'recruitment_template_job_tag', 30);
add_action('jobboard_single_before_footer',             'recruitment_job_join_us', 10);

add_action('jobboard_single_summary_after',                 'recruitment_single_job_start', 5);
add_action('jobboard_single_summary_after',                 'recruitment_job_actions', 20);
add_action('jobboard_single_summary_after',                 'recruitment_single_job_end', 30);
add_action('jobboard_single_summary_after',                 'recruitment_single_job_sidebar', 40);

/** DB **********************************************/
add_action('jobboard_table_applied_title',     'recruitment_job_loop_summary_start', 5);
add_action('jobboard_table_applied_title',     'recruitment_job_loop_summary_end', 50);

/** User ********************************************/
remove_action('jobboard_users_loop_employer_summary', 'jb_template_user_loop_summary_vacancies', 30);
remove_action('jobboard_users_loop_candidate_summary', 'jb_template_user_loop_summary_salary', 30);
remove_action('jobboard_users_loop_employer_summary', 'jb_template_user_loop_summary_specialism', 40);
remove_action('jobboard_users_loop_candidate_summary', 'jb_template_user_loop_summary_specialism', 40);
remove_action('jobboard_users_loop_summary_after', 'jb_template_user_loop_actions', 10);
add_action('jobboard_users_loop_summary_before', 'recruitment_user_grid_before', 5);
add_action('jobboard_users_loop_summary_after', 'recruitment_user_grid_after', 150);
add_action('jobboard_users_loop_employer_summary', 'jb_template_user_loop_summary_specialism', 150);
add_action('jobboard_users_loop_candidate_summary', 'jb_template_user_loop_summary_specialism', 150);
add_action('jobboard_users_loop_employer_summary', 'jb_template_user_loop_summary_vacancies', 160);
add_action('jobboard_users_loop_candidate_summary', 'jb_template_user_loop_summary_salary', 160);
add_action('jobboard_user_before_employer_content', 'recruitment_user_skills', 20);
add_action('jobboard_user_before_candidate_content', 'recruitment_user_skills', 20);
add_filter('jobboard_user_class_args', 'recruitment_user_class_args');

remove_action('jobboard_search_form', 'jb_search_form_specialisms', 30);
remove_action('jobboard_search_form', 'jb_search_form_locations', 40);
add_action('jobboard_search_form', 'jb_search_form_specialisms', 40);
add_action('jobboard_search_form', 'jb_search_form_locations', 30);