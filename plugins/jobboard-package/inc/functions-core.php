<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function jb_package_is_featured( $post = '' ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	if ( get_post_meta( $post->ID, '_featured', true ) ) {
		return true;
	} else {
		return false;
	}
}

function jb_package_the_pricing_table_class( $class = array() ) {

	$class[] = 'table';

	if ( jb_package_is_featured() ) {
		$class[] = 'active';
	}

	$class = apply_filters( 'jb/package/pricing/class', $class );

	echo implode( ' ', $class );
}

function jb_package_the_price_html() {
	echo jb_package_get_price_html();
}

function jb_package_the_price() {
	echo jb_package_get_price();
}

function jb_package_count_pending_orders() {
	$posts = new WP_Query( array( 'post_type' => 'jb-orders', 'post_status' => 'pending', 'posts_per_page' => - 1 ) );

	return $posts->post_count;
}

function jb_package_count_jobs() {

	$user_id = get_current_user_id();
	$count   = 0;

	if ( $user_id ) {
		$posts = new WP_Query( array(
			'post_type'      => 'jobboard-post-jobs',
			'author'    => $user_id,
			'posts_per_page' => - 1
		) );
		$count = $posts->post_count;
	}

	return $count;
}

function jb_package_count_jobs_feature() {
	global $wpdb;

	$user_id = get_current_user_id();
	$count   = 0;

	if ( $user_id ) {
		$query = 'SELECT COUNT(*) FROM %1$s AS p LEFT JOIN %2$s AS mt ON p.ID = mt.post_id WHERE p.post_author = %3$d AND p.post_status IN (\'publish\', \'pending\') AND mt.meta_key = \'_featured\' AND mt.meta_value = 1';
		$count = $wpdb->get_var( $wpdb->prepare( $query, $wpdb->posts, $wpdb->postmeta, $user_id ) );
	}

	return $count;
}

function jb_package_get_price( $post = '' ) {

	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$price = get_post_meta( $post->ID, '_price', true );

	if ( $price ) {
		return (int) $price;
	} else {
		return 0;
	}
}

function jb_package_get_price_html( $price = '', $post = '' ) {

	if ( ! $price ) {
		$price = jb_package_get_price( $post );
	}

	$price           = '<span class="price">' . $price . '</span>';
	$currency_symbol = '<span class="currency">' . jb_get_currency_symbol( jb_get_option( 'default-currency', 'USD' ) ) . '</span>';

	switch ( jb_get_option( 'package-currency-position', 'left' ) ) {
		case 'right':
			return $price . $currency_symbol;
		case 'left_space':
			return $currency_symbol . ' ' . $price;
		case 'right_space':
			return $price . $currency_symbol;
		default:
			return $currency_symbol . $price;
	}
}

function jb_package_get_payment( $payment ) {
	$payments = jb_package_get_payments();
	$args     = isset( $payments[ $payment ] ) ? $payments[ $payment ] : false;

	return apply_filters( 'jb/package/payment', $args, $payment );
}

function jb_package_get_payments() {
	$payment_methods = array();


	if ( intval( jb_get_option( 'paypal-enable', 0 ) ) === 1 ) {

		$payment_methods['paypal'] = array(
			'name' => esc_html__( 'PayPal', JB_PACKAGE_TEXT_DOMAIN ),
			'desc' => esc_html__( 'Pay width PayPal', JB_PACKAGE_TEXT_DOMAIN ),
			'icon' => jb_package()->plugin_directory_uri . 'assets/image/paypal.png'
		);
	}

	if ( intval( jb_get_option( 'bank-tranfer-enable', 0 ) ) === 1 ) {
		$payment_methods['bank-transfer'] = array(
			'name' => esc_html__( 'Direct Bank Transfer', JB_PACKAGE_TEXT_DOMAIN ),
			'desc' => esc_html__( 'Make your payment directly into our bank account.', JB_PACKAGE_TEXT_DOMAIN ),
			'icon' => jb_package()->plugin_directory_uri . 'assets/image/bank-transfer.png'
		);
	}

	return apply_filters( 'jobboard_package_payments', $payment_methods );
}


function jb_package_get_package() {
	$_package   = array();
	$employers  = get_posts( array( 'post_type' => 'jb-package-employer' ) );
	$candidates = get_posts( array( 'post_type' => 'jb-package-candidate' ) );

	if ( is_array( $employers ) ) {
		foreach ( $employers as $employer ) {
			$_package[ $employer->ID ] = "#Employer {$employer->post_title}";
		}
	}

	if ( is_array( $candidates ) ) {
		foreach ( $candidates as $candidate ) {
			$_package[ $candidate->ID ] = "#Candidate {$candidate->post_title}";
		}
	}

	return $_package;
}

function jb_package_get_order_status_args() {
	return apply_filters( 'jb/package/statuses', array(
		'processing' => esc_html__( 'Processing', JB_PACKAGE_TEXT_DOMAIN ),
		'on-hold'    => esc_html__( 'On Hold', JB_PACKAGE_TEXT_DOMAIN ),
		'completed'  => esc_html__( 'Completed', JB_PACKAGE_TEXT_DOMAIN ),
		'cancelled'  => esc_html__( 'Cancelled', JB_PACKAGE_TEXT_DOMAIN ),
		'refunded'   => esc_html__( 'Refunded', JB_PACKAGE_TEXT_DOMAIN ),
		'failed'     => esc_html__( 'Failed', JB_PACKAGE_TEXT_DOMAIN ),
		'pending'    => esc_html__( 'Pending', JB_PACKAGE_TEXT_DOMAIN )
	) );
}

function jb_package_get_order_status( $status = 'pending' ) {
	$status_list = jb_package_get_order_status_args();

	return isset( $status_list[ $status ] ) ? $status_list[ $status ] : esc_html__( 'Other', JB_PACKAGE_TEXT_DOMAIN );
}

function jb_package_get_order_status_html( $status = 'pending' ) {
	$status_name = jb_package_get_order_status( $status );

	return '<span class="order-status ' . esc_attr( $status ) . '">' . esc_html( $status_name ) . '</span>';
}

function jb_package_get_rules_text( $rule, $value ) {
	$rules = apply_filters( 'jobboard_package_rules_text', array(
		'_jobs'       => sprintf( _n( 'Post Up To %s%s Job%s', 'Post Up To %s%s Jobs%s', $value, JB_PACKAGE_TEXT_DOMAIN ), '<span>', $value, '</span>' ),
		'_features'   => sprintf( _n( 'Up To %s%s Featured Job%s', 'Up To %s%s Featured Jobs%s', $value, JB_PACKAGE_TEXT_DOMAIN ), '<span>', $value, '</span>' ),
		'_membership' => sprintf( _n( 'Up To %s%s Month Membership%s', 'Up To %s%s Months Membership%s', $value, JB_PACKAGE_TEXT_DOMAIN ), '<span>', $value, '</span>' ),
		'_apply'      => sprintf( _n( 'Up To %s%s Job Applied%s', 'Up To %s%s Jobs Applied%s', $value, JB_PACKAGE_TEXT_DOMAIN ), '<span>', $value, '</span>' ),
		'_cvs'        => sprintf( _n( 'Download CV Up To %s%s CVs%s', 'Download CV Up To %s%s CVs%s', $value, JB_PACKAGE_TEXT_DOMAIN ), '<span>', $value, '</span>' )
	) );

	if ( $rules[ $rule ] ) {
		return $rules[ $rule ];
	} else {
		return $value;
	}
}

function jb_package_get_employer_rules( $post_id = '' ) {
	global $post;

	if ( ! $post_id ) {
		$post_id = $post->ID;
	}

	$rules = array(
		'_jobs'       => jb_package_get_rule( '_jobs', $post_id ),
		'_features'   => jb_package_get_rule( '_features', $post_id ),
		'_membership' => jb_package_get_rule( '_membership', $post_id ),
		'_cvs'        => jb_package_get_rule( '_cvs', $post_id ),
	);

	return apply_filters( 'jobboard_package_employer_rules', $rules );
}

function jb_package_get_candidate_rules( $post_id = '' ) {
	global $post;

	if ( ! $post_id ) {
		$post_id = $post->ID;
	}

	$rules = array(
		'_apply'      => jb_package_get_rule( '_apply', $post_id ),
		'_membership' => jb_package_get_rule( '_membership', $post_id ),
		'_cvs'        => jb_package_get_rule( '_cvs', $post_id ),
	);

	return apply_filters( 'jobboard_package_candidate_rules', $rules );
}

function jb_package_get_rule( $name, $post_id ) {

	$rule = get_post_meta( $post_id, $name, true );

	if ( $rule < 0 ) {
		$rule = esc_html__( 'Unlimited', JB_PACKAGE_TEXT_DOMAIN );
	}

	return $rule;
}

function jb_package_get_client_ip() {
	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		$ipaddress = getenv( 'HTTP_CLIENT_IP' );
	} else if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
	} else if ( getenv( 'HTTP_X_FORWARDED' ) ) {
		$ipaddress = getenv( 'HTTP_X_FORWARDED' );
	} else if ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
	} else if ( getenv( 'HTTP_FORWARDED' ) ) {
		$ipaddress = getenv( 'HTTP_FORWARDED' );
	} else if ( getenv( 'REMOTE_ADDR' ) ) {
		$ipaddress = getenv( 'REMOTE_ADDR' );
	} else {
		$ipaddress = esc_html__( 'UNKNOWN', JB_PACKAGE_TEXT_DOMAIN );
	}

	return $ipaddress;
}

function jb_package_get_table_count() {
	global $wp_query;

	return isset( $wp_query->post_count ) ? $wp_query->post_count : 0;
}

function jb_package_get_current_package( $user_id = '' ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( ! $user_id ) {
		return false;
	}

	$order = new WP_Query( array(
		'author'         => $user_id,
		'posts_per_page' => 1,
		'post_type'      => 'jb-orders',
		'post_status'    => 'completed',
		'orderby'        => 'date',
		'order'          => 'DESC'
	) );

	if ( $order->post_count == 0 ) {
		return false;
	}

	if ( ! $package_id = get_post_meta( $order->post->ID, '_package_id', true ) ) {
		return false;
	}

	if ( ! $package = get_post( $package_id ) ) {
		return false;
	}

	$limit       = (int) get_post_meta( $package->ID, '_membership', true );
	$order_time  = strtotime( $order->post->post_date );
	$extend_time = strtotime( sprintf( _n( '%s +%d month', '%s +%d months', $limit ), $order->post->post_date, $limit ) );

	if ( $order_time <= $extend_time ) {
		return $package;
	}

	return false;
}

function jb_package_get_currencies() {
	return array( '$' => '$' );
}

function jb_package_get_listing_view() {
	if ( ( $package = jb_package_get_current_package() ) !== false ) {
//		$listing_view = get_post_meta( $package->ID, '_list_view', true );
		return 1;
	}

	return 0;
}

function jb_package_get_employer_package_default() {
	$package = get_posts( array(
		'post_type'  => 'jb-package-employer',
		'meta_query' => array(
			array(
				'key'     => '_default',
				'value'   => 1,
				'compare' => '=',
			)
		)
	) );

	if ( count( $package ) === 0 ) {
		return false;
	}

	return $package[0]->ID;
}

function jb_package_get_candidate_package_default() {
	$package = get_posts( array(
		'post_type'  => 'jb-package-candidate',
		'meta_query' => array(
			array(
				'key'     => '_default',
				'value'   => 1,
				'compare' => '=',
			)
		)
	) );

	if ( count( $package ) === 0 ) {
		return false;
	}

	return $package[0]->ID;
}

