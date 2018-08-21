<?php
/**
 * Package FormHandler.
 *
 * @class 		JB_Package_FormHandler
 * @version		1.0.0
 * @package		JB_Package/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}


class JB_Package_FormHandler
{
    function __construct(){
        add_action( 'jobboard_form_action_package_checkout', array($this, 'checkout') );
        add_action( 'jobboard_package_checkout_paypal', array($this, 'paypal'));
    }

    function checkout(){

        /* if payment id == null. */
        if(empty($_POST['payment'])){
            jb_notice_add( esc_html__( 'Error: You need to select a payment method!', JB_PACKAGE_TEXT_DOMAIN ), 'error' );
            return false;
        }

        /* if package id == null. */
        if(empty($_POST['package_id'])){
            jb_notice_add( esc_html__( 'Error: You need to select a package!', JB_PACKAGE_TEXT_DOMAIN ), 'error' );
            return false;
        }

        /* get package. */
        $post = get_post($_POST['package_id']);

        /* if package does not exists. */
        if(!$post){
            jb_notice_add( esc_html__( 'Error: Package not found!', JB_PACKAGE_TEXT_DOMAIN ), 'error' );
            return false;
        }

        /* if package does not exists. */
        if(!in_array($post->post_type, array('jb-package-employer', 'jb-package-candidate'))){
            jb_notice_add( esc_html__( 'Error: Package not found!', JB_PACKAGE_TEXT_DOMAIN ), 'error' );
            return false;
        }

        /**
         * process post data.
         *
         * get price, invoice, order_id...
         */
        $post->price            = jb_package_get_price($post->ID);
        $post->payment          = sanitize_key($_POST['payment']);
        $post->invoice_prefix   = jb_get_option('payment-invoice-prefix', 'JOBBOARD-');
        $post->order_id         = $this->insert_order($post);
        $post->invoice          = $post->invoice_prefix . $post->order_id;

        if(!$post->price){
            jb_notice_add(sprintf(esc_html__( 'Successfully order %s package.', JB_PACKAGE_TEXT_DOMAIN ), $post->post_title));
            return true;
        }

        do_action('jobboard_package_checkout_' . $_POST['payment'], $post);
    }

    function insert_order($post){

        $order = apply_filters("jobboard_package_order_{$post->payment}_args", array(
            'post_title'    => $post->invoice_prefix . $post->ID,
            'post_type'     => 'jb-orders',
            'post_status'   => 'pending',
        ), $post);

        if(!$post->price){
            $order['post_status'] = 'completed';
        }

        $order_id = wp_insert_post($order);

        if(!is_wp_error($order_id)) {
            update_post_meta($order_id, '_package_id', $post->ID);
            update_post_meta($order_id, '_client_ip', jb_package_get_client_ip());
            update_post_meta($order_id, '_payment', $post->payment);
            update_post_meta($order_id, '_price', $post->price);
        }

        return $order_id;
    }

    function paypal($post){

        $business       = jb_get_option('payment-paypal-email');

        if(!$business){
            jb_notice_add( esc_html__( 'Error: Business email not found!', JB_PACKAGE_TEXT_DOMAIN ), 'error' );
            return false;
        }

        $currency_code  = jb_get_option('package-currency', 'USD');
        $page           = jb_page_endpoint_url('package', jb_page_permalink('dashboard'));

        $query = array(
            'cmd'           => '_cart',
            'business'      => $business,
            'no_note'       => 1,
            'currency_code' => $currency_code,
            'charset'       => 'utf-8',
            'rm'            => 1,
            'upload'        => 1,
            'return'        => add_query_arg('status', 'return', $page),
            'cancel_return' => add_query_arg('status', 'cancel', $page),
            'page_style'    => '',
            'paymentaction' => 'sale',
            'bn'            => esc_html__('JB Cart', JB_PACKAGE_TEXT_DOMAIN),
            'invoice'       => $post->invoice,
            'notify_url'    => '',
            'no_shipping'   => 1,
            'tax_cart'      => '0.00',
            'item_name_1'   => $post->post_title,
            'quantity_1'    => 1,
            'amount_1'      => $post->price,
            'item_number_1' => ''
        );

        $base_url = jb_get_option('payment-paypal-sandbox', false) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr' ;

        $paypal = add_query_arg($query, $base_url);

        wp_redirect($paypal);
    }
}