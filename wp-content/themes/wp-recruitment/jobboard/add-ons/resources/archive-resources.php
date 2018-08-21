<?php
/**
 * @Template: archive-resources.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 13-Dec-17
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $opt_theme_options;
?>
<?php get_header('jobboard'); ?>
    <div class="jobboard-archive-loop jobboard-resources-wrap">
        <div class="container">
            <?php if(!empty($opt_theme_options['resources_title'])) : ?>
                <div class="jobboard-resources-heading">
                    <h3><?php echo esc_attr( $opt_theme_options['resources_title'] ); ?></h3>
                    <p><?php echo esc_html( $opt_theme_options['resources_description'] ); ?></p>
                </div>
            <?php endif; ?>
            <div class="jobboard-resources-primary row">
                <?php if (have_posts()) : ?>

                    <?php do_action('jobboard_resources_loop_before'); ?>
                    
                        <?php while (have_posts()) : the_post(); ?>
                    <?php
                        $file_id = get_post_meta(get_the_ID(), '_resources_file', true);
                        $file_size = filesize( get_attached_file( $file_id ));
                        $file_size_layout = '';
                        if($file_size >= 0 && $file_size < 1024){
                            $file_size_layout = $file_size.'B';
                        }
                        if($file_size >= 1024 && $file_size < 1048576){
                            $file_size_layout = number_format($file_size/1024,2).'KB';
                        }

                        if($file_size >= 1073741824){
                            $file_size_layout = number_format($file_size/1024,2).'MB';
                        }
                        $array_file = explode('.', get_attached_file($file_id));
                        $extension = end($array_file);

                        ?>
                            <div class="jr-item col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="jr-item-inner">
                                    <div class="jr-holder">
                                        <h3 class="jr-title"><i class="zmdi zmdi-file-text"></i><?php echo get_the_title() ?></h3>
                                        <span class="jr-file-size">.<?php echo esc_attr($extension)?> (<?php echo esc_attr($file_size_layout) ?>)</span>
                                        <div class="jr-content"><?php echo get_the_excerpt() ?></div>
                                    </div>
                                    <form class="jr-action">
                                        <input type="text" placeholder="<?php echo esc_html__('Email Address', 'wp-recruitment') ?>">
                                        <button class="jr-button-download" data-id="<?php echo get_the_ID() ?>"><?php echo esc_html__('Free Download', 'wp-recruitment')?></button>
                                        <?php wp_nonce_field('jr_download', '_wp_nonce_jr_download'); ?>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        
                    <?php do_action('jobboard_resources_loop_after'); ?>

                <?php else: ?>

                    <?php jb_get_template_part('loop/not-found'); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
<?php get_footer('jobboard'); ?>