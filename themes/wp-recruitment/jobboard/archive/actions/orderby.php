<?php
/**
 * Loop job order
 *
 * This template can be overridden by copying it to yourtheme/jobboard/loop/order/orderby.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        FOX
 * @package    JobBoard/Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="archive-orderby">
    <span class="jb-sort"><i class="zmdi zmdi-swap-vertical"></i><?php echo esc_html__('Sort', 'wp-recruitment') ?><i
                class="zmdi zmdi-chevron-down"></i></span>
    <ul class="jb-orderby-list" name="orderby">
        <?php foreach ($catalog_orderby_options as $id => $name) : ?>
            <li><input id="jb-orderby-<?php echo esc_attr($id); ?>" type="radio" class="jb-orderby" name="orderby"
                       value="<?php echo esc_attr($id); ?>" <?php checked($orderby, $id); ?>><label
                        for="jb-orderby-<?php echo esc_attr($id); ?>"><i
                            class="zmdi zmdi-check"></i><?php echo esc_html($name); ?></label></li>
        <?php endforeach; ?>
    </ul>
    <?php if (!empty($_REQUEST['employer_id'])) {
        ?>
        <input type="hidden" name="employer_id" value="<?php echo esc_attr($_REQUEST['employer_id']) ?>">
        <?php
    } ?>
</div>

