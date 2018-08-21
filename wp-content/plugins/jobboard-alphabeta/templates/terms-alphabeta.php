<?php
/**
 * The Template for displaying terms listing.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/alphabeta/terms-alphabeta.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Alphabeta/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(is_wp_error($terms)){
    return;
}

$id = uniqid();
?>

<div class="terms-alphabeta jobboard-alphabeta">
    <div class="find-chars">
        <ul>
            <?php foreach ($chars as $key => $char): ?>
            <li><a href="#<?php echo esc_attr($id . '-' . $key); ?>"><?php echo esc_html($char); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="terms-listing alphabeta-listing">
        <ul>
            <?php
            $index          = 1;
            $last_char      = '';
            $terms_count    = count($terms);
            ?>
            <?php foreach ($terms as $term): ?>
                <?php $char = strtolower(substr($term->name, 0, 1)); ?>
                <?php if($last_char && ($last_char != $char)): ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($last_char != $char): $last_char = $char; ?>
                <li id="<?php echo esc_html($id . '-' . $char); ?>" class="listing-group">
                    <span class="group-char"><?php echo esc_html(strtoupper($char)); ?></span>
                    <ul class="clearfix">
                <?php endif; ?>
                        <li>
                            <a class="term-name" href="<?php echo esc_url(get_term_link($term->term_id)); ?>"><?php echo esc_html($term->name); ?></a>
                            <span class="term-count"><?php printf(esc_html__('(%s) positions', JB_ALPHABETA_TEXT_DOMAIN), $term->count); ?></span>
                        </li>
                <?php if($index == $terms_count): ?>
                    </ul>
                </li>
                <?php endif; ?>
            <?php $index++; endforeach; ?>
        </ul>
    </div>
</div>
