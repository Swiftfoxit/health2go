<?php
/**
 * The Template display current package.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/current.php.
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

if(empty($package)){
    return;
}
?>

<div class="jobboard-package package-post">
    <div class="package-heading dashboard-heading">
        <h3><?php esc_html_e('Your Package', 'wp-recruitment'); ?></h3>
        <span><?php esc_html_e("Tokens are required to post jobs. Select the package that's right for you and choose a payment method below.", 'wp-recruitment'); ?></span>
        <p><span><?php echo sprintf(esc_html__("For billing information %sContact Us%s", 'wp-recruitment'), '<a href="'.esc_url($package->contact).'">', '</a>'); ?></span></p>
    </div>
    <div class="package-progress">
        <div class="progress">
            <div class="progress-bar <?php echo esc_attr($package->class); ?>" role="progressbar" aria-valuenow="<?php echo esc_attr($package->process);?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo esc_attr($package->process);?>%">
                <span><?php echo sprintf(esc_html__('%s%d%s/ %s%d%s Tokens Remaining', 'wp-recruitment'),'', $package->jobs, '', '', $package->limit, ''); ?></span>
            </div>
        </div>
        <a href="<?php echo esc_url($package->add_more); ?>" class="button"><?php esc_html_e('Add More', 'wp-recruitment') ?></a>
    </div>
</div>
