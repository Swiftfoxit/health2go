<?php
/**
 * @Template: class.jr-handle.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 13-Dec-17
 */
if (!defined('ABSPATH')) {
    die();
}
if (!class_exists('JB_Resources_Handle')) {
    class JB_Resources_Handle
    {
        public function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'jr_enqueue_scripts'));
            add_action('wp_ajax_jr_download_handle', array($this, 'jr_download_handle'));
            add_action('wp_ajax_nopriv_jr_download_handle', array($this, 'jr_download_handle'));
        }

        function jr_download_handle()
        {
            if (empty($_REQUEST['email']) || empty($_REQUEST['rs_id']) || !wp_verify_nonce($_REQUEST['nonce'], 'jr_download')) {
                $rp = array(
                    'stt' => 'error',
                    'msg' => esc_html__('Download failed !', JB_RESOURCES_TEXT_DOMAIN)
                );
                wp_send_json($rp);
                die();
            }

            if (get_post($_REQUEST['rs_id']) === null) {
                $rp = array(
                    'stt' => 'error',
                    'msg' => esc_html__('Resource not available !', JB_RESOURCES_TEXT_DOMAIN)
                );
                wp_send_json($rp);
                die();
            }
            $file_id = get_post_meta($_REQUEST['rs_id'], '_resources_file', true);
            $link = wp_get_attachment_url($file_id);
            if (empty($link)) {
                $rp = array(
                    'stt' => 'error',
                    'msg' => esc_html__('Resource not available !', JB_RESOURCES_TEXT_DOMAIN)
                );
                wp_send_json($rp);
                die();
            }

            $email = $_REQUEST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rp = array(
                    'stt' => 'error',
                    'msg' => esc_html__('Email invalid !', JB_RESOURCES_TEXT_DOMAIN)
                );
                wp_send_json($rp);
                die();
            }
            $new_id = wp_insert_post(array(
                'post_title' => $email,
                'post_type' => 'jr-downloaded',
                'post_status' => 'publish'
            ));
            update_post_meta($new_id,'_resource_id',$_REQUEST['rs_id']);

            $headers = array('Content-Type: text/html; charset=UTF-8');
            $mail_title = esc_html__('Recruitment Resources Download', JB_RESOURCES_TEXT_DOMAIN);
            $mail_content = esc_html__('You have downloaded a resources on website', JB_RESOURCES_TEXT_DOMAIN) . ' <a href="' . get_bloginfo("url") . '">' . get_bloginfo("name") . '</a><br/>';
            $mail_content .= esc_html__('Please click on this link to download:', JB_RESOURCES_TEXT_DOMAIN) . ' <a href="' . $link . '">' . $link . '</a><br/>';
            $mail_content .= esc_html__('Regards, ', JB_RESOURCES_TEXT_DOMAIN) . '<a href="' . get_bloginfo("url") . '">' . get_bloginfo("name") . '</a>';
            wp_mail($email, $mail_title, $mail_content, $headers);
            $rp = array(
                'stt' => 'done',
                'msg' => esc_html__('Please check the email to download the resource !', JB_RESOURCES_TEXT_DOMAIN)
            );
            wp_send_json($rp);
            die();
        }

        function jr_enqueue_scripts()
        {
            if (is_post_type_archive('jb-resources')) {
                wp_enqueue_script('jobboard-resources.js', jb_resources()->plugin_directory_uri . 'assets/jobboard-resources.js', array(), 'all', true);
                $params = array(
                    'ajax_url' => admin_url('admin-ajax.php')
                );
                wp_localize_script('jobboard-resources.js', 'data_ajax', $params);
            }
        }

    }
}