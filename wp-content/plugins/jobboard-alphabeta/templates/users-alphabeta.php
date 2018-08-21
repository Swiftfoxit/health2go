<?php
/**
 * The Template for displaying users listing.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/add-ons/alphabeta/users-alphabeta.php.
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

if(empty($users)){
    return;
}

$id = uniqid();
?>
<div class="users-alphabeta jobboard-alphabeta">
    <div class="find-chars">
        <ul>
            <?php foreach ($chars as $key => $char): ?>
            <li><a href="#<?php echo esc_attr($id . '-' . $key); ?>"><?php echo esc_html($char); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="users-listing alphabeta-listing">
        <ul>
            <?php
            $index          = 1;
            $last_char      = '';
            $terms_count    = count($users);
            ?>
            <?php foreach ($users as $user): ?>
                <?php $char = strtolower(substr($user->data->display_name, 0, 1)); ?>
                <?php if($last_char && ($last_char != $char)): ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($last_char != $char): $last_char = $char; ?>
                <li id="<?php echo esc_html($id . '-' .  $char); ?>" class="listing-group">
                    <span class="group-char"><?php echo esc_html(strtoupper($char)); ?></span>
                    <ul class="clearfix">
                <?php endif; ?>
                        <li>
                            <a class="user-name" href="<?php echo esc_url(jb_account_get_permalink($user->data->ID)); ?>"><?php echo esc_html($user->data->display_name); ?></a>
                            <span class="user-count"><?php printf(esc_html__('(%s) positions', JB_ALPHABETA_TEXT_DOMAIN), jb_employer_get_vacancies($user->data->ID)); ?></span>
                        </li>
                <?php if($index == $terms_count): ?>
                    </ul>
                </li>
                <?php endif; ?>
            <?php $index++; endforeach; ?>
        </ul>
    </div>
</div>
