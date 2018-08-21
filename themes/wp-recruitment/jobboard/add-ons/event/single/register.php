<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/28/2017
 * Time: 8:57 AM
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<div class="register-event loop-readmore col-lg-3 col-md-4 col-sm-12 col-xs-12 text-right text-left-sm text-left-xs">
    <button id="register-event" data-event="<?php echo get_the_ID() ?>"><?php esc_html_e('Register Event', 'wp-recruitment'); ?></button>
    <div class="event-social-share">
    	<?php recruitment_get_socials_share_job(); ?>
    </div>
</div>
