<?php
/**
 * The Template display pricing table feature.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/pricing/feature.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        FOX
 * @package    JobBoard/Package/Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (empty($features)) {
    return;
}
?>

<ul class="pricing-feature">

    <?php foreach ($features as $rule => $value): ?>

        <?php if ($value != 0): ?>
            <li><?php echo jb_package_get_rules_text($rule, $value); ?></li>

        <?php endif; ?>

    <?php endforeach; ?>

</ul>
