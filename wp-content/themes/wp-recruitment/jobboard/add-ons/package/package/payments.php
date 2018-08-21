<?php
/**
 * The Template display package payments.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/package/package/payments.php.
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

if( empty($payments)){
    return;
}
?>

<div class="package-payments">
    <p><?php esc_html_e('Payment Option:', 'wp-recruitment'); ?></p>
    <div>
        <ul class="payments">
        <?php foreach ($payments as $payment => $value): ?>

            <li id="payment-<?php echo esc_attr($payment); ?>" data-id="<?php echo esc_attr($payment); ?>" class="payment payment-<?php echo esc_attr($payment); ?>">
                <div class="payment-name">
                    <strong><?php echo esc_html($value['name']); ?></strong>

                    <?php if(!empty($value['desc'])): ?>

                    <p><?php echo wp_kses_post($value['desc']) ?></p>

                    <?php endif; ?>

                </div>

                <?php if(!empty($value['icon'])): ?>

                <div class="patment-icon">
                    <img src="<?php echo esc_url($value['icon']); ?>" alt="<?php echo esc_html($value['name']); ?>">
                </div>

                <?php endif; ?>

            </li>
        <?php endforeach; ?>
        </ul>
        <div class="actions">

            <?php
            /**
             * @hook
             */
            do_action('jobboard_package_payment_actions');
            ?>

            <input type="submit" name="package-checkout" class="button btn btn-xlg submit-button" value="<?php esc_html_e('Process Payment', 'wp-recruitment') ?>">
        </div>
    </div>
    <?php wp_nonce_field( 'package_checkout' ); ?>
    <input type="hidden" id="package_id" name="package_id" value="">
    <input type="hidden" id="package_name" name="package_name" value="">
    <input type="hidden" id="package_price" name="package_price" value="">
    <input type="hidden" id="currency" name="currency" value="<?php echo esc_attr(jb_get_currency_symbol()); ?>">
    <input type="hidden" id="payment" name="payment" value="<?php echo esc_attr(array_keys($payments)[0]); ?>">
    <input type="hidden" name="action" value="package_checkout">
    <input type="hidden" name="form" value="jobboard-form">
</div>
