<?php
/**
 * Admin Class.
 *
 * @author FOX
 * @package ZNews_Twitter
 * @version 1.0.0
 */
if (! defined ( 'ABSPATH' )) {
	exit (); // Exit if accessed directly
}

if (! class_exists ( 'ZNews_Twitter_Admin' )) {

	class ZNews_Twitter_Admin {

		function __construct() {

			add_action( 'admin_init', array(
				$this,
				'register_plugin_settings' ));


			// add admin page.
			add_action ( 'admin_menu', array (
				$this,
				'add_admin_page'
			) );

			add_action( 'admin_init' , array(
				$this,
				'updated_option_clear_cache'
			));

			// get current tab content.
			add_action( 'newstwitter/inc/admin/tab/content', array(
				$this,
				'add_admin_tab_content'
			));
		}

		/**
		 * register settings.
		 *
		 * @package ZNews_Twitter
		 */
		function register_plugin_settings() {

			/* reservation options. */
			register_setting('newstwitter-general-group', 'newstwitter_consumer_key');
			register_setting('newstwitter-general-group', 'newstwitter_consumer_secret');
			register_setting('newstwitter-general-group', 'newstwitter_access_token');
			register_setting('newstwitter-general-group', 'newstwitter_access_token_secret');
			register_setting('newstwitter-general-group', 'newstwitter_screen_name');
			register_setting('newstwitter-general-group', 'newstwitter_items_syn');
			register_setting('newstwitter-general-group', 'newstwitter_cache_time');
		}

		/**
		 * Add admin pages.
		 *
		 * @package ZNews_Twitter
		 */
		function add_admin_page() {
			add_options_page ( __ ( 'News Twitter', 'news-twitter' ), __ ( 'News Twitter', 'news-twitter' ), 'manage_options', 'news_twitter_admin', array (
				$this,
				'add_admin_page_main'
			) );
		}

		/**
		 * Admin page options.
		 *
		 * General, Products, Reservation, Custom Fields ...
		 * @package ZNews_Twitter
		 */
		function add_admin_page_main() {

			global $current_tab;

			$current_tab = 'general';

			if(!empty($_REQUEST['tab']))
				$current_tab = $_REQUEST['tab'];

			$tabs = array (
				'general' => esc_html__('Twitter', 'news-twitter')
			);

			$tabs = apply_filters('newstwitter/admin/tabs', $tabs);

			?>
			<div class="wrap news-twitter">
				<form id="mainform" method="post" action="options.php">
					<div class="news-twitter-woocommerce-settings" id="icon-woocommerce">
						<br />
					</div>
					<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
						<?php foreach ($tabs as $key => $tab): ?>
							<a href="<?php echo admin_url( 'options-general.php?page=news_twitter_admin&tab=' . $key ); ?>" class="nav-tab<?php echo ( $current_tab == $key ? ' nav-tab-active' : '' ) ; ?>"><?php echo esc_html($tab); ?></a>
						<?php endforeach; ?>
					</h2>

					<?php do_action('newstwitter/inc/admin/tab/content'); ?>

					<?php submit_button(); ?>

				</form>
			</div>
			<?php
		}

		/**
		 * Admin tab options.
		 *
		 * content tabs.
		 * @package ZNews_Twitter
		 */
		function add_admin_tab_content() {

			global $current_tab;

			if(empty($current_tab)) return ;

			$tab = apply_filters('newstwitter/inc/admin/tab/template', znews_twitter()->plugin_dir . "inc/admin/html_tab_$current_tab.php");

			if(!file_exists($tab)) return ;

			settings_fields( "newstwitter-$current_tab-group" );
			do_settings_sections( "newstwitter-$current_tab-group" );

			require_once $tab;
		}

		function updated_option_clear_cache(){

			if(!isset($_REQUEST['page']) || $_REQUEST['page'] != 'news_twitter_admin')
				return;

			if(!isset($_REQUEST['settings-updated']))
				return;

			update_option('_znews_twitter_token', '');

			$screen_name = get_option('newstwitter_screen_name') ? get_option('newstwitter_screen_name') : 'realjoomlaman';

			delete_transient($screen_name);
		}
		
		/**
		 * Text field.
		 * 
		 * @param array $options
		 */
		private function option_text($options){
				
			$option_value = get_option( $options['id'], $options['default'] );
			
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $options['id'] ); ?>"><?php echo esc_html( $options['title'] ); ?></label>
				</th>
				<td class="forminp">
					<input name="<?php echo esc_attr( $options['id'] ); ?>" id="<?php echo esc_attr( $options['id'] ); ?>" type="text" value="<?php echo esc_attr( $option_value ); ?>" placeholder="<?php echo esc_attr( $options['placeholder'] ); ?>" />
				</td>
			</tr>
			<?php
		}
	}

	new ZNews_Twitter_Admin ();
}