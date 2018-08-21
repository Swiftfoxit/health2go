<?php
/**
 * JobBoard Query.
 *
 * @class        JobBoard_Query
 * @version        1.0.0
 * @package        JobBoard/Classes
 * @category    Class
 * @author        FOX
 */

if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('JobBoard_Query')) :
    class JobBoard_Query
    {

        public $query_vars = array();

        function __construct()
        {
            add_action('init', array($this, 'add_endpoints'), 5);
            add_filter('the_post', array($this, 'the_post'));
            add_filter('rewrite_rules_array', array($this, 'rewrites'));
            add_filter('query_vars', array($this, 'add_query_vars'));

            if (!is_admin()) {
                add_action('pre_get_posts', array($this, 'pre_get_posts'));
                add_action('posts_results', array($this, 'posts_results'), 10, 2);
                add_action('parse_request', array($this, 'parse_request'), 0);
                add_filter('body_class', array($this, 'body_class'));
                add_filter('post_class', array($this, 'post_class'), 20, 3);
            }

            $this->init_query_vars();
        }

        function rewrites($wp_rules)
        {
            $employer = jb_get_option('profile-employer-slug', 'employer');
            $candidate = jb_get_option('profile-candidate-slug', 'candidate');
            $new_rules = array(
                "{$employer}/(.+)\$" => 'index.php?account=$matches[1]',
                "{$candidate}/(.+)\$" => 'index.php?account=$matches[1]',
            );

            return array_merge($new_rules, $wp_rules);
        }

        function init_query_vars()
        {
            $this->query_vars = apply_filters('jobboard_query_endpoint_args', array(
                'dashboard' => 'dashboard',
                'applied' => jb_get_option('endpoint-applied', 'applied'),
                'profile' => jb_get_option('endpoint-profile', 'profile'),
                'jobs' => jb_get_option('endpoint-jobs', 'jobs'),
                'new' => jb_get_option('endpoint-new', 'new'),
                'account' => 'account',
            ));
        }

        function get_endpoint_title($endpoint)
        {
            switch ($endpoint) {
                case 'applied' :
                    $title = esc_html__('Application History', JB_TEXT_DOMAIN);
                    break;
                case 'profile' :
                    $title = esc_html__('Manage Profile', JB_TEXT_DOMAIN);
                    break;
                case 'jobs' :
                    $title = esc_html__('Application History', JB_TEXT_DOMAIN);
                    break;
                case 'new' :
                    $title = esc_html__('Post New', JB_TEXT_DOMAIN);
                    break;
                default:
                    $title = '';
                    break;
            }

            return apply_filters('jobboard_query_endpoint_' . $endpoint . '_title', $title);
        }

        public function get_query_vars()
        {
            return $this->query_vars;
        }

        public function get_current_endpoint()
        {
            global $wp;
            foreach ($this->get_query_vars() as $key => $value) {
                if (isset($wp->query_vars[$key])) {
                    return $key;
                }
            }

            return '';
        }

        public function parse_request()
        {
            global $wp;

            // Map query vars to their keys, or get them if endpoints are not supported
            foreach ($this->query_vars as $key => $var) {
                if (isset($_GET[$var])) {
                    $wp->query_vars[$key] = $_GET[$var];
                } elseif (isset($wp->query_vars[$var])) {
                    $wp->query_vars[$key] = $wp->query_vars[$var];
                }
            }
        }

        protected function get_endpoints_mask()
        {
            if ('page' === get_option('show_on_front')) {
                $page_on_front = get_option('page_on_front');
                $employer_page_id = jb_get_option('page-employer', 0);
                $candidate_page_id = jb_get_option('page-candidate', 0);

                if (in_array($page_on_front, array($employer_page_id, $candidate_page_id))) {
                    return EP_ROOT | EP_PAGES;
                }
            }

            return EP_PAGES;
        }

        function add_query_vars($vars)
        {
            foreach ($this->query_vars as $key => $var) {
                $vars[] = $key;
            }

            return $vars;
        }

        function add_endpoints()
        {

            $mask = $this->get_endpoints_mask();

            foreach ($this->query_vars as $var) {
                if (!empty($var)) {
                    add_rewrite_endpoint($var, $mask);
                }
            }
        }

        function pre_get_posts($q)
        {
            if (!$q->is_main_query()) {
                return;
            }

            if ($q->is_page() && 'page' === get_option('show_on_front') && absint($q->get('page_id')) === jb_page_id('jobs')) {
                global $wp_post_types;

                $q->set('page_id', '');

                if (isset($q->query['paged'])) {
                    $q->set('paged', $q->query['paged']);
                }

                $jobs_page = get_post(jb_page_id('jobs'));

                $wp_post_types['jobboard-post-jobs']->ID = $jobs_page->ID;
                $wp_post_types['jobboard-post-jobs']->post_title = $jobs_page->post_title;
                $wp_post_types['jobboard-post-jobs']->post_name = $jobs_page->post_name;
                $wp_post_types['jobboard-post-jobs']->post_type = $jobs_page->post_type;
                $wp_post_types['jobboard-post-jobs']->ancestors = get_ancestors($jobs_page->ID, $jobs_page->post_type);

                $this->job_query($q);

                $q->is_singular = false;
                $q->is_post_type_archive = true;
                $q->is_archive = true;
                $q->is_page = true;

                add_filter('post_type_archive_title', '__return_empty_string', 5);
            } elseif ($q->is_home && $account = urldecode(get_query_var('account'))) {
                if ($user = get_user_by('login', $account)) {
                    if (is_jb_account($user->ID)) {
                        $GLOBALS['jobboard_account'] = $user;
                        $q->query['author'] = $user->ID;
                        $q->query_vars['author'] = $user->ID;
                        $q->is_home = false;
                        $q->is_archive = false;
                        $q->is_author = true;
                        $q->is_profile = true;
                    } else {
                        $q->is_404 = true;
                    }
                } else {
                    $q->is_404 = true;
                }
            } elseif ($q->is_post_type_archive('jobboard-post-jobs') || $q->is_tax(get_object_taxonomies('jobboard-post-jobs')) || is_jb_employer_jobs()) {
                $this->job_query($q);
            }

            remove_action('pre_get_posts', array($this, 'pre_get_posts'));
        }

        function posts_results($posts, $q)
        {

            if (isset($q->is_profile) && $q->is_profile) {
                $posts = false;
            }

            return $posts;
        }

        function the_post($post)
        {
            if ($post->post_type == 'jobboard-post-jobs' && is_jb_candidate()) {
                $post->app_status = JB()->job->get_status($post->ID);
            }

            return $post;
        }

        function job_query($q)
        {
            $ordering = $this->get_catalog_ordering_args();
            $q->set('post_status', 'publish');
            $q->set('post_type', 'jobboard-post-jobs');
            $q->set('posts_per_page', jb_get_option('posts-per-page', 12));
            $q->set('tax_query', $this->get_filter_specialism_query($q->get('tax_query')));
            $q->set('date_query', $this->get_filter_date_query($q->get('date_query')));
            $q->set('meta_query', $this->get_filter_meta_query($q->get('meta_query')));
            $q->set('orderby', $ordering['orderby']);
            $q->set('order', $ordering['order']);

            if (!empty($ordering['meta_query'])) {
                $q->set('meta_query', $ordering['meta_query']);
            }
            if (!empty($_REQUEST['employer_id'])) {
                $q->set('author', intval($_REQUEST['employer_id']));
            }
            do_action('jobboard_pre_get_posts', $q, $this);
        }

        function get_filter_specialism_query($tax_query = array())
        {

            if (!is_array($tax_query)) {
                $tax_query = array();
            }

            $specialism = !empty($_GET['specialism-filters']) ? $_GET['specialism-filters'] : (!empty($_GET['specialism']) ? $_GET['specialism'] : '');

            if (!empty($specialism)) {

                $tax_query[] = array(
                    'taxonomy' => 'jobboard-tax-specialisms',
                    'field' => 'term_id',
                    'terms' => $specialism
                );

            }

            $location = !empty($_GET['location-filters']) ? $_GET['location-filters'] : (!empty($_GET['location']) ? $_GET['location'] : '');

            if (!empty($location) && is_array($location)) {
                if (!empty(get_query_var('jobboard-tax-locations'))) {
                    $location[] = get_query_var('jobboard-tax-locations');
                }
                set_query_var('jobboard-tax-locations',null);
                $tax_query[] = array(
                    'taxonomy' => 'jobboard-tax-locations',
                    'field' => 'slug',
                    'terms' => $location,
                    'operator' => 'IN',
                );
                $tax_query['relation'] = 'AND';
            }

            return apply_filters('jb/query/filter/specialism', $tax_query, $this);
        }

        function get_filter_date_query($date_query = array())
        {

            if (empty($_GET['date-filters'])) {
                return $date_query;
            }

            $hour = (int)$_GET['date-filters'];

            $date = date('Y-m-d H:i:s', strtotime("-{$hour} hour", current_time('timestamp')));

            $date_query[] = array(
                'after' => $date
            );

            return apply_filters('jb/query/filter/date', $date_query, $this);
        }

        function get_filter_meta_query($meta_query = array())
        {
            if (empty($_GET['salary-filters'])) {
                return $meta_query;
            }
            if (!is_array($meta_query)) {
                $meta_query = array(
                    'relation' => 'OR'
                );
            }
            $salaries = $_GET['salary-filters'];
            if (!empty($salaries) && is_array($salaries)) {
                foreach ($salaries as $salary) {
                    if (strpos($salary, ',') !== false && strpos($salary, 'max') === false) {
                        $values = explode(',', $salary);
                        $meta_query[] = array(

                            'key' => '_salary_min',

                            'value' => $values,

                            'type' => 'numeric',

                            'compare' => 'BETWEEN',

                        );
                        $meta_query[] = array(

                            'key' => '_salary_max',

                            'value' => $values,

                            'type' => 'numeric',

                            'compare' => 'BETWEEN',

                        );
                        if (isset($values[0]) && isset($values[1])) {
                            $meta_query[] = array(
                                'relation' => 'AND',
                                array(
                                    'key' => '_salary_min',

                                    'value' => $values[0],

                                    'type' => 'numeric',

                                    'compare' => '<'
                                ),
                                array(

                                    'key' => '_salary_max',

                                    'value' => $values[1],

                                    'type' => 'numeric',

                                    'compare' => '>',

                                )
                            );
                        }
                    } elseif (strpos($salary, ',') !== false && strpos($salary, 'max') !== false) {
                        $values = explode(',', $salary);
                        if (isset($values[0])) {
                            $meta_query[] = array(

                                'key' => '_salary_min',

                                'value' => $values[0],

                                'type' => 'numeric',

                                'compare' => '>',

                            );
                            $meta_query[] = array(

                                'key' => '_salary_max',

                                'value' => $values[0],

                                'type' => 'numeric',

                                'compare' => '>',

                            );
                        }
                    }
                }
            }


            return apply_filters('jb/query/filter/salary', $meta_query, $this);
        }

        function get_catalog_ordering_args($orderby = '', $order = 'DESC')
        {

            if (!$orderby) {
                $orderby = !empty($_GET['orderby']) ? $_GET['orderby'] : 'date';
            }

            $orderby = strtolower($orderby);
            $order = strtoupper($order);
            $args = array();

            // default - menu_order
            $args['orderby'] = 'date';
            $args['order'] = $order == 'DESC' ? 'DESC' : 'ASC';
            $args['meta_key'] = '';

            switch ($orderby) {
                case 'name':
                    $args['orderby'] = 'name';
                    $args['order'] = 'ASC';
                    break;
                case 'applied':
                    $args['orderby'] = 'meta_value_num';
                    $args['meta_key'] = '_applications';
                    $args['order'] = 'DESC';
                    break;
                case 'salary':
                    $args['meta_query'] = array(
                        array(
                            'key' => '_salary_min',
                            'type' => 'DECIMAL',
                            'compare' => 'EXISTS'
                        ),
                        array(
                            'key' => '_salary_currency',
                            'compare' => 'EXISTS'
                        )
                    );
                    $args['orderby'] = "_salary_currency _salary_min";
                    $args['order'] = 'DESC';
                    break;
            }

            return apply_filters('jb/query/catalog/ordering', $args);
        }

        public function body_class($class)
        {

            if (!is_jb()) {
                return $class;
            }

            $class[] = 'is-jobboard';

            if (is_jb_dashboard()) {

                $class[] = 'is-dashboard';

                if (is_jb_candidate_dashboard()) {
                    $class[] = 'is-candidate-dashboard';
                } elseif (is_jb_employer_dashboard()) {
                    $class[] = 'is-employer-dashboard';
                }
            } elseif (is_jb_jobs() || is_jb_taxonomy() || is_jb_employer_jobs()) {
                $class[] = 'is-jobs';
            } elseif (is_jb_account_listing()) {
                $class[] = 'is-accounts';
            } elseif (is_jb_profile()) {
                $class[] = 'is-profile';
            } elseif (is_jb_job()) {
                $class[] = 'is-job';
            }

            return $class;
        }

        public function post_class($classes, $class = '', $post_id = '')
        {
            if (!$post_id || 'jobboard-post-jobs' !== get_post_type($post_id)) {
                return $classes;
            }

            $classes[] = 'clearfix';

            if (false !== ($key = array_search('hentry', $classes))) {
                unset($classes[$key]);
            }

            return $classes;
        }
    }
endif;