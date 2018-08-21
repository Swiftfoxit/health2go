<?php
/**
 * The Template display input feature.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/feature.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Package/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<label for="_featured" class="field-featured">
    <input id="_featured" type="checkbox" name="_featured" class="checkbox" value="1"<?php echo esc_attr($attributes); ?>>
    <?php echo sprintf(esc_html__('Featured Job? %d/%d Remaining', JB_PACKAGE_TEXT_DOMAIN), $current, $limits); ?>
</label>
