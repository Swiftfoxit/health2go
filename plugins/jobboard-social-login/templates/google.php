<?php
/**
 * The Template for displaying google login button.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/social-login/google.php.
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

<button type="button" class="social-google button" data-notice="<?php echo esc_attr($notice); ?>">
    <i class="fa fa-google"></i>
    <?php esc_html_e('Google', JB_SOCIAL_LOGIN_TEXT_DOMAIN); ?>
</button>
