<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 9:19 AM
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_Extension_rc_datetime' ) ) {

	class ReduxFramework_Extension_rc_datetime {

		// Protected vars
		protected $parent;
		public $extension_url;
		public $extension_dir;
		public static $theInstance;
		public static $version = "4.0";
		public $is_field = false;

		public function __construct( $parent ) {
			$this->parent       = $parent;
			$this->field_name   = 'rc_datetime';

			self::$theInstance  = $this;

			$this->is_field     = Redux_Helpers::isFieldInUse($parent, $this->field_name);

			add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
				$this,
				'overload_field_path'
			) ); // Adds the local field
		}

		public function overload_field_path( $field ) {
			return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
		}
	}
}
