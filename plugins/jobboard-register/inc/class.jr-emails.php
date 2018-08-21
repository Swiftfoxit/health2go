<?php
/**
 * JobBoard Register Emails.
 *
 * @class 		JobBoard_Register_Emails
 * @version		1.0.0
 * @package		JobBoard/Register/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}

class JobBoard_Register_Emails{
    function __construct()
    {
        add_action('jobboard_candidate_registered', array($this, 'candidate_registered'));
        add_action('jobboard_employer_registered', array($this, 'employer_registered'));
    }

    function candidate_registered($user){
        $to             = $user->user_email;
        $from           = jb_get_option('register-candidate-email-from', get_bloginfo('name'));
        $reply          = jb_get_option('register-candidate-email-reply', get_bloginfo('admin_email'));
        $subject        = jb_get_option('register-candidate-email-subject', get_bloginfo('description'));

        ob_start();

        if(jb_get_option("register-candidate-active")){
            $user->login_page = get_site_url();
            jb_register()->get_template('emails/candidate-welcome.php', array('candidate' => $user));
        } else {
            $user->active_url = $this->generate_active_keys($user, 'jobboard_role_candidate');
            jb_register()->get_template('emails/candidate-active.php', array('candidate' => $user));
        }

        $message        = ob_get_clean();
        $email          = new JobBoard_Emails($to, $from, $reply, $subject, $message);

        $email->send();
    }

    function employer_registered($user){
        $to             = $user->user_email;
        $from           = jb_get_option('register-employer-email-from', get_bloginfo('name'));
        $reply          = jb_get_option('register-employer-email-reply', get_bloginfo('admin_email'));
        $subject        = jb_get_option('register-employer-email-subject', get_bloginfo('description'));

        ob_start();

        if(jb_get_option("register-employer-active")){
            $user->login_page = get_site_url();
            jb_register()->get_template('emails/employer-welcome.php', array('employer' => $user));
        } else {
            $user->active_url = $this->generate_active_keys($user, 'jobboard_role_employer');
            jb_register()->get_template('emails/employer-active.php', array('employer' => $user));
        }

        $message        = ob_get_clean();
        $email          = new JobBoard_Emails($to, $from, $reply, $subject, $message);

        $email->send();
    }

    function generate_active_keys($user, $role){
        $key = md5(microtime() . $user->user_email);
        $url = add_query_arg(array(
            'action'    => 'register-active',
            'email'     => $user->user_email,
            'key'       => $key
        ),get_site_url());

        update_user_meta($user->ID, '_jobboard_register_key', $key);
        update_user_meta($user->ID, '_jobboard_register_role', $role);

        return $url;
    }
}

new JobBoard_Register_Emails();