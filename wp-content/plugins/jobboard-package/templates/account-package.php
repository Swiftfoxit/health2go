<?php
/**
 * The Template for displaying current package.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/account-package.php.
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

<div class="content-package">
    <div class="package-heading heading">
        <h3 class="title"><?php esc_html_e('Your Package', 'jobboard'); ?></h3>
        <span class="info"><?php echo sprintf(esc_html__('You are own a %s package, You can also update up to other packages.', 'jobboard'), '<b>'. esc_html($name) .'</b>'); ?></span>
        <a class="view" href="<?php echo esc_url(jb_page_endpoint_url('package')); ?>"><?php esc_html_e('Update package', 'jobboard'); ?></a>
    </div>
</div>
