<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Job Salary Filters Widget.
 *
 * @author   JobBoard
 * @category Widgets
 * @package  JobBoard/Widgets
 * @version  1.0.0
 * @extends  JB_Widget
 */
class JB_Widget_Salary_Filters extends JB_Widget
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->widget_cssclass = 'jobboard-widget widget-salary-filters';
        $this->widget_description = esc_html__('A Salary filters box for JobBoard only.', JB_TEXT_DOMAIN);
        $this->widget_id = 'jobboard-widget-salary-filters';
        $this->widget_name = esc_html__('JobBoard Salary Filters', JB_TEXT_DOMAIN);
        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => esc_html__('Salary', JB_TEXT_DOMAIN),
                'label' => esc_html__('Title', JB_TEXT_DOMAIN)
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
    public function widget($args, $instance)
    {
        $values = isset($_GET['salary-filters']) ? $_GET['salary-filters'] : array();
        $symb = jb_get_currency_symbol(jb_get_option('default-currency'));
        $list_salary = apply_filters('jb/widget/filters/salary', array(
            '0,15000' => '0 - 15000',
            '15000,20000' => '15000 - 20000',
            '20000,30000' => '20000 - 30000',
            '30000,40000' => '30000 - 40000',
            '40000,max' => '40000+',
        ));

        $this->widget_start($args, $instance);

        jb_get_template('widgets/widget-salary-filters.php', array('list_salary' => $list_salary, 'values' => $values));

        $this->widget_end($args);
    }
}
