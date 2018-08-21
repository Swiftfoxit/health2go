<?php
/**
 * The Template for displaying account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/dashboard/account.php.
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

$show_siderbar = true;
$endpoint = JB()->query->get_current_endpoint();
if(!is_active_sidebar('dashboard') || !is_user_logged_in() || in_array($endpoint, array('jobs', 'package', 'transactions','applied', 'basket', 'resumes-manager'))){
    $show_siderbar = false;
}

if(function_exists('jb_package') && jb_package()){
    if($endpoint == 'new' && jb_package()->is_extend()){
        $show_siderbar = false;
    }
}

if($show_siderbar){
    $class1 = 'col-xs-12 col-sm-12 col-md-3 col-lg-3';
    $class2 = 'col-xs-12 col-sm-12 col-md-6 col-lg-6';
} else {
    $class1 = 'col-xs-12 col-sm-4 col-md-3 col-lg-3';
    $class2 = 'col-xs-12 col-sm-8 col-md-9 col-lg-9';
}

?>

<div class="dashboard-navigations <?php echo esc_attr($class1); ?>">

    <?php do_action("jobboard_dashboard_".jb_account_type()."_navigation"); ?>

</div>
<div class="dashboard-content <?php echo esc_attr($class2); ?>">

    <?php do_action("jobboard_dashboard_".jb_account_type()."_content"); ?>

</div>

<?php if($show_siderbar): ?>
    <div class="dashboard-sidebar col-xs-12 col-sm-12 col-md-3 col-lg-3">

        <?php dynamic_sidebar('dashboard'); ?>

    </div>
<?php endif; ?>
