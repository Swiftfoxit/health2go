<?php
/**
 * The Template for displaying widgets salary filters.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/widgets/widget-salary-filters.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        KP
 * @package    JobBoard/Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (empty($list_salary)) {
    return;
}
?>

<div class="widget-content checkbox-style">
    <ul>

        <?php
        foreach ($list_salary as $k => $val):
            $checked = in_array($k, $values) ? 'checked="checked"' : '';
            ?>
            <li>
                <input id="salary-filter-<?php echo esc_attr($k); ?>" type="checkbox" name="salary-filters[]" value="<?php echo esc_attr($k); ?>" <?php echo $checked; ?>/>
                <label for="salary-filter-<?php echo esc_attr($k); ?>"><?php echo esc_html($val); ?></label>
            </li>

        <?php endforeach; ?>

    </ul>
</div>
