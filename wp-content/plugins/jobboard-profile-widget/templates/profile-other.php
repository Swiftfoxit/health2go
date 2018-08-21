<?php
/**
 * The Template for displaying nav for other user.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/profile/profile-other.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Profile/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<ul class="navigations">
    <li class="nav-profile">
        <a href="<?php echo esc_url(get_edit_user_link()); ?>" class="title"><?php esc_html_e('Manage Profile', JB_PROFILE_WIDGET_TEXT_DOMAIN); ?></a>
    </li>
    <?php if(is_super_admin()): ?>
    <li class="nav-add"><a href="<?php echo esc_url(admin_url('post-new.php?post_type=jobboard-post-jobs')); ?>" class="title"><?php esc_html_e('Add New', JB_PROFILE_WIDGET_TEXT_DOMAIN); ?></a></li>
    <li class="nav-jobs"><a href="<?php echo esc_url(admin_url('edit.php?post_type=jobboard-post-jobs')); ?>" class="title"><?php esc_html_e('Manager Jobs', JB_PROFILE_WIDGET_TEXT_DOMAIN); ?></a></li>
    <li class="nav-settings"><a href="<?php echo esc_url(admin_url('edit.php?post_type=jobboard-post-jobs&page=JobBoard')); ?>" class="title"><?php esc_html_e('JobBoard Settings', JB_PROFILE_WIDGET_TEXT_DOMAIN); ?></a></li>
    <?php endif; ?>
    <li class="nav-logout">
        <a href="<?php echo esc_url(wp_logout_url()); ?>" class="title"><?php esc_html_e('Logout', JB_PROFILE_WIDGET_TEXT_DOMAIN); ?></a>
    </li>
</ul>
