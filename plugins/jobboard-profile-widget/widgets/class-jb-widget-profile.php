<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Job Profile Widget.
 *
 * @author   JobBoard
 * @category Widgets
 * @package  JobBoard/Widgets
 * @version  1.0.0
 * @extends  JB_Widget
 */
class JB_Widget_Profile extends JB_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'jobboard-widget widget-profile';
        $this->widget_description = esc_html__( 'JobBoard login/register/profile.', 'jobboard' );
        $this->widget_id          = 'jb-widget-profile';
        $this->widget_name        = esc_html__( 'JobBoard Profile', 'jobboard' );
        $this->settings           = array(
            'title'  => array(
                'type'  => 'text',
                'std'   => esc_html__('Login/Register', 'jobboard'),
                'label' => esc_html__( 'Title', 'jobbojobboard-profile-widgetard' )
            ),
            'dropdown'      => array(
                'type'      => 'select',
                'std'       => 'normal',
                'label'     => esc_html__( 'Drop Down', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                'options'   => array(
                    'normal'      => esc_html__( 'Normal', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'click' => esc_html__( 'Click', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'hover' => esc_html__( 'Hover', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                )
            ),
            'show_name'      => array(
                'type'      => 'select',
                'std'       => 'display_name',
                'label'     => esc_html__( 'User Name', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                'options'   => array(
                    ''                  => esc_html__( 'Hide', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'user_login'        => esc_html__( 'Username', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'user_email'        => esc_html__( 'User email', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'user_firstname'    => esc_html__( 'User first name', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'user_lastname'     => esc_html__( 'User last name', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                    'display_name'      => esc_html__( 'User display name', JB_PROFILE_WIDGET_TEXT_DOMAIN ),
                )
            ),
            'show_avatar'    => array(
                'type'      => 'radio',
                'std'       => 'before',
                'options'   => array(
                    'hide'  => esc_html__('Hide', JB_PROFILE_WIDGET_TEXT_DOMAIN),
                    'before'=> esc_html__('Before Title', JB_PROFILE_WIDGET_TEXT_DOMAIN),
                    'after' => esc_html__('After Title', JB_PROFILE_WIDGET_TEXT_DOMAIN),
                ),
                'label'     => esc_html__( 'Profile Picture', JB_PROFILE_WIDGET_TEXT_DOMAIN )
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

        global $jobboard_profile;

        $dropdown = isset( $instance['dropdown'] ) ? $instance['dropdown'] : $this->settings['dropdown']['std'];

        add_filter('widget_title', array($this, 'widget_title'), 10, 2);

        $this->widget_start( $args, $instance );

        $jobboard_profile->get_template('profile-content.php', array('dropdown' => $dropdown, 'instance' => $instance));

        $this->widget_end( $args );

        remove_filter('widget_title', array($this, 'widget_title'), 10);
    }

    public function widget_title($title, $instance){

        $class = 'profile-login';

        if(is_user_logged_in()) {

            $current_user   = wp_get_current_user();
            $show_avatar    = isset( $instance['show_avatar'] ) ? $instance['show_avatar'] : $this->settings['show_avatar']['std'];
            $show_name      = isset( $instance['show_name'] ) ? $instance['show_name'] : $this->settings['show_name']['std'];
            $avatar_image   = get_avatar($current_user->user_email);

            if($show_name) {
                $title      = $current_user->$show_name;
            }

            switch ($show_avatar){
                case 'after':
                    $title .= $avatar_image;
                    break;
                case 'before':
                    $title = $avatar_image . $title;
                    break;
            }

            $class = 'profile-logged';
        }

        return '<span class="jobboard-widget-title profile-title ' . $class . '">'. $title . '</span>';
    }
}
