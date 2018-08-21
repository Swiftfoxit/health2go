<?php
/**
 * Package Template
 *
 * Functions for the templating system.
 *
 * @author   FOX
 * @category Core
 * @package  JobBoard/Package/Functions
 * @version  1.0.0
 */

function jb_template_package_header()
{
    jb_package()->get_template('package/header.php', array('contact' => get_permalink(jb_get_option('payment-contact', 0))));
}

function jb_template_package_pricing_table()
{
    global $wp_query;

    $wp_query = jb_package()->get_package();

    jb_package()->get_template('pricing.php');

    wp_reset_query();
}

function jb_template_package_pricing_table_header()
{
    jb_package()->get_template('pricing/header.php');
}

function jb_template_package_pricing_table_feature()
{
    $features = is_jb_employer() ? jb_package_get_employer_rules() : jb_package_get_candidate_rules();
    jb_package()->get_template('pricing/feature.php', array('features' => $features));
}

function jb_template_package_pricing_table_footer()
{
    jb_package()->get_template('pricing/footer.php');
}

function jb_template_package_payment()
{
    jb_package()->get_template('package/payments.php', array('payments' => jb_package_get_payments()));
}

function jb_template_package_add_action()
{
    $package = jb_package()->package;
    $limits = get_post_meta($package->ID, '_features', true);
    $current = jb_package_count_jobs_feature();
    $attributes = '';

    if (isset($_REQUEST['post_id']) && is_jb_featured(sanitize_text_field($_REQUEST['post_id']))) {
        $attributes .= ' checked';
    } elseif (isset($_POST['_featured'])) {
        $attributes .= ' checked';
    }

    if ($current > $limits) {
        $attributes .= ' disabled';
    }

    jb_package()->get_template('feature.php', array('current' => $current, 'limits' => $limits, 'attributes' => $attributes));
}

function jb_template_package_current_package()
{
    $package = jb_package_get_current_package();
    if ($package) {
        jb_package()->get_template('account-package.php', array('name' => $package->post_title));
    } else {
        jb_package()->get_template('account-none.php');
    }
}

function jb_template_package_transactions()
{
    global $wp_query;

    $columns = apply_filters('jobboard_table_transactions_columns', array(
        'order'  => esc_html__('Your Orders', JB_PACKAGE_TEXT_DOMAIN),
        'total'  => esc_html__('Total', JB_PACKAGE_TEXT_DOMAIN),
        'date'   => esc_html__('Date', JB_PACKAGE_TEXT_DOMAIN),
        'status' => esc_html__('Status', JB_PACKAGE_TEXT_DOMAIN),
    ));

    $query = array(
        'post_type'      => 'jb-orders',
        'post_status'    => array('processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed', 'pending'),
        'posts_per_page' => 20,
        'author'         => get_current_user_id()
    );

    $wp_query = new WP_Query($query);

    jb_get_template('dashboard/global/table.php', array('jobs' => $wp_query, 'table' => 'transactions', 'columns' => $columns));
}

function jb_template_package_transactions_date()
{
    global $post;

    $date_format = get_option('date_format');
    $time_format = get_option('time_format');

    $attr = array(
        'date'  => mysql2date($date_format . ' ' . $time_format, $post->post_date),
        'title' => mysql2date($date_format, $post->post_date)
    );

    jb_get_template('dashboard/loop/date.php', $attr);
}

function jb_template_package_transactions_title()
{
    global $post;

    $order = $post->post_title;

    jb_package()->get_template('loop/order.php', array('order' => $order));
}

function jb_template_package_transactions_package()
{
    global $post;

    $package = get_post_meta($post->ID, '_package_id', true);

    jb_package()->get_template('loop/package.php', array('package' => get_the_title($package)));
}

function jb_template_package_transactions_price()
{
    global $post;

    $price = get_post_meta($post->ID, '_price', true);

    jb_package()->get_template('loop/price.php', array('price' => $price));
}

function jb_template_package_transactions_via()
{
    global $post;

    $payment = get_post_meta($post->ID, '_payment', true);
    $payment_args = jb_package_get_payment($payment);

    jb_package()->get_template('loop/via.php', array('payment' => $payment_args['name']));
}

function jb_template_package_transactions_status()
{
    global $post;

    $title = $post->post_status;

    jb_get_template('dashboard/loop/status.php', array('status' => $post->post_status, 'title' => $title));
}

function jb_template_package_transactions_pagination()
{

    $base = jb_page_endpoint_base_pagination('transactions', jb_page_permalink('dashboard'));

    jb_get_template('global/pagination.php', array('base' => $base));

    wp_reset_query();
}

function jb_after_payment_bank_transfer()
{
    ?>
    <div id="bank-transfer" class="modal" tabindex="-1" role="dialog"
         aria-labelledby="bank-transfer"
         aria-hidden="true" style="display: none; padding-right: 17px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="bank-transfer-label"><?php esc_html_e('Direct Bank Transfer', JB_PACKAGE_TEXT_DOMAIN); ?></h4>
                </div>
                <div class="modal-body" style="overflow: hidden;">
                    <p>
                        <?php echo jb_get_option('bank-tranfer-description'); ?>
                    </p>
                    <div class="clearfix">
                        <h4><?php esc_html_e('Bank Accounts', JB_PACKAGE_TEXT_DOMAIN); ?></h4>
                        <?php
                        $bank_accounts = jb_get_option('bank_account', array());
                        foreach ($bank_accounts as $bank_account) {
                            ?>
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <label><?php esc_html_e('Account Name', JB_PACKAGE_TEXT_DOMAIN); ?></label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <?php echo $bank_account['account_name']; ?>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <label><?php esc_html_e('Account number', JB_PACKAGE_TEXT_DOMAIN); ?></label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <?php echo $bank_account['account_number']; ?>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <label><?php esc_html_e('Bank Name', JB_PACKAGE_TEXT_DOMAIN); ?></label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <?php echo $bank_account['bank_name']; ?>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <label><?php esc_html_e('IBAN', JB_PACKAGE_TEXT_DOMAIN); ?></label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <?php echo $bank_account['iban']; ?>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <label><?php esc_html_e('BIC / Swift', JB_PACKAGE_TEXT_DOMAIN); ?></label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <?php echo $bank_account['bic_swift']; ?>
                                </div>
                            </div>
                            <br><br>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button btn btn-xlg close" data-dismiss="modal" style="background: #4e007a"
                            aria-label="Close">
                        <span aria-hidden="true">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}