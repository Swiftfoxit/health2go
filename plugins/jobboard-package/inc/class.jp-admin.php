<?php
/**
 * JobBoard Package Admin.
 *
 * Action/filter hooks used for JobBoard Package admin.
 *
 * @author        FOX
 * @category    Core
 * @package    JobBoard/Package
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'JB_Package_Admin' ) ) :
	class JB_Package_Admin {

		function __construct() {
			add_filter( 'jobboard_admin_sections', array( $this, 'sections_admin' ) );
			add_filter( 'jobboard_admin_endpoints_employer', array( $this, 'sections_endpoints' ) );
		}

		function sections_admin( $sections ) {
			$sections[] = array(
				'title'  => esc_html__( 'Package & Payment', 'jobboard-package' ),
				'id'     => 'package-setting',
				'icon'   => 'dashicons dashicons-vault',
				'fields' => array(
					array(
						'id'       => 'package-currency',
						'type'     => 'select',
						'title'    => esc_html__( 'Currency', 'jobboard-package' ),
						'subtitle' => esc_html__( 'This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.', 'jobboard-package' ),
						'default'  => 'USD',
						'options'  => jb_get_currencies_options(),
					),
					array(
						'id'       => 'package-currency-position',
						'type'     => 'select',
						'title'    => esc_html__( 'Currency Position', 'jobboard-package' ),
						'subtitle' => esc_html__( 'This sets the number of decimal points shown in displayed prices.', 'jobboard-package' ),
						'default'  => 'left',
						'options'  => array(
							'left'        => esc_html__( 'Left ($99.99)', 'jobboard-package' ),
							'right'       => esc_html__( 'Right (99.99$)', 'jobboard-package' ),
							'left_space'  => esc_html__( 'Left with space ($ 99.99)', 'jobboard-package' ),
							'right_space' => esc_html__( 'Right with space (99.99 $)', 'jobboard-package' ),
						),
					),
					array(
						'id'       => 'payment-invoice-prefix',
						'type'     => 'text',
						'title'    => esc_html__( 'Invoice Prefix', 'jobboard-package' ),
						'subtitle' => esc_html__( 'Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.', 'jobboard-package' ),
						'default'  => 'JOBBOARD-',
					),
					array(
						'id'       => 'payment-contact',
						'type'     => 'select',
						'data'     => 'pages',
						'title'    => esc_html__( 'Contact Page', 'jobboard-package' ),
						'subtitle' => esc_html__( 'Please select a contact page, billing information.', 'jobboard-package' ),
					)
				)
			);

			$sections[] = array(
				'title'      => esc_html__( 'Direct Bank Tranfer', 'jobboard-package' ),
				'id'         => 'bank-tranfer',
				'icon'       => 'dashicons dashicons-awards',
				'desc'       => esc_html__( 'Allows payments by BACS, more commonly known as direct bank/wire transfer.', 'jobboard-package' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'bank-tranfer-enable',
						'title'    => esc_html__( 'Enable', 'jobboard-package' ),
						'subtitle' => esc_html__( 'Enable bank-transfer.', 'jobboard-package' ),
						'type'     => 'switch',
						'default'  => 0
					),
					array(
						'id'          => 'bank-tranfer-description',
						'type'        => 'textarea',
						'title'       => esc_html__( 'Description', 'jobboard-package' ),
						'subtitle'    => esc_html__( 'Payment method description that the customer will see on your checkout.', 'jobboard-package' ),
						'placeholder' => esc_html__( 'Enter your description', 'jobboard-package' ),
						'default'     => esc_html__( 'Make your payment directly into our bank account. Please use your Order ID, your Username, your Email as the payment reference. Your order will not be shipped until the funds have cleared in our account.', 'jobboard-package' ),
						'required'    => array( 'bank-tranfer-enable', '=', 1)
					),
					array(
						'id'         => 'bank_account',
						'type'       => 'rc_collapse',
						'title'      => esc_html__( 'Bank Account', 'jobboard-package' ),
						'subtitle'   => esc_html__( 'Add your bank account.', 'jobboard-package' ),
						'add_button' => true,
						'fields'     => array(
							array(
								'label' => esc_html__( 'Account name', 'jobboard-package' ),
								'name'  => 'account_name'
							),
							array(
								'label' => esc_html__( 'Account number', 'jobboard-package' ),
								'name'  => 'account_number'
							),
							array(
								'label' => esc_html__( 'Bank name', 'jobboard-package' ),
								'name'  => 'bank_name'
							),
							array(
								'label' => esc_html__( 'IBAN', 'jobboard-package' ),
								'name'  => 'iban'
							),
							array(
								'label' => esc_html__( 'BIC / Swift', 'jobboard-package' ),
								'name'  => 'bic_swift'
							),

						),
						'required'   => array( 'bank-tranfer-enable', '=', 1 )
					)
				)
			);

			$sections[] = array(
				'title'      => esc_html__( 'Paypal', 'jobboard-package' ),
				'id'         => 'payment-paypal',
				'icon'       => 'dashicons dashicons-awards',
				'desc'       => esc_html__( 'PayPal standard sends customers to PayPal to enter their payment information.', 'jobboard-package' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'paypal-enable',
						'title'    => esc_html__( 'Enable', 'jobboard-package' ),
						'subtitle' => esc_html__( 'Enable Paypal payment gateway.', 'jobboard-package' ),
						'type'     => 'switch',
						'default'  => 0
					),
					array(
						'id'          => 'payment-paypal-email',
						'type'        => 'text',
						'title'       => esc_html__( 'PayPal Email', 'jobboard-package' ),
						'subtitle'    => esc_html__( 'Please enter your PayPal email address; this is needed in order to take payment.', 'jobboard-package' ),
						'placeholder' => esc_html__( 'you@youremail.com', 'jobboard-package' ),
						'default'     => false,
						'required'   => array( 'paypal-enable', '=', 1 )
					),
					array(
						'id'       => 'payment-paypal-sandbox',
						'type'     => 'switch',
						'title'    => esc_html__( 'PayPal Sandbox', 'jobboard-package' ),
						'subtitle' => esc_html__( 'PayPal sandbox can be used to test payments.', 'jobboard-package' ),
						'desc'     => sprintf( esc_html__( 'Sign up for a developer account %1$shere%2$s.', 'jobboard-package' ), '<a href="https://developer.paypal.com">', '</a>' ),
						'default'  => false,
						'required'   => array( 'paypal-enable', '=', 1 )
					)
				)
			);

			return apply_filters( 'jobboard_package_admin_options', $sections );
		}

		function sections_endpoints( $sections ) {

			$sections[] = array(
				'id'          => 'endpoint-packages',
				'type'        => 'text',
				'title'       => esc_html__( 'Packages', 'jobboard-basket' ),
				'subtitle'    => esc_html__( 'Endpoint for the candidate → view manage job basket page', 'jobboard-basket' ),
				'placeholder' => esc_html__( 'package', 'jobboard-basket' )
			);

			$sections[] = array(
				'id'          => 'endpoint-transactions',
				'type'        => 'text',
				'title'       => esc_html__( 'Transactions', 'jobboard-basket' ),
				'subtitle'    => esc_html__( 'Endpoint for the candidate → view manage job basket page', 'jobboard-basket' ),
				'placeholder' => esc_html__( 'transactions', 'jobboard-basket' )
			);

			return apply_filters( 'jobboard_package_admin_endpoints', $sections );
		}
	}
endif;

new JB_Package_Admin();