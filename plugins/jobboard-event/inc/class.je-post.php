<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 8:27 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'JB_Event_Post' ) ) {
	class JB_Event_Post {
		public function __construct() {
			add_action( 'init', array( $this, 'add_event_post' ) );
		}

		public function add_event_post() {
			global $redux_meta;
			$page_event   = jb_get_option( 'page-events' );
			$event_labels = array(
				'name'           => esc_html__( 'Events', JB_EVENT_TEXT_DOMAIN ),
				'singular_name'  => esc_html__( 'Events', JB_EVENT_TEXT_DOMAIN ),
				'menu_name'      => esc_html__( 'JB Event', JB_EVENT_TEXT_DOMAIN ),
				'name_admin_bar' => esc_html__( 'Events', JB_EVENT_TEXT_DOMAIN ),
				'add_new'        => esc_html__( 'New', JB_EVENT_TEXT_DOMAIN ),
				'add_new_item'   => esc_html__( 'New Event', JB_EVENT_TEXT_DOMAIN ),
				'edit_item'      => esc_html__( 'Edit Event', JB_EVENT_TEXT_DOMAIN ),
				'all_items'      => esc_html__( 'All', JB_EVENT_TEXT_DOMAIN )
			);

			$event = array(
				'menu_icon' => 'dashicons-pressthis',
				'labels'    => $event_labels,
				'show_ui'   => true,
				'public'    => true,
				'supports'  => array( 'title', 'editor' ),
			);

			if ( $page_event && get_post( $page_event ) ) {
				$event['has_archive'] = get_page_uri( $page_event );
			}

			$event_type_labels = array(
				'name'              => esc_html__( 'Types', JB_EVENT_TEXT_DOMAIN ),
				'singular_name'     => esc_html__( 'Type', JB_EVENT_TEXT_DOMAIN ),
				'search_items'      => esc_html__( 'Search Types', JB_EVENT_TEXT_DOMAIN ),
				'all_items'         => esc_html__( 'All Types', JB_EVENT_TEXT_DOMAIN ),
				'parent_item'       => esc_html__( 'Parent Type', JB_EVENT_TEXT_DOMAIN ),
				'parent_item_colon' => esc_html__( 'Parent Type:', JB_EVENT_TEXT_DOMAIN ),
				'edit_item'         => esc_html__( 'Edit Type', JB_EVENT_TEXT_DOMAIN ),
				'update_item'       => esc_html__( 'Update Type', JB_EVENT_TEXT_DOMAIN ),
				'add_new_item'      => esc_html__( 'Add New Type', JB_EVENT_TEXT_DOMAIN ),
				'new_item_name'     => esc_html__( 'New Type', JB_EVENT_TEXT_DOMAIN ),
				'menu_name'         => esc_html__( 'Types', JB_EVENT_TEXT_DOMAIN ),
			);

			$event_type = array(
				'hierarchical'       => true,
				'labels'             => $event_type_labels,
				'show_ui'            => true,
				'show_admin_column'  => true,
				'query_var'          => true,
				'show_in_quick_edit' => true,
				'rewrite'            => array(
					'slug' => jb_get_option( 'taxonomy-event-type-slug', 'event-type' )
				),
			);

			register_post_type( 'jb-events', $event );
			register_taxonomy( 'jobboard-event-type', array( 'jb-events' ), apply_filters( 'jb/taxonomy/event_type/args', $event_type ) );

			$setting                  = JB()->post->post_args();
			$setting['open_expanded'] = false;
			$redux_meta->post->add( $setting, $this->sections_event(), 'event-edit', esc_html__( 'Edit', JB_EVENT_TEXT_DOMAIN ), 'jb-events' );

            /**
             * Create post type Events Registered
             */
            $pending_inbox = new WP_Query( array( 'post_type' => 'je-inbox', 'post_status' => 'pending' ) );
            $inbox_pending = $pending_inbox->found_posts;
            $menu_label = sprintf(__('Events Registered %s',JB_EVENT_TEXT_DOMAIN), "<span class='update-plugins count-$inbox_pending' title='$inbox_pending'><span class='update-count'>" . number_format_i18n($inbox_pending) . "</span></span>");
            $labels = array(
                'name' => esc_attr__('Events', JB_EVENT_TEXT_DOMAIN),
                'all_items' => $menu_label,
                'singular_name' => esc_attr__('Show Event', JB_EVENT_TEXT_DOMAIN),
                'add_new' => esc_attr__('New Event', JB_EVENT_TEXT_DOMAIN),
                'add_new_item' => esc_attr__('New Event', JB_EVENT_TEXT_DOMAIN),
                'edit_item' => esc_attr__('Edit Event', JB_EVENT_TEXT_DOMAIN),
            );
            $args = array(
                'labels' => $labels,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail'
                ),
                'show_ui' => true,
                'hierarchical' => true,
                'public' => false,
                'show_in_nav_menus' => true,
                'menu_icon' => 'dashicons-list-view',
                'has_archive' => false,
                'show_in_menu' => 'edit.php?post_type=jb-events',
            );
            register_post_type('je-inbox', $args);
		}

		public function sections_event() {
			$sections = array(
				'setting' => array(
					'title'  => 'Event information',
					'id'     => 'event-meta',
					'fields' => array(
						array(
							'id'       => '_start',
							'type'     => 'rc_datetime',
							'title'    => esc_html__( 'Start', JB_EVENT_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Choose datetime.', JB_EVENT_TEXT_DOMAIN )
						),
						array(
							'id'       => '_end',
							'type'     => 'rc_datetime',
							'title'    => esc_html__( 'End', JB_EVENT_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Choose datetime.', JB_EVENT_TEXT_DOMAIN )
						),
						array(
							'id'       => '_address',
							'type'     => 'textarea',
							'title'    => esc_html__( 'Complete Address', JB_EVENT_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Enter you complete address with city, state or country.', JB_EVENT_TEXT_DOMAIN ),
							'default'  => '',
						)
					)
				),
				'event_speaker' => array(
					'title'  => 'Event Speaker',
					'id'     => 'event-speaker',
					'fields' => array(
                        array(
                            'id'       => '_speaker_avatar',
                            'title'    => esc_html__( 'Avatar', JB_EVENT_TEXT_DOMAIN ),
                            'subtitle' => esc_html__( 'Choose avatar.', JB_EVENT_TEXT_DOMAIN ),
                            'type'     => 'media',
                            'input'    => 'image',
                            'types'    => 'jpg,png',
                            'col'      => 6,
                        ),
						array(
							'id'       => '_speaker_name',
							'type'     => 'text',
							'title'    => esc_html__( 'Name', JB_EVENT_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Input Event Speaker name.', JB_EVENT_TEXT_DOMAIN )
						),
						array(
							'id'       => '_speaker_address',
							'type'     => 'text',
							'title'    => esc_html__( 'Address', JB_EVENT_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Input Event Speaker address', JB_EVENT_TEXT_DOMAIN )
						),
					)
				)
			);

			return apply_filters( 'jobboard_event_sections', $sections );
		}

	}
}