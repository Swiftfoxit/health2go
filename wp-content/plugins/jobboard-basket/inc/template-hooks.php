<?php
/**
 * JobBoard Basket Template Hooks
 *
 * Action/filter hooks used for JobBoard Basket functions/templates.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Basket/Templates
 * @version     1.0.0
 */

add_action('jobboard_loop_actions',             'jb_template_widget_basket_button_loop', 5 );
add_action('jobboard_single_actions',           'jb_template_widget_basket_button_single', 5 );

/************************* Widget **************************/
add_action('jobboard_basket_widget_content',    'jb_template_widget_basket_content');
add_action('jobboard_basket_widget_loop',       'jb_template_job_loop_summary_title', 10);
add_action('jobboard_basket_widget_loop',       'jb_template_job_loop_summary_location', 20);
add_action('jobboard_basket_widget_loop',       'jb_template_job_loop_summary_type', 30);
add_action('jobboard_basket_widget_footer',     'jb_template_widget_basket_button_manage', 10);
add_action('jobboard_basket_widget_footer',     'jb_template_widget_basket_button_clear', 20);

/************************* Dashboard **************************/
add_action('jobboard_endpoint_candidate_basket','jb_template_widget_basket_page_manage' );

add_action('jobboard_table_basket_title',       'jb_template_basket_loop_title');
add_action('jobboard_table_basket_title',       'jb_template_candidate_applied_locations');
add_action('jobboard_table_basket_type',        'jb_template_candidate_applied_salary');
add_action('jobboard_table_basket_type',        'jb_template_candidate_applied_type');
add_action('jobboard_table_basket_date',        'jb_template_candidate_applied_date');
add_action('jobboard_table_basket_actions',     'jb_template_basket_loop_actions');