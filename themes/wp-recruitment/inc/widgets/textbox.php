<?php
class CMS_TextBox_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'cms_textbox_widget',
            esc_html__('CMS Text Box', "wp-recruitment"),
            array('description' => esc_html__('Social Widget', "wp-recruitment"),)
        );
    }

    function widget($args, $instance) {
        global $woocommerce;

        extract($args);

        $title = isset($instance['title']) ? (!empty($instance['title']) ? $instance['title']: '') : '';
        $content = isset($instance['content']) ? (!empty($instance['content']) ? $instance['content']: '') : '';
        $button_text = isset($instance['button_text']) ? (!empty($instance['button_text']) ? $instance['button_text']: '') : '';
        $button_link = isset($instance['button_link']) ? (!empty($instance['button_link']) ? $instance['button_link']: '') : '';
        $box_color = isset($instance['box_color']) ? (!empty($instance['box_color']) ? $instance['box_color']: '') : ''; ?>

        <?php if(!empty($title)) : ?>
            <div class="cms-job-cv widget <?php echo esc_attr($box_color); ?>">
                <h3><?php echo esc_attr( $title ); ?></h3>
                <div class="cms-job-cv-content"><?php echo esc_attr( $content ); ?></div>
                <a class="btn" href="<?php echo esc_url($button_link); ?>"><?php echo esc_attr( $button_text ); ?></a>
            </div>
    <?php endif; }

    function update( $new_instance, $old_instance ) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['content'] = strip_tags($new_instance['content']);
         $instance['button_text'] = strip_tags($new_instance['button_text']);
         $instance['button_link'] = strip_tags($new_instance['button_link']);
         $instance['box_color'] = strip_tags($new_instance['box_color']);

         return $instance;
    }

    function form( $instance ) {
         $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
         $content = isset($instance['content']) ? esc_attr($instance['content']) : '';
         $button_text = isset($instance['button_text']) ? esc_attr($instance['button_text']) : '';
         $button_link = isset($instance['button_link']) ? esc_attr($instance['button_link']) : '';
         $box_color = isset($instance['box_color']) ? esc_attr($instance['box_color']) : '';

         ?>
         <p><label for="<?php echo esc_url($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title', "wp-recruitment" ); ?></label>
         <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

         <p><label for="<?php echo esc_url($this->get_field_id('content')); ?>"><?php esc_html_e( 'Content', "wp-recruitment" ); ?></label>
         <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('content') ); ?>" name="<?php echo esc_attr( $this->get_field_name('content') ); ?>" type="text" value="<?php echo esc_attr( $content ); ?>" /></p>

         <p><label for="<?php echo esc_url($this->get_field_id('Button Text')); ?>"><?php esc_html_e( 'Button Text', "wp-recruitment" ); ?></label>
         <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('button_text') ); ?>" name="<?php echo esc_attr( $this->get_field_name('button_text') ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" /></p>

         <p><label for="<?php echo esc_url($this->get_field_id('Button Link')); ?>"><?php esc_html_e( 'Button Link', "wp-recruitment" ); ?></label>
         <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('button_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('button_link') ); ?>" type="text" value="<?php echo esc_attr( $button_link ); ?>" /></p>
         
         <p><label for="<?php echo esc_url($this->get_field_id('box_color')); ?>"><?php esc_html_e( 'Box Color', "wp-recruitment" ); ?></label>
         <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('box_color') ); ?>" name="<?php echo esc_attr( $this->get_field_name('box_color') ); ?>">
            <option value="color1"<?php if( $box_color == 'color1' ){ echo 'selected="selected"';} ?>><?php esc_html_e('Color 1', "wp-recruitment"); ?></option>
            <option value="color2"<?php if( $box_color == 'color2' ){ echo 'selected="selected"';} ?>><?php esc_html_e('Color 2', "wp-recruitment"); ?></option>
         </select>
         </p>
    <?php
    }

}

function register_textbox_widget() {
    register_widget('CMS_TextBox_Widget');
}
add_action('widgets_init', 'register_textbox_widget'); ?>