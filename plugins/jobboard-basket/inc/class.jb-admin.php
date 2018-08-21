<?php
/**
 * JobBoard Basket Admin.
 *
 * Action/filter hooks used for JobBoard Basket admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Basket
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Basket_Admin')) :
    class JB_Basket_Admin{

        function __construct()
        {
            add_filter('jobboard_admin_sections', array($this, 'sections_admin'));
            add_filter('jobboard_admin_endpoints_candidate', array($this, 'endpoints_candidate'));
        }

        function sections_admin($sections){
            $sections['basket-settings'] = array(
                'title'            => esc_html__( 'Cart & Basket', JB_BASKET_TEXT_DOMAIN ),
                'id'               => 'basket-settings',
                'icon'             => 'dashicons dashicons-cart',
                'fields'           => array(
                    array(
                        'id'            => 'basket-items',
                        'type'          => 'spinner',
                        'title'         => esc_html__( 'Items Limit', JB_BASKET_TEXT_DOMAIN ),
                        'subtitle'      => esc_html__( 'Limit the maximum number of jobs that are in the basket per user.', JB_BASKET_TEXT_DOMAIN ),
                        'desc'          => esc_html__( 'items/account', JB_BASKET_TEXT_DOMAIN),
                        'default'       => '30',
                        'min'           => '1',
                        'step'          => '5',
                        'max'           => '100',
                    )
                )
            );

            return $sections;
        }

        function endpoints_candidate($sections){
            $sections[] = array(
                'id'       => 'endpoint-basket',
                'type'     => 'text',
                'title'    => esc_html__( 'Job Basket', JB_BASKET_TEXT_DOMAIN ),
                'subtitle' => esc_html__( 'Endpoint for the candidate → view manage job basket page', JB_BASKET_TEXT_DOMAIN ),
                'placeholder' => esc_html__('basket', JB_BASKET_TEXT_DOMAIN)
            );

            return $sections;
        }
    }
endif;

new JB_Basket_Admin();