<?php
/**
 * The Template for displaying search form.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/search/search-form.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Search/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="jb-s-wrapper <?php echo esc_attr( $atts['layout'] ); ?>" style="background-color: <?php echo esc_attr( $atts['box_color'] )?>">
    <form class="jobboard-form search-form jb-s-form" method="get" action="<?php echo esc_url( home_url( '/'  ) ); ?>">

        <?php do_action('jobboard_search_form',$atts); ?>

    </form>
</div>
