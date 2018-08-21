<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 11/22/2017
 * Time: 11:31 AM
 */

?>
<div class="loop-duration">
	<?php if ( isset( $date ) ): ?>
        <div class="entry-date"><?php echo $date; ?></div>
	<?php endif; ?>
	<?php if ( isset( $time ) ): ?>
        <div class="entry-time"><?php echo $time; ?></div>
	<?php endif; ?>
</div>
