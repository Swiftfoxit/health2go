<?php
/**
 * The Template for displaying field locations.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/search/locations.php.
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

<select class="search-locations select" name="location">
    <option value=""><?php esc_html_e('All Locations', JB_SEARCH_TEXT_DOMAIN); ?></option>

    <?php if(!empty($locations)): foreach ($locations as $id => $location): ?>

        <option value="<?php echo esc_attr($id); ?>"<?php selected($value, $id); ?>><?php echo esc_html($location); ?></option>

    <?php endforeach; endif; ?>

</select>