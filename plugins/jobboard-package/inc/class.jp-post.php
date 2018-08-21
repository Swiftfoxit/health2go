<?php
/**
 * JobBoard Package Post.
 *
 * Action/filter hooks used for JobBoard Package post.
 *
 * @author        FOX
 * @category    Core
 * @package    JobBoard/Package
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('JB_Package_Post')) :
    class JB_Package_Post
    {

        function __construct()
        {
            add_action('init', array($this, 'post_status'), 0);
            add_action('init', array($this, 'post_types'), 5);
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 5);
            add_action('manage_jb-orders_posts_custom_column', array($this, 'display_orders_column'), 10, 2);
            add_action('manage_jb-package-employer_posts_custom_column', array($this, 'display_package_employer_column'), 10, 2);
            add_action('manage_jb-package-candidate_posts_custom_column', array($this, 'display_package_candidate_column'), 10, 2);
            add_action('redux/options/jobboard_meta/options', array($this, 'set_invoice_value'), 50);

            add_filter('post_row_actions', array($this, 'remove_quick_edit'), 10, 2);
            add_filter('manage_jb-orders_posts_columns', array($this, 'add_orders_column'));
            add_filter('manage_edit-jb-orders_sortable_columns', array($this, 'orders_sortable_columns'));
            add_filter('manage_jb-package-employer_posts_columns', array($this, 'add_package_employer_column'));
            add_filter('manage_jb-package-candidate_posts_columns', array($this, 'add_package_candidate_column'));
            add_filter('redux/options/jobboard_meta/field/_customer_id', array($this, 'rename_customer_id'));
            add_filter('redux/options/jobboard_meta/field/_invoice', array($this, 'rename_invoice'));
        }

        function post_types()
        {
            global $redux_meta;

            $package_labels = array(
                'name'           => esc_html__('Packages', JB_PACKAGE_TEXT_DOMAIN),
                'singular_name'  => esc_html__('Packages', JB_PACKAGE_TEXT_DOMAIN),
                'menu_name'      => esc_html__('Packages', JB_PACKAGE_TEXT_DOMAIN),
                'name_admin_bar' => esc_html__('Packages', JB_PACKAGE_TEXT_DOMAIN),
                'add_new'        => esc_html__('New Package', JB_PACKAGE_TEXT_DOMAIN),
                'add_new_item'   => esc_html__('New Package', JB_PACKAGE_TEXT_DOMAIN),
                'edit_item'      => esc_html__('Edit Package', JB_PACKAGE_TEXT_DOMAIN),
                'all_items'      => esc_html__('Employer', JB_PACKAGE_TEXT_DOMAIN),
            );

            $package_args = array(
                'labels'       => $package_labels,
                'show_ui'      => true,
                'public'       => false,
                'show_in_menu' => 'edit.php?post_type=jobboard-post-jobs',
                'supports'     => array('title'),
            );

            $orders_labels = array(
                'name'           => esc_html__('Orders', JB_PACKAGE_TEXT_DOMAIN),
                'singular_name'  => esc_html__('Orders', JB_PACKAGE_TEXT_DOMAIN),
                'menu_name'      => esc_html__('Orders', JB_PACKAGE_TEXT_DOMAIN),
                'name_admin_bar' => esc_html__('Orders', JB_PACKAGE_TEXT_DOMAIN),
                'add_new'        => esc_html__('New Order', JB_PACKAGE_TEXT_DOMAIN),
                'add_new_item'   => esc_html__('New Order', JB_PACKAGE_TEXT_DOMAIN),
                'edit_item'      => esc_html__('Edit Order', JB_PACKAGE_TEXT_DOMAIN),
                'all_items'      => esc_html__('Orders', JB_PACKAGE_TEXT_DOMAIN)
            );

            $orders_args = array(
                'labels'       => $orders_labels,
                'show_ui'      => true,
                'public'       => false,
                'show_in_menu' => 'edit.php?post_type=jobboard-post-jobs',
                'supports'     => array(''),
            );

            register_post_type('jb-orders', $orders_args);
            register_post_type('jb-package-employer', $package_args);

            $package_args['labels']['all_items'] = esc_html__('Candidate', JB_PACKAGE_TEXT_DOMAIN);
            register_post_type('jb-package-candidate', $package_args);

            if (empty($redux_meta)) {
                return;
            }

            $setting = JB()->post->post_args();
            $redux_meta->post->add($setting, $this->sections_package_employer(), 'package-employer-meta', esc_html__('Package Data', JB_PACKAGE_TEXT_DOMAIN), 'jb-package-employer');
            $redux_meta->post->add($setting, $this->sections_package_candidate(), 'package-candidate-meta', esc_html__('Package Data', JB_PACKAGE_TEXT_DOMAIN), 'jb-package-candidate');
            $setting['open_expanded'] = true;
            $redux_meta->post->add($setting, $this->sections_order(), 'order-edit', esc_html__('Edit', JB_PACKAGE_TEXT_DOMAIN), 'jb-orders');
        }

        function post_status()
        {
            $status = jb_package_get_order_status_args();

            foreach ($status as $k => $v) {
                register_post_status($k, array(
                    'label'                     => $v,
                    'label_count'               => _n_noop("{$v} (%s)", "{$v} (%s)", JB_PACKAGE_TEXT_DOMAIN),
                    'public'                    => true,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    'exclude_from_search'       => true,
                ));
            }
        }

        function sections_package_employer()
        {
            $sections = array(
                array(
                    'title'  => esc_html__('Options', JB_PACKAGE_TEXT_DOMAIN),
                    'id'     => 'options',
                    'icon'   => 'dashicons dashicons-admin-settings',
                    'fields' => array(
                        array(
                            'id'       => '_featured',
                            'type'     => 'switch',
                            'title'    => esc_html__('Featured', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Feature package, package will display prominently.', JB_PACKAGE_TEXT_DOMAIN),
                            'default'  => false,
                        ),
                        array(
                            'id'          => '_price',
                            'type'        => 'rc_number',
                            'title'       => sprintf(esc_html__('Price (%s)', JB_PACKAGE_TEXT_DOMAIN), jb_get_currency_symbol(jb_get_option('default-currency', 'USD'))),
                            'subtitle'    => esc_html__('Package price, set the price for the package.', JB_PACKAGE_TEXT_DOMAIN),
                            'placeholder' => esc_html__('Free Package', JB_PACKAGE_TEXT_DOMAIN),
                            'min'         => 0,
                            'step'        => 0.01
                        ),
                        array(
                            'id'       => '_membership',
                            'type'     => 'rc_number',
                            'title'    => esc_html__('Membership (Month)', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Membership, limited membership time for package.', JB_PACKAGE_TEXT_DOMAIN),
                            'default'  => 1,
                            'min'      => 1
                        )
                    )
                ),
                array(
                    'title'  => esc_html__('Rule', JB_PACKAGE_TEXT_DOMAIN),
                    'id'     => 'rule',
                    'icon'   => 'dashicons dashicons-unlock',
                    'fields' => array(
                        array(
                            'id'       => '_profile',
                            'type'     => 'switch',
                            'title'    => esc_html__('Public Profile', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Candidate can view profile the employer, default private.', JB_PACKAGE_TEXT_DOMAIN),
                            'default'  => false,
                        ),
                        array(
                            'id'          => '_jobs',
                            'type'        => 'rc_number',
                            'title'       => esc_html__('Jobs', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle'    => esc_html__('Limit number submit jobs, default "0" job.', JB_PACKAGE_TEXT_DOMAIN),
                            'placeholder' => esc_html__('0', JB_PACKAGE_TEXT_DOMAIN),
                            'min'         => 0
                        ),
                        array(
                            'id'          => '_features',
                            'type'        => 'rc_number',
                            'title'       => esc_html__('Jobs Feature', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle'    => esc_html__('Limit number featured jobs, default "0" job.', JB_PACKAGE_TEXT_DOMAIN),
                            'placeholder' => esc_html__('0', JB_PACKAGE_TEXT_DOMAIN),
                            'min'         => 0
                        ),
                        array(
                            'id'          => '_cvs',
                            'type'        => 'rc_number',
                            'title'       => esc_html__('View CV', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle'    => esc_html__('Limit number of cv can download, default "-1" CV.', JB_PACKAGE_TEXT_DOMAIN),
                            'placeholder' => esc_html__('0', JB_PACKAGE_TEXT_DOMAIN),
                            'default'     => -1,
                            'min'         => -1
                        )
                    )
                )
            );

            return $sections;
        }

        function sections_package_candidate()
        {
            $sections = array(
                array(
                    'title'  => esc_html__('Options', JB_PACKAGE_TEXT_DOMAIN),
                    'id'     => 'options',
                    'icon'   => 'dashicons dashicons-admin-settings',
                    'fields' => array(
                        array(
                            'id'       => '_featured',
                            'type'     => 'switch',
                            'title'    => esc_html__('Featured', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Feature package, package will display prominently.', JB_PACKAGE_TEXT_DOMAIN),
                            'default'  => false,
                        ),
                        array(
                            'id'          => '_price',
                            'type'        => 'rc_number',
                            'title'       => sprintf(esc_html__('Price (%s)', JB_PACKAGE_TEXT_DOMAIN), jb_get_currency_symbol(jb_get_option('default-currency', 'USD'))),
                            'subtitle'    => esc_html__('Package price, set the price for the package.', JB_PACKAGE_TEXT_DOMAIN),
                            'placeholder' => esc_html__('Free Package', JB_PACKAGE_TEXT_DOMAIN),
                            'min'         => 0
                        ),
                        array(
                            'id'       => '_membership',
                            'type'     => 'rc_number',
                            'title'    => esc_html__('Membership (Month)', JB_PACKAGE_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Membership, limited membership time for package.', JB_PACKAGE_TEXT_DOMAIN),
                            'default'  => 1,
                            'min'      => 1
                        )
                    )),
                array(
                    'title'  => esc_html__('Rule', 'jobboard-package'),
                    'id'     => 'rule',
                    'icon'   => 'dashicons dashicons-unlock',
                    'fields' => array(
                        array(
                            'id'       => '_profile',
                            'type'     => 'switch',
                            'title'    => esc_html__('Public Profile', 'jobboard-package'),
                            'subtitle' => esc_html__('Employer can view profile the candidate, default private.', 'jobboard-package'),
                            'default'  => false,
                        ),
                        array(
                            'id'          => '_apply',
                            'type'        => 'rc_number',
                            'title'       => esc_html__('Apply Jobs', 'jobboard-package'),
                            'subtitle'    => esc_html__('Limit the number of jobs to be applied, default 0.', 'jobboard-package'),
                            'placeholder' => esc_html__('Unlimited', 'jobboard-package'),
                            'default'     => 5,
                            'min'         => 1
                        ),
                        array(
                            'id'          => '_cvs',
                            'type'        => 'rc_number',
                            'title'       => esc_html__('View CV', 'jobboard-package'),
                            'subtitle'    => esc_html__('Limit the number of cv can download, default -1.', 'jobboard-package'),
                            'placeholder' => esc_html__('Unlimited', 'jobboard-package'),
                            'default'     => -1,
                            'min'         => -1
                        ),
                    )
                )
            );
            return $sections;
        }

        function sections_order()
        {

            $payments = jb_package_get_payments();
            $_payments = array();
            $_package = jb_package_get_package();
            foreach ($payments as $k => $v) {
                $_payments[$k] = $v['name'];
            }

            $sections = array(
                array(
                    'title'  => '',
                    'id'     => 'order-meta',
                    'fields' => array(
                        array(
                            'id'       => '_invoice',
                            'type'     => 'text',
                            'title'    => esc_html__('Invoice', 'jobboard-package'),
                            'subtitle' => esc_html__('Change invoice ID.', 'jobboard-package')
                        ),
                        array(
                            'id'       => '_price',
                            'type'     => 'rc_number',
                            'title'    => sprintf(esc_html__('Price (%s)', 'jobboard-package'), jb_get_currency_symbol(jb_get_option('default-currency', 'USD'))),
                            'subtitle' => esc_html__('Change price.', 'jobboard-package'),
                            'min'      => 0,
                        ),
                        array(
                            'id'       => '_package_id',
                            'type'     => 'select',
                            'title'    => esc_html__('Package ID', 'jobboard-package'),
                            'subtitle' => esc_html__('Change Package ID.', 'jobboard-package'),
                            'options'  => $_package
                        ),
                        array(
                            'id'       => '_payment',
                            'type'     => 'select',
                            'title'    => esc_html__('Payment', 'jobboard-package'),
                            'subtitle' => esc_html__('Change payment method.', 'jobboard-package'),
                            'options'  => $_payments,
                        ),
                        array(
                            'id'          => '_customer_id',
                            'type'        => 'rc_ajax_select',
                            'title'       => esc_html__('Customer', 'jobboard-package'),
                            'subtitle'    => esc_html__('Change Customer.', 'jobboard-package'),
                            'desc'        => esc_html__('Enter user ID, user email, user display name...', 'jobboard-package'),
                            'source'      => 'user',
                            'source-type' => 'user',
                            'save'        => 'user'
                        ),
                    )
                )
            );

            return $sections;
        }

        function rename_invoice($field)
        {
            $field['name'] = 'post_title';
            return $field;
        }

        /**
         * rename customer id field.
         *
         * @param $field
         * @return mixed
         */
        function rename_customer_id($field)
        {

            $field['name'] = 'post_author_override';

            return $field;
        }

        /**
         * remove submit meta boxes.
         */
        function remove_meta_boxes()
        {
            remove_meta_box('submitdiv', 'jb-orders', 'side');
        }

        /**
         * set options.
         *
         * @param $options
         * @return mixed
         */
        function set_invoice_value($options)
        {
            global $post;

            if (!is_admin()) {
                return $options;
            }

            if (!$post) {
                return $options;
            }

            if ($post->post_type != 'jb-orders') {
                return $options;
            }

            $options['_invoice'] = $post->post_title;
            $options['_customer_id'] = $post->post_author;

            return $options;
        }

        /**
         * remove quick edit.
         *
         * @param $actions
         * @param $post
         * @return mixed
         */
        function remove_quick_edit($actions, $post)
        {
            if ($post->post_type == 'jb-orders') {
                unset($actions['inline hide-if-no-js']);
            }
            return $actions;
        }

        function add_meta_boxes()
        {
            add_meta_box('orders-info', esc_html__('Order', 'jobboard-package'), array($this, 'orders_info_meta_box'), 'jb-orders');
            add_filter("postbox_classes_jb-orders_orders-info", array($this, 'add_meta_box_class'));
        }

        function add_meta_box_class($class)
        {

            $screen = get_current_screen();

            if ($screen->action === 'add') {
                $class[] = 'hidden';
            }

            return $class;
        }

        function add_orders_column($columns)
        {


            unset($columns['title']);
            unset($columns['date']);

            $columns['order'] = sprintf(esc_html__('%s Order', 'jobboard-package'), '<i class="dashicons dashicons-cart"></i>');
            $columns['package'] = sprintf(esc_html__('%s Package', 'jobboard-package'), '<i class="dashicons dashicons-portfolio"></i>');
            $columns['time'] = sprintf(esc_html__('%s Date', 'jobboard-package'), '<i class="dashicons dashicons-calendar-alt"></i>');
            $columns['total'] = sprintf(esc_html__('%s Total', 'jobboard-package'), '<i class="dashicons dashicons-chart-line"></i>');
            $columns['status'] = sprintf(esc_html__('%s Status', 'jobboard-package'), '<i class="dashicons dashicons-flag"></i>');
            $columns['actions'] = sprintf(esc_html__('%s Actions', 'jobboard-package'), '<i class="dashicons dashicons-admin-settings"></i>');
            $columns['actions'] = sprintf(esc_html__('%s Actions', 'jobboard-package'), '<i class="dashicons dashicons-admin-settings"></i>');

            return $columns;
        }

        function add_package_employer_column($columns)
        {
            unset($columns['date']);
            $columns['featured'] = __('Featured', 'jobboard-package');
            $columns['jobs'] = __('Listing', 'jobboard-package');
            $columns['feature'] = __('Jobs Feature', 'jobboard-package');
            $columns['membership'] = __('Membership', 'jobboard-package');
            return $columns;
        }

        function add_package_candidate_column($columns)
        {
            unset($columns['date']);
            $columns['featured'] = __('Featured', 'jobboard-package');
            $columns['applied'] = __('Applied', 'jobboard-package');
            $columns['membership'] = __('Membership', 'jobboard-package');
            return $columns;
        }

        function display_package_employer_column($column, $post_id)
        {
            switch ($column) {
                case 'featured':
                    if (get_post_meta($post_id, '_featured', true)) {
                        echo '<span class="dashicons dashicons-star-filled"></span>';
                    } else {
                        echo '<span class="dashicons dashicons-star-empty"></span>';
                    }
                    break;
                case 'jobs';
                    $jobs = jb_package_get_rule('_jobs', $post_id);
                    printf(_n('%s job', '%s jobs', $jobs, 'jobboard-package'), '<span>' . esc_html($jobs) . '</span>');
                    break;
                case 'feature';
                    $feature = jb_package_get_rule('_features', $post_id);
                    printf(_n('%s job', '%s jobs', $feature, 'jobboard-package'), '<span>' . esc_html($feature) . '</span>');
                    break;
                case 'membership':
                    $membership = jb_package_get_rule('_membership', $post_id);
                    printf(_n('%s / %s month', '%s / %s months', $membership, 'jobboard-package'), jb_package_get_price_html(), '<span>' . esc_html($membership) . '</span>');
                    break;
            }
        }

        function display_package_candidate_column($column, $post_id)
        {
            switch ($column) {
                case 'featured':
                    if (get_post_meta($post_id, '_featured', true)) {
                        echo '<span class="dashicons dashicons-star-filled"></span>';
                    } else {
                        echo '<span class="dashicons dashicons-star-empty"></span>';
                    }
                    break;
                case 'applied':
                    $applied = jb_package_get_rule('_apply', $post_id);
                    printf(_n('%s job', '%s jobs', $applied, 'jobboard-package'), '<span>' . esc_html($applied) . '</span>');
                    break;
                case 'membership':
                    $membership = jb_package_get_rule('_membership', $post_id);
                    printf(_n('%s / %s month', '%s / %s months', $membership, 'jobboard-package'), jb_package_get_price_html(), '<span>' . esc_html($membership) . '</span>');
                    break;
            }
        }

        function display_orders_column($column, $post_id)
        {
            global $post;

            switch ($column) {
                case 'order':
                    $author = get_user_by('ID', $post->post_author);
                    $order = '<a href="' . esc_url(get_edit_post_link($post_id)) . '">#' . get_the_title() . '</a>';
                    $user_name = '<a href="' . get_edit_profile_url($post->post_author) . '">' . $author->display_name . '</a>';

                    echo '<div class="order-title clearfix">';
                    echo '<div class="order-avatar"><a href="' . get_edit_profile_url($post->post_author) . '">' . get_avatar($post->post_author) . '</a></div>';

                    echo '<div class="order-info">';
                    echo '<div class="order-info-header row-title">';

                    echo sprintf(esc_html__('%s by %s', 'jobboard-package'), $order, $user_name);

                    echo '</div>';
                    echo '<a class="order-info-email" href="mailto:' . $author->user_email . '"><i class="dashicons dashicons-email"></i> ' . $author->user_email . '</a>';
                    echo '</div>';
                    echo '</div>';
                    break;
                case 'package':
                    $package = get_post_meta($post->ID, '_package_id', true);
                    echo '<a href="' . esc_url(get_edit_post_link($package)) . '">' . get_the_title($package) . '</a>';
                    break;
                case 'time':

                    $t_time = get_the_time(esc_html__('Y/m/d g:i:s A', 'jobboard-package'), $post);
                    $h_time = get_the_time(esc_html__('Y/m/d', 'jobboard-package'), $post);

                    echo '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
                    break;
                case 'total':

                    $payment = get_post_meta($post->ID, '_payment', true);
                    $payment_args = jb_package_get_payment($payment);
                    $price = get_post_meta($post->ID, '_price', true);

                    echo '<span class="amount">' . jb_package_get_price_html($price) . '</span>';
                    echo '<small class="meta">' . sprintf(__('Via %s', 'jobboard-package'), $payment_args['name']) . '</small>';
                    break;
                case 'status':
                    echo jb_package_get_order_status_html($post->post_status);
                    break;
                case 'actions':
                    echo '<a class="button tips view" href="' . esc_url(get_edit_post_link($post_id)) . '" title="View"><i class="dashicons dashicons-visibility"></i></a>';
                    break;
            }
        }

        function orders_sortable_columns($columns)
        {
            $custom = array(
                'order' => 'ID',
                'time'  => 'date'
            );

            return wp_parse_args($custom, $columns);
        }

        function orders_info_meta_box()
        {
            global $post;

            $payment = get_post_meta($post->ID, '_payment', true);
            $payment_args = jb_package_get_payment($payment);
            $price = get_post_meta($post->ID, '_price', true);
            $customer = get_user_by('ID', $post->post_author);
            $package = get_post_meta($post->ID, '_package_id', true);
            $t_time = get_the_time(esc_html__('Y/m/d g:i:s A', 'jobboard-package'), $package);
            $base = get_post_meta($package, '_price', true);
            $client_ip = get_post_meta($post->ID, '_client_ip', true);

            echo '<div class="order-title"><h1>' . sprintf(esc_html__('Order #%s details', 'jobboard-package'), $post->post_title) . '</h1>';
            echo jb_package_get_order_status_html($post->post_status);
            echo '<span class="order-payment">' . sprintf(esc_html__('Payment via %s.', 'jobboard-package'), $payment_args['name']) . '</span></div>';
            echo '<table class="order-table"><tbody>';
            echo '<tr>';
            echo '<th>' . sprintf(esc_html__('%s Order', 'jobboard-package'), '<i class="dashicons dashicons-welcome-write-blog"></i>') . '</th>';
            echo '<th>' . sprintf(esc_html__('%s Package', 'jobboard-package'), '<i class="dashicons dashicons-portfolio"></i>') . '</th>';
            echo '<th>' . sprintf(esc_html__('%s Customer', 'jobboard-package'), '<i class="dashicons dashicons-admin-users"></i>') . '</th>';
            echo '</tr>';
            echo '<tr>';
            echo '<td><table class="table-order"><tbody>';
            echo '<tr>';
            echo '<th>' . esc_html__('Invoice', 'jobboard-package') . '</th>';
            echo '<td><strong>#' . esc_html($post->post_title) . '</strong></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>' . esc_html__('Buy Price', 'jobboard-package') . '</th>';
            echo '<td><strong>' . jb_package_get_price_html($price) . '</strong></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>' . esc_html__('Date', 'jobboard-package') . '</th>';
            echo '<td>' . esc_html($t_time) . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>' . esc_html__('Via', 'jobboard-package') . '</th>';
            echo '<td>' . esc_html($payment_args['name']) . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>' . esc_html__('IP Address', 'jobboard-package') . '</th>';
            echo '<td>' . esc_html($client_ip) . '</td>';
            echo '</tr>';
            echo '</tbody></table></td>';
            echo '<td><table class="table-package"><tbody>';
            echo '<tr>';
            echo '<th>' . esc_html__('Type', 'jobboard-package') . '</th>';
            echo '<td><a href="' . esc_url(get_permalink($package)) . '">' . get_the_title($package) . '</a></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>' . esc_html__('Base', 'jobboard-package') . '</th>';
            echo '<td><strong>' . jb_package_get_price_html($base) . '</strong></td>';
            echo '</tr>';
            echo '</tbody></table></td>';
            echo '<td><table class="table-user"><tbody>';
            echo '<tr>';
            echo '<th>' . esc_html__('User Name', 'jobboard-package') . '</th>';
            echo '<td><a href="' . esc_url(get_edit_profile_url($customer->ID)) . '">' . esc_html($customer->display_name) . '</a></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<th>' . esc_html__('Email Address', 'jobboard-package') . '</th>';
            echo '<td><a href="mailto:' . esc_attr($customer->user_email) . '">' . esc_html($customer->user_email) . '</a></td>';
            echo '</tr>';
            echo '</tbody></table></td>';
            echo '</tr>';
            echo '</tbody></table>';
        }
    }

endif;

new JB_Package_Post();