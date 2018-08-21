<div class="locations-listing">
	<?php if ( isset( $title ) ): ?>
        <h1><?php echo $title; ?></h1>
	<?php endif; ?>
	<?php if ( isset( $description ) ): ?>
        <p class="description">Search for jobs around a specific area, local to you or across the globe.</p>
	<?php endif; ?>
    <div class="listing">
		<?php if ( isset( $locations ) ): ?>
			<?php foreach ( $locations as $key => $location ): ?>
                <?php echo $key; ?>
                <div class="item">
                    <div class="thumbnail">
                        <img src="<?php echo jb_get_location_thum( $key ); ?>" alt="<?php echo $location; ?>">
                    </div>
                    <div class="meta">
                        <a class=""
                           href="<?php echo get_term_link( $key, 'jobboard-tax-locations' ); ?>"><?php esc_html_e( 'View Jobs', 'jobboard-shortcodes' ); ?></a>
                        <h2 class="city"><?php echo $location; ?></h2>
                    </div>
                </div>
			<?php endforeach; ?>
		<?php endif; ?>
    </div>
	<?php if ( isset( $view ) ): ?>
        <div class="view-all">
            <a href="<?php echo $view; ?>"><?php esc_html_e( 'View all Locations', 'jobboard-shortcodes' ); ?></a>
        </div>
	<?php endif; ?>
</div>