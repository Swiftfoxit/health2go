<?php
/**
 * JobBoard Post.
 *
 * @class        JobBoard_Post
 * @version        1.0.0
 * @package        JobBoard/Classes
 * @category    Class
 * @author        FOX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'JobBoard_Post' ) ) :
	class JobBoard_Post {

		function __construct() {
			add_action( 'init', array( $this, 'post_types' ), 5 );

			add_action( 'admin_menu', array( $this, 'remove_meta_boxes' ) );
			add_action( 'delete_post', array( $this, 'delete_post' ) );

			add_action( 'manage_jobboard-post-jobs_posts_custom_column', array( $this, 'column_jobs_td' ), 10, 2 );
			add_filter( 'manage_jobboard-post-jobs_posts_columns', array( $this, 'column_jobs' ) );
			add_filter( 'manage_edit-jobboard-post-jobs_sortable_columns', array( $this, 'column_jobs_sortable' ) );

			add_filter( 'manage_edit-jobboard-tax-types_columns', array( $this, 'custom_type_colunm' ) );
			add_filter( 'manage_jobboard-tax-types_custom_column', array( $this, 'custom_type_row' ), 10, 3 );
			add_filter( 'manage_edit-jobboard-tax-specialisms_columns', array( $this, 'custom_specialism_colunm' ) );
			add_filter( 'manage_jobboard-tax-specialisms_custom_column', array(
				$this,
				'custom_specialism_row'
			), 10, 3 );

			/* custom fields */
			//add_filter( 'jobboard_job_sections', array($this, 'sections_job_social'));
			add_filter( 'jobboard_type_sections', array( $this, 'custom_fields_type' ) );
			add_filter( 'jobboard_specialism_sections', array( $this, 'custom_fields_specialism' ) );
			add_filter( 'jobboard_location_sections', array( $this, 'custom_fields_location' ) );

			/* remove post custom field. */
			add_filter( 'redux/options/jobboard_meta/section/basic', array( $this, 'remove_job_custom_fields' ) );
		}

		function post_types() {
			global $redux_meta;

			$page_job = jb_get_option( 'page-jobs' );

			$labels = array(
				'name'           => esc_html__( 'JobBoard', JB_TEXT_DOMAIN ),
				'singular_name'  => esc_html__( 'JobBoard', JB_TEXT_DOMAIN ),
				'menu_name'      => esc_html__( 'JobBoard', JB_TEXT_DOMAIN ),
				'name_admin_bar' => esc_html__( 'JobBoard', JB_TEXT_DOMAIN ),
				'add_new'        => esc_html__( 'New', JB_TEXT_DOMAIN ),
				'add_new_item'   => esc_html__( 'New Job', JB_TEXT_DOMAIN ),
				'all_items'      => esc_html__( 'All', JB_TEXT_DOMAIN ),
			);

			$args = array(
				'labels'          => $labels,
				'show_ui'         => true,
				'public'          => true,
				'menu_icon'       => 'dashicons-marker',
				'menu_position'   => 50,
				'rewrite'         => array(
					'slug' => jb_get_option( 'post-job-slug', 'job' ),
				),
				'supports'        => array( 'title', 'editor', 'thumbnail' ),
				'capability_type' => array( 'job', 'jobs' ),
				'map_meta_cap'    => true
			);

			if ( $page_job && get_post( $page_job ) ) {
				$args['has_archive'] = get_page_uri( $page_job );
			}

			/* job type */
			$job_type_labels = array(
				'name'              => esc_html__( 'Types', JB_TEXT_DOMAIN ),
				'singular_name'     => esc_html__( 'Job Type', JB_TEXT_DOMAIN ),
				'search_items'      => esc_html__( 'Search Types', JB_TEXT_DOMAIN ),
				'all_items'         => esc_html__( 'All Types', JB_TEXT_DOMAIN ),
				'parent_item'       => esc_html__( 'Parent Job Type', JB_TEXT_DOMAIN ),
				'parent_item_colon' => esc_html__( 'Parent Job Type:', JB_TEXT_DOMAIN ),
				'edit_item'         => esc_html__( 'Edit Job Type', JB_TEXT_DOMAIN ),
				'update_item'       => esc_html__( 'Update Job Type', JB_TEXT_DOMAIN ),
				'add_new_item'      => esc_html__( 'Add New Job Type', JB_TEXT_DOMAIN ),
				'new_item_name'     => esc_html__( 'New Job Type', JB_TEXT_DOMAIN ),
				'menu_name'         => esc_html__( 'Types', JB_TEXT_DOMAIN ),
			);

			$job_type = array(
				'hierarchical' => true,
				'labels'       => $job_type_labels,
				'show_ui'      => true,
				'query_var'    => true,
				'rewrite'      => array(
					'slug' => jb_get_option( 'taxonomy-type-slug', 'type' )
				),
				'capabilities' => array(
					'manage_terms' => 'manage_job_type_terms',
					'edit_terms'   => 'edit_job_type_terms',
					'delete_terms' => 'delete_job_type_terms',
					'assign_terms' => 'assign_job_type_terms'
				)
			);

			/* job specialism */
			$job_specialism_labels = array(
				'name'              => esc_html__( 'Specialisms', JB_TEXT_DOMAIN ),
				'singular_name'     => esc_html__( 'Specialism', JB_TEXT_DOMAIN ),
				'search_items'      => esc_html__( 'Search Specialisms', JB_TEXT_DOMAIN ),
				'all_items'         => esc_html__( 'All Specialisms', JB_TEXT_DOMAIN ),
				'parent_item'       => esc_html__( 'Parent Specialism', JB_TEXT_DOMAIN ),
				'parent_item_colon' => esc_html__( 'Parent Specialism:', JB_TEXT_DOMAIN ),
				'edit_item'         => esc_html__( 'Edit Specialism', JB_TEXT_DOMAIN ),
				'update_item'       => esc_html__( 'Update Specialism', JB_TEXT_DOMAIN ),
				'add_new_item'      => esc_html__( 'Add New Specialism', JB_TEXT_DOMAIN ),
				'new_item_name'     => esc_html__( 'New Specialism', JB_TEXT_DOMAIN ),
				'menu_name'         => esc_html__( 'Specialisms', JB_TEXT_DOMAIN ),
			);

			$job_specialism = array(
				'hierarchical'       => true,
				'labels'             => $job_specialism_labels,
				'show_ui'            => true,
				'show_admin_column'  => false,
				'query_var'          => true,
				'show_in_quick_edit' => false,
				'rewrite'            => array(
					'slug' => jb_get_option( 'taxonomy-specialism-slug', 'specialism' )
				),
				'capabilities'       => array(
					'manage_terms' => 'manage_job_specialism_terms',
					'edit_terms'   => 'edit_job_specialism_terms',
					'delete_terms' => 'delete_job_specialism_terms',
					'assign_terms' => 'assign_job_specialism_terms'
				)
			);

			/* job location */
			$job_location_labels = array(
				'name'              => esc_html__( 'Locations', JB_TEXT_DOMAIN ),
				'singular_name'     => esc_html__( 'Location', JB_TEXT_DOMAIN ),
				'search_items'      => esc_html__( 'Search Locations', JB_TEXT_DOMAIN ),
				'all_items'         => esc_html__( 'All Locations', JB_TEXT_DOMAIN ),
				'parent_item'       => esc_html__( 'Parent Location', JB_TEXT_DOMAIN ),
				'parent_item_colon' => esc_html__( 'Parent Location:', JB_TEXT_DOMAIN ),
				'edit_item'         => esc_html__( 'Edit Location', JB_TEXT_DOMAIN ),
				'update_item'       => esc_html__( 'Update Location', JB_TEXT_DOMAIN ),
				'add_new_item'      => esc_html__( 'Add New Location', JB_TEXT_DOMAIN ),
				'new_item_name'     => esc_html__( 'New Location', JB_TEXT_DOMAIN ),
				'menu_name'         => esc_html__( 'Locations', JB_TEXT_DOMAIN ),
			);

			$job_location = array(
				'hierarchical'       => true,
				'labels'             => $job_location_labels,
				'show_ui'            => true,
				'show_admin_column'  => false,
				'query_var'          => true,
				'show_in_quick_edit' => false,
				'rewrite'            => array(
					'slug' => jb_get_option( 'taxonomy-location-slug', 'location' )
				),
				'capabilities'       => array(
					'manage_terms' => 'manage_job_location_terms',
					'edit_terms'   => 'edit_job_location_terms',
					'delete_terms' => 'delete_job_location_terms',
					'assign_terms' => 'assign_job_location_terms'
				)
			);

			/* job tag. */
			$job_tag = array(
				'hierarchical'       => false,
				'show_ui'            => true,
				'show_admin_column'  => false,
				'query_var'          => true,
				'show_in_quick_edit' => false,
				'rewrite'            => array(
					'slug' => jb_get_option( 'taxonomy-tag-slug', 'job-tag' )
				),
				'capabilities'       => array(
					'manage_terms' => 'manage_job_tag_terms',
					'edit_terms'   => 'edit_job_tag_terms',
					'delete_terms' => 'delete_job_tag_terms',
					'assign_terms' => 'assign_job_tag_terms'
				)
			);

			register_post_type( 'jobboard-post-jobs', apply_filters( 'jb/post/job/args', $args ) );
			register_taxonomy( 'jobboard-tax-types', array( 'jobboard-post-jobs' ), apply_filters( 'jb/taxonomy/type/args', $job_type ) );
			register_taxonomy( 'jobboard-tax-locations', array( 'jobboard-post-jobs' ), apply_filters( 'jb/taxonomy/location/args', $job_location ) );
                register_taxonomy( 'jobboard-tax-specialisms', array( 'jobboard-post-jobs' ), apply_filters( 'jb/taxonomy/specialism/args', $job_specialism ) );
			register_taxonomy( 'jobboard-tax-tags', array( 'jobboard-post-jobs' ), apply_filters( 'jb/taxonomy/tag/args', $job_tag ) );

			if ( empty( $redux_meta ) ) {
				return;
			}
			$redux_meta->post->add( $this->post_args(), $this->sections_job(), 'jobboard_meta', esc_html__( 'Setting', JB_TEXT_DOMAIN ), 'jobboard-post-jobs' );
			$redux_meta->taxonomy->add( $this->taxonomy_args(), $this->sections_type(), 'jobboard-tax-types', esc_html__( 'Setting', JB_TEXT_DOMAIN ) );
			$redux_meta->taxonomy->add( $this->taxonomy_args(), $this->sections_specialism(), 'jobboard-tax-specialisms', esc_html__( 'Setting', JB_TEXT_DOMAIN ) );
			$redux_meta->taxonomy->add( $this->taxonomy_args(), $this->sections_location(), 'jobboard-tax-locations', esc_html__( 'Setting', JB_TEXT_DOMAIN ) );
		}

		/* remove meta locations. */
		function remove_meta_boxes() {
			remove_meta_box( 'jobboard-tax-locationsdiv', 'jobboard-post-jobs', 'side' );
		}

		/**
		 * remove job fields.
		 *
		 * all basic post fields, post_title, content ...
		 *
		 * @param $section
		 *
		 * @return mixed
		 */
		function remove_job_custom_fields( $section ) {

			if ( empty( $section['fields'] ) ) {
				return $section;
			}

			$post_fields = array(
				'post_title',
				'content',
				'_thumbnail_id',
				'_address',
				'_location',
				'_types',
				'_specialisms'
			);

			foreach ( $section['fields'] as $k => $field ) {
				if ( in_array( $field['id'], $post_fields ) ) {
					unset( $section['fields'][ $k ] );
				}
			}

			return $section;
		}

		function delete_post( $post_id ) {

			$post = get_post( $post_id );

			if ( $post->post_type != 'jobboard-post-jobs' ) {
				return $post_id;
			}

			global $wpdb;

			$wpdb->delete( $wpdb->prefix . 'jobboard_applied', array(
				'post_id' => $post->ID
			), array(
				'%d'
			) );

			$wpdb->delete( $wpdb->prefix . 'jobboard_geolocation', array(
				'post_id' => $post->ID
			), array(
				'%d'
			) );
		}

		function custom_fields_type( $fields ) {

			$custom_field = jb_get_option( 'type-custom-fields' );

			if ( $custom_field ) {
				$fields['basic']['fields'] = array_merge( $fields['basic']['fields'], $custom_field );
			}

			return $fields;
		}

		function custom_fields_specialism( $fields ) {

			$custom_field = jb_get_option( 'spec-custom-fields' );

			if ( $custom_field ) {
				$fields['basic']['fields'] = array_merge( $fields['basic']['fields'], $custom_field );
			}

			return $fields;
		}

		function custom_fields_location( $fields ) {

			$custom_field = jb_get_option( 'spec-custom-fields' );

			if ( $custom_field ) {
				$fields = array( array( 'title' => '', 'id' => 'basic-setting', 'fields' => $custom_field ) );
			}

			return $fields;
		}

		function custom_type_colunm( $colunms ) {
			$_colunms                = array();
			$_colunms['cb']          = '<input type="checkbox" />';
			$_colunms['color']       = esc_html__( 'Color', JB_TEXT_DOMAIN );
			$_colunms['name']        = $colunms['name'];
			$_colunms['description'] = $colunms['description'];
			$_colunms['slug']        = $colunms['slug'];
			$_colunms['posts']       = $colunms['posts'];

			return $_colunms;
		}

		function custom_type_row( $content, $column_name, $term_id ) {
			if ( $column_name == 'color' ) {
				$color = get_term_meta( $term_id, '_color', true );
				if ( $color ) {
					$content = '<span style="background-color: ' . esc_attr( $color ) . '"></span>';
				} else {
					$content = '<span style="background-color: #ffffff"></span>';
				}
			}

			return $content;
		}

		function custom_specialism_colunm( $colunms ) {
			$_colunms                = array();
			$_colunms['cb']          = '<input type="checkbox" />';
			$_colunms['icon']        = esc_html__( 'Media', JB_TEXT_DOMAIN );
			$_colunms['name']        = isset( $colunms['name'] ) ? $colunms['name'] : '';
			$_colunms['description'] = isset( $colunms['description'] ) ? $colunms['description'] : '';
			$_colunms['slug']        = isset( $colunms['slug'] ) ? $colunms['slug'] : '';
			$_colunms['posts']       = isset( $colunms['posts'] ) ? $colunms['posts'] : '';

			return $_colunms;
		}

		function custom_specialism_row( $content, $column_name, $term_id ) {
			if ( $column_name == 'icon' ) {
				$media = get_term_meta( $term_id, '_media', true );
				$_null = '<img alt="" src="' . esc_url( jb_get_placeholder_image( '50x50' ) ) . '">';
				if ( $media ) {
					$icon = get_term_meta( $term_id, '_icon', true );
					if ( $icon ) {
						$content = '<i class="' . esc_html( $icon ) . '"></i>';
					} else {
						$content = $_null;
					}
				} else {
					$image = get_term_meta( $term_id, '_image', true );
					if ( ! empty( $image['thumbnail'] ) ) {
						$content = '<img alt="" src="' . esc_url( $image['thumbnail'] ) . '">';
					} else {
						$content = $_null;
					}
				}
			}

			return $content;
		}

		function column_jobs( $columns ) {
			unset( $columns['date'] );
			unset( $columns['title'] );

			$columns['job']          = sprintf( esc_html__( '%s Job', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-welcome-write-blog"></i>' );
			$columns['user']         = sprintf( esc_html__( '%s By', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-admin-users"></i>' );
			$columns['salary']       = sprintf( esc_html__( '%s Salary', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-chart-line"></i>' );
			$columns['applications'] = sprintf( esc_html__( '%s Applications', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-groups"></i>' );
			$columns['time']         = sprintf( esc_html__( '%s Date', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-calendar-alt"></i>' );
			$columns['status']       = sprintf( esc_html__( '%s Status', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-flag"></i>' );
			$columns['actions']      = sprintf( esc_html__( '%s Actions', JB_TEXT_DOMAIN ), '<i class="dashicons dashicons-admin-settings"></i>' );

			return $columns;
		}

		function column_jobs_td( $column, $post_id ) {
			global $post;

			switch ( $column ) {
				case 'job':
					echo '<a class="job-title" href="' . esc_url( get_edit_post_link( $post_id ) ) . '">' . get_the_title() . '</a>';
					echo '<div class="job-type"><i class="dashicons dashicons-clock"></i>' . jb_job_get_type( $post_id ) . '</div>';
					echo '<div class="job-location"><i class="dashicons dashicons-location"></i>' . jb_job_location_html( $post_id ) . '</div>';
					get_inline_data( $post );
					break;
				case 'user':
					$author = get_user_by( 'ID', $post->post_author );
					if ( $author !== false ) {
						echo '<div class="user clearfix">';
						echo '<div class="user-avatar"><a href="' . get_edit_profile_url( $post->post_author ) . '">' . get_avatar( $post->post_author ) . '</a></div>';
						echo '<div class="user-info">';
						echo '<a class="user-name" href="' . get_edit_profile_url( $post->post_author ) . '">' . $author->display_name . '</a>';
						echo '<a class="user-email" href="mailto:' . $author->user_email . '"><i class="dashicons dashicons-email"></i> ' . $author->user_email . '</a>';
						echo '</div>';
						echo '</div>';
					}
					break;
				case 'salary':
					echo '<div class="job-salary">' . jb_job_get_salary( $post->ID ) . '</div>';
					break;
				case 'applications':
					add_thickbox();
					global $post;
					$number = get_post_meta( $post->ID, '_application_ids', true );
					if ( empty( $number ) ) {
						$number = JB()->employer->count_applied( '', array( 'approved', 'reject', 'applied' ) );
					} else {
						$number = sprintf( esc_html__( '%d New', JB_TEXT_DOMAIN ), count( $number ) );
					}
					?>
                    <a title="<?php esc_html_e( 'Applications', JB_TEXT_DOMAIN ); ?>"
                       href="#TB_inline?width=600&height=550&inlineId=application-<?php echo $post_id; ?>"
                       class="thickbox application md-trigger" data-id="<?php the_ID(); ?>">
						<?php echo sprintf( esc_html__( 'View Applications [%s]', JB_TEXT_DOMAIN ), $number ); ?>
                    </a>
                    <div id="application-<?php echo $post_id; ?>" style="display:none;">
						<?php
						jb_template_employer_jobs_modal_search();
						jb_template_employer_jobs_modal_table();
						?>
                    </div>
					<?php
					break;
				case 'time':
					$t_time = get_the_time( esc_html__( 'Y/m/d g:i:s A', JB_TEXT_DOMAIN ), $post );
					$h_time = get_the_time( esc_html__( 'Y/m/d', JB_TEXT_DOMAIN ), $post );
					echo '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
					break;
				case 'status':
					echo '<div class="job-status ' . esc_attr( $post->post_status ) . '">' . jb_job_status( $post->post_status ) . '</div>';
					break;
				case 'actions':
					echo '<a class="button tips view" href="' . esc_url( get_edit_post_link( $post_id ) ) . '" title="View"><i class="dashicons dashicons-visibility"></i></a>';
					break;
			}
		}

		function column_jobs_sortable( $columns ) {
			$custom = array(
				'job'  => 'name',
				'time' => 'date'
			);

			return wp_parse_args( $custom, $columns );
		}

		public function post_args() {
			$args = array(
				// TYPICAL -> Change these values as you need/desire
				'opt_name'             => 'jobboard_meta',
				// This is where your data is stored in the database and also becomes your global variable name.
				'display_name'         => '',
				// Name that appears at the top of your panel
				'display_version'      => '',
				// Version that appears at the top of your panel
				'menu_type'            => 'hidden',
				//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'       => false,
				// Show the sections below the admin menu item or not
				'menu_title'           => '',
				'page_title'           => '',
				// You will need to generate a Google API key to use this feature.
				// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
				'google_api_key'       => '',
				// Set it you want google fonts to update weekly. A google_api_key value is required.
				'google_update_weekly' => true,
				// Must be defined to add google fonts to the typography module
				'async_typography'     => true,
				// Use a asynchronous font on the front end or font string
				//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
				'admin_bar'            => false,
				// Show the panel pages on the admin bar
				'admin_bar_icon'       => '',
				// Choose an icon for the admin bar menu
				'admin_bar_priority'   => 50,
				// Choose an priority for the admin bar menu
				'global_variable'      => '',
				// Set a different name for your global variable other than the opt_name
				'dev_mode'             => false,
				'forced_dev_mode_off'  => true,
				// Show the time the page took to load, etc
				'update_notice'        => false,
				// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
				'customizer'           => false,
				// Enable basic customizer support
				'open_expanded'        => false,
				// Allow you to start the panel in an expanded way initially.
				'disable_save_warn'    => true,
				// Disable the save warning when a user changes a field

				// OPTIONAL -> Give you extra features
				'page_priority'        => null,
				// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent'          => '',
				// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions'     => '',
				// Permissions needed to access the options panel.
				'menu_icon'            => '',
				// Specify a custom URL to an icon
				'last_tab'             => '',
				// Force your panel to always open to a specific tab (by id)
				'page_icon'            => '',
				// Icon displayed in the admin panel next to your menu_title
				'page_slug'            => '',
				// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
				'save_defaults'        => false,
				// On load save the defaults to DB before user clicks save or not
				'default_show'         => false,
				// If true, shows the default value next to each field that is not the default value.
				'default_mark'         => '',
				// What to print by the field's title if the value shown is default. Suggested: *
				'show_import_export'   => false,
				// Shows the Import/Export panel when not used as a field.
				'show_options_object'  => false,
				// CAREFUL -> These options are for advanced use only
				'transient_time'       => 60 * MINUTE_IN_SECONDS,
				'output'               => false,
				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag'           => false,
				// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				'footer_credit'        => false,
				// Disable the footer credit of Redux. Please leave if you can help it.

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database'             => '',
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'use_cdn'              => true,
				// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
			);

			return apply_filters( 'jb/post/args', $args );
		}

		public function taxonomy_args() {
			$args = array(
				// TYPICAL -> Change these values as you need/desire
				'opt_name'             => 'jobboard_taxonomy_meta',
				// This is where your data is stored in the database and also becomes your global variable name.
				'display_name'         => '',
				// Name that appears at the top of your panel
				'display_version'      => '',
				// Version that appears at the top of your panel
				'menu_type'            => 'hidden',
				//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'       => false,
				// Show the sections below the admin menu item or not
				'menu_title'           => '',
				'page_title'           => '',
				// You will need to generate a Google API key to use this feature.
				// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
				'google_api_key'       => '',
				// Set it you want google fonts to update weekly. A google_api_key value is required.
				'google_update_weekly' => true,
				// Must be defined to add google fonts to the typography module
				'async_typography'     => true,
				// Use a asynchronous font on the front end or font string
				//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
				'admin_bar'            => false,
				// Show the panel pages on the admin bar
				'admin_bar_icon'       => '',
				// Choose an icon for the admin bar menu
				'admin_bar_priority'   => 50,
				// Choose an priority for the admin bar menu
				'global_variable'      => '',
				// Set a different name for your global variable other than the opt_name
				'dev_mode'             => false,
				'forced_dev_mode_off'  => true,
				// Show the time the page took to load, etc
				'update_notice'        => false,
				// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
				'customizer'           => false,
				// Enable basic customizer support
				'open_expanded'        => true,
				// Allow you to start the panel in an expanded way initially.
				'disable_save_warn'    => true,
				// Disable the save warning when a user changes a field

				// OPTIONAL -> Give you extra features
				'page_priority'        => null,
				// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent'          => '',
				// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions'     => '',
				// Permissions needed to access the options panel.
				'menu_icon'            => '',
				// Specify a custom URL to an icon
				'last_tab'             => '',
				// Force your panel to always open to a specific tab (by id)
				'page_icon'            => '',
				// Icon displayed in the admin panel next to your menu_title
				'page_slug'            => '',
				// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
				'save_defaults'        => false,
				// On load save the defaults to DB before user clicks save or not
				'default_show'         => false,
				// If true, shows the default value next to each field that is not the default value.
				'default_mark'         => '',
				// What to print by the field's title if the value shown is default. Suggested: *
				'show_import_export'   => false,
				// Shows the Import/Export panel when not used as a field.
				'show_options_object'  => false,
				// CAREFUL -> These options are for advanced use only
				'transient_time'       => 60 * MINUTE_IN_SECONDS,
				'output'               => true,
				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tag'           => false,
				// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				'footer_credit'        => false,
				// Disable the footer credit of Redux. Please leave if you can help it.

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database'             => '',
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'use_cdn'              => true,
				// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
			);

			return apply_filters( 'jb/taxonomy/args', $args );
		}

		function sections_job() {
			$sections = array(
				'basic'    => array(
					'title'  => esc_html__( 'Information', JB_TEXT_DOMAIN ),
					'id'     => 'basic',
					'icon'   => 'dashicons dashicons-info',
					'fields' => array(
						array(
							'id'       => '_featured',
							'type'     => 'switch',
							'title'    => esc_html__( 'Featured', JB_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Featured listings will be sticky during searches, and can be styled differently.', JB_TEXT_DOMAIN ),
							'default'  => false,
						),
						array(
							'id'          => '_salary_min',
							'type'        => 'rc_number',
							'title'       => esc_html__( 'Min', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Enter min salary (number)', JB_TEXT_DOMAIN ),
							'placeholder' => esc_html__( '1000', JB_TEXT_DOMAIN ),
							'min'         => 0,
							'step'        => 0.01
						),
						array(
							'id'          => '_salary_max',
							'type'        => 'rc_number',
							'title'       => esc_html__( 'Max', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Enter max salary (number)', JB_TEXT_DOMAIN ),
							'placeholder' => esc_html__( '2000', JB_TEXT_DOMAIN ),
							'min'         => 0,
							'step'        => 0.01
						),
						array(
							'id'       => '_salary_currency',
							'type'     => 'select',
							'title'    => esc_html__( 'Currency', JB_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Select currency for salary', JB_TEXT_DOMAIN ),
							'options'  => jb_get_currencies_options(),
							'default'  => jb_get_option( 'default-currency', 'USD' )
						),
						array(
							'id'          => '_salary_extra',
							'type'        => 'text',
							'title'       => esc_html__( 'Extra', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Enter extra salary', JB_TEXT_DOMAIN ),
							'placeholder' => esc_html__( '/ Month or + Car ...', JB_TEXT_DOMAIN )
						)
					)
				),
				'location' => array(
					'title'  => esc_html__( 'Location & Address', JB_TEXT_DOMAIN ),
					'id'     => 'location',
					'icon'   => 'dashicons dashicons-location-alt',
					'fields' => array(
						array(
							'id'          => '_location',
							'type'        => 'rc_taxonomy_level',
							'title'       => esc_html__( 'Location', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Country/City', JB_TEXT_DOMAIN ),
							'placeholder' => array(
								esc_html__( 'Country', JB_TEXT_DOMAIN ),
								esc_html__( 'City', JB_TEXT_DOMAIN ),
								esc_html__( 'District', JB_TEXT_DOMAIN ),
							),
							'taxonomy'    => 'jobboard-tax-locations',
							'level'       => 3,
							'save'        => 'taxonomy'
						),
						array(
							'id'       => '_address',
							'type'     => 'textarea',
							'title'    => esc_html__( 'Complete Address', JB_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Enter you complete address with city, state or country.', JB_TEXT_DOMAIN ),
							'default'  => '',
						)
					)
				),
				'employer' => array(
					'title'  => esc_html__( 'Employer info', JB_TEXT_DOMAIN ),
					'id'     => 'employer',
					'icon'   => 'dashicons dashicons-businessman',
					'fields' => array(
						array(
							'id'          => '_customer_id',
							'type'        => 'rc_ajax_select',
							'title'       => esc_html__( 'Employer', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Change employer.', JB_TEXT_DOMAIN ),
							'desc'        => esc_html__( 'Enter user ID, user email, user display name...', JB_TEXT_DOMAIN ),
							'source'      => 'user',
							'source-type' => 'user',
							'save'        => 'user'
						),
						array(
							'id'          => '_customer_display_name',
							'type'        => 'text',
							'title'       => esc_html__( 'Name', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Customize for employer name, default get from author.', JB_TEXT_DOMAIN ),
							'placeholder' => esc_html__( 'Author name.', JB_TEXT_DOMAIN )
						),
						array(
							'id'          => '_customer_url',
							'type'        => 'text',
							'title'       => esc_html__( 'Website', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Customize for employer website url, default get from author.', JB_TEXT_DOMAIN ),
							'placeholder' => esc_html__( 'http://', JB_TEXT_DOMAIN )
						),
						array(
							'id'          => '_customer_email',
							'type'        => 'text',
							'title'       => esc_html__( 'Email', JB_TEXT_DOMAIN ),
							'subtitle'    => esc_html__( 'Customize for employer email, when the candidate apply job you will receive an email.', JB_TEXT_DOMAIN ),
							'placeholder' => esc_html__( 'email@your-domain.com', JB_TEXT_DOMAIN )
						)
					)
				)
			);

			return apply_filters( 'jobboard_job_sections', $sections );
		}

		function sections_job_social( $sections ) {
			if ( $socials = jb_get_option( 'employer-social-fields' ) ) {
				foreach ( $socials as $social ) {
					if ( $social['type'] !== 'text' ) {
						continue;
					}
					$social['name_suffix']            = '[' . $social['id'] . ']';
					$social['id']                     = '_customer_social';
					$sections['employer']['fields'][] = $social;
				}
			}

			return $sections;
		}

		function sections_type() {

			$sections = array(
				'basic' => array(
					'title'  => '',
					'id'     => 'basic',
					'fields' => array(
						array(
							'id'    => '_color',
							'type'  => 'color',
							'title' => esc_html__( 'Job Type Color', JB_TEXT_DOMAIN )
						)
					)
				)
			);

			return apply_filters( 'jobboard_type_sections', $sections );
		}

		function sections_specialism() {
			$sections = array(
				'basic' => array(
					'title'  => '',
					'id'     => 'basic',
					'fields' => array(
						array(
							'id'       => '_media',
							'type'     => 'switch',
							'title'    => esc_html__( 'Icon/Image', JB_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'You can select icon or image for specialism', JB_TEXT_DOMAIN ),
							'default'  => true,
							'on'       => 'Icon(Class)',
							'off'      => 'Image (jpg, png..)',
						),
						array(
							'id'       => '_image',
							'type'     => 'media',
							'url'      => true,
							'title'    => '',
							'subtitle' => esc_html__( 'Select a image.', JB_TEXT_DOMAIN ),
							'required' => array( '_media', '=', false )
						),
						array(
							'id'       => '_icon',
							'type'     => 'rc_icons',
							'title'    => '',
							'fonts'    => array(
								array(
									'name'  => 'Font Awesome',
									'id'    => 'font-awesome',
									'file'  => JB()->plugin_directory_uri . 'assets/css/font-awesome.min.css',
									'class' => jb_awesome_class()
								)
							),
							'subtitle' => esc_html__( 'Add a icon class.', JB_TEXT_DOMAIN ),
							'required' => array( '_media', '=', true )
						),
					)
				)
			);

			return apply_filters( 'jobboard_specialism_sections', $sections );
		}

		function sections_location() {
			$sections = array(
				'basic' => array(
					'title'  => '',
					'id'     => 'basic',
					'fields' => array(
						array(
							'id'       => '_thumbnail',
							'title'    => esc_html__( 'Thumbnail', JB_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Upload thumbnail.', JB_TEXT_DOMAIN ),
							'type'     => 'media',
							'input'    => 'image',
							'types'    => 'jpg,png',
							'col'      => 6,
						),
						array(
							'id'       => '_flag',
							'title'    => esc_html__( 'National Flag', JB_TEXT_DOMAIN ),
							'subtitle' => esc_html__( 'Upload National Flag.', JB_TEXT_DOMAIN ),
							'type'     => 'media',
							'input'    => 'image',
							'types'    => 'jpg,png',
							'col'      => 6,
						)
					)
				)
			);

			return apply_filters( 'jobboard_location_sections', $sections );
		}
	}

endif;