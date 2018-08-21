<?php

function jb_alerts_form_alerts(){
    jb_alerts()->get_template('endpoint-alerts.php');
}

function jb_alerts_form_alerts_sections(){
    global $wpdb;

    $user_id        = get_current_user_id();
    $types          = jb_get_type_options();
    $specialisms    = jb_get_specialism_options();
    $locations      = jb_get_taxonomy_options(
        array(
            'taxonomy'   => 'jobboard-tax-locations',
            'hide_empty' => false,
            'parent'     => 0
        )
    );

    $alerts_values = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}jobboard_interest WHERE user_id = %d", $user_id));

    $alerts_values[] = (object)array(
        'id'            => 'add',
        'types'         => '',
        'specialisms'   => '',
        'locations'     => '',
        'keywords'      => '',
    );

    foreach ($alerts_values as $k => $alert){

        $alert_specialisms  = maybe_unserialize($alert->specialisms);
        $alert_keywords     = maybe_unserialize($alert->keywords);
        $alert_locations    = maybe_unserialize($alert->locations);
        $alert_types        = maybe_unserialize($alert->types);

        $fields = array(
            array (
                'id'         => "specialisms-{$alert->id}",
                'name'       => "alerts[{$alert->id}][specialisms][]",
                'title'      => esc_html__('Job Sector', JB_ALEART_TEXT_DOMAIN ),
                'type'       => 'select',
                'value'      => $alert_specialisms,
                'multi'      => true,
                'options'    => $specialisms,
            ),
            array (
                'id'         => "keywords-{$alert->id}",
                'name'       => "alerts[{$alert->id}][keywords][]",
                'title'      => esc_html__('Keywords', JB_ALEART_TEXT_DOMAIN ),
                'type'       => 'tags',
                'value'      => $alert_keywords,
                'options'    => array()
            ),
            array (
                'id'         => "locations-{$alert->id}",
                'name'       => "alerts[{$alert->id}][locations][]",
                'title'      => esc_html__('Locations', JB_ALEART_TEXT_DOMAIN ),
                'type'       => 'select',
                'value'      => $alert_locations,
                'multi'      => true,
                'options'    => $locations,
            ),
            array (
                'id'         => "types-{$alert->id}",
                'name'       => "alerts[{$alert->id}][types][]",
                'title'      => esc_html__('Employment Types', JB_ALEART_TEXT_DOMAIN ),
                'type'       => 'select',
                'value'      => $alert_types,
                'multi'      => true,
                'options'    => $types,
            )
        );

        $fields = apply_filters('jobboard_alerts_fields', $fields, $alert);
        $active = $k == 0 ? ' active' : '';

        jb_alerts()->get_template('alerts-sections.php', array('fields' => $fields, 'active' => $active));

        if(!$alert_specialisms && !$alert_keywords && !$alert_locations && !$alert_types){
            break;
        }
    }
}

function jb_alerts_form_alerts_actions(){

    jb_alerts()->get_template('alerts-actions.php');
}

function jb_alerts_form_notices(){

    $user_id        = get_current_user_id();
    $posted         = get_user_meta($user_id, '_jobboard_alert_posted', true);
    $interest       = get_user_meta($user_id, '_jobboard_alert_interest', true);
    $schedule       = get_user_meta($user_id, '_jobboard_alert_schedule', true);
    $schedule_type  = get_user_meta($user_id, '_jobboard_alert_schedule_type', true);

    $fields = apply_filters('jobboard_alerts_notices_fields', array(
        array(
            'id'         => 'alerts-heading',
            'title'      => esc_html__('Job Alerts', JB_ALEART_TEXT_DOMAIN ),
            'type'       => 'heading',
            'heading'    => 'h3'
        ),
        array (
            'id'         => 'alerts-posted',
            'type'       => 'checkbox',
            'title'      => '',
            'value'      => $posted,
            'options'    => array(
                1 => esc_html__('Email me when a new job is posted', JB_ALEART_TEXT_DOMAIN ),
            )
        ),
        array (
            'id'         => 'alerts-interest',
            'type'       => 'checkbox',
            'title'      => '',
            'value'      => $interest,
            'options'    => array(
                1 => esc_html__('Email me when a job of interest is posted', JB_ALEART_TEXT_DOMAIN )
            )
        ),
        array(
            'id'         => 'newsletter-heading',
            'title'      => esc_html__('Newsletter', JB_ALEART_TEXT_DOMAIN ),
            'type'       => 'heading',
            'heading'    => 'h3'
        ),
        array (
            'id'         => 'alerts-schedule',
            'type'       => 'checkbox',
            'value'      => $schedule,
            'options'    => array(
                1 => esc_html__('Subscribe to newsletter?', JB_ALEART_TEXT_DOMAIN ),
            )
        ),
        array (
            'id'         => 'alerts-schedule-type',
            'type'       => 'radio',
            'title'      => '',
            'value'      => $schedule_type,
            'options'    => array(
                'daily'     => esc_html__('Daily', JB_ALEART_TEXT_DOMAIN ),
                'weekly'    => esc_html__('Weekly', JB_ALEART_TEXT_DOMAIN ),
                'monthly'   => esc_html__('Monthly', JB_ALEART_TEXT_DOMAIN ),
            )
        ),
    ));

    jb_alerts()->get_template('endpoint-notices.php', array('fields' => $fields));
}

function jb_alerts_form_notices_actions(){
    jb_alerts()->get_template('notices-actions.php');
}