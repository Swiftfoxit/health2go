<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 11:31 AM
 */

?>
<div class="loop-duration col-lg-4 col-md-4 col-sm-12 col-xs-12">
	<?php if ( isset( $date ) ): ?>
        <div class="entry-date"><?php echo wp_kses_post($date); ?></div>
	<?php endif; ?>
	<?php if ( isset( $date_2 ) ): ?>
        <div class="entry-date"><?php echo wp_kses_post($date_2); ?></div>
	<?php endif; ?>
	<?php if ( isset( $time ) ): ?>
        <div class="entry-time"><?php echo wp_kses_post($time); ?></div>
	<?php endif; ?>
</div>
