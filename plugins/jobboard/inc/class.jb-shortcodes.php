<?php
/**
 * JobBoard Shortcodes.
 *
 * @class        JobBoard_Shortcodes
 * @version        1.0.0
 * @package        JobBoard/Classes
 * @category    Class
 * @author        FOX
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('JobBoard_Shortcodes')):

    class JobBoard_Shortcodes
    {

        function __construct()
        {
            add_shortcode('jobboard-dashboard', array($this, 'shortcodes_dashboard'));
//	        add_shortcode( 'jobboard-jobs-locations', array( $this, 'shortcodes_jobs_locations' ) );
        }


	    public function shortcodes_jobs_locations( $atts = array(), $content ) {
//		    $atts = shortcode_atts(
//			    array(
//				    'title'       => esc_html__( 'Jobs by Location', "jobboard" ),
//				    'description' => __( "Description", "jobboard" ),
//				    'country'     => '0',
//				    'count'       => 4,
//				    'view'        => '',
//			    ), $atts, 'jobboard-jobs-locations' );
//
//		    $country = 0;
//
//		    if ( $atts['country'] !== 'All Locations' ) {
//			    $country = get_term_by( 'name', $atts['country'], 'jobboard-tax-locations' );
//			    $country = $country->term_id;
//		    }
//
//		    $locations = jb_get_locations( $country, $atts['count']);
//
//		    jb_get_template( 'shortcodes/locations-listing.php', array(
//			    'locations'   => $locations,
//			    'title'       => $atts['title'],
//			    'description' => $atts['description'],
//			    'view'        => $atts['view'],
//		    ) );

	    }

        function shortcodes_dashboard($atts = array(), $content = '')
        {
            global $jobboard;
            if (!is_array($atts)) {
                $atts = array();
            }

            if (is_jb_candidate_dashboard()) {
                $atts['type'] = 'candidate';
            } elseif (is_jb_employer_dashboard()) {
                $atts['type'] = 'employer';
            } elseif (get_current_user_id()) {
                $atts['type'] = 'other';
            } else {
                $atts['type'] = 'not_logged';
            }

            $jobboard->account = $atts['type'];

            ob_start();
            jb_get_template('dashboard/dashboard.php', array('atts' => $atts, 'content' => $content));
            return ob_get_clean();
        }
    }

endif;