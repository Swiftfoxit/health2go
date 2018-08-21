<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Job Featured Widget.
 *
 * @author   JobBoard
 * @category Widgets
 * @package  JobBoard/Widgets
 * @version  1.0.0
 * @extends  JB_Widget
 */
class JB_Widget_Jobs extends JB_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$times = apply_filters( 'jobboard_widget_jobs_times', array(
			12   => esc_html__( 'Last 12 Hours', JB_TEXT_DOMAIN ),
			24   => esc_html__( 'Last 24 Hours', JB_TEXT_DOMAIN ),
			168  => esc_html__( 'Last 7 Days', JB_TEXT_DOMAIN ),
			720  => esc_html__( 'Last 30 Days', JB_TEXT_DOMAIN ),
			2160 => esc_html__( 'Last 3 Months', JB_TEXT_DOMAIN ),
			0    => esc_html__( 'All', JB_TEXT_DOMAIN ),
		) );

		$type = apply_filters( 'jobboard_widget_jobs_types', array(
			'latest'   => esc_html__( 'Latest', JB_TEXT_DOMAIN ),
			'featured' => esc_html__( 'Featured', JB_TEXT_DOMAIN ),
			'popular'  => esc_html__( 'Most Popular', JB_TEXT_DOMAIN ),
		) );

		$this->widget_cssclass    = 'jobboard-widget widget-jobs';
		$this->widget_description = esc_html__( 'A list of jobs.', JB_TEXT_DOMAIN );
		$this->widget_id          = 'jobboard-widget-jobs';
		$this->widget_name        = esc_html__( 'JobBoard Jobs List', JB_TEXT_DOMAIN );
		$this->settings           = array(
			'title'          => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Featured Jobs', JB_TEXT_DOMAIN ),
				'label' => esc_html__( 'Title', JB_TEXT_DOMAIN )
			),
			'type'           => array(
				'type'    => 'select',
				'std'     => 'featured',
				'label'   => esc_html__( 'Data Type', JB_TEXT_DOMAIN ),
				'options' => $type
			),
			'posts_per_page' => array(
				'type'  => 'number',
				'std'   => 5,
				'min'   => 1,
				'step'  => 1,
				'max'   => 50,
				'label' => esc_html__( 'Number of jobs to show', JB_TEXT_DOMAIN )
			),
			'date_posted'    => array(
				'type'    => 'select',
				'std'     => 0,
				'label'   => esc_html__( 'Date Posted', JB_TEXT_DOMAIN ),
				'options' => $times
			),
			'hide_salary'    => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Hide Salary', JB_TEXT_DOMAIN )
			),
			'hide_location'      => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Hide Location', JB_TEXT_DOMAIN )
			)
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$posts_per_page = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : $this->settings['posts_per_page']['std'];
		$date_posted    = isset( $instance['date_posted'] ) ? $instance['date_posted'] : $this->settings['date_posted']['std'];
		$type           = isset( $instance['type'] ) ? $instance['type'] : $this->settings['type']['std'];
		$hide_salary    = isset( $instance['hide_salary'] ) ? $instance['hide_salary'] : $this->settings['hide_salary']['std'];
		$hide_location      = isset( $instance['hide_location'] ) ? $instance['hide_location'] : $this->settings['hide_location']['std'];

		$query = array(
			'post_type'      => 'jobboard-post-jobs',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page
		);

		switch ( $type ) {
			case 'featured':
				$query['meta_query'] = array(
					array(
						'key'     => '_featured',
						'value'   => '1',
						'compare' => '='
					)
				);
				break;
			case 'popular':
				$query['meta_query'] = array(
					array(
						'key'     => '_featured',
						'value'   => '1',
						'compare' => '='
					)
				);
				break;
			case 'similar':

				break;
		}

		$query['date_query'] = JB()->job->query_date_posted( $date_posted );

		do_action( "jobboard_widget_jobs_query_{$type}_before" );

		$jobs = new WP_Query( apply_filters( "jobboard_widget_jobs_query_{$type}_args", $query ) );

		do_action( "jobboard_widget_jobs_query_{$type}_after" );

		$this->widget_start( $args, $instance );

		jb_get_template( 'widgets/widget-jobs.php', array(
			'jobs'        => $jobs,
			'hide_salary' => $hide_salary,
			'hide_location' => $hide_location
		) );

		$this->widget_end( $args );

		wp_reset_postdata();
	}
}
