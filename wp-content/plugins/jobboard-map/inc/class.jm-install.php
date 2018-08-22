<?php
/**
 * JobBoard Map Install.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Map
 * @version     1.0.0
 */

if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('JB_Map_Install')) :
    class JB_Map_Install{

        function install()
        {
            self::create_tables();
        }

        /**
         * Set up the database tables which the plugin needs to function.
         */
        private function create_tables() {
            global $wpdb;

            $wpdb->hide_errors();

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta( self::get_schema() );
        }

        /**
         * Get Table schema.
         * @return string
         */
        private function get_schema()
        {
            global $wpdb;

            $collate = '';

            if ($wpdb->has_cap('collation')) {
                $collate = $wpdb->get_charset_collate();
            }

            $tables = "CREATE TABLE {$wpdb->prefix}jobboard_geolocation (id bigint(20) NOT NULL auto_increment, post_id bigint(20) NOT NULL, lat FLOAT(10,6) NOT NULL, lng FLOAT(10,6) NOT NULL, PRIMARY KEY  (id)) $collate;";
            $tables = "CREATE TABLE {$wpdb->prefix}jobboard_userlocation (id bigint(20) NOT NULL auto_increment, user_id bigint(20) NOT NULL, lat FLOAT(10,6) NOT NULL, lng FLOAT(10,6) NOT NULL, PRIMARY KEY  (id)) $collate;";

            return $tables;
        }
    }
endif;