<?php
/**
 * The Template for displaying job applied.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/dashboard/candidate/applied.php.
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
?>

<div class="col-xs-12">

    <?php if ( have_posts() ) : ?>

        <table class="jb-applied">
            <tbody>

            <tr>

            <?php foreach ($columns as $key => $column): ?>

                <th id="jb-applied-col-<?php echo esc_attr($key); ?>"><?php echo esc_html($column); ?></th>

            <?php endforeach; ?>

            </tr>

            <?php while ( have_posts() ) : the_post(); ?>

            <tr>

                <?php foreach ($columns as $key => $v): $column = ''; ?>

                    <td>

                    <?php switch ($key){
                        case 'title':
                            $column  = '<h3 class="jb-applied-title"><a href="'.esc_url(get_permalink()).'">'.get_the_title().'</a></h3>';
                            $column .= '<span class="jb-applied-location">'.jb_job_location_html().'</span>';
                            break;
                        case 'detail':
                            $column .= '<span class="jb-applied-type">'.jb_job_get_type().'</span>';
                            break;
                        case 'date':
                            $column  = '<span class="jb-applied-date">'.esc_html(JB()->candidate->get_applied_date()).'</span>';
                            break;
                    }

                    echo wp_kses_post(apply_filters('wpl_jobboard_candidate_applied_column', $column, $key));

                    ?>

                    </td>

                <?php endforeach; ?>

            </tr>

            <?php endwhile; // end of the loop. ?>

            </tbody>
        </table>

    <?php else: ?>

    <?php endif; ?>

</div>
