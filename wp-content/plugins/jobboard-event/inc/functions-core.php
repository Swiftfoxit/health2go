<?php

function is_jb_event() {
	return is_singular( array( 'jb-events' ) );
}

function je_single_event_get_type() {
	global $post;

	$term = wp_get_post_terms( $post->ID, 'jobboard-event-type' );
	if ( ! is_wp_error( $term ) ) {
		return $term[0];
	}

	return false;
}