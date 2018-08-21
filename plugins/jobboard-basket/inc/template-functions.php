<?php
/**
 * JobBoard Basket Template
 *
 * Functions for the templating system.
 *
 * @author   FOX
 * @category Core
 * @package  JobBoard/Basket/Functions
 * @version  1.0.0
 */
function jb_template_widget_basket_button_loop(){
    if(is_jb_applied()){
        jb_basket()->get_template('buttons/loop-applied.php', array( 'app_url' => jb_page_endpoint_url('applied', jb_page_permalink('dashboard'))));
    } elseif (in_jb_basket()){
        jb_basket()->get_template('buttons/loop-added.php', array( 'basket_url' => jb_basket()->get_basket_url()));
    } else {
        jb_basket()->get_template('buttons/loop-add.php');
    }
}

function jb_template_widget_basket_button_single(){

    if(is_jb_applied()){
        return;
    }

    if(in_jb_basket()){
        jb_basket()->get_template('buttons/single-added.php', array( 'basket_url' => jb_basket()->get_basket_url()));
    } else {
        jb_basket()->get_template('buttons/single-add.php');
    }
}

function jb_template_widget_basket_content($basket){
    jb_basket()->get_template('basket-items.php', array('basket' => $basket));
}

function jb_template_widget_basket_button_manage(){
    jb_basket()->get_template('actions/manage.php', array( 'basket_url' => jb_basket()->get_basket_url() ) );
}

function jb_template_widget_basket_button_clear(){
    jb_basket()->get_template('actions/clear.php');
}

function jb_template_widget_basket_page_manage(){
    global $wp_query;

    $columns = apply_filters('jobboard_table_basket_columns', array(
        'title'     => esc_html__('Job Title', JB_BASKET_TEXT_DOMAIN),
        'type'      => esc_html__('Type', JB_BASKET_TEXT_DOMAIN),
        'date'      => esc_html__('Date Added', JB_BASKET_TEXT_DOMAIN),
        'actions'   => esc_html__('Actions', JB_BASKET_TEXT_DOMAIN)
    ));

    $wp_query = jb_basket()->get_basket();
    $base     = jb_page_endpoint_base_pagination('basket', jb_page_permalink('dashboard'));

    jb_get_template( 'dashboard/global/table.php', array('jobs' => $wp_query, 'table' => 'basket', 'columns' => $columns));
    jb_get_template( 'global/pagination.php', array('base' => $base));


    jb_basket()->remove_all_count();
    wp_reset_query();
}

function jb_template_basket_loop_title(){
    global $post, $jobboard_basket;

    $status = '';
    if(!empty($jobboard_basket->add_new) && in_array($post->ID, $jobboard_basket->add_new)){
        $status = array('id'    => 'new', 'name'  => esc_html__('New', JB_BASKET_TEXT_DOMAIN));
        $jobboard_basket->remove_new[] = $post->ID;
    }

    jb_get_template('loop/title.php', array('status' => $status));
}

function jb_template_basket_loop_actions(){
    global $post;

    $actions = apply_filters( 'jobboard_template_basket_actions' , array(
        array(
            'id'            => 'apply',
            'icon'          => 'fa fa-check',
            'title'         => esc_html__('Quick Apply', JB_BASKET_TEXT_DOMAIN),
            'attribute'     => array(
                'data-id'   => $post->ID
            )
        ),
        array(
            'id'            => 'remove',
            'icon'          => 'fa fa-times',
            'title'         => esc_html__('Remove', JB_BASKET_TEXT_DOMAIN),
            'attribute'     => array(
                'data-id'       => $post->ID
            )
        )
    ));

    jb_get_template('dashboard/loop/actions.php', array('actions' => $actions));
}