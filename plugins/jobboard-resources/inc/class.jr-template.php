<?php
/**
 * @Template: class.jr-template.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 13-Dec-17
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}
if ( ! class_exists( 'JB_Resources_Template' ) ) {
    class JB_Resources_Template {
        public function __construct() {
            add_filter( 'template_include', array( $this, 'template_loader' ) );
        }

        function template_loader( $template ) {
            if ( is_post_type_archive( 'jb-resources' ) ) {
                $file   = 'archive-resources.php';
                $find[] = $file;
                $find[] = JB_Resources()->template_path() . $file;
            }

            if ( isset( $file ) ) {
                $template = locate_template( array_unique( $find ) );
                if ( ! $template ) {
                    $template = JB_Resources()->plugin_directory . 'templates/' . $file;
                }
            }

            return apply_filters( 'jobboard_resources_template_include', $template );
        }
    }
}