<?php
/**
 * JobBoard Alerts.
 *
 * @class 		JobBoard_Alerts_Install
 * @version		1.0.0
 * @package		JobBoard/Alerts/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JobBoard_Alerts_Install')) :

    class JobBoard_Alerts_Install{

        function install()
        {
            self::setup_endpoints();
            self::create_tables();

            // Trigger action
            do_action( 'jobboard_alerts_installed' );
        }

        private function setup_endpoints(){
            add_rewrite_endpoint('alerts', EP_PAGES);
            add_rewrite_endpoint('notices', EP_PAGES);
            flush_rewrite_rules();
        }

        private function create_tables() {
            global $wpdb;

            $wpdb->hide_errors();

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta( self::get_schema() );
        }

        private function get_schema()
        {
            global $wpdb;

            $collate = '';

            if ($wpdb->has_cap('collation')) {
                $collate = $wpdb->get_charset_collate();
            }

            $tables  = "CREATE TABLE {$wpdb->prefix}jobboard_interest (
                      id bigint(20) NOT NULL auto_increment,
                      user_id bigint(20) NOT NULL,
                      types longtext NULL,
                      specialisms longtext NULL,
                      locations longtext NULL,
                      keywords longtext NULL,
                      PRIMARY KEY  (id)
                    ) $collate; ";

            $tables .= "CREATE TABLE {$wpdb->prefix}jobboard_subscribe (
                      id bigint(20) NOT NULL auto_increment,
                      email varchar(100) NOT NULL,
                      PRIMARY KEY  (id)
                    ) $collate;";

            return $tables;
        }
    }
endif;