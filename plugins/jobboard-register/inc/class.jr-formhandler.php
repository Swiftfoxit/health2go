<?php
/**
 * Register FormHandler.
 *
 * @class 		JB_Register_FormHandler
 * @version		1.0.0
 * @package		JB_Register/Classes
 * @category	Class
 * @author 		FOX
 */

if (! defined('ABSPATH')) {
    exit();
}


class JB_Register_FormHandler
{
    function __construct(){
        add_action( 'jobboard_form_action_register_account', array($this, 'register_account') );
        add_action( 'jobboard_form_action_forgot_password', array($this, 'forgot_password') );
        add_action( 'jobboard_form_action_reset_password', array($this, 'reset_password') );
        add_action( 'template_redirect', array($this, 'register_active') );
    }

    function validate_captcha(){
        $site_key = jb_get_option('re-captcha-site-key');
        $secret_key = jb_get_option('re-captcha-secret-key');

        if (!jb_get_option('protect-register', 0)) {
            return false;
        }

        if (!$site_key || !$secret_key) {
            jb_notice_add(esc_html__('Site key or Secret key is null.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return true;
        }

        if (empty($_POST['g-recaptcha-response'])) {
            jb_notice_add(esc_html__('You need to verify captcha before ordering.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return true;
        }

        $args = array(
            'method'  => 'POST',
            'timeout' => 15,
            'body'    => array(
                'secret'   => $secret_key,
                'response' => $_POST['g-recaptcha-response']
            ),
        );

        if (is_wp_error($remote = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', $args))) {
            jb_notice_add(sprintf(esc_html__('Error: %s',JB_REGISTER_TEXT_DOMAIN), $remote->get_error_message()), 'error');
            return true;
        }

        $data = json_decode($remote['body']);

        if ($data->success) {
            return false;
        } else {
            jb_notice_add(esc_html__('Captcha not correct.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return true;
        }

        jb_notice_add(esc_html__('Do not verify captcha.', JB_REGISTER_TEXT_DOMAIN), 'error');
        return true;
    }

    function register_account(){

        if ($this->validate_captcha()){
            return false;
        }

        global $jobboard_register;
        $fields      = $jobboard_register->get_custom_fields();
        $form        = $this->form_validate($fields);
        $user_type   = $form['meta']['user_type'];

        if(!in_array($user_type, array('candidate', 'employer'))){
            return false;
        }

        unset($form['meta']['user_type']);
        if(!$user_id = $this->register_user($form)){
            return false;
        }

        $user = get_user_by('id', $user_id);

        if(jb_get_option("register-{$user_type}-active", false)){
            wp_update_user(array('ID' => $user->ID, 'role' => "jobboard_role_{$user_type}"));
            if(jb_get_option("register-{$user_type}-login", true)) {
                $this->set_login($user);
            }
        }

        do_action("jobboard_{$user_type}_registered", $user);
        do_action("jobboard_user_registered", $user);

        return true;
    }

    function register_user($form){
        global $jobboard_register;

        if($form['validate']){
            jb_notice_add(esc_html__('Error : You need to enter all required fields.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return false;
        }

        if(email_exists($form['user']['user_email'])){
            $page_forgot = jb_get_option('page-forgot-password', 0);
            jb_notice_add(sprintf(esc_html__('Error : User email already exist, if you lost password you can click %sForgot Password%s.', JB_REGISTER_TEXT_DOMAIN), '<a href="'.esc_url(get_permalink($page_forgot)).'">', '</a>'), 'error');
            JB()->session->set( 'validate', array('user_email') );
            return false;
        }

        if(strlen($form['meta']['user_pass']) < 8){
            jb_notice_add(esc_html__('Error : password must be greater than 8 characters.', JB_REGISTER_TEXT_DOMAIN), 'error');
            JB()->session->set( 'validate', array('user_pass') );
            return false;
        }

        if($form['meta']['user_pass'] !== $form['meta']['confirm_pass']){
            jb_notice_add(esc_html__('Error : User password not same confirm password.', JB_REGISTER_TEXT_DOMAIN), 'error');
            JB()->session->set( 'validate', array('user_pass', 'confirm_pass') );
            return false;
        }

        $form['user']['user_pass']      = $form['meta']['user_pass'];
        $form['user']['display_name']   = $form['meta']['last_name'];

        unset($form['meta']['user_pass']);
        unset($form['meta']['confirm_pass']);

        if(is_wp_error($user = wp_insert_user($form['user']))){
            jb_notice_add(sprintf(esc_html__('Error : %s.', JB_REGISTER_TEXT_DOMAIN), $user->get_error_message()), 'error');
            return false;
        }

        if(!empty($form['files'])){
            foreach ($form['files'] as $meta => $file){
                $form['meta'][$meta] = JB()->form->upload_files($file);
            }
        }

        if(!empty($form['meta'])) {
            foreach ($form['meta'] as $meta => $value) {
                update_user_meta($user, $meta, $value);
            }
        }

        jb_notice_add(sprintf(esc_html__('Success : User %s Created!', JB_REGISTER_TEXT_DOMAIN), $form['user']['user_login']));

        $jobboard_register->registed = true;

        return $user;
    }

    function register_active(){

        if(empty($_GET['action']) || empty($_GET['email']) || empty($_GET['key'])){
            return;
        }

        if($_GET['action'] !== 'register-active'){
            return;
        }

        if(!$user = get_user_by('email', $_GET['email'])){
            jb_notice_add( esc_html__( 'Notice : Your account has been removed!', JB_REGISTER_TEXT_DOMAIN ), 'notice');
            return;
        }

        $server_key = get_user_meta($user->ID, '_jobboard_register_key', true);
        $local_key  = $_GET['key'];

        if($server_key !== $local_key){
            return;
        }

        $role       = get_user_meta($user->ID, '_jobboard_register_role', true);

        if(in_array($role, $user->roles)){
            jb_notice_add( esc_html__( 'Success : Your account has been activated!', JB_REGISTER_TEXT_DOMAIN ), 'notice');
            return;
        }

        wp_update_user(array('ID' => $user->ID, 'role' => $role));

        $this->set_login($user);

        jb_notice_add( esc_html__( 'Success : Your account has been activated!', JB_REGISTER_TEXT_DOMAIN ));
    }

    function set_login($user){
        clean_user_cache($user->ID);
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true, false);
        update_user_caches($user);

        do_action('wp_login', $user->user_login, $user);
    }

    function form_validate($fields){

        if(empty($fields)){
            return array('validate' => true);
        }

        $user       = array();
        $user_meta  = array();
        $validate   = array();
        $user_keys  = jb_user_keys();
        $files      = array();

        foreach ($fields as $field){
            if(empty($field['id'])){
                continue;
            }

            if($field['type'] == 'media' && isset($_FILES[$field['id']]) && $_FILES[$field['id']]['error'] == 0){
                $files[$field['id']] = $_FILES[$field['id']];
                continue;
            } elseif ($field['type'] == 'media' && isset($field['require'])){
                $validate[$field['id']] = $field['id'];
                continue;
            }

            if(isset($field['require']) && empty($_POST[$field['id']])){
                $validate[$field['id']] = $field['id'];
                continue;
            }

            if(in_array($field['id'], $user_keys) && isset($_POST[$field['id']])){
                $user[$field['id']] = $_POST[$field['id']];
                continue;
            }

            if(isset($_POST[$field['id']])) {
                $user_meta[$field['id']] = $_POST[$field['id']];
            }
        }

        JB()->session->set( 'validate', $validate );

        return array(
            'validate' => !empty($validate) ? true : false,
            'user'     => $user,
            'meta'     => $user_meta,
            'files'    => $files
        );
    }

    function forgot_password(){
        if(empty($_POST['email'])){
            jb_notice_add(esc_html__('Error : You need to enter an email address.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return;
        }

        if(!is_email($_POST['email'])){
            jb_notice_add(esc_html__('Error : This is not an email address.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return;
        }

        if(!email_exists($_POST['email'])){
            jb_notice_add(esc_html__('Error : Email address does not exist.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return;
        }

        $user    = get_user_by('email', $_POST['email']);

        $from    = jb_get_option('register-forgot-email-from', get_bloginfo('name'));;
        $reply   = jb_get_option('register-forgot-email-reply', get_bloginfo('admin_email'));
        $subject = jb_get_option('register-forgot-email-subject', get_bloginfo('description'));

        $user->forgot_password = $this->forgot_keys($user);

        ob_start();

        jb_register()->get_template('emails/forgot-password.php', array('user' => $user));

        $message = ob_get_clean();
        $email   = new JobBoard_Emails($user->user_email, $from, $reply, $subject, $message);
        $email->send();

        jb_notice_add(esc_html__('Success : New password has been sent to your email.', JB_REGISTER_TEXT_DOMAIN));
    }

    function forgot_keys($user){
        $key  = md5(microtime() . $user->user_email);
        $page = jb_get_option('page-forgot-password');
        $url  = add_query_arg(array(
            'action'    => 'reset-password',
            'email'     => $user->user_email,
            'key'       => $key
        ), get_permalink($page));

        update_user_meta($user->ID, '_jobboard_forgot_key', $key);

        return $url;
    }

    function reset_password(){
        if(!$user = jb_register()->validate_reset_password()){
            return;
        }

        if(empty($_POST['new_pass']) || empty($_POST['confirm_pass'])){
            return;
        }

        $new_pass       = $_POST['new_pass'];
        $confirm_pass   = $_POST['confirm_pass'];

        if($new_pass !== $confirm_pass){
            jb_notice_add(esc_html__('Error : New password not same confirm password.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return;
        }

        if(strlen($new_pass) < 8){
            jb_notice_add(esc_html__('Error : password must be greater than 8 characters.', JB_REGISTER_TEXT_DOMAIN), 'error');
            return;
        }

        if(is_wp_error($error = wp_update_user(array('ID' => $user->ID, 'user_pass' => $new_pass)))){
            jb_notice_add(sprintf(esc_html__('Error : %s.', JB_REGISTER_TEXT_DOMAIN), $error->get_error_message()), 'error');
            return;
        }

        $this->set_login($user);
        update_user_meta($user->ID, '_jobboard_forgot_key', '');

        jb_notice_add(esc_html__('Success : Your password has been changed.', JB_REGISTER_TEXT_DOMAIN));
    }
}

new JB_Register_FormHandler();