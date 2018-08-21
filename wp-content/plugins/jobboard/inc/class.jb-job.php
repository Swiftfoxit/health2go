<?php
/**
 * JobBoard Job.
 *
 * @class        JobBoard_Job
 * @version        1.0.0
 * @package        JobBoard/Classes
 * @category    Class
 * @author        FOX
 */

if (!defined('ABSPATH')) {
    exit();
}

class JobBoard_Job
{

    function __construct()
    {
        add_filter('jobboard_catalog_showing_args', array($this, 'showing_args'));
        add_filter('the_author', array($this, 'get_author_name'));
        add_filter('author_link', array($this, 'get_author_link'));
    }

    function apply($user_id, $post_id, $job_status = 'applied')
    {
        global $wpdb;

        if ($validate = apply_filters('jobboard_job_apply_validate', false)) {
            return jb_error_args(true, $validate);
        }

        if (!$post = get_post($post_id)) {
            return jb_error_args(true, esc_html__('Post does not exist.', JB_TEXT_DOMAIN));
        }

        if ($apply = $this->get_row($user_id, $post->ID)) {
            if ($apply->app_status != $job_status) {
                $wpdb->update($wpdb->prefix . 'jobboard_applied', array(
                    'app_status' => $job_status,
                    'app_date'   => current_time('mysql'),
                ), array(
                    'app_id' => $apply->app_id
                ), array(
                    '%s',
                    '%s'
                ), array(
                    '%d'
                ));
            } else {
                return jb_error_args(true, esc_html__('Post ready exist.', JB_TEXT_DOMAIN));
            }
            $applied_id = $apply->app_id;
        } else {
            $insert = $wpdb->insert(
                $wpdb->prefix . 'jobboard_applied',
                array(
                    'user_id'    => $user_id,
                    'post_id'    => $post->ID,
                    'app_status' => $job_status,
                    'app_date'   => current_time('mysql')
                ),
                array('%d', '%d', '%s', '%s')
            );

            if (!$insert) {
                return jb_error_args(true, esc_html__('Cannot add apply form to database.', JB_TEXT_DOMAIN));
            }

            $applied_id = absint($wpdb->insert_id);
        }

        if (!$applied_id) {
            return jb_error_args(true, esc_html__('Cannot apply this job.', JB_TEXT_DOMAIN));
        }

        if ($job_status === 'applied') {
            $new_application = get_post_meta($post->ID, '_application_ids', true);
            $new_application = $new_application && is_array($new_application) ? array_push($new_application, $user_id) : array($user_id);
            $new_application = !is_array($new_application)? $new_application : array($user_id);
            update_post_meta($post->ID, '_application_ids', array_unique($new_application));
        }

        do_action('jobboard_job_applied', $user_id, $post->ID, $job_status);
        return $applied_id;
    }

    function showing_args($showing)
    {
        global $wp_query, $paged;

        if (!is_jb_jobs()) {
            return $showing;
        }

        $posts_per_page = $wp_query->get('posts_per_page');
        $showing['paged'] = $paged ? $paged : 1;
        $showing['current'] = $wp_query->post_count;
        $showing['all'] = $wp_query->found_posts;
        $posts_per_pages = $showing['paged'] * $posts_per_page;

        if ($posts_per_pages <= $showing['all']) {
            $showing['current'] = $posts_per_pages;
        } else {
            $showing['current'] = $showing['all'];
        }

        $showing['paged'] = $showing['current'] - $wp_query->post_count;

        return $showing;
    }

    function get_row($user_id, $post_id, $status = '')
    {

        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}jobboard_applied as jb WHERE jb.user_id = %d AND jb.post_id = %d", $user_id, $post_id);

        if ($status) {
            $query .= " AND jb.app_status = '{$status}'";
        }

        return $wpdb->get_row($query);
    }

    function get_status($post_id, $user_id = '')
    {

        if (!$user_id = get_current_user_id()) {
            return null;
        }

        global $wpdb;

        return $wpdb->get_var($wpdb->prepare("SELECT jb.app_status FROM {$wpdb->prefix}jobboard_applied as jb WHERE jb.user_id = %d AND jb.post_id = %d", $user_id, $post_id));
    }

    function count($user_id = '')
    {

        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $results = array();
        $counts = array();

        if ($user_id) {

            global $wpdb;

            $query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s";
            $query .= " AND post_author = %d";
            $query .= ' GROUP BY post_status';

            $results = (array)$wpdb->get_results($wpdb->prepare($query, 'jobboard-post-jobs', $user_id), ARRAY_A);
        }

        if (!empty($results)) {
            foreach ($results as $row) {
                $counts[$row['post_status']] = $row['num_posts'];
            }
        }

        return apply_filters('jobboard_count_jobs', $counts);
    }

    function count_featured($user_id = '')
    {

        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $count = 0;

        if ($user_id) {

            global $wpdb;

            $query = "SELECT COUNT(*) FROM {$wpdb->posts}";
            $query .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";
            $query .= " WHERE post_type = %s AND post_author = %d AND {$wpdb->postmeta}.meta_key = %s AND {$wpdb->postmeta}.meta_value = %s";

            $count = $wpdb->get_var($wpdb->prepare($query, 'jobboard-post-jobs', $user_id, '_featured', '1'));
        }

        return apply_filters('jobboard_count_jobs_featured', $count);
    }

    function posts_fields($fields)
    {
        return $fields .= ",jb.*";
    }

    function posts_join($join)
    {
        global $wpdb;

        $join .= "LEFT JOIN {$wpdb->prefix}jobboard_applied as jb ON $wpdb->posts.ID = jb.post_id";
        return $join;
    }

    function posts_where($where, $args)
    {

        global $wpdb;

        if (!$user_id = get_current_user_id()) {
            return $where;
        }

        $where .= $wpdb->prepare(" AND jb.user_id = %d", $user_id);

        if (!empty($args->query['app_status'])) {
            if (is_array($args->query['app_status'])) {
                $status = implode("','", $args->query['app_status']);
                $where .= " AND jb.app_status IN ('$status')";
            } else {
                $where .= $wpdb->prepare(" AND jb.app_status = %s", $args->query['app_status']);
            }
        }

        return $where;
    }

    function posts_orderby($orderby, $args)
    {

        if ($args->query['orderby'] == 'app_date') {
            return "jb.app_date " . strtoupper($args->query['order']);
        }

        return $orderby;
    }

    function query($args)
    {
        /* query job. */
        $query = wp_parse_args($args, array(
            'post_type'   => 'jobboard-post-jobs',
            'post_status' => 'publish',
            'app_status'  => '',
            'paged'       => 1,
            'orderby'     => 'date',
            'order'       => 'DESC',
        ));

        /* add custom query. */
        add_filter('posts_fields', array($this, 'posts_fields'));
        add_filter('posts_join', array($this, 'posts_join'));
        add_filter('posts_where', array($this, 'posts_where'), 10, 2);
        add_filter('posts_orderby', array($this, 'posts_orderby'), 10, 2);

        $jobs = new WP_Query(apply_filters('jb/job/query', $query));

        /* remove custom query */
        remove_filter('posts_fields', array($this, 'posts_fields'));
        remove_filter('posts_join', array($this, 'posts_join'));
        remove_filter('posts_where', array($this, 'posts_where'));
        remove_filter('posts_orderby', array($this, 'posts_orderby'));

        return $jobs;
    }

    function query_date_posted($date_posted = 0)
    {
        $date_query = array();

        if (!$date_posted) {
            return $date_query;
        }

        $date = date('Y-m-d H:i:s', strtotime("-{$date_posted} hour", current_time('timestamp')));

        $date_query = array(
            array(
                'after' => $date
            )
        );

        return apply_filters('jb/job/query/date', $date_query, $date_posted);
    }

    function get_author_name($display_name)
    {
        global $post;
        if (!empty($post->ID) && $custom_display_name = get_post_meta($post->ID, '_customer_display_name', true)) {
            $display_name = $custom_display_name;
        }
        return $display_name;
    }

    function get_author_link($link)
    {
        global $post;
        if (!empty($post->ID) && $custom_link = get_post_meta($post->ID, '_customer_url', true)) {
            $link = $custom_link;
        }
        return $link;
    }
}