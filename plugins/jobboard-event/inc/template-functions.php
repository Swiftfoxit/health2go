<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 10:55 AM
 */

function je_template_job_loop_summary_title()
{
    global $post;

    $status = '';
    $featured = get_post_meta($post->ID, '_featured', true);

    if ($featured) {
        $status = array('id' => 'featured', 'name' => esc_html__('Featured', JB_EVENT_TEXT_DOMAIN));
    }

    JB_Event()->get_template('loop/title.php', array('status' => $status));
}

function je_template_job_loop_summary_duration()
{
    global $post;

    $start = get_post_meta($post->ID, '_start', true);
    $end = get_post_meta($post->ID, '_end', true);
    $date_start = date('F, d Y', strtotime($start));
    $date_end = date('F, d Y', strtotime($end));
    if ($date_start === $date_end) {
        $date = date('F, d Y', strtotime($start));
        $time = date('h:i A', strtotime($start)) . ' - ' . date('h:i A', strtotime($end));
        JB_Event()->get_template('loop/duration.php', array('date' => $date, 'time' => $time));
    } else {
        $date = date('F, d Y', strtotime($start));
        $time = date('h:i A', strtotime($start));
        $date_2 = date('F, d Y', strtotime($end));
        $time_2 = date('h:i A', strtotime($end));
        $date_time_start = $date . '<span class="entry-time">' . $time . '</span>';
        $date_time_end = $date_2 . '<span class="entry-time">' . $time_2 . '</span>';
        JB_Event()->get_template('loop/duration.php', array('date' => $date_time_start, 'date_2' => $date_time_end));
    }
}
function je_template_job_event_duration($post_id)
{
    $post = get_post($post_id);

    $start = get_post_meta($post->ID, '_start', true);
    $end = get_post_meta($post->ID, '_end', true);
    $date_start = date('F, d Y', strtotime($start));
    $date_end = date('F, d Y', strtotime($end));
    if ($date_start === $date_end) {
        $date = date('F, d Y', strtotime($start));
        $time = date('h:i A', strtotime($start)) . ' - ' . date('h:i A', strtotime($end));
        return array('date' => $date, 'time' => $time);
    } else {
        $date = date('F, d Y', strtotime($start));
        $time = date('h:i A', strtotime($start));
        $date_2 = date('F, d Y', strtotime($end));
        $time_2 = date('h:i A', strtotime($end));
        $date_time_start = $date . '<span class="entry-time">' . $time . '</span>';
        $date_time_end = $date_2 . '<span class="entry-time">' . $time_2 . '</span>';
        return array('date' => $date_time_start, 'date_2' => $date_time_end);
    }
}

function je_template_job_duration($post_id)
{
    $start = get_post_meta($post_id, '_start', true);
    $end = get_post_meta($post_id, '_end', true);
    $date_start = date('F, d Y', strtotime($start));
    $date_end = date('F, d Y', strtotime($end));
    if ($date_start === $date_end) {
        $date = date('F, d Y', strtotime($start));
        $time = date('h:i A', strtotime($start)) . ' - ' . date('h:i A', strtotime($end));
        return array('date' => $date, 'time' => $time);
    } else {
        $date = date('F, d Y', strtotime($start));
        $time = date('h:i A', strtotime($start));
        $date_2 = date('F, d Y', strtotime($end));
        $time_2 = date('h:i A', strtotime($end));
        $date_time_start = $date . '<span class="entry-time">' . $time . '</span>';
        $date_time_end = $date_2 . '<span class="entry-time">' . $time_2 . '</span>';
        return array('date' => $date_time_start, 'date_2' => $date_time_end);
    }
}

function je_template_job_location($post_id)
{
    return get_post_meta($post_id, '_address', true);
}

function je_template_event_register_form()
{
    if (!empty($_REQUEST['id']) && !empty(get_post($_REQUEST['id']))) {
        ob_start();
        JB_Event()->get_template('single/viewing-form.php', array('event_id' => $_REQUEST['id']));
        wp_send_json(ob_get_clean());
        die();
    } else {
        wp_send_json('error');
        die();
    }
}


function je_save_register_event()
{
    if (!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'je_register_event')) {
        if (!empty($_REQUEST['name'])) {
            $name = $_REQUEST['name'];
        }
        if (!empty($_REQUEST['email'])) {
            $email = $_REQUEST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message['msg'] = '<p>' . esc_html__('Email invalid!', JB_EVENT_TEXT_DOMAIN) . '</p>';
                $message['stt'] = 'error_email';
                wp_send_json($message);
                die();
            }
        }
        if (!empty($_REQUEST['phone'])) {
            $phone = $_REQUEST['phone'];
        }
        if (!empty($_REQUEST['message'])) {
            $message = $_REQUEST['message'];
        }
        if (!empty($_REQUEST['id_event'])) {
            $id_event = $_REQUEST['id_event'];
            if (get_post($id_event) === null) {
                $message['msg'] = '<p>' . esc_html__('Event not available !', JB_EVENT_TEXT_DOMAIN) . '</p>';
                $message['stt'] = 'error';
                wp_send_json($message);
                die();
            }
        }

        //Handle content
        $title = esc_html__('Name:', JB_EVENT_TEXT_DOMAIN) . ' ' . $name . ' ' . esc_html__('- Event ID:', JB_EVENT_TEXT_DOMAIN) . ' ' . $id_event;
        $contents = '<h3>-----' . esc_html__('Subscriber Information', 'property') . '-----</h3><br/>';
        $contents .= '' . esc_html__('Name: ', JB_EVENT_TEXT_DOMAIN) . '<b>' . $name . '</b><br/>';
        $contents .= '' . esc_html__('Email: ', JB_EVENT_TEXT_DOMAIN) . '<b>' . $email . '</b><br/>';
        $contents .= '' . esc_html__('Phone: ', JB_EVENT_TEXT_DOMAIN) . '<b>' . $phone . '</b><br/>';
        $contents .= '<h3>-----' . esc_html__('Event Information', JB_EVENT_TEXT_DOMAIN) . '-----</h3><br/>';
        $contents .= '' . esc_html__('ID:', JB_EVENT_TEXT_DOMAIN) . ' ' . '<b>' . $id_event . '</b><br/>';
        $contents .= '' . esc_html__('Event name:', JB_EVENT_TEXT_DOMAIN) . ' ' . '<b>' . get_the_title($id_event) . '</b><br/>';
        $post_new = wp_insert_post(array(
            'post_title' => $title,
            'post_content' => $contents,
            'post_type' => 'je-inbox',
            'post_status' => 'pending',
        ));
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $mail_title = esc_html__('Register', JB_EVENT_TEXT_DOMAIN);
        $mail_content = esc_html__('You registered event', JB_EVENT_TEXT_DOMAIN) . '<br/>';
        $mail_content .= $contents;
        $mail_content .= esc_html__('Please wait our contact. Thank you very much!', JB_EVENT_TEXT_DOMAIN);
        $mail_content .= esc_html__('Regards, ', JB_EVENT_TEXT_DOMAIN) . '<a href="' . get_bloginfo("url") . '">' . get_bloginfo("name") . '</a>';
        wp_mail($email, $mail_title, $mail_content, $headers);
        if ($post_new) {
            $message['msg'] = '<p>' . esc_html__('Event register Successfully!', JB_EVENT_TEXT_DOMAIN) . '</p>';
            $message['stt'] = 'done';
            wp_send_json($message);
            die();
        } else {
            $message['msg'] = '<p>' . esc_html__('Event register failed !', JB_EVENT_TEXT_DOMAIN) . '</p>';
            $message['stt'] = 'error';
            wp_send_json($message);
            die();
        }
    } else {
        $message['msg'] = '<p>' . esc_html__('Event register failed !', JB_EVENT_TEXT_DOMAIN) . '</p>';
        $message['stt'] = 'error';
        wp_send_json($message);
        die();
    }
}

function je_template_job_loop_summary_location()
{
    JB_Event()->get_template('loop/location.php');
}

function je_template_event_loop_actions_readmore()
{
    JB_Event()->get_template('loop/readmore.php');
}

function je_template_event_loop_actions()
{
    JB_Event()->get_template('loop/actions.php');
}

function je_template_single_header()
{
    JB_Event()->get_template('single/header.php');
}

function je_template_single_register()
{
    JB_Event()->get_template('single/register.php');
}

function je_template_single_summary()
{
    $avatar = get_post_meta(get_the_ID(), '_speaker_avatar', true);
    $name = get_post_meta(get_the_ID(), '_speaker_name', true);
    $address = get_post_meta(get_the_ID(), '_speaker_address', true);
    ?>

    <?php if (!empty($name)) : ?>
    <div class="jobboard-event-speaker">
        <img src="<?php echo !empty($avatar['id']) ? wp_get_attachment_image_url($avatar['id']) : '' ?>" alt="">
        <div class="je-speaker-holder">
            <span><?php echo esc_html__('Event Speaker', JB_EVENT_TEXT_DOMAIN) ?></span>
            <h4 class="je-speaker-name"><?php echo $name ?></h4>
            <span class="je-speaker-address"><?php echo $address ?></span>
        </div>
    </div>
<?php endif; ?>
    <div class="jobboard-event-content"><?php the_content(); ?></div>
    <?php

    ?>
<?php }

function je_template_single_map()
{
    if (function_exists('jb_map')) {
        JB_Event()->get_template('single/map.php');
    }
}

function je_template_events_like()
{
    $ids = wp_get_post_terms(get_the_ID(), 'jobboard-event-type', array('fields' => 'ids'));
    $events = get_posts(array(
        'post_type' => 'jb-events',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'jobboard-event-type',
                'field' => 'term_id',
                'terms' => $ids,
                'operator' => 'IN',
            ),
        )
    ));
    JB_Event()->get_template('single/map.php');
    JB_Event()->get_template('single/more-events.php', array('events' => $events));
}

function je_template_loop_start()
{
    JB_Event()->get_template('loop/loop-start.php');
}

function je_template_loop_end()
{
    JB_Event()->get_template('loop/loop-end.php');
}

function je_template_job_loop_summary_start()
{
    JB_Event()->get_template('loop/summary-start.php');
}

function je_template_job_loop_summary_end()
{
    JB_Event()->get_template('loop/summary-end.php');
}
