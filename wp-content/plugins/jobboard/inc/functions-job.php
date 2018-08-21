<?php
/**
 * JobBoard Job Functions
 *
 * Functions for job specific things.
 *
 * @author   FOX
 * @category Core
 * @package  JobBoard/Functions
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function jb_job_schema() {
    return apply_filters('jb/job/schema', 'http://schema.org/JobPosting');
}

function jb_the_page_title( $echo = true ) {

    if ( is_search() ) {
        $page_title = sprintf( __( 'Search Results: &ldquo;%s&rdquo;', JB_TEXT_DOMAIN ), get_search_query() );

        if ( get_query_var( 'paged' ) )
            $page_title .= sprintf( __( '&nbsp;&ndash; Page %s', JB_TEXT_DOMAIN ), get_query_var( 'paged' ) );

    } elseif ( is_tax() ) {

        $page_title = single_term_title( "", false );

    } else {

        $page_id = jb_page_id( 'jobs' );
        $page_title   = get_the_title( $page_id );

    }

    $page_title = apply_filters( 'jb/job/page/title', $page_title );

    if ( $echo ) {
        echo $page_title;
    } else {
        return $page_title;
    }
}

function jb_job_the_types(){
    echo jb_job_get_type();
}

function jb_job_get_type($id = '', $style = '', $before = '', $after = ''){

    $post = get_post($id);

    if(!$post)
        return false;

    $terms = get_the_terms( $post->ID, 'jobboard-tax-types' );

    if ( is_wp_error( $terms ) )
        return $terms;

    if ( !isset( $terms[0] ) )
        return false;

    $link = get_term_link( $terms[0], 'jobboard-tax-types' );

    if ( is_wp_error( $link ) )
        return $link;

    $color = get_term_meta($terms[0]->term_id, '_color', true);
    $style = apply_filters('jb/job/type/style', $style);

    if($style == 'background'){
        $style = 'background-color:' . $color . ';';
    } else {
        $style = 'color:' . $color . ';';
    }

    $type = '<a href="' . esc_url( $link ) . '" style="'.esc_attr($style).'" class="job-type" rel="tag">' . $before . $terms[0]->name . $after . '</a>';

    return apply_filters( "jb/job/type/html", $type , $style);
}

function jb_job_the_locations(){
    echo jb_job_location_html();
}

function jb_job_location_html($id = '', $before = '', $sep = ', ', $after = ''){
    $post = get_post($id);

    if(!$post) {
        return false;
    }

    $terms = wp_get_post_terms($post->ID, 'jobboard-tax-locations');

    if( is_wp_error($terms) ) {
        return false;
    }

    $_terms = array();
    jb_sort_terms($terms, $_terms);

    $links  = array();

    foreach ( $_terms as $index => $term ) {
        $link    = get_term_link( $term->term_id, 'jobboard-tax-locations' );
        $links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . esc_html($term->name) . '</a>';
    }

//    if($address = jb_job_meta('_address')) {
//        $links[] = '<span>'.esc_html($address).'</span>';
//    }

    $term_links = apply_filters( "jobboard_job_location", $links );

    return $before . join( $sep, $term_links ) . $after;
}

function jb_job_location_text($id = ''){
    $post = get_post($id);

    if(!$post) {
        return false;
    }

    $terms = wp_get_post_terms($post->ID, 'jobboard-tax-locations');

    if( is_wp_error($terms) ) {
        return false;
    }

    $_terms = array();
    jb_sort_terms($terms, $_terms);

    $location  = array();

    foreach ( $_terms as $index => $term ) {
        $location[] = esc_html($term->name);
    }

//    if($address = jb_job_meta('_address')) {
//        $links[] = '<span>'.esc_html($address).'</span>';
//    }
    return implode(', ',$location);
}

function jb_job_image_url($size = 'medium', $no_image_size = '200x200') {
    global $post, $authordata;
    $employer_picture = get_user_meta($authordata->ID, 'user_avatar', true);
    if ( has_post_thumbnail() ) {
        $image = get_the_post_thumbnail_url($post->ID, $size);
    } elseif (isset($authordata->ID) && !empty($employer_picture['id'])){
        $image = wp_get_attachment_image_url($employer_picture['id'], $size);
    } else {
        $image = jb_get_placeholder_image($no_image_size);
    }

    return apply_filters('jobboard_job_image_url', $image);
}

/**
 * job salary.
 *
 * @param $post
 * @param bool $echo
 * @return array|default|string
 */
function jb_job_salary($post = '', $echo = true){

    $salary = jb_job_meta('_salary', '', $post);

    if($echo){
        echo esc_html($salary);
    } else {
        return $salary;
    }
}

function jb_job_the_salary(){
    echo jb_job_get_salary();
}

function jb_job_get_salary($post_id = ''){
    global $post;

    if(!$post_id && !empty($post->ID)){
        $post_id = $post->ID;
    }

    if(!$post_id){
        return false;
    }

    $min_salary     = jb_job_get_min_salary($post_id);
    $max_salary     = jb_job_get_max_salary($post_id);
    $extra_salary   = jb_job_get_extra_salary($post_id);
    $currency       = jb_job_get_currency_symbol($post_id);
    $position       = jb_get_option('currency-position', 'left');
    $salary         = '';

    if($min_salary != ''){
        $salary .= jb_get_salary_currency($min_salary, $currency, $position);
    }

    if($max_salary != ''){
        $salary .= sprintf(' - %s', jb_get_salary_currency($max_salary, $currency, $position));
    }

    if($extra_salary != ''){
        $salary .= sprintf(' %s', $extra_salary);
    }

    $salary = apply_filters('jb/job/salary', $salary, $min_salary, $max_salary, $extra_salary, $currency);

    return $salary;
}

function jb_job_get_min_salary($post_id){
    return jb_job_meta('_salary_min', 0, $post_id);
}

function jb_job_get_max_salary($post_id){
    return jb_job_meta('_salary_max', 0, $post_id);
}

function jb_job_get_extra_salary($post_id){
    return jb_job_meta('_salary_extra', '', $post_id);
}

function jb_job_get_currency($post_id){
    return jb_job_meta('_salary_currency', jb_get_option('currency', 'USD'), $post_id);
}

function jb_job_get_currency_symbol($post_id){
    $currency = jb_job_get_currency($post_id);
    return jb_get_currency_symbol($currency);
}

function jb_job_excerpt($text = '', $num_words = 30, $more = null){

    $raw_excerpt = $text;

    if(!$text) {

        $text = get_the_content('');
        $text = strip_shortcodes($text);

        /** This filter is documented in wp-includes/post-template.php */
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
    }

    $num_words = apply_filters('jb/job/excerpt/words', $num_words);

    $text = wp_trim_words($text, $num_words, $more);

    return apply_filters( 'jb/job/excerpt', $text, $raw_excerpt );
}

function jb_job_status($status = ''){
    global $post;

    $list = jb_job_status_list();

    if(!$status && isset($post->post_status)){
        $status = $post->post_status;
    }

    if(isset($list[$status])){
        return $list[$status];
    } else {
        return $list['trash'];
    }
}

function jb_job_apply_status(){
    global $post;

    if(!isset($post->app_status)){
        return false;
    }

    return $post->app_status;
}

function jb_job_status_list(){

    $status = apply_filters('jb/job/status/list', array(
        'publish' => esc_html__('Approved', JB_TEXT_DOMAIN),
        'pending' => esc_html__('Pending', JB_TEXT_DOMAIN),
        'trash'   => esc_html__('Rejected', JB_TEXT_DOMAIN)
    ));

    return $status;
}

function jb_job_meta($key, $default = '', $post = ''){

    $post = get_post($post);

    if(!$key){
        return $default;
    }

    $value = maybe_unserialize(get_post_meta($post->ID, $key, true));

    if(!empty($value)){
        return $value;
    } else {
        return $default;
    }
}

function jb_job_count_applied(){
    return JB()->employer->count_applied('', array('approved'));
}

function jb_job_apply_fields(){
    $fields = array(
        array (
            'id'         => 'display_name',
            'title'      => esc_html__('Full Name *', JB_TEXT_DOMAIN ),
            'placeholder'=> esc_html__('your name.', JB_TEXT_DOMAIN ),
            'type'       => 'text',
            'require'    => 1,
        ),
        array (
            'id'         => 'user_email',
            'title'      => esc_html__('Email Address *', JB_TEXT_DOMAIN ),
            'placeholder'=> esc_html__('name@your-domain.com.', JB_TEXT_DOMAIN ),
            'type'       => 'text',
            'input'      => 'email',
            'require'    => 1,
        ),
        array (
            'id'         => 'covering',
            'title'      => esc_html__('Covering Letter *', JB_TEXT_DOMAIN ),
            'type'       => 'textarea',
            'require'    => 1,
            'placeholder'=> esc_html__('Explain to the employer why you fit the job role.', JB_TEXT_DOMAIN )
        ),
        array(
            'id'         => 'cv',
            'title'      => esc_html__('CV *', JB_TEXT_DOMAIN ),
            'button'     => esc_html__('Select CV', JB_TEXT_DOMAIN ),
            'type'       => 'media',
            'require'    => 1,
            'types'      => 'pdf,doc,docx,rtf',
            'size'       => 1024
        )
    );

    if($user = wp_get_current_user()){
        $user_keys = jb_user_keys();
        foreach ($fields as $index => $field){

            if(in_array($field['id'], $user_keys)){
                $fields[$index]['value'] = $user->{$field['id']};
            } else {
                $fields[$index]['value'] = get_user_meta($user->ID, $field['id'], true);
            }

            if(!empty($_POST[$field['id']])){
                $fields[$index]['value'] = $_POST[$field['id']];
            }
        }
    }

    return apply_filters('jobboard_apply_job_fields', $fields);
}