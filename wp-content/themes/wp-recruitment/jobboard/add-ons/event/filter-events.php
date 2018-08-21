<?php
/**
 * @Template: filter-events.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 07-Dec-17
 */
$term_list = get_terms(array(
        'taxonomy' => 'jobboard-event-type',
        'hide_empty' => false,
    )
);
?>
<ul class="jobboard-filter-events">
    <li class="jb-filter-event-types jb-event-active" id="jb-filter-event-type-0">
        <span><?php echo esc_html__('All Events', 'wp-recruitment') ?></span>
    </li>
    <?php
    foreach ($term_list as $term) {
        ?>
        <li class="jb-filter-event-types" id="jb-filter-event-type-<?php echo esc_attr($term->term_id) ?>">
            <span><?php echo esc_attr($term->name) ?></span>
        </li>
        <?php
    }
    ?>
</ul>
