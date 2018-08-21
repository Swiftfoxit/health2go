<?php
/**
 * @Template: class-jr-post.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 12-Dec-17
 */
if (!defined('ABSPATH')) {
    die();
}
if (!class_exists('JR_Post')) {
    class JR_Post
    {
        public function __construct()
        {
            add_action('init', array($this, 'register_post_types'), 1);
            add_action('template_redirect', array($this, 'page_redirect'));
            add_action('manage_jr-downloaded_posts_custom_column', array($this, 'column_jobs_td'), 10, 2);
            add_filter('manage_jr-downloaded_posts_columns', array($this, 'column_jobs'));
            add_filter('manage_edit-jr-downloaded_sortable_columns', array($this, 'column_jobs_sortable'));
        }

        public function register_post_types()
        {
            if (!is_blog_installed() || post_type_exists('jb-resources')) {
                return;
            }
            $labels = array(
                'name' => __("JB Resources", JB_RESOURCES_TEXT_DOMAIN),
                'singular_name' => __("JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'menu_name' => _x("JB Resources", 'Admin menu name', JB_RESOURCES_TEXT_DOMAIN),
                'add_new' => __("Add JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'add_new_item' => __("Add new Jobboard Resource", JB_RESOURCES_TEXT_DOMAIN),
                'edit' => __("Edit", JB_RESOURCES_TEXT_DOMAIN),
                'edit_item' => __("Edit JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'new_item' => __("New JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                "all_items" => __("All JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'view' => __("View JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'view_item' => __("View JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'search_items' => __("Search JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'parent' => __("Parent JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'featured_image' => __("Resource image", JB_RESOURCES_TEXT_DOMAIN),
                'filter_items_list' => __("Filter JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'items_list_navigation' => __("JB Resource navigation", JB_RESOURCES_TEXT_DOMAIN),
                'items_list' => __("Products list", JB_RESOURCES_TEXT_DOMAIN),
            );
            $labels_dl = array(
                'name' => esc_html__('Downloaded List', JB_RESOURCES_TEXT_DOMAIN),
                'singular_name' => esc_html__('Downloaded List', JB_RESOURCES_TEXT_DOMAIN),
                'menu_name' => esc_html__('Downloaded List', JB_RESOURCES_TEXT_DOMAIN),
                "all_items" => esc_html__('Downloaded List', JB_RESOURCES_TEXT_DOMAIN),
                'search_items' => __("Search JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'filter_items_list' => __("Filter JB Resource", JB_RESOURCES_TEXT_DOMAIN),
                'items_list_navigation' => __("JB Resource navigation", JB_RESOURCES_TEXT_DOMAIN),
                'items_list' => __("Products list", JB_RESOURCES_TEXT_DOMAIN),
            );
            global $redux_meta;
            $page_resources = jb_get_option('page-resources');
            $labels = apply_filters('jr_post_type_labels', $labels);
            $args = array(
                'labels' => $labels,
                'description' => __('This is where you can add new products to your store.', JB_RESOURCES_TEXT_DOMAIN),
                'show_ui' => true,
                'rewrite' => array(
                    'slug' => jb_get_option('resources-slug', 'recruitment-resources')
                ),
                'public' => true,
                'show_in_nav_menus' => true,
                'menu_icon' => 'dashicons-category',
                'show_in_menu' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'author'),
            );
            $labels_dl = apply_filters('jr_post_type_dl_labels', $labels_dl);
            $args_dl = array(
                'labels' => $labels_dl,
                'description' => __('This is where you can add new products to your store.', JB_RESOURCES_TEXT_DOMAIN),
                'show_ui' => true,
                'public' => false,
                'show_in_nav_menus' => false,
                'show_in_menu' => 'edit.php?post_type=jb-resources',
                'supports' => array('title', 'editor', 'thumbnail', 'author'),
                'capability_type' => 'post',
                'capabilities' => array(
                    'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
                ),
                'map_meta_cap' => true,
            );
            if ($page_resources && get_post($page_resources)) {
                $args['has_archive'] = get_page_uri($page_resources);
            }
            register_post_type('jb-resources', $args);
            register_post_type('jr-downloaded', $args_dl);
            $setting = JB()->post->post_args();
            $setting['open_expanded'] = true;
            $redux_meta->post->add($setting, $this->sections_event(), 'resources-edit', esc_html__('Edit', JB_RESOURCES_TEXT_DOMAIN), 'jb-resources');

        }

        function page_redirect()
        {
            if (!empty($_GET['page_id']) && $_GET['page_id'] === intval(jb_get_option('page-resources'))) {
                wp_safe_redirect(get_post_type_archive_link("jb-resources"));
                exit;
            }
        }

        public function rewrite_permark_link()
        {
            unregister_post_type($this->post_type);
            $this->register_post_types();
            flush_rewrite_rules();
        }

        public function sections_event()
        {
            $sections = array(
                'setting' => array(
                    'title' => '',
                    'id' => 'resources-meta',
                    'fields' => array(
                        array(
                            'id' => '_resources_file',
                            'type' => 'rc_select_file',
                            'title' => esc_html__('Resources File', JB_RESOURCES_TEXT_DOMAIN),
                            'subtitle' => esc_html__('Choose file.', JB_RESOURCES_TEXT_DOMAIN),
                            'button_upload' => esc_html__('Upload', JB_RESOURCES_TEXT_DOMAIN),
                            'button_remove' => esc_html__('Remove', JB_RESOURCES_TEXT_DOMAIN),
                        )
                    )
                )
            );

            return $sections;
        }

        public function add_download_page_menu()
        {
            add_submenu_page('edit.php?post_type=jb-resources', esc_html__('Downloaded List', JB_RESOURCES_TEXT_DOMAIN), esc_html__('Downloaded List', JB_RESOURCES_TEXT_DOMAIN), 'manage_options', 'jr-downloaded', array($this, 'jr_downloaded_list'));
        }

        public function jr_downloaded_list()
        {
            $jrs = get_posts(array(
                'post_type' => 'jb-resources',
                'posts_per_page' => -1
            ));
            $downloaded_list = array();
            foreach ($jrs as $jr) {
                if (is_array(get_post_meta($jr->ID, '_list_mail_dowload', true)))
                    $downloaded_list = array_merge($downloaded_list, get_post_meta($jr->ID, '_list_mail_dowload', true));
            }
            $sortable_array = $this->jr_array_sort($downloaded_list, 'dl_id', SORT_DESC);
            jb_resources()->get_template('downloaded-list.php', array('jr' => $sortable_array));
        }

        function jr_array_sort($array, $on, $order = SORT_ASC)
        {
            $new_array = array();
            $sortable_array = array();

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 === $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }

                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                        break;
                    case SORT_DESC:
                        arsort($sortable_array);
                        break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }

            return $new_array;
        }

        function column_jobs_td($column, $post_id)
        {
            global $post;

            switch ($column) {
                case 'jr_dl_id':
                    echo '<span>#JR_DL_' . $post_id . '</span>';
                    break;
                case 'jr_dl_email':
                    echo '<span>' . get_the_title($post_id) . '</span>';
                    break;
                case 'jr_id':
                    $jr_id = get_post_meta($post_id, '_resource_id', true);
                    echo '<a href="' . get_permalink($jr_id) . '">' . $jr_id . ' - ' . get_the_title($jr_id) . '</a>';
                    break;
                case 'date_time':
                    $t_time = get_the_time( esc_html__( 'Y/m/d g:i:s A', 'jobboard' ), $post );
                    $h_time = get_the_time( esc_html__( 'Y/m/d', 'jobboard' ), $post );
                    echo '<span>'. $t_time . '</span>';
                    break;
            }
        }

        function column_jobs($columns)
        {
            unset($columns['date']);
            unset($columns['title']);
            unset($columns['author']);
            unset($columns['date']);

            $columns['jr_dl_id'] = sprintf(esc_html__('%s ID', JB_RESOURCES_TEXT_DOMAIN), '<i class="dashicons dashicons-welcome-write-blog"></i>');
            $columns['jr_dl_email'] = sprintf(esc_html__('%s Email', JB_RESOURCES_TEXT_DOMAIN), '<i class="dashicons dashicons-admin-users"></i>');
            $columns['jr_id'] = sprintf(esc_html__('%s Resource ID', JB_RESOURCES_TEXT_DOMAIN), '<i class="dashicons dashicons-admin-links"></i>');
            $columns['date_time'] = sprintf(esc_html__('%s Date', JB_RESOURCES_TEXT_DOMAIN), '<i class="dashicons dashicons-calendar-alt"></i>');
            return $columns;
        }

        function column_jobs_sortable($columns)
        {
            $custom = array(
                'jr_dl_id' => 'name',
                'date_time' => 'date'
            );

            return wp_parse_args($custom, $columns);
        }
    }
}