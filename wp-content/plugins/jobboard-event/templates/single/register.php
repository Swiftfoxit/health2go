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

<div class="register-event">
    <button id="register-event"
            data-event="<?php echo get_the_ID() ?>"><?php esc_html_e('Register Event', JB_EVENT_TEXT_DOMAIN); ?></button>
</div>
