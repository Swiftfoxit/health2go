<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 10/20/2017
 * Time: 4:51 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'JobBoard_Extension' ) ) :
	class JobBoard_Extension {
		public function __construct() {
			//add_action( 'admin_menu', array( $this, 'add_submenu' ) );
		}

		public function add_submenu() {
			$this->page = add_submenu_page(
				'edit.php?post_type=jobboard-post-jobs',
				esc_html__( "Extensions & Support", JB_TEXT_DOMAIN ),
				esc_html__( "Extensions & Support", JB_TEXT_DOMAIN ),
				'manage_options',
				'jobboard-extension',
				array( $this, 'view_settings_page' )
			);
		}

		public function view_settings_page() {
			$extensions = $this->get_extensions();
			?>
            <div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Extensions & Support', JB_TEXT_DOMAIN )?></h1>
            <div class="jobboard-addons-list">
            <?php


			foreach ( $extensions as $key => $value ) {
				?>
                <div class="jobboard-addons <?php echo $key; ?>">
                    <div class="jobboard-addons-content">
                        <div class="thumbnail">
                            <img src="<?php echo $value['thumbnail']; ?>">
                        </div>
                        <h3><?php echo $value['name']; ?></h3>
                        <p> <?php echo $value['description']; ?></p>
                        <?php if (isset($value['price'])): ?>
                        <p class="jobboard-addons-price">$<?php echo $value['price']; ?></p>
                        <a class="jobboard-addons-payment" target="_blank"
                           href="<?php echo $this->payment( $value ); ?>"><?php esc_html_e( 'Buy Now', JB_TEXT_DOMAIN ); ?></a>
                        <?php endif; ?>

                         <?php if (isset($value['from'])): ?>
                        <p class="jobboard-addons-price">From: $<?php echo $value['from']; ?></p>
                        <a class="jobboard-addons-payment"
                           href="#"><?php esc_html_e( 'Contact Now', JB_TEXT_DOMAIN ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
				<?php
			}
			echo '<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="e6fdc6d1-6f11-456c-bb70-327da0454e66";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>';
			echo '</div></div>';
		}

		public function get_extensions() {
			return apply_filters( 'jobboard_extensions', array(
				'jobboard-stripe'   => array(
					'name'        => esc_html__( 'Jobboard Stripe', JB_TEXT_DOMAIN ),
					'description' => esc_html__( "Support for Stripe payment gateway.", JB_TEXT_DOMAIN ),
					'thumbnail'   => JB()->plugin_directory_uri . 'assets/images/stripe.png',
					'price'       => 21
				),
				'jobboard-razorpay' => array(
					'name'        => esc_html__( 'Jobboard Razorpay', JB_TEXT_DOMAIN ),
					'description' => esc_html__( "Support for Razorpay payment gateway.", JB_TEXT_DOMAIN ),
					'thumbnail'   => JB()->plugin_directory_uri . 'assets/images/razorpay.png',
					'price'       => 21
				),
				'jobboard-import' => array(
					'name'        => esc_html__( 'Jobboard Import', JB_TEXT_DOMAIN ),
					'description' => esc_html__( "Import job from other website, googlesheet, bla, bla...", JB_TEXT_DOMAIN ),
					'thumbnail'   => JB()->plugin_directory_uri . 'assets/images/import.png',
					'from'       => 50
				)
			) );
		}

		public function payment( $addon ) {
			$actual_link = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$query       = array(
				'cmd'           => '_cart',
				'business'      => 'vanquan805@gmail.com',
				'no_note'       => 1,
				'currency_code' => 'USD',
				'charset'       => 'utf-8',
				'rm'            => 1,
				'upload'        => 1,
				'return'        => add_query_arg( 'status', 'return', $actual_link ),
				'cancel_return' => add_query_arg( 'status', 'cancel', $actual_link ),
				'page_style'    => '',
				'paymentaction' => 'sale',
				'bn'            => esc_html__( 'JB Cart', 'jobboard-package' ),
				'invoice'       => get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'admin_email' ),
				'notify_url'    => '',
				'no_shipping'   => 1,
				'tax_cart'      => '0.00',
				'item_name_1'   => $addon['name'],
				'quantity_1'    => 1,
				'amount_1'      => $addon['price'],
				'item_number_1' => ''
			);

			$base_url = 'https://www.paypal.com/cgi-bin/webscr';

			$paypal = add_query_arg( $query, $base_url );

			return $paypal;
		}
	}
endif;