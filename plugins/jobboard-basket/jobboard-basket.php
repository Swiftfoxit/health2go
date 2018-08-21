<?php
/**
 * Plugin Name: JobBoard Basket
 * Plugin URI: http://fsflex.com/
 * Description: Basket manager and widget for JobBoard plugin.
 * Version: 1.0.7
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-basket
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
define('JB_BASKET_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if ( ! class_exists( 'JB_Basket' ) ) {
	class JB_Basket {
		public static $instance = null;

		public $file;
		public $basename;
		public $plugin_directory;
		public $plugin_directory_uri;

		// new item id add to basket.
		public $add_new = array();
		public $remove_new = array();

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new JB_Basket();
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
			$this->file = __FILE__;
			/* base name. */
			$this->basename = plugin_basename( $this->file );
			/* base plugin. */
			$this->plugin_directory     = plugin_dir_path( $this->file );
			$this->plugin_directory_uri = plugin_dir_url( $this->file );
		}

		private function init() {
			add_action( 'init', array( $this, 'setup_endpoints' ), 5 );
			register_activation_hook( $this->file, array( $this, 'setup_endpoints' ) );
		}

		private function includes() {

			require_once $this->plugin_directory . 'inc/class.jb-admin.php';
			require_once $this->plugin_directory . 'inc/class.jb-update.php';
			require_once $this->plugin_directory . 'inc/conditional-functions.php';

			if ( ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) ) {
				require_once $this->plugin_directory . 'inc/template-functions.php';
				require_once $this->plugin_directory . 'inc/template-hooks.php';
			}
		}

		private function actions() {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'widgets_init', array( $this, 'add_widgets' ) );
			add_action( 'wp_login', array( $this, 'set_basket' ), 10, 2 );

			add_action( 'wp_ajax_jobboard_basket_ajax_add', array( $this, 'ajax_add_basket' ) );
			add_action( 'wp_ajax_nopriv_jobboard_basket_ajax_add', array( $this, 'ajax_add_basket' ) );
			add_action( 'wp_ajax_jobboard_basket_ajax_update', array( $this, 'ajax_update_basket' ) );
			add_action( 'wp_ajax_nopriv_jobboard_basket_ajax_update', array( $this, 'ajax_update_basket' ) );
			add_action( 'wp_ajax_jobboard_basket_ajax_apply', array( $this, 'ajax_apply_basket' ) );
			add_action( 'wp_ajax_nopriv_jobboard_basket_ajax_apply', array( $this, 'ajax_apply_basket' ) );
			add_action( 'wp_ajax_jobboard_basket_ajax_delete', array( $this, 'ajax_delete' ) );
			add_action( 'wp_ajax_nopriv_jobboard_basket_ajax_delete', array( $this, 'ajax_delete' ) );
			add_action( 'wp_ajax_jobboard_basket_ajax_delete_all', array( $this, 'ajax_delete_all' ) );
			add_action( 'wp_ajax_nopriv_jobboard_basket_ajax_delete_all', array( $this, 'ajax_delete_all' ) );

			add_action( 'jobboard_job_applied', array( $this, 'remove_count' ), 10, 2 );
			add_filter( 'jobboard_query_endpoint_args', array( $this, 'add_endpoint' ) );
			add_filter( 'jobboard_query_endpoint_basket_title', array( $this, 'add_endpoint_title' ) );
			add_filter( 'jobboard_candidate_navigation_args', array( $this, 'add_endpoint_menu' ) );
			add_filter( 'jobboard_dashboard_navigation_basket_title', array( $this, 'custom_nav_title' ) );
		}

		function setup_endpoints() {
			add_rewrite_endpoint( 'basket', EP_PAGES );
			flush_rewrite_rules();
		}

		function add_scripts() {
			wp_enqueue_style( 'jobboard-basket-css', $this->plugin_directory_uri . 'assets/css/jobboard-basket.css' );
			wp_register_script( 'jobboard-basket-js', $this->plugin_directory_uri . 'assets/js/jobboard-basket.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'jobboard-basket-js', 'jobboard_localize_basket', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'jobboard-basket-js' );
		}

		function add_widgets() {
			if ( ! class_exists( 'JB_Widget' ) ) {
				include_once( JB()->plugin_directory . 'abstracts/abstract-jb-widget.php' );
			}

			include_once( 'widgets/class-jb-widget-basket.php' );

			register_widget( 'JB_Widget_Basket' );
		}

		function add_basket( $id, $user_id = '' ) {
			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			$apply = null;

			if ( is_jb_candidate( $user_id ) && ! JB()->job->get_row( $user_id, $id ) ) {
				$apply = JB()->job->apply( $user_id, $id, 'basket' );
			} else {
				setcookie( "jobboard-basket[{$id}]", $id, $this->get_expire( 15 ), '/' );
				$apply = $id;
			}

			return $apply;
		}

		function add_endpoint( $endpoint ) {
			$endpoint['basket'] = jb_get_option( 'endpoint-basket', 'basket' );

			return $endpoint;
		}

		function add_endpoint_title() {
			return esc_html__( 'Job Basket', JB_BASKET_TEXT_DOMAIN );
		}

		function add_endpoint_menu( $args ) {
			$args[] = array(
				'id'       => 'basket',
				'endpoint' => jb_get_option( 'endpoint-basket', 'basket' ),
				'title'    => $this->add_endpoint_title()
			);

			return $args;
		}

		function ajax_add_basket() {
			if ( empty( $_POST['id'] ) ) {
				wp_send_json( array( 'type' => 'error', 'message' => esc_html__( "Error : 404", JB_BASKET_TEXT_DOMAIN ) ) );
				exit();
			}

			$basket  = $this->add_basket( $_POST['id'] );
			$message = array(
				'type'    => 'success',
				'message' => sprintf( esc_html__( "Successfully Added for '%s'", JB_BASKET_TEXT_DOMAIN ), get_the_title( $_POST['id'] ) )
			);

			if ( isset( $basket['error'] ) ) {
				$message['type']    = 'error';
				$message['message'] = sprintf( esc_html__( "Error : %s" ), $basket['message'] );
			}

			wp_send_json( $message );
			exit();
		}

		function ajax_apply_basket() {
			if ( empty( $_POST['id'] ) ) {
				wp_send_json( array( 'type' => 'error', 'message' => esc_html__( "Error : 404", JB_BASKET_TEXT_DOMAIN ) ) );
				exit();
			}

			$post    = get_post( $_POST['id'] );
			$user_id = get_current_user_id();
			$user    = wp_get_current_user();
			$cv      = get_user_meta( $user_id, 'cv', true );

			if ( ! is_jb_candidate( $user_id ) ) {
				wp_send_json( array(
					'type'    => 'error',
					'message' => esc_html__( "Error : Access denied.", JB_BASKET_TEXT_DOMAIN )
				) );
				exit();
			}

			if ( !isset( $cv['id'] ) || empty( $cv['id'] ) ) {
				wp_send_json( array(
					'type'    => 'error',
					'message' => esc_html__( 'Error : You must complete your profile.', JB_BASKET_TEXT_DOMAIN )
				) );
				exit();
			}

			$apply = JB()->job->apply( $user_id, $post->ID );

			if ( ! isset( $apply['error'] ) ) {
				$send_email              = new JobBoard_Emails();
				$employer                = get_userdata( $post->post_author );
				$employer->manager       = jb_page_endpoint_url( 'jobs', jb_page_permalink( 'dashboard' ) );
				$candidate               = get_userdata( $user_id );
				$candidate->manager      = jb_page_endpoint_url( 'applied', jb_page_permalink( 'dashboard' ) );
				$candidate->covering     = esc_html__( 'Quick apply', JB_BASKET_TEXT_DOMAIN );
				$candidate->user_email   = $user->user_email;
				$candidate->display_name = $user->display_name;

				if ( $custom_email = get_post_meta( $post->ID, '_customer_email', true ) ) {
					$employer->user_email = $custom_email;
				}

				$send_email->attachments[] = get_attached_file( $cv['id'] );
				$send_email->candidate_applied( $post, $employer, $candidate );
				$send_email->employer_applied( $post, $employer, $candidate );

				wp_send_json( array(
					'type'    => 'success',
					'message' => sprintf( esc_html__( "Successfully Applied for '%s'", JB_BASKET_TEXT_DOMAIN ), $post->post_title )
				) );

			} else {
				wp_send_json( array(
					'type'    => 'error',
					'message' => sprintf( esc_html__( 'Error : %s', JB_BASKET_TEXT_DOMAIN ), $apply['message'] )
				) );
			}

			exit();
		}

		function ajax_update_basket() {
			$basket        = $this->get_basket_user();
			$data          = array();
			$data['count'] = isset( $basket->post_count ) ? $basket->post_count : 0;

			ob_start();

			$this->get_template( 'basket-items.php', array( 'basket' => $basket ) );

			$data['html'] = ob_get_clean();

			wp_send_json( $data );

			exit();
		}

		function ajax_delete_all() {
			$this->delete_all_cookie();

			if ( ! is_jb_candidate() ) {
				wp_send_json( array(
					'type'    => 'error',
					'message' => esc_html__( "Error : Access denied.", JB_BASKET_TEXT_DOMAIN )
				) );
				exit();
			}

			$message = array(
				'type'    => 'success',
				'message' => esc_html__( "Successfully Clear All Basket.", JB_BASKET_TEXT_DOMAIN )
			);

			$delete = $this->delete_all();

			if ( isset( $delete['error'] ) ) {
				$message['type']    = 'error';
				$message['message'] = sprintf( esc_html__( "Error : %s", JB_BASKET_TEXT_DOMAIN ), $delete['message'] );
			}
			wp_send_json( $message );
			exit();
		}

		function ajax_delete() {
			if ( empty( $_POST['id'] ) ) {
				wp_send_json( array( 'type' => 'error', 'message' => esc_html__( "Error : 404", JB_BASKET_TEXT_DOMAIN ) ) );
				exit();
			}

			if ( ! is_jb_candidate() ) {
				wp_send_json( array(
					'type'    => 'error',
					'message' => esc_html__( "Error : Access denied.", JB_BASKET_TEXT_DOMAIN )
				) );
				exit();
			}

			$message = array(
				'type'    => 'success',
				'message' => sprintf( esc_html__( "Successfully Removed for '%s'", JB_BASKET_TEXT_DOMAIN ), get_the_title( $_POST['id'] ) )
			);

			$delete = $this->delete( $_POST['id'] );

			if ( isset( $delete['error'] ) ) {
				$message['type']    = 'error';
				$message['message'] = sprintf( esc_html__( "Error : %s", JB_BASKET_TEXT_DOMAIN ), $delete['message'] );
			}

			$this->delete_a_cookie( $_POST['id'] );
			wp_send_json( $message );
			exit();
		}

		function delete_all_cookie() {
			if ( empty( $_COOKIE['jobboard-basket'] ) ) {
				return;
			}

			foreach ( $_COOKIE['jobboard-basket'] as $k => $v ) {
				unset ( $_COOKIE["jobboard-basket[$k]"] );
				setcookie( "jobboard-basket[$k]", '', time() - 3600, '/' );
			}
		}

		function delete_a_cookie( $id ) {
			if ( empty( $_COOKIE['jobboard-basket'] ) ) {
				return;
			}

			unset( $_COOKIE["jobboard-basket[{$id}]"] );

			setcookie( "jobboard-basket[$id]", '', time() - 3600, '/' );
		}

		function delete_all( $user_id = '' ) {
			global $wpdb;

			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			$delete = $wpdb->delete(
				$wpdb->prefix . 'jobboard_applied',
				array(
					'user_id'    => $user_id,
					'app_status' => 'basket'
				)
			);

			if ( $delete ) {
				update_user_meta( $user_id, '_jobboard_basket_ids', array() );
			} else {
				return jb_error_args( true, esc_html__( 'Cannot remove all items.', JB_BASKET_TEXT_DOMAIN ) );
			}

			return true;
		}

		function delete( $id, $user_id = '' ) {
			global $wpdb;

			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			if ( ! JB()->job->get_row( $user_id, $id ) ) {
				return jb_error_args( true, esc_html__( 'Item does not exist.', JB_BASKET_TEXT_DOMAIN ) );
			}

			$delete = $wpdb->delete( $wpdb->prefix . 'jobboard_applied',
				array(
					'user_id'    => $user_id,
					'post_id'    => $id,
					'app_status' => 'basket'
				)
			);

			if ( $delete ) {
				$this->remove_count( $user_id, $id );
			} else {
				return jb_error_args( true, esc_html__( 'Cannot remove this item.', JB_BASKET_TEXT_DOMAIN ) );
			}

			return true;
		}

		function remove_all_count() {
			$user_id = get_current_user_id();
			$ids     = get_user_meta( $user_id, '_jobboard_basket_ids', true );

			if ( empty( $ids ) || empty( $this->remove_new ) ) {
				return;
			}

			update_user_meta( $user_id, '_jobboard_basket_ids', array_diff( $ids, $this->remove_new ) );
		}

		function remove_count( $user_id, $post_id ) {
			$basket_count = get_user_meta( $user_id, '_jobboard_basket_ids', true );
			if ( empty( $basket_count ) ) {
				return;
			}
			/* remove count. */
			if ( in_array( $post_id, $basket_count ) ) {
				update_user_meta( $user_id, '_jobboard_basket_ids', array_diff( $basket_count, array( $post_id ) ) );
			}
		}

		function set_basket( $user, $data ) {
			if ( is_jb_candidate( $data->ID ) && ! empty( $_COOKIE['jobboard-basket'] ) ) {
				foreach ( $_COOKIE['jobboard-basket'] as $index => $post_id ) {
					$this->add_basket( $post_id, $data->ID );
					$this->delete_a_cookie( $post_id );
				}
			}
		}

		function custom_nav_title( $title ) {

			$basket = get_user_meta( get_current_user_id(), '_jobboard_basket_ids', true );

			if ( empty( $basket ) ) {
				return $title;
			}

			$this->add_new = $basket;

			ob_start();

			jb_get_template( 'global/count.php', array( 'count' => count( $basket ) ) );

			return $title . ob_get_clean();
		}

		function get_expire( $days = 15 ) {
			return time() + 86400 * $days;
		}

		function get_basket() {
			global $wp_query;

			$paged = 1;
			/* get current paged.  */
			if ( ! empty( $_REQUEST['paged'] ) ) {
				$paged = (int) $_REQUEST['paged'];
			} elseif ( ! empty( $wp_query->query['basket'] ) ) {
				$paged = str_replace( 'page/', null, $wp_query->query['basket'] );
			}

			$query = array(
				'paged'          => $paged,
				'app_status'     => 'basket',
				'orderby'        => 'app_date',
				'posts_per_page' => jb_get_option( 'dashboard-per-page', 12 )
			);

			return JB()->job->query( $query );
		}

		function get_basket_widget() {
			return JB()->job->query( array(
				'posts_per_page' => jb_get_option( 'basket-items', 30 ),
				'paged'          => 0,
				'app_status'     => 'basket',
				'orderby'        => 'app_date'
			) );
		}

		function get_basket_url() {
			return jb_page_endpoint_url( 'basket', jb_page_permalink( 'dashboard' ) );
		}

		function get_basket_cookie() {
			$post_ids = ! empty( $_COOKIE['jobboard-basket'] ) ? $_COOKIE['jobboard-basket'] : array( 0 );

			return new WP_Query( array(
				'post_type'      => 'jobboard-post-jobs',
				'post_status'    => 'publish',
				'post__in'       => $post_ids,
				'posts_per_page' => jb_get_option( 'basket-items', 30 )
			) );
		}

		function get_basket_user() {
			return is_jb_candidate() ? $this->get_basket_widget() : $this->get_basket_cookie();
		}

		function get_template( $template_name, $args = array() ) {
			jb_get_template( $template_name, $args, JB()->template_path() . 'add-ons/basket/', $this->plugin_directory . 'templates/' );
		}
	}
}

function jb_basket() {
	return JB_Basket::instance();
}

$GLOBALS['jobboard_basket'] = jb_basket();