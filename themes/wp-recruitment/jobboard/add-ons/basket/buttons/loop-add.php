<?php
/**
 * The Template for displaying archive basket add button.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/basket/buttons/loop-add.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        FOX
 * @package    JobBoard/Basket/Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$require_login = jb_get_option('add-job-require-login',false);
?>

<button class="btn btn-default basket-add<?php echo (!is_user_logged_in() && $require_login === '1') ? ' md-trigger': '' ?>"
        data-id="<?php the_ID(); ?>"<?php echo (!is_user_logged_in() && $require_login === '1') ?  ' data-modal="jobboard-login-add-job"' : ''?>>
    <i class="fa fa-shopping-cart cart"></i>
    <i class="fa fa-spinner jobboard-loading" style="display: none"></i>
    <span class="add"><?php esc_attr_e('Add', 'wp-recruitment'); ?></span>
    <span class="added" style="display: none"><?php esc_attr_e('Added', 'wp-recruitment'); ?></span>
</button>

