<?php
/**
 * @Template: more-events.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 14-Dec-17
 */
?>
<div class="jobboard-event-related">
    <h3><?php echo esc_html__('More Events Like This', 'wp-recruitment'); ?></h3>
    <?php foreach ($events as $event):
        if ($event->ID !== get_the_ID()):
            ?>
            <article id="loop-<?php echo esc_attr($event->ID) ?>"
                     class="post-<?php echo esc_attr($event->ID) ?> jb-events type-jb-events status-publish hentry jobboard-event-type-job-searching">
                <div class="loop-summary">
                    <div class="loop-title">
                        <h2 class="entry-title">
                            <a href="<?php echo get_permalink($event->ID) ?>"
                               rel="bookmark"><?php echo esc_attr($event->post_title) ?></a>
                        </h2>
                    </div>
                </div>
                <div class="loop-actions row">
                    <?php $duration = je_template_job_event_duration($event->ID); ?>
                    <div class="loop-duration col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <?php if ( isset( $duration['date'] ) ): ?>
                            <div class="entry-date"><?php echo wp_kses_post($duration['date']); ?></div>
                        <?php endif; ?>
                        <?php if ( isset( $duration['date_2'] ) ): ?>
                            <div class="entry-date"><?php echo wp_kses_post($duration['date_2']); ?></div>
                        <?php endif; ?>
                        <?php if ( isset( $duration['time'] ) ): ?>
                            <div class="entry-time"><?php echo wp_kses_post($duration['time']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="loop-locations col-lg-5 col-md-4 col-sm-12 col-xs-12">
                        <?php echo esc_attr(get_post_meta($event->ID, '_address', true)) ?>
                    </div>
                    <div class="loop-readmore col-lg-3 col-md-4 col-sm-12 col-xs-12 text-right text-left-sm text-left-xs">
                        <a class="btn"
                           href="<?php echo get_permalink($event->ID) ?>"><?php echo esc_html__('View Event', 'wp-recruitment') ?></a>
                    </div>
                </div>

            </article>
        <?php
        endif;
    endforeach; ?>
</div>