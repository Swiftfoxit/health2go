<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Job Newsletter Widget.
 *
 * @author   JobBoard
 * @category Widgets
 * @package  JobBoard/Widgets
 * @version  1.0.0
 * @extends  JB_Widget
 */
class JB_Widget_Newsletter extends JB_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'jobboard-widget widget-newsletter';
        $this->widget_description = esc_html__( 'A Newsletter box for JobBoard only.', JB_ALEART_TEXT_DOMAIN );
        $this->widget_id          = 'jobboard-widget-newsletter';
        $this->widget_name        = esc_html__( 'JobBoard Newsletter', JB_ALEART_TEXT_DOMAIN );
        $this->settings           = array(
            'title'         => array(
                'type'      => 'text',
                'std'       => esc_html__('Job Newsletter', JB_ALEART_TEXT_DOMAIN),
                'label'     => esc_html__( 'Title', JB_ALEART_TEXT_DOMAIN )
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
        $this->widget_start( $args, $instance );

        jb_alerts()->get_template('newsletter-form.php');

        $this->widget_end( $args );
    }
}
