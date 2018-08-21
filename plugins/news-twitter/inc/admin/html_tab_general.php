<?php
/**
 * Generate fields in General tab.
 *
 * @author 		Jax Porter
 * @version     1.0.0
 */

/* clear cache. */
$screen_name = get_option('newstwitter_screen_name');
$screen_name ? delete_transient($screen_name) : delete_transient('realjoomlaman') ;
?>
<h3><?php _e('Twitter API Settings', 'news-twitter'); ?></h3>
<p><?php _e('The general settings of twitter plugin', 'news-twitter'); ?></p>
<p><a href="https://apps.twitter.com/" target="_blank"><?php esc_html_e('Get Your API Key', 'news-twitter'); ?></a></p>
<table class="form-table">
    <tbody>
    <?php $this->option_text(array(
    		'id'=>'newstwitter_consumer_key',
    		'title' => 'Consumer Key',
    		'default' => '',
    		'placeholder' => 'qBQQF5Cmantse0ptg413Mw'
    ));
    $this->option_text(array(
    		'id'=>'newstwitter_consumer_secret',
    		'title' => 'Consumer Secret',
    		'default' => '',
    		'placeholder' => 'BS9qgCk4BD7bvDWIwLCoD0FYoDiQkX7VPDBz1pBN9IA'
    ));
    $this->option_text(array(
    		'id'=>'newstwitter_access_token',
    		'title' => 'Access Token',
    		'default' => '',
    		'placeholder' => '123293200-Y2CxwzKN5SgjfnnAplH6nD4ETtUNluJdvJ3YD6Xy'
    ));
    $this->option_text(array(
    		'id'=>'newstwitter_access_token_secret',
    		'title' => 'Access Token Secret',
    		'default' => '',
    		'placeholder' => 'EgVTFys3agS8HKJ5RyYggNVFDo0XR6wbfNAnG9LX3igyb'
    ));
    $this->option_text(array(
    		'id'=>'newstwitter_screen_name',
    		'title' => 'Screen Name',
    		'default' => '',
    		'placeholder' => 'realjoomlaman'
    ));
	$this->option_text(array(
		'id'=>'newstwitter_items_syn',
		'title' => 'Limit Items',
		'default' => '5',
		'placeholder' => '5'
	));
    $this->option_text(array(
    		'id'=>'newstwitter_cache_time',
    		'title' => 'Cache Time (minutes)',
    		'default' => '',
    		'placeholder' => '10'
    ));?>
    </tbody>
</table>