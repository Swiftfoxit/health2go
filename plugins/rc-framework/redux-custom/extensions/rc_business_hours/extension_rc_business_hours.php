<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'ReduxFramework_Extension_rc_business_hours' ) ) {
    class ReduxFramework_Extension_rc_business_hours {
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public static $version = "4.0";
        public $is_field = false;

        public function __construct( $parent ) {
            $this->parent       = $parent;
            $this->field_name   = 'rc_business_hours';
            self::$theInstance  = $this;
            $this->is_field     = Redux_Helpers::isFieldInUse($parent, $this->field_name);

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
                $this,
                'overload_field_path'
            ) );
        }

        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }
    }
}
