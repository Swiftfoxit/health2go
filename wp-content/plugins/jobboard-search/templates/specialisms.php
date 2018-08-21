<?php
/**
 * The Template for displaying field specialisms.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/search/specialisms.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Search/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<select class="search-specialisms select" name="specialism">
    <option value=""><?php esc_html_e('All Specialism', JB_SEARCH_TEXT_DOMAIN); ?></option>

    <?php if(!empty($specialisms)): foreach ($specialisms as $id => $specialism): ?>

        <option value="<?php echo esc_attr($id); ?>"<?php selected($value, $id); ?>><?php echo esc_html($specialism); ?></option>

    <?php endforeach; endif; ?>

</select>
