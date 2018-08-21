<?php
/**
 * The Template for displaying user button download cv.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/user/cv.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        FOX
 * @package    JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="job-button-apply">
    <div class="summary-actions">
		<?php if ( function_exists( 'jb_package' ) ): ?>
			<?php
			$cv = get_post_meta( jb_package_get_current_package( get_current_user_id() )->ID, '_cvs', true );
			$cv = intval( $cv );
			?>
			<?php if ( $cv !== - 1 ): ?>
                <button type="button" class="button download-cv btn btn-default btn-xlg"
                        data-account="<?php echo jb_account_get_account_slug(); ?>"><i
                            class="fa fa-download"></i><?php esc_html_e( 'Download CV', JB_TEXT_DOMAIN ) ?></button>
			<?php endif; ?>
		<?php else: ?>
            <a href="<?php echo esc_url( $cv ); ?>" target="_blank" class="button download-cv btn btn-default btn-xlg"
               data-account="<?php echo jb_account_get_account_slug(); ?>"><i
                        class="fa fa-download"></i><?php esc_html_e( 'Download CV', JB_TEXT_DOMAIN ) ?></a>
		<?php endif; ?>
    </div>
</div>