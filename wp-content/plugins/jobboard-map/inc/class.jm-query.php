<?php
/**
 * JobBoard Map Admin.
 *
 * Action/filter hooks used for JobBoard Map admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Map
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Map_Query')) :
    class JB_Map_Query{

        function posts_fields_request($fields, $q){
            global $wpdb;

            $fields .= ',jg.lat,jg.lng';

            if($q->get('lat') && $q->get('lng')){
                $fields .= $wpdb->prepare(",( 3959 * acos( cos( radians(%f) ) * cos( radians( jg.lat ) ) * cos( radians( jg.lng ) - radians(%f) ) + sin( radians(%f) ) * sin( radians( jg.lat ) ) ) ) AS distance",$q->get('lat'), $q->get('lng'), $q->get('lat'));
            }

            return $fields;
        }

        function posts_join_request($join){
            global $wpdb;
            return $join .= " LEFT JOIN {$wpdb->prefix}jobboard_geolocation AS jg ON jg.post_id = {$wpdb->posts}.ID";
        }

        function posts_where_request($where, $q){
            global $wpdb;

            if(!$q->get('radius')){
                return $where;
            }

            return $where .= $wpdb->prepare(" HAVING distance < %f", $q->get('radius'));
        }

        function posts_orderby_request($orderby, $q){

            if(!$q->get('lat') || !$q->get('lng')){
                return $orderby;
            }

            return 'distance ASC';
        }

        function archive_map($options = array()){

            $markers = array();

            $options = wp_parse_args($options, array(
                'lat'           => null,
                'lng'           => null,
                'radius'        => null,
                's'             => ''
            ));

            $query = array(
                'post_type'     => 'jobboard-post-jobs',
                'post_status'   => 'publish',
                'posts_per_page'=> jb_get_option('map-marker-limit', 20),
                'lat'           => $options['lat'],
                'lng'           => $options['lng'],
                'radius'        => $options['radius'],
                's'             => $options['s']
            );

            add_filter('posts_fields', array($this, 'posts_fields_request'), 10, 2);
            add_filter('posts_join', array($this, 'posts_join_request'));
            add_filter('posts_where', array($this, 'posts_where_request'), 10, 2);
            add_filter('posts_orderby', array($this, 'posts_orderby_request'), 10, 2);

            $jobs = new WP_Query($query);

            remove_filter('posts_fields', array($this, 'posts_fields_request'));
            remove_filter('posts_join', array($this, 'posts_join_request'));
            remove_filter('posts_where', array($this, 'posts_where_request'));
            remove_filter('posts_orderby', array($this, 'posts_orderby_request'));

            if($jobs->have_posts()){
                while ($jobs->have_posts()){ $jobs->the_post(); global $post;
                    $markers[$post->ID]['lat']  = (float)$post->lat;
                    $markers[$post->ID]['lng']  = (float)$post->lng;
                    $markers[$post->ID]['info'] = apply_filters('jb/map/info/window', jb_map()->get_template_info_window(), $post);
                }
            }

            wp_reset_postdata();

            return $markers;
        }

        function update_user_location($user_id, $lat, $lng){
            global $wpdb;

            $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}jobboard_userlocation WHERE user_id = %d", $user_id));

            $wpdb->replace($wpdb->prefix . 'jobboard_userlocation', array(
                'id'        => $id,
                'user_id'   => $user_id,
                'lat'       => $lat,
                'lng'       => $lng,
            ), array(
                '%d',
                '%d',
                '%f',
                '%f'
            ));
        }

        function update_geo_location($post_id, $lat, $lng){
            global $wpdb;

            $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}jobboard_geolocation WHERE post_id = %d", $post_id));

            $wpdb->replace($wpdb->prefix . 'jobboard_geolocation', array(
                'id'      => $id,
                'post_id' => $post_id,
                'lat' => $lat,
                'lng' => $lng,
            ), array(
                '%d',
                '%d',
                '%f',
                '%f'
            ));
        }
    }
endif;