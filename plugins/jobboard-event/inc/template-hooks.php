<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 10:55 AM
 */

add_action('wp_ajax_show_event_register_form','je_template_event_register_form');
add_action('wp_ajax_nopriv_show_event_register_form','je_template_event_register_form');

add_action('wp_ajax_je_save_register_event','je_save_register_event');
add_action('wp_ajax_nopriv_je_save_register_event','je_save_register_event');

add_action( 'jobboard_event_loop_actions', 'je_template_event_loop_actions_readmore', 30 );

add_action( 'jobboard_event_loop_item_summary', 'je_template_job_loop_summary_start', 5 );

add_action( 'jobboard_event_loop_item_summary', 'je_template_job_loop_summary_title', 10 );
add_action( 'jobboard_event_loop_actions', 'je_template_job_loop_summary_duration', 10 );
add_action( 'jobboard_event_loop_actions', 'je_template_job_loop_summary_location', 20 );
//add_action('jobboard_event_loop_item_summary',                'jb_template_job_loop_summary_excerpt', 30 );

add_action( 'jobboard_event_loop_item_summary', 'je_template_job_loop_summary_end', 100 );

add_action( 'jobboard_event_loop_item_summary_after', 'je_template_event_loop_actions', 10 );

add_action('jobboard_event_loop_before', 'je_template_loop_start');
add_action('jobboard_event_loop_after', 'je_template_loop_end');

add_action('jobboard_event_single_header_meta', 'je_template_job_loop_summary_duration', 10);
add_action('jobboard_event_single_header_meta', 'je_template_job_loop_summary_location', 20);

//add_action('jobboard_event_single_summary_before', 'je_template_single_header');
add_action('jobboard_event_single_summary', 'je_template_single_header', 10);
add_action('jobboard_event_single_header_meta', 'je_template_single_register', 20);
add_action('jobboard_event_single_summary', 'je_template_single_summary', 30);
add_action('jobboard_event_single_summary_after', 'je_template_events_like',20);