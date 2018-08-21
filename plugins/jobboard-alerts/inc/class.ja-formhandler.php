<?php
/**
 * Alerts FormHandler.
 *
 * @class 		JB_Alerts_FormHandler
 * @version		1.0.0
 * @package		JB_Package/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}


class JB_Alerts_FormHandler
{
    function __construct()
    {
        add_action( 'jobboard_form_action_alerts_alerts', array($this, 'alerts') );
        add_action( 'jobboard_form_action_alerts_notices', array($this, 'notices') );
        add_action( 'jobboard_form_action_alerts_newsletter', array($this, 'newsletter') );
    }

    function alerts(){

        if(!$user_id = get_current_user_id()){
            return;
        }

        global $wpdb;

        $alerts = !empty($_POST['alerts']) ? $_POST['alerts'] : array('add' => 'add');
        $ids    = $wpdb->get_col($wpdb->prepare("SELECT id FROM {$wpdb->prefix}jobboard_interest WHERE user_id = %d", $user_id));

        foreach ($alerts as $id => $alert){

            $data = array();

            $data['types']      = !empty($alert['types']) ? maybe_serialize($alert['types']) : '';
            $data['specialisms']= !empty($alert['specialisms']) ? maybe_serialize($alert['specialisms']) : '';
            $data['locations']  = !empty($alert['locations']) ? maybe_serialize($alert['locations']) : '';
            $data['keywords']   = !empty($alert['keywords']) ? maybe_serialize($alert['keywords']) : '';

            if(in_array($id, $ids)){
                $key = array_search($id, $ids);
                unset($ids[$key]);
                $wpdb->update($wpdb->prefix . 'jobboard_interest', $data, array('id' => $id,'user_id' => $user_id), array('%s','%s','%s','%s'), array('%d', '%d'));
            } elseif($id === 'add' && (!empty($data['types']) || !empty($data['specialisms']) || !empty($data['locations']) || !empty($data['keywords']))) {
                $data['user_id'] = $user_id;
                $wpdb->insert($wpdb->prefix . 'jobboard_interest', $data, array('%s','%s','%s','%s','%d'));
            }
        }

        if(!empty($ids)){
            foreach ($ids as $id) {
                $wpdb->delete($wpdb->prefix . 'jobboard_interest', array('id' => $id));
            }
        }

        jb_notice_add(esc_html__('Successfully Alerts Updated.', JB_ALEART_TEXT_DOMAIN));
    }

    function notices(){

        if(!$user_id = get_current_user_id()){
            return;
        }

        if(!empty($_POST['alerts-posted'])){
            update_user_meta($user_id, '_jobboard_alert_posted', 1);
        } else {
            update_user_meta($user_id, '_jobboard_alert_posted', 0);
        }

        if(!empty($_POST['alerts-interest'])){
            update_user_meta($user_id, '_jobboard_alert_interest', 1);
        } else {
            update_user_meta($user_id, '_jobboard_alert_interest', 0);
        }

        if(!empty($_POST['alerts-schedule'])){
            update_user_meta($user_id, '_jobboard_alert_schedule', 1);
        } else {
            update_user_meta($user_id, '_jobboard_alert_schedule', 0);
        }

        if(!empty($_POST['alerts-schedule-type'])){
            update_user_meta($user_id, '_jobboard_alert_schedule_type', $_POST['alerts-schedule-type']);
        }

        jb_notice_add(esc_html__('Successfully Alerts Updated.', JB_ALEART_TEXT_DOMAIN));
    }

    function newsletter(){
        if(empty($_POST['email'])){
            return;
        }

        $email = sanitize_email($_POST['email']);

        if(!is_email($email)){
            return;
        }

        global $wpdb;

        if($user_id = email_exists($email)){
            update_user_meta($user_id, '_jobboard_alert_schedule', 1);
            update_user_meta($user_id, '_jobboard_alert_schedule_type', 'daily');
        } elseif (!$wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}jobboard_subscribe WHERE email = %s", $email))) {
            $wpdb->insert($wpdb->prefix . 'jobboard_subscribe', array(
                'email' => $email
            ), array('%s'));
        }

        jb_notice_add(esc_html__('Success : You have subscribed!', JB_ALEART_TEXT_DOMAIN));
    }
}

new JB_Alerts_FormHandler();