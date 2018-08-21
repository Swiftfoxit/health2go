<?php
/**
 * The Template for displaying social login buttons.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/social-login/content-social.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Social/Login/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="gap"><span><?php echo esc_html__('or', 'wp-recruitment'); ?></span></div>
<div class="jobboard-social-login clearfix">
    <?php do_action('jobboard_social_login_content'); ?>
</div>
