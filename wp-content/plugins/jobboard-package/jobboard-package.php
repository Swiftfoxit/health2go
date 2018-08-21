<?php
/**
 * Plugin Name: JobBoard Package
 * Plugin URI: http://fsflex.com/
 * Description: JobBoard Package.
 * Version: 1.2.2
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-package
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

define('JB_PACKAGE_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if ( ! class_exists( 'JB_Package' ) ) {
	class JB_Package {
		public static $instance = null;

		public $file;
		public $basename;
		public $plugin_directory;
		public $plugin_directory_uri;

		public $package;
		public $form;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new JB_Package();
				self::$instance->setup_globals();
				self::$instance->init();

				if ( ! function_exists( 'is_plugin_active' ) ) {
					require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				}

				if ( is_plugin_active( 'jobboard/jobboard.php' ) ) {
					self::$instance->includes();
					self::$instance->actions();
				}
			}

			return self::$instance;
		}

		private function setup_globals() {
			$this->file                 = __FILE__;
			$this->basename             = plugin_basename( $this->file );
			$this->plugin_directory     = plugin_dir_path( $this->file );
			$this->plugin_directory_uri = plugin_dir_url( $this->file );
		}

		private function init() {
			register_activation_hook( $this->file, array( $this, 'setup_endpoints' ) );
		}

		private function includes() {
			require_once $this->plugin_directory . 'inc/class.jp-admin.php';
			require_once $this->plugin_directory . 'inc/class.jp-post.php';
			require_once $this->plugin_directory . 'inc/class.jp-update.php';
			require_once $this->plugin_directory . 'inc/functions-core.php';

			if ( $this->is_request( 'frontend' ) ) {
				require_once $this->plugin_directory . 'inc/class.jp-formhandler.php';
				require_once $this->plugin_directory . 'inc/template-functions.php';
				require_once $this->plugin_directory . 'inc/template-hooks.php';

				$this->form = new JB_Package_FormHandler();
			}
		}

		private function actions() {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'wp_ajax_jobboard_download_cv', array( $this, 'ajax_download_cv' ) );
			add_action( 'admin_menu', array( $this, 'get_menu_notice' ), 100 );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
			add_action( 'save_post_jobboard-post-jobs', array( $this, 'save_post' ) );

			add_filter( 'jobboard_query_endpoint_args', array( $this, 'add_endpoint' ) );
			add_filter( 'jobboard_query_endpoint_package_title', array( $this, 'add_endpoint_package_title' ) );
			add_filter( 'jobboard_query_endpoint_transactions_title', array(
				$this,
				'add_endpoint_transactions_title'
			) );
			add_filter( 'jobboard_employer_navigation_args', array( $this, 'add_endpoint_menu' ) );
			add_filter( 'jobboard_candidate_navigation_args', array( $this, 'add_endpoint_menu' ) );

			add_action( 'jobboard_endpoint_employer_new', array( $this, 'get_template_add_new' ), 0 );
			add_action( 'jobboard_endpoint_employer_package', array( $this, 'get_template_package' ) );
			add_action( 'jobboard_endpoint_candidate_package', array( $this, 'get_template_package' ) );

			add_filter( 'jobboard_form_handler_validate_add_job', array( $this, 'add_new_job_validate' ) );
			add_filter( 'jobboard_form_handler_validate_apply_job', array( $this, 'apply_job_validate' ) );
		}

		function setup_endpoints() {
			add_rewrite_endpoint( 'package', EP_PAGES );
			add_rewrite_endpoint( 'transactions', EP_PAGES );
			flush_rewrite_rules();
		}

		/**
		 * What type of request is this?
		 *
		 * @param  string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		function save_post( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			$this->package = jb_package_get_current_package();

			if ( ! $this->package ) {
				return;
			}

			$limits  = get_post_meta( $this->package->ID, '_features', true );
			$current = jb_package_count_jobs_feature();

			if ( ! empty( $_POST['_featured'] ) && $current < $limits ) {
				update_post_meta( $post_id, '_featured', 1 );
			} else {
				update_post_meta( $post_id, '_featured', 0 );
			}
		}

		function is_extend( $user_id = '' ) {
			$this->package = jb_package_get_current_package( $user_id );

			if ( ! $this->package ) {
				return true;
			}

			$jobs  = get_post_meta( $this->package->ID, '_jobs', true );
			$count = jb_package_count_jobs();

			if ( $count >= intval($jobs) ) {
				return array($count, $jobs);
			}

			return false;
		}

		function add_scripts() {
			if ( is_jb_dashboard() ) {
				wp_enqueue_script( 'jobboard-package-js', $this->plugin_directory_uri . 'assets/js/jobboard-package.js', array( 'jquery' ), time(), true );
			}

			wp_enqueue_script( 'jobboard-package-download', $this->plugin_directory_uri . 'assets/js/jobboard-package-download.js', array( 'jquery' ), time(), true );
		}

		function ajax_download_cv() {
			if ( false === $user_id = get_current_user_id() ) {
				wp_die( json_encode( array() ) );
			}

			$downloaded = get_user_meta( $user_id, 'jb_cv_downloaded', true );
			$downloaded = ! empty( $downloaded ) ? intval( $downloaded ) : 0;
			$package    = jb_package_get_current_package( $user_id );

			if ( $package === false && ! is_admin() ) {
				wp_die( json_encode( array() ) );
			}

			$limit_download = - 1;

			if ( $package != false ) {
				$limit_download = get_post_meta( $package->ID, '_cvs', true );
				$limit_download = ( ! empty( $limit_download ) ) ? intval( $limit_download ) : 0;
			} elseif ( is_admin() ) {
				$limit_download = 0;
			}

			if ( $limit_download == 0 || $downloaded < $limit_download ) {
				$user = get_user_by( 'slug', $_POST['account'] );

				if ( empty( $_POST['account'] ) || empty( $user ) ) {
					wp_die( json_encode( array() ) );
				}
				$url_download = jb_candidate_get_cv_url( $user->data->ID );
				if ( ! empty( $url_download ) ) {
					$downloaded += 1;
					update_user_meta( $user_id, 'jb_cv_downloaded', $downloaded );
					wp_die( json_encode( array( 'url_download' => $url_download ) ) );
				}
				wp_die( json_encode( array() ) );
			}

			wp_die( json_encode( array() ) );
		}

		function add_admin_scripts() {
			$screen = get_current_screen();

			if ( ! isset( $screen->id ) ) {
				return;
			}

			switch ( $screen->id ) {
				case 'edit-jb-orders':
					wp_enqueue_style( 'jb-post-orders-css', $this->plugin_directory_uri . 'assets/css/post-orders.css' );
					break;
				case 'jb-orders':
					global $post;
					wp_enqueue_style( 'jb-edit-post-order-css', $this->plugin_directory_uri . 'assets/css/edit-post-order.css' );

					wp_register_script( 'jb-edit-post-order-js', $this->plugin_directory_uri . 'assets/js/edit-post-order.js', array( 'jquery' ), time(), true );
					wp_localize_script( 'jb-edit-post-order-js', 'jb_order', array(
						'status'  => jb_package_get_order_status_args(),
						'publish' => esc_html__( 'Update', 'jobboard-package' ),
						'current' => $post->post_status
					) );
					wp_enqueue_script( 'jb-edit-post-order-js' );
					break;
				case 'edit-jb-package-employer':
					wp_enqueue_style( 'jb-post-package-css', $this->plugin_directory_uri . 'assets/css/post-packages.css' );
					break;
				case 'jb-package-employer':
					wp_enqueue_style( 'jb-edit-post-package-css', $this->plugin_directory_uri . 'assets/css/edit-post-package.css' );
					break;
				case 'edit-jb-package-candidate':
					wp_enqueue_style( 'jb-post-package-css', $this->plugin_directory_uri . 'assets/css/post-packages.css' );
					break;
				case 'jb-package-candidate':
					wp_enqueue_style( 'jb-edit-post-package-css', $this->plugin_directory_uri . 'assets/css/edit-post-package.css' );
					break;
			}
		}

		function add_endpoint( $endpoint ) {
			$endpoint['package']      = jb_get_option( 'endpoint-packages', 'package' );
			$endpoint['transactions'] = jb_get_option( 'endpoint-transactions', 'transactions' );

			return $endpoint;
		}

		function add_endpoint_package_title() {
			return esc_html__( 'Packages', 'jobboard-package' );
		}

		function add_endpoint_transactions_title() {
			return esc_html__( 'Transactions', 'jobboard-package' );
		}

		function add_endpoint_menu( $args ) {
			$args[] = array(
				'id'       => 'package',
				'endpoint' => jb_get_option( 'endpoint-packages', 'package' ),
				'title'    => $this->add_endpoint_package_title()
			);
			$args[] = array(
				'id'       => 'transactions',
				'endpoint' => jb_get_option( 'endpoint-transactions', 'transactions' ),
				'title'    => $this->add_endpoint_transactions_title()
			);

			return $args;
		}

		function add_new_job_validate( $validate ) {

			if ( $this->is_extend() ) {

				jb_notice_add( esc_html__( 'Error : Package not found !', 'jobboard-package' ), 'error' );

				return true;
			}

			return $validate;
		}

		function apply_job_validate( $validate ) {
			$package = jb_package_get_current_package();

			if ( ! $package ) {
				jb_notice_add( esc_html__( 'You need to purchase a package before applying job.', 'jobboard-package' ), 'error' );

				return true;
			}

			$applied = JB()->candidate->count_applied_all();
			$limit   = get_post_meta( $package->ID, '_apply', true );
			if ( $applied >= $limit ) {
				jb_notice_add( esc_html__( 'Apply job limited, you can update your package.', 'jobboard-package' ), 'error' );

				return true;
			}

			return $validate;
		}

		function get_menu_notice() {
			global $submenu;

			$key = "edit.php?post_type=jobboard-post-jobs";

			if ( empty( $submenu[ $key ] ) ) {
				return;
			}

			$count = jb_package_count_pending_orders();

			if ( ! $count ) {
				return;
			}

			foreach ( $submenu[ $key ] as $k => $menu ) {
				if ( $menu[2] == 'edit.php?post_type=jb-orders' ) {
					$submenu[ $key ][ $k ][0] .= ' <span class="update-plugins count-' . $count . '"><span class="update-count">' . $count . '</span></span>';
					break;
				}
			}

			return $submenu;
		}

		function get_package( $query = array(), $type = '' ) {
			if ( ! $type ) {
				$type = is_jb_employer() ? 'jb-package-employer' : 'jb-package-candidate';
			}

			$query = wp_parse_args( $query, array(
				'post_type'   => $type,
				'post_status' => 'publish',
				'orderby'     => 'meta_value_num',
				'meta_key'    => '_price',
				'order'       => 'ASC'
			) );

			return new WP_Query( $query );
		}

		function get_orders( $query = array() ) {

			$query = wp_parse_args( $query, array(
				'post_type'      => 'jb-orders',
				'post_status'    => 'any',
				'posts_per_page' => jb_get_option( 'dashboard-table-row', 10 )
			) );

			return new WP_Query( $query );
		}

		function get_template( $template_name, $args = array() ) {
			jb_get_template( $template_name, $args, JB()->template_path() . 'add-ons/package/', $this->plugin_directory . 'templates/' );
		}

		function get_template_package() {
			$this->get_template( 'packages.php' );
		}

		function get_template_current_package() {

			$jobs  = jb_package_count_jobs();
			$limit = (int) get_post_meta( $this->package->ID, '_jobs', true );

			$this->package->jobs     = $jobs;
			$this->package->limit    = $limit;
			$this->package->process  = round( ( $jobs / $limit ) * 100 );
			$this->package->add_more = jb_page_endpoint_url( 'package', jb_page_permalink( 'dashboard' ) );
			$this->package->class    = 'progress-bar-success';
			$this->package->contact  = get_permalink( jb_get_option( 'payment-contact', 0 ) );

			if ( $this->package->process >= 70 && $this->package->process < 90 ) {
				$this->package->class = 'progress-bar-warning';
			} elseif ( $this->package->process >= 90 ) {
				$this->package->class = 'progress-bar-danger';
			}

			$this->get_template( 'current.php', array( 'package' => $this->package ) );
		}

		function get_template_add_new() {

			if ( $this->is_extend() ) {
				remove_action( 'jobboard_endpoint_employer_new', 'jb_template_employer_job_new' );
				add_action( 'jobboard_endpoint_employer_new', array( $this, 'get_template_package' ) );
			} else {
				add_action( 'jobboard_endpoint_employer_new', array( $this, 'get_template_current_package' ), 5 );
			}
		}
	}
}

function jb_package() {
	return JB_Package::instance();
}

$GLOBALS['jobboard_package'] = jb_package();