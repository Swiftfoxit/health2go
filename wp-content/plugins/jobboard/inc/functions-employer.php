<?php
/**
 * JobBoard Employer Functions
 *
 * Functions for account specific things.
 *
 * @author   FOX
 * @category Core
 * @package  JobBoard/Functions
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function jb_employer_navigation_args() {
    $endpoint_jobs     = jb_get_option('endpoint-jobs', 'jobs');
    $endpoint_profile  = jb_get_option('endpoint-profile', 'profile');
    $endpoint_new      = jb_get_option('endpoint-new', 'new');
    $navigation        = apply_filters( 'jobboard_employer_navigation_args', array(
        array(
            'id'        => 'dashboard',
            'endpoint'  => 'dashboard',
            'title'     => esc_html__( 'My Account', JB_TEXT_DOMAIN )
        ),
        array(
            'id'        => 'jobs',
            'endpoint'  => $endpoint_jobs,
            'title'     => esc_html__( 'Application History', JB_TEXT_DOMAIN )
        ),
        array(
            'id'        => 'profile',
            'endpoint'  => $endpoint_profile,
            'title'     => esc_html__( 'Manage Profile', JB_TEXT_DOMAIN )
        ),
        array(
            'id'        => 'new',
            'endpoint'  => $endpoint_new,
            'title'     => esc_html__( 'Post New', JB_TEXT_DOMAIN )
        )
    ));

    $navigation[] = array(
        'id'        => 'logout',
        'endpoint'  => 'logout',
        'title'     => esc_html__( 'Logout', JB_TEXT_DOMAIN )
    );

    return $navigation;
}

function jb_employer_count_applied($user_id = '', $date = 30){
    $applied = JB()->employer->count_applied_since_days($user_id, $date);
    return apply_filters('jobboard_employer_applied_count', $applied);
}

function jb_employer_profile_custom_field(){
    $fields = apply_filters('jobboard_employer_profile_fields', jb_get_option('employer-custom-fields'));
    return jb_account_custom_fields_value('', $fields);
}

function jb_employer_job_custom_field(){
    $fields     = array(
        array(
            'id'         => 'post-heading',
            'title'      => esc_html__('Post A Job', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Make sure you have completed all required fields (*), before job submission.', JB_TEXT_DOMAIN ),
            'type'       => 'heading',
            'heading'    => 'h3'
        ),
        array (
            'id'         => 'post_title',
            'title'      => esc_html__('Job Title *', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter your job title', JB_TEXT_DOMAIN ),
            'notice'     => esc_html__('is required !', JB_TEXT_DOMAIN),
            'type'       => 'text',
            'require'    => 1,
            'col'        => 6,
            'placeholder'=> esc_html__('Job Title *', JB_TEXT_DOMAIN )
        ),
        array (
            'id'         => '_salary_min',
            'title'      => esc_html__('Min Salary *', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter min salary (number)', JB_TEXT_DOMAIN ),
            'notice'     => esc_html__('is required !', JB_TEXT_DOMAIN),
            'type'       => 'text',
            'input'      => 'number',
            'require'    => 1,
            'col'        => 6,
            'placeholder'=> esc_html__('Min Salary *', JB_TEXT_DOMAIN )
        ),
        array (
            'id'         => '_salary_max',
            'title'      => esc_html__('Max Salary', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter max salary (number)', JB_TEXT_DOMAIN ),
            'type'       => 'text',
            'input'      => 'number',
            'col'        => 6,
            'placeholder'=> esc_html__('Max Salary', JB_TEXT_DOMAIN )
        ),
        array (
            'id'         => '_salary_currency',
            'title'      => esc_html__('Currency', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Select currency for salary', JB_TEXT_DOMAIN ),
            'notice'     => esc_html__('is required !', JB_TEXT_DOMAIN),
            'type'       => 'select',
            'col'        => 6,
            'require'    => 1,
            'value'      => jb_get_option('default-currency', 'USD'),
            'options'    => jb_get_currencies_options(),
        ),
        array (
            'id'         => '_salary_extra',
            'title'      => esc_html__('Bonus or Exception', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter your bonus, exception, condition...', JB_TEXT_DOMAIN ),
            'type'       => 'text',
            'placeholder'=> esc_html__('+ Relocation Bonus', JB_TEXT_DOMAIN )
        ),
        array (
            'id'         => 'types',
            'title'      => esc_html__('Contract Type *', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Select a job type', JB_TEXT_DOMAIN ),
            'notice'     => esc_html__('is required !', JB_TEXT_DOMAIN),
            'type'       => 'radio',
            'value'      => 2,
            'require'    => 1,
            'options'    => jb_get_type_options()
        ),
        array (
            'id'         => 'post_content',
            'title'      => esc_html__('Job Description *', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter your job content.', JB_TEXT_DOMAIN ),
            'notice'     => esc_html__('is required !', JB_TEXT_DOMAIN),
            'type'       => 'textarea',
            'require'    => 1,
            'placeholder'=> esc_html__('Job description *', JB_TEXT_DOMAIN )
        ),
        array (
            'id'         => 'specialisms',
            'name'       => 'specialisms[]',
            'title'      => esc_html__('Specialisms & Skill', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Select specialisms and skill for job.', JB_TEXT_DOMAIN ),
            'notice'     => esc_html__('is required !', JB_TEXT_DOMAIN),
            'placeholder'=> esc_html__('Specialisms & Skill *', JB_TEXT_DOMAIN ),
            'type'       => 'select',
            'multi'      => true,
            'col'        => 6,
            'require'    => 1,
            'options'    => jb_get_specialism_options(),
        ),
        array (
            'id'         => 'tags',
            'name'       => 'tags[]',
            'title'      => esc_html__('Tags', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter your job tags.', JB_TEXT_DOMAIN ),
            'placeholder'=> esc_html__('Tags', JB_TEXT_DOMAIN ),
            'type'       => 'tags',
            'col'        => 6,
            'options'    => array(),
        ),
        array(
            'id'         => 'featured-image',
            'title'      => esc_html__('Featured Image', JB_TEXT_DOMAIN ),
            'type'       => 'media',
            'input'      => 'image',
            'types'      => 'jpg',
            'size'       => 1024
        ),
        array(
            'id'         => 'locations',
            'type'       => 'location',
            'title'      => esc_html__('Job Address', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Select job address.', JB_TEXT_DOMAIN ),
            'taxonomy'   => 'jobboard-tax-locations',
            'options'    => array(
                array(
                    'id'            => 'country',
                    'placeholder'   => esc_html__('Country', JB_TEXT_DOMAIN )
                ),
                array(
                    'id'            => 'city',
                    'placeholder'   => esc_html__('City', JB_TEXT_DOMAIN )
                ),
                array(
                    'id'            => 'district',
                    'placeholder'   => esc_html__('District', JB_TEXT_DOMAIN )
                ),
            )
        ),
        array (
            'id'         => '_address',
            'title'      => esc_html__('Complete Address', JB_TEXT_DOMAIN ),
            'subtitle'   => esc_html__('Enter your job address.', JB_TEXT_DOMAIN ),
            'type'       => 'textarea',
            'placeholder'=> esc_html__('Complete Address', JB_TEXT_DOMAIN )
        )
    );

    return apply_filters('jobboard_add_job_fields', $fields);
}

function jb_employer_trending_specialisms(){
    global $jobboard_account;
    $user_id = isset($jobboard_account->ID) ? $jobboard_account->ID : '';
    $specialisms = JB()->employer->get_trending_taxonomies($user_id, 'jobboard-tax-specialisms', 5);

    if(empty($specialisms)){
        return;
    }

    echo '<ul>';

    foreach ($specialisms as $specialism){
        $term_url = get_term_link((int)$specialism->term_id, 'jobboard-tax-specialisms');
        if(is_wp_error($term_url)){
            continue;
        }
        echo '<li><a href="' . esc_url($term_url) . '">' . esc_html($specialism->name) . '</a></li>';
    }

    echo '<ul>';
}

function jb_employer_the_vacancies(){
    echo jb_employer_get_vacancies();
}

function jb_employer_get_vacancies($user_id = ''){
    global $jobboard_account;

    if(!$user_id && isset($jobboard_account->ID)) {
        $user_id = $jobboard_account->ID;
    }

    $vacancies = get_user_meta($user_id, 'job_vacancies', true);

    return $vacancies ? $vacancies : 0;
}