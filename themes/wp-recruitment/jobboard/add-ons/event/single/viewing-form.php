<?php
/**
 * @Template: viewing-form.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 08-Dec-17
 */
$event = get_post($event_id);
$duration = je_template_job_duration($event_id);
?>
<div class="je-viewing-form">
    <div class="je-viewing-form-inner">
        <div class="je-viewing-form-header">
            <div class="je-viewing-heading">
                <h2><?php echo esc_html__('Register Event', 'wp-recruitment') ?></h2>
                <p><?php esc_html_e('Thanks for showing interest in the event below. Fill out the form to register your interest in our event and for further information.', 'wp-recruitment'); ?></p>
            </div>
            <span class="je-viewing-close"><i class="zmdi zmdi-close"></i></span>
            <div class="je-viewing-property-item hidden-sm hidden-xs clearfix">
                <h3><?php echo esc_attr($event->post_title) ?></h3>
                <div class="loop-actions row">
                    <div class="loop-duration col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <?php
                            if (!empty($duration['date'])) {
                                ?>
                                <div class="je-reg-date entry-date"><?php echo wp_kses_post($duration['date']) ?></div>
                                <?php
                            }
                            ?>
                            <?php
                            if (!empty($duration['date_2'])) {
                                ?>
                                <div class="je-reg-date-2 entry-date"><?php echo wp_kses_post($duration['date_2']) ?></div>
                                <?php
                            }
                            ?>
                            <?php
                            if (!empty($duration['time'])) {
                                ?>
                                <div class="je-reg-date-time entry-time"><?php echo wp_kses_post($duration['time']) ?></div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="loop-locations col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <?php echo esc_attr(je_template_job_location($event_id)); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="je-viewing-form-body">

            <input class="je-reg-name" type="text" name="fullname"
                   placeholder="<?php esc_html_e('Full Name*', 'wp-recruitment'); ?>"
                   required>
            <input class="je-reg-email" type="email" name="email"
                   placeholder="<?php esc_html_e('Email Address*', 'wp-recruitment'); ?>" required>
            <input class="je-reg-number" type="tel" name="phone"
                   placeholder="<?php esc_html_e('Contact Number', 'wp-recruitment'); ?>">
            <button class="je-btn-reg" type="submit" data-id="<?php echo esc_attr($event_id); ?>"><?php esc_html_e('Register Event', 'wp-recruitment'); ?></button>
            <?php wp_nonce_field('je_register_event', '_wp_nonce_register_event'); ?>
        </div>
    </div>
</div>