<?php
/**
 * The Template display packages.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/packages.php.
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

<form action="" method="POST" class="jobboard-form package-form">

    <?php
    /**
     * @hook
     */
    do_action('jobboard_package_content_before');
    ?>

    <?php
    /**
     * @hook
     */
    do_action('jobboard_package_content');
    ?>

    <?php
    /**
     * @hook
     */
    do_action('jobboard_package_content_after');
    ?>

</form>
