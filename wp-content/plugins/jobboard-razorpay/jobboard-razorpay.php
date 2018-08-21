<?php
/**
 * Plugin Name: JobBoard Razor Pay
 * Plugin URI: http://fsflex.com/
 * Description: Razor Pay (Credit Card/Debit Card/NetBanking) for JobBoard plugin.
 * Version: 1.0.2
 * Author: FSFlex
 * Author URI: http://fsflex.com/
 * License: GPLv2 or later
 * Text Domain: jobboard-razorpay
 */
if (! defined('ABSPATH')) {
    exit();
}

define('JB_RAZORPAY_TEXT_DOMAIN','jobboard');

// Don't duplicate me!
if (! class_exists('JB_RazorPay')) {
    class JB_RazorPay
    {
        public static $instance = null;

        public $file;
        public $basename;
        public $plugin_directory;
        public $plugin_directory_uri;

        public static function instance(){
            if(is_null(self::$instance)){
                self::$instance = new JB_RazorPay();
                self::$instance->setup_globals();

                if ( ! function_exists( 'is_plugin_active' ) ) {
                    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
                }

                if ( is_plugin_active('jobboard/jobboard.php')){
                    self::$instance->actions();
                }
            }

            return self::$instance;
        }

        private function setup_globals()
        {
            $this->file = __FILE__;
            /* base name. */
            $this->basename = plugin_basename($this->file);
            /* base plugin. */
            $this->plugin_directory = plugin_dir_path($this->file);
            $this->plugin_directory_uri = plugin_dir_url($this->file);
        }

        private function actions(){

            add_action('wp_enqueue_scripts', array($this, 'scripts'));
            add_action('wp_ajax_jobboard_razorpay_insert_order', array($this, 'insert_order'));
            add_action('jobboard_package_order_razorpay_args', array($this, 'checkout'), 10, 2);

            add_filter('fsflex_update_plugin_check_list', array($this, 'update'));
            add_filter('fsflex_update_plugin_jobboard-razorpay_data', array($this, 'plugin_info'));
            add_filter('jobboard_package_admin_options', array($this, 'options'));
            add_filter('jobboard_package_payments', array($this, 'payment'));
        }

        function scripts(){
            if (!is_jb_dashboard()) {
                return;
            }

            $options = array(
                'key'           => jb_get_option('payment-razorpay-api'),
                'description'   => jb_get_option('payment-razorpay-desc'),
                'theme'         => array(
                    'color'     => jb_get_option('payment-razorpay-color')
                ),
                'redirect'      => jb_page_endpoint_url('transactions', jb_page_permalink('dashboard'))
            );

            $image = jb_get_option('payment-razorpay-image');

            if(!empty($image['thumbnail'])){
                $options['image'] = $image['thumbnail'];
            }

            wp_enqueue_script( 'razorpay', 'https://checkout.razorpay.com/v1/checkout.js');
            wp_register_script( 'jobboard-razorpay-js', $this->plugin_directory_uri . 'assets/js/jobboard-razorpay.js', array('jquery', 'razorpay'), time(), true);
            wp_localize_script('jobboard-razorpay-js', 'jobboard_razorpay', $options);
            wp_enqueue_script('jobboard-razorpay-js');
        }

        function insert_order(){

            if(empty($_POST['payment_id'])){
                exit();
            }

            jb_package()->form->checkout();

            exit();
        }

        function checkout($order, $post){

            if($post->payment == 'razorpay'){
                $order['post_title'] = sanitize_key($_POST['payment_id']);
            }

            return $order;
        }

        function payment($payments){

            $payments['razorpay'] = array(
                'name' => esc_html__('RazorPay', JB_RAZORPAY_TEXT_DOMAIN),
                'desc' => esc_html__('Credit Card/Debit Card/NetBanking', JB_RAZORPAY_TEXT_DOMAIN),
                'icon' => $this->plugin_directory_uri . 'assets/images/razorpay.png'
            );

            return $payments;
        }

        function options($sections){

            $sections[] = array(
                'title'            => esc_html__( 'RazorPay', JB_RAZORPAY_TEXT_DOMAIN ),
                'id'               => 'payment-razorpay',
                'icon'             => 'dashicons dashicons-awards',
                'desc'             => esc_html__('Payment Gateway Solution for India.', JB_RAZORPAY_TEXT_DOMAIN),
                'subsection'       => true,
                'fields'           => array(
                    array(
                        'id'       => 'payment-razorpay-api',
                        'type'     => 'text',
                        'title'    => esc_html__( 'API Key', JB_RAZORPAY_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Please enter your RazorPay API keys; this is needed in order to take payment.', JB_RAZORPAY_TEXT_DOMAIN ),
                        'placeholder' => esc_html__('rzp_test_rqyFKYGwqKOIC8', JB_RAZORPAY_TEXT_DOMAIN),
                    ),
                    array(
                        'id'       => 'payment-razorpay-desc',
                        'type'     => 'textarea',
                        'title'    => esc_html__( 'Description', JB_RAZORPAY_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'This controls the description which the user sees during checkout.', JB_RAZORPAY_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'       => 'payment-razorpay-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Theme Color', JB_RAZORPAY_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Brand color to alter the appearance of checkout form.', JB_RAZORPAY_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'       => 'payment-razorpay-image',
                        'type'     => 'media',
                        'title'    => esc_html__( 'Image', JB_RAZORPAY_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Link to an image (usually merchant logo) shown in the checkout form.', JB_RAZORPAY_TEXT_DOMAIN ),
                    ),
                )
            );

            return $sections;
        }

        function update($slugs = array()){
            $slugs[] = 'jobboard-razorpay';
            return $slugs;
        }

        function plugin_info(){
            return 'jobboard-razorpay';
        }

        function get_template($template_name, $args = array()){
            jb_get_template($template_name, $args, JB()->template_path() . 'add-ons/razorpay/', $this->plugin_directory . 'templates/');
        }
    }
}

function jb_razorpay(){
    return JB_RazorPay::instance();
}

$GLOBALS['jobboard_razorpay'] = jb_razorpay();