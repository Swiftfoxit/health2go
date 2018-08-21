<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Job Date Filters Widget.
 *
 * @author   JobBoard
 * @category Widgets
 * @package  JobBoard/Widgets
 * @version  1.0.0
 * @extends  JB_Widget
 */
class JB_Widget_Date_Filters extends JB_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'jobboard-widget widget-date-filters';
        $this->widget_description = esc_html__( 'A date filters box for JobBoard only.', JB_TEXT_DOMAIN );
        $this->widget_id          = 'jobboard-widget-date-filters';
        $this->widget_name        = esc_html__( 'JobBoard Date Filters', JB_TEXT_DOMAIN );
        $this->settings           = array(
            'title'  => array(
                'type'  => 'text',
                'std'   => esc_html__( 'Date Posted', JB_TEXT_DOMAIN ),
                'label' => esc_html__( 'Title', JB_TEXT_DOMAIN )
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

        $value = isset($_GET['date-filters']) ? $_GET['date-filters'] : '';

        $times = apply_filters('jb/widget/filters/times', array(
            6   => esc_html__('Last 6 Hours',   JB_TEXT_DOMAIN),
            12  => esc_html__('Last 12 Hours',  JB_TEXT_DOMAIN),
            24  => esc_html__('Last 24 Hours',  JB_TEXT_DOMAIN),
            168 => esc_html__('Last 7 Days',    JB_TEXT_DOMAIN),
            720 => esc_html__('Last 30 Days',   JB_TEXT_DOMAIN),
            0   => esc_html__('All',            JB_TEXT_DOMAIN),
        ));


        $this->widget_start( $args, $instance );

        jb_get_template('widgets/widget-date-filters.php', array('times' => $times, 'value' => $value));

        $this->widget_end( $args );
    }
}
