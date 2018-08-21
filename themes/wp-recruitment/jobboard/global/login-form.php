<?php
/**
 * The Template for displaying login form.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/global/login-form.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<form id="<?php echo esc_attr($args['form_id']); ?>" class="jobboard-form jb-form jb-login-form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">

    <?php if(!class_exists('JB_Demo')): ?>
        <h3 class="hidden-xs"><?php echo esc_html__('Existing Users Login Below', 'wp-recruitment'); ?></h3>
    <?php endif; ?>

    <div class="hidden-xs login-logo"><?php recruitment_header_logo(); ?></div>

    <h4><?php echo esc_html__('Login to Apply', 'wp-recruitment'); ?></h4>
    
    <?php do_action('jobboard_form_login_before'); ?>

    <p class="login-username">
        
        <input type="text" name="log" id="<?php echo esc_attr($args['form_id']) . '-log'; ?>" class="input" value="" size="20" placeholder="Username or Email Address" />
    </p>

    <p class="login-password">
        
        <input type="password" name="pwd" id="<?php echo esc_attr($args['form_id']) . '-pwd'; ?>" class="input" value="" size="20" placeholder="Password" />
    </p>

    <div class="login-remember clearfix">
        <div class="remember">
            <input name="rememberme" type="checkbox" id="<?php echo esc_attr($args['form_id']) . '-rememberme'; ?>" value="forever"/>
            <span><?php echo esc_html($args['label_remember']); ?></span>
        </div>
        <a class="right" href="<?php echo esc_url(jb_page_permalink('forgot-password')); ?>"><?php esc_html_e('Forgot Password?', 'wp-recruitment'); ?></a>
    </div>

    <p class="login-submit clearfix">
        <input type="submit" name="wp-submit" id="<?php echo esc_attr($args['form_id']) . '-submit'; ?>" class="button button-primary" value="<?php echo esc_attr($args['label_log_in']); ?>" />
        <?php do_action('jobboard_form_login_after'); ?>
        <input type="hidden" name="redirect_to" value="<?php echo esc_url($args['redirect_to']); ?>" />
        <input type="hidden" name="dashboard" value="<?php echo esc_attr($args['dashboard']); ?>">
    </p>
</form>
