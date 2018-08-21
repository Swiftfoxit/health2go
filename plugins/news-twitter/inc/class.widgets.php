<?php
class ZNews_Twitter_Widget extends WP_Widget {
	
	function __construct() {
		parent::__construct ( 'znews-twitter-widget', esc_html__ ( 'News Twitter', 'news-twitter' ), array (), 'znews-twitter-widget' );
	}
	
	function widget($args, $instance) {
		
		echo $args['before_widget'];
		
		/* widget title. */
		if($instance['title']){
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		$shortcode_attr = '';

		foreach ($instance as $key => $val){
			$shortcode_attr .= ' ' . $key . '="' . $val . '"';
		}

		echo do_shortcode("[z-news-twitter{$shortcode_attr}]");
		
		echo $args['after_widget'];
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['mode'] = strip_tags($new_instance['mode']);
		$instance['row'] = strip_tags($new_instance['row']);
		$instance['speed'] = strip_tags($new_instance['speed']);
		$instance['auto'] = strip_tags($new_instance['auto']);
		$instance['ticker'] = strip_tags($new_instance['ticker']);
		$instance['minslides'] = strip_tags($new_instance['minslides']);
		$instance['maxslides'] = strip_tags($new_instance['maxslides']);
		$instance['slidewidth'] = strip_tags($new_instance['slidewidth']);
		$instance['controls'] = strip_tags($new_instance['controls']);
		$instance['pager'] = strip_tags($new_instance['pager']);
		$instance['layout'] = strip_tags($new_instance['layout']);

		return $instance;
	}
	
	function form($instance)
	{
		
		$instance = array_merge( array( 
				'title' => esc_html__('News Twitter', 'news-twitter'),
				'mode' => 'horizontal',
				'row' => 1,
				'speed' => 5000,
				'auto' => 1,
				'ticker' => 0,
				'minslides' => 1,
				'maxslides' => 1,
				'slidewidth' => 0,
				'controls' => 0,
				'pager' => 0,
				'layout' => '',
		), (array) $instance );

		$_template = array_merge(array(esc_html__("Default", "news-twitter") => '',), znews_twitter()->get_layouts());

		?>
		<p>
			<a href="options-general.php?page=news_twitter_admin"><?php esc_html_e('Setting API Key Here', 'news-twitter'); ?></a>
		</p>
		<p>
        	<label for=""><?php _e('Title:', 'news-twitter'); ?></label> <input
        		id="<?php echo esc_attr($this->get_field_id('title')); ?>"
        		name="<?php echo esc_attr( $this->get_field_name('title') ); ?>"
        		class="widefat" type="text"
        		value="<?php echo isset($instance['title']) ? $instance['title'] : ''; ?>"
        		placeholder="<?php _e('Twitter:', 'news-twitter'); ?>">
        </p>
        <p>
        	<label for=""><?php _e('Min Slides:', 'news-twitter'); ?></label> <input
        		id="<?php echo esc_attr($this->get_field_id('minslides')); ?>"
        		name="<?php echo esc_attr( $this->get_field_name('minslides') ); ?>"
        		class="widefat" type="number" min="1"
        		value="<?php echo isset($instance['minslides']) ? $instance['minslides'] : ''; ?>"
        		placeholder="1">
        </p>
        <p>
        	<label for=""><?php _e('Max Slides:', 'news-twitter'); ?></label> <input
        		id="<?php echo esc_attr($this->get_field_id('maxslides')); ?>"
        		name="<?php echo esc_attr( $this->get_field_name('maxslides') ); ?>"
        		class="widefat" type="number" min="1"
        		value="<?php echo isset($instance['maxslides']) ? $instance['maxslides'] : ''; ?>"
        		placeholder="1">
        </p>
        <p>
        	<label for=""><?php _e('Slide Width:', 'news-twitter'); ?></label> <input
        		id="<?php echo esc_attr($this->get_field_id('slidewidth')); ?>"
        		name="<?php echo esc_attr( $this->get_field_name('slidewidth') ); ?>"
        		class="widefat" type="number" min="1"
        		value="<?php echo isset($instance['slidewidth']) ? $instance['slidewidth'] : ''; ?>"
        		placeholder="0">
        </p>
		<p>
			<label for=""><?php _e('Row:', 'news-twitter'); ?></label> <input
				id="<?php echo esc_attr($this->get_field_id('row')); ?>"
				name="<?php echo esc_attr( $this->get_field_name('row') ); ?>"
				class="widefat" type="number" min="1"
				value="<?php echo isset($instance['row']) ? $instance['row'] : '1'; ?>"
				placeholder="1">
		</p>
        <p>
        	<label for=""><?php _e('Speed:', 'news-twitter'); ?></label> <input
        		id="<?php echo esc_attr($this->get_field_id('speed')); ?>"
        		name="<?php echo esc_attr( $this->get_field_name('speed') ); ?>"
        		class="widefat" type="number" min="0"
        		value="<?php echo isset($instance['speed']) ? $instance['speed'] : ''; ?>"
        		placeholder="5000">
        </p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('layout')); ?>"><?php _e('Layout : ', 'news-twitter'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr( $this->get_field_name('layout') ); ?>">
				<?php foreach ($_template as $key => $layout): ?>
					<option value="<?php echo esc_attr__($layout); ?>"<?php if($instance['layout'] == $layout) { echo ' selected="selected"';} ?>><?php echo esc_html__($key); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
        <p>
        	<label style="text-decoration: underline;"><?php _e('Slider Mode:', 'news-twitter'); ?></label><br>
        	<label for=""><?php _e('Horizontal:', 'news-twitter'); ?></label> <input
        		name="<?php echo esc_attr( $this->get_field_name('mode') ); ?>"
        		class="widefat" type="radio"
        		value="horizontal"<?php if($instance['mode'] == 'horizontal') { echo 'checked=" checked"'; } ?>>
        	<label for=""><?php _e('Vertical:', 'news-twitter'); ?></label>
        		<input
        		name="<?php echo esc_attr( $this->get_field_name('mode') ); ?>"
        		class="widefat" type="radio"
        		value="vertical"<?php if($instance['mode'] == 'vertical') { echo 'checked=" checked"'; } ?>>
			<label for=""><?php _e('Fade:', 'news-twitter'); ?></label>
			<input
				name="<?php echo esc_attr( $this->get_field_name('mode') ); ?>"
				class="widefat" type="radio"
				value="fade"<?php if($instance['mode'] == 'fade') { echo 'checked=" checked"'; } ?>>
        </p>
        <p>
        	<label style="text-decoration: underline;"><?php _e('Auto Slider:', 'news-twitter'); ?></label><br>
        	<label for=""><?php _e('No:', 'news-twitter'); ?></label> <input
        		name="<?php echo esc_attr( $this->get_field_name('auto') ); ?>"
        		class="widefat" type="radio"
        		value="0"<?php if($instance['auto'] == 0) { echo 'checked=" checked"'; } ?>>
        	<label for=""><?php _e('Yes:', 'news-twitter'); ?></label>
        		<input
        		name="<?php echo esc_attr( $this->get_field_name('auto') ); ?>"
        		class="widefat" type="radio"
        		value="1"<?php if($instance['auto'] == 1) { echo 'checked=" checked"'; } ?>>
        </p>
        <p>
        	<label style="text-decoration: underline;"><?php _e('Ticker Mode:', 'news-twitter'); ?></label><br>
        	<label for=""><?php _e('No:', 'news-twitter'); ?></label> <input
        		name="<?php echo esc_attr( $this->get_field_name('ticker') ); ?>"
        		class="widefat" type="radio"
        		value="0"<?php if($instance['ticker'] == 0) { echo 'checked=" checked"'; } ?>>
        	<label for=""><?php _e('Yes:', 'news-twitter'); ?></label>
        		<input
        		name="<?php echo esc_attr( $this->get_field_name('ticker') ); ?>"
        		class="widefat" type="radio"
        		value="1"<?php if($instance['ticker'] == 1) { echo 'checked=" checked"'; } ?>>
        </p>
        <p>
        	<label style="text-decoration: underline;"><?php _e('Controls:', 'news-twitter'); ?></label><br>
        	<label for=""><?php _e('Hide:', 'news-twitter'); ?></label> <input
        		name="<?php echo esc_attr( $this->get_field_name('controls') ); ?>"
        		class="widefat" type="radio"
        		value="0"<?php if($instance['controls'] == 0) { echo 'checked=" checked"'; } ?>>
        	<label for=""><?php _e('Show:', 'news-twitter'); ?></label>
        		<input
        		name="<?php echo esc_attr( $this->get_field_name('controls') ); ?>"
        		class="widefat" type="radio"
        		value="1"<?php if($instance['controls'] == 1) { echo 'checked=" checked"'; } ?>>
        </p>
        <p>
        	<label style="text-decoration: underline;"><?php _e('Pager:', 'news-twitter'); ?></label><br>
        	<label for=""><?php _e('Hide:', 'news-twitter'); ?></label> <input
        		name="<?php echo esc_attr( $this->get_field_name('pager') ); ?>"
        		class="widefat" type="radio"
        		value="0"<?php if($instance['pager'] == 0) { echo 'checked=" checked"'; } ?>>
        	<label for=""><?php _e('Show:', 'news-twitter'); ?></label>
        		<input
        		name="<?php echo esc_attr( $this->get_field_name('pager') ); ?>"
        		class="widefat" type="radio"
        		value="1"<?php if($instance['pager'] == 1) { echo 'checked=" checked"'; } ?>>
        </p>
		<?php
	}
}