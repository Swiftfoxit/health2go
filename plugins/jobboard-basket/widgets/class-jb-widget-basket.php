<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Job Basket Widget.
 *
 * @author   JobBoard
 * @category Widgets
 * @package  JobBoard/Widgets
 * @version  1.0.0
 * @extends  JB_Widget
 */
class JB_Widget_Basket extends JB_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'jobboard-widget widget-basket';
        $this->widget_description = esc_html__( 'A Basket box for JobBoard only.', JB_BASKET_TEXT_DOMAIN );
        $this->widget_id          = 'jobboard-widget-basket';
        $this->widget_name        = esc_html__( 'JobBoard Basket', JB_BASKET_TEXT_DOMAIN );
        $this->settings           = array(
            'title'         => array(
                'type'      => 'text',
                'std'       => esc_html__('Job Basket', JB_BASKET_TEXT_DOMAIN),
                'label'     => esc_html__( 'Title', JB_BASKET_TEXT_DOMAIN )
            ),
            'header_text'   => array(
                'type'      => 'text',
                'std'       => '',
                'label'     => esc_html__( 'Header Title', JB_BASKET_TEXT_DOMAIN )
            ),
            'dropdown'      => array(
                'type'      => 'select',
                'std'       => 'normal',
                'label'     => esc_html__( 'Drop Down', JB_BASKET_TEXT_DOMAIN ),
                'options'   => array(
                    'normal'      => esc_html__( 'Normal', JB_BASKET_TEXT_DOMAIN ),
                    'click' => esc_html__( 'Click', JB_BASKET_TEXT_DOMAIN ),
                    'hover' => esc_html__( 'Hover', JB_BASKET_TEXT_DOMAIN ),
                )
            ),
            'show_count'    => array(
                'type'      => 'radio',
                'std'       => 'after',
                'options'   => array(
                    'hide'  => esc_html__('Hide', JB_BASKET_TEXT_DOMAIN),
                    'before'=> esc_html__('Before Title', JB_BASKET_TEXT_DOMAIN),
                    'after' => esc_html__('After Title', JB_BASKET_TEXT_DOMAIN),
                ),
                'label'     => esc_html__( 'Basket Counts', 'jobboard' )
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
        global $jobboard_basket;

        $dropdown           = isset( $instance['dropdown'] ) ? $instance['dropdown'] : $this->settings['dropdown']['std'];
        $header_text        = isset( $instance['header_text'] ) ? $instance['header_text'] : $this->settings['header_text']['std'];

        $basket             = $jobboard_basket->get_basket_user();

        $instance['count']  = isset($basket->post_count) ? $basket->post_count : 0;

        add_filter('widget_title', array($this, 'widget_title'), 10, 2);

        $this->widget_start( $args, $instance );

        $jobboard_basket->get_template('basket-content.php', array(
            'basket'        => $basket,
            'dropdown'      => $dropdown,
            'header_text'   => $header_text
        ));

        $this->widget_end( $args );

        remove_filter('widget_title', array($this, 'widget_title'), 10);

        wp_reset_postdata();
    }

    public function widget_title($title, $instance){

        $show_count = isset( $instance['show_count'] ) ? $instance['show_count'] : $this->settings['show_count']['std'];

        $count_html = '<span class="jobboard-count">'.esc_html($instance['count']).'</span>';

        switch ($show_count){
            case 'after':
                $title .= $count_html;
                break;
            case 'before':
                $title = $count_html . $title;
                break;
        }

        return '<span class="jobboard-widget-title basket-title">' . $title. '</span>';
    }
}
