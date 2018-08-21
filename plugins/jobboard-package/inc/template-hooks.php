<?php
/**
 * Package Template Hooks
 *
 * Action/filter hooks used for JobBoard functions/templates.
 *
 * @author        FOX
 * @category    Core
 * @package    JobBoard/Package/Templates
 * @version     1.0.0
 */
add_action('jobboard_package_content_after', 'jb_after_payment_bank_transfer');
add_action('jobboard_form_post_actions', 'jb_template_package_add_action');

add_action('jobboard_package_content_before', 'jb_template_package_header');
add_action('jobboard_package_content', 'jb_template_package_pricing_table', 10);
add_action('jobboard_package_content', 'jb_template_package_payment', 20);

add_action('jobboard_package_pricing_table', 'jb_template_package_pricing_table_header');
add_action('jobboard_package_pricing_table', 'jb_template_package_pricing_table_feature');
add_action('jobboard_package_pricing_table', 'jb_template_package_pricing_table_footer');

add_action('jobboard_endpoint_candidate_page', 'jb_template_package_current_package', 30);
add_action('jobboard_endpoint_candidate_transactions', 'jb_template_package_transactions', 10);
add_action('jobboard_endpoint_candidate_transactions', 'jb_template_package_transactions_pagination', 20);
add_action('jobboard_endpoint_employer_page', 'jb_template_package_current_package', 30);
add_action('jobboard_endpoint_employer_transactions', 'jb_template_package_transactions', 10);
add_action('jobboard_endpoint_employer_transactions', 'jb_template_package_transactions_pagination', 20);

add_action('jobboard_table_transactions_order', 'jb_template_package_transactions_title', 10);
add_action('jobboard_table_transactions_order', 'jb_template_package_transactions_package', 20);
add_action('jobboard_table_transactions_total', 'jb_template_package_transactions_price', 10);
add_action('jobboard_table_transactions_total', 'jb_template_package_transactions_via', 20);
add_action('jobboard_table_transactions_date', 'jb_template_package_transactions_date');
add_action('jobboard_table_transactions_status', 'jb_template_package_transactions_status');