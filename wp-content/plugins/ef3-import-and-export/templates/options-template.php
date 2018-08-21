<?php
/**
 * Created by PhpStorm.
 * User: FOX
 * Date: 3/31/2016
 * Time: 1:42 PM
 */

/* ini get */
$_search = array('M','G','K','m','g','k');

$memory_limit = (int)str_replace($_search, null, ini_get("memory_limit"));
$max_time = (int)ini_get("max_execution_time");
$post_max_size = (int)str_replace($_search, null, ini_get('post_max_size'));
$php_ver = PHP_VERSION;

$_notice = ($memory_limit < 128 || $max_time < 60 || $post_max_size < 32) ? 'redux-critical' : 'redux-info';

/* get all demo */
$demos = $this->get_all_demo_folder();

/* get demo installed. */
$demo_installed = get_option('ef3-current-demo-installed');

$create_demo = $this->export_demo_mode();

?>

<div class="hasIcon redux-notice-field redux-field-info <?php echo esc_attr($_notice); ?>">
    <p class="redux-info-icon"><i class="el el-info-circle icon-large"></i></p>
    <p class="redux-info-desc">
        <table class="ef3-server-info">
            <tr>
                <th><?php esc_html_e('PHP Version:', 'envato-market'); ?></th>
                <td><i class="el el-check"></i></td>
                <td><?php echo esc_html($php_ver); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Memory Limit:', 'envato-market') ?></th>
                <?php if($memory_limit >= 128): ?>
                    <td><i class="el el-check"></i></td>
                    <td><?php echo sprintf(esc_html__('Currently: %s (Mb)', ''), $memory_limit); ?></td>
                <?php else: ?>
                    <td><i class="el el-remove-circle"></i></td>
                    <td style="color: #ff6262"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 128M)', ''), $memory_limit); ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <th><?php esc_html_e('Max. Execution Time:', 'envato-market') ?></th>
                <?php if($max_time >= 60): ?>
                    <td><i class="el el-check"></i></td>
                    <td><?php echo sprintf(esc_html__('Currently: %s (s)', ''), $max_time); ?></td>
                <?php else: ?>
                    <td><i class="el el-remove-circle"></i></td>
                    <td style="color: #ff6262"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 60s)', ''), $max_time); ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <th><?php esc_html_e('Max. Post Size:', 'envato-market') ?></th>
                <?php if($post_max_size >= 32): ?>
                    <td><i class="el el-check"></i></td>
                    <td><?php echo sprintf(esc_html__('Currently: %s (Mb)', ''), $post_max_size); ?></td>
                <?php else: ?>
                    <td><i class="el el-remove-circle"></i></td>
                    <td style="color: #ff6262"><?php echo sprintf(esc_html__('Currently: %s (the minimum required 32M)', ''), $post_max_size); ?></td>
                <?php endif; ?>
            </tr>
        </table>
    </p>
</div><!-- server info -->

<?php if($demos): ?>

<div class="ef3-import-and-export">
    <ul class="ef3-list-demos">

    <?php foreach ($demos as $demo): ?>

        <?php

        $demo_url = trailingslashit($this->theme_url . $demo);

        $demo_opacity = $demo_action = $input_disabled = '';

        /**
         * $demo_installed == $demo
         */
        if($demo_installed == $demo){
            $demo_action = '<form method="post">
                <a class="button uninstall-demo" href="tools.php?page=wordpress-reset" title="'.esc_attr__('Uninstall reset all wordpress data, you can back-up all data before uninstall.', 'ef3-import-and-export').'"><span class="dashicons dashicons-trash"></span></a>
                <button type="button" class="button button-primary install-demo" data-demo="'.esc_attr($demo).'"><span class="dashicons dashicons-controls-repeat"></span> '.esc_html__('Update', 'ef3-import-and-export').'</button>
            </form>';
        } else {

            if ($demo_installed){
                $demo_opacity = ' ef3-opacity-0-5';
                $input_disabled = ' disabled="disabled"';
            }

            if($create_demo) $demo_action = '<a href="#" class="button delete-demo" title="'.esc_attr__('Delete Demo Files','ef3-import-and-export').'"><span class="dashicons dashicons-no"></span></a>';
            $demo_action .= '<button type="button" class="button button-primary install-demo" data-demo="'.esc_attr($demo).'"'.$input_disabled.'><span class="dashicons dashicons-randomize"></span> '.esc_html__('Install', 'ef3-import-and-export').'</button>';
        }

        ?>

        <li class="ef3-demo<?php echo esc_attr($demo_opacity); ?>">
            <div class="ef3-content">
                <div class="ef3-thumb"><img src="<?php echo esc_url($demo_url . 'screenshot.png') ?>" alt="<?php echo esc_attr($demo); ?>"></div>
                <div class="ef3-action">
                    <h3><?php echo esc_attr($demo); ?></h3>
                    <div>
                        <button type="button" class="button select-import-data" title="<?php esc_attr_e('Default import all data.', 'ef3-import-and-export'); ?>"<?php echo $input_disabled; ?>><span class="dashicons dashicons-media-archive"></span> <?php esc_html_e('Select Data', 'ef3-import-and-export'); ?></button>
                        <ul class="ef3-data-import">
                            <li>
                                <input class="all-data" type="checkbox" value="all" checked="checked">
                                <label><?php esc_html_e('All', 'ef3-import-and-export'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="attachment">
                                <label><?php esc_html_e('Media', 'ef3-import-and-export'); ?></label>
                            </li>

                            <?php if(function_exists('cptui_get_post_type_data')): ?>

                            <li>
                                <input type="checkbox" value="ctp_ui">
                                <label><?php esc_html_e('Post Type', 'ef3-import-and-export'); ?></label>
                            </li>

                            <?php endif;?>

                            <?php if(class_exists('ReduxFramework')): ?>
                                <li>
                                    <input type="checkbox" value="settings">
                                    <label><?php esc_html_e('Theme Options', 'ef3-import-and-export'); ?></label>
                                </li>
                            <?php endif;?>
                            <li>
                                <input type="checkbox" value="content">
                                <label><?php esc_html_e('Content', 'ef3-import-and-export'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="widgets">
                                <label><?php esc_html_e('Widgets', 'ef3-import-and-export'); ?></label>
                            </li>
                            <li>
                                <input type="checkbox" value="options">
                                <label><?php esc_html_e('WP Settings', 'ef3-import-and-export'); ?></label>
                            </li>

                            <?php if(class_exists('RevSlider')): ?>

                                <li>
                                    <input type="checkbox" value="revslider">
                                    <label><?php esc_html_e('Slider Revolution', 'ef3-import-and-export'); ?></label>
                                </li>

                            <?php endif;?>

                            <?php do_action('ef3-import-action-list-after'); ?>

                        </ul>

                        <?php echo $demo_action; ?>

                    </div>
                </div><!-- actions. -->
                <div class="ef3-demo-process">
                    <div class="ef3-process">
                        <span>0%</span>
                        <div></div>
                    </div>
                </div><!-- process bar. -->
            </div>
        </li>

    <?php endforeach; ?>

    </ul>

</div><!-- demo list. -->

<?php endif; ?>

<?php if($create_demo): ?>

<div class="redux-custom redux-notice-field redux-field-info" style="border-color:purple;">
    <p class="redux-info-desc"><b><?php esc_attr_e('Create new demo data (only for developer)', 'ef3-import-and-export'); ?></b><br><?php esc_attr_e('Set demo name and click to button, tool auto package demo.', 'ef3-import-and-export'); ?></p>
</div><!-- notice -->

<div class="ef3-export-demo">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Demo Name (*)', 'ef3-import-and-export'); ?>
                        <span class="description"><?php esc_html_e('Enter demo slug (EXP : demo 1, demo-1, theme-name-demo-name)', 'ef3-import-and-export'); ?></span>
                    </div>
                </th>
                <td>
                    <fieldset class="redux-field-container redux-field redux-field-init redux-container-text ">
                        <input id="ef3-demo-slug" type="text" value="" class="regular-text" placeholder="<?php esc_attr_e('demo-name', 'ef3-import-and-export'); ?>">
                    </fieldset>
                </td>
            </tr>

            <?php do_action('ef3-export-action-before'); ?>

            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Data (*)', 'ef3-import-and-export'); ?>
                        <span class="description"><?php esc_html_e('Select data export.', 'ef3-import-and-export'); ?></span>
                    </div>
                </th>
                <td>
                <fieldset class="redux-field-container redux-field ef3-export-types">

                        <input name="ef3-export-type[]" type="checkbox" value="attachment" checked="checked">
                        <label><?php esc_html_e('Media', 'ef3-import-and-export'); ?></label>

                        <input name="ef3-export-type[]" type="checkbox" value="widgets" checked="checked">
                        <label><?php esc_html_e('Widgets', 'ef3-import-and-export'); ?></label>

                    <?php if(class_exists('ReduxFramework')): ?>

                        <input name="ef3-export-type[]" type="checkbox" value="settings" checked="checked">
                        <label><?php esc_html_e('Theme Options', 'ef3-import-and-export'); ?></label>

                    <?php endif;?>

                        <input name="ef3-export-type[]" type="checkbox" value="options" checked="checked">
                        <label><?php esc_html_e('WP Settings', 'ef3-import-and-export'); ?></label>

                    <?php if(function_exists('cptui_get_post_type_data')): ?>
                    
                        <input name="ef3-export-type[]" type="checkbox" value="ctp_ui" checked="checked">
                        <label><?php esc_html_e('Post Type', 'ef3-import-and-export'); ?></label>

                    <?php endif;?>

                        <input name="ef3-export-type[]" type="checkbox" value="content" checked="checked">
                        <label><?php esc_html_e('Content', 'ef3-import-and-export'); ?></label>

                    <?php if(class_exists('RevSlider')): ?>

                        <input name="ef3-export-type[]" type="checkbox" value="revslider" checked="checked">
                        <label><?php esc_html_e('Slider Revolution', 'ef3-import-and-export'); ?></label>

                    <?php endif;?>

                    <?php if(ef3_git_exists()): ?>
                        <input name="ef3-export-type[]" type="checkbox" value="git" checked="checked">
                        <label><?php esc_html_e('Sync Git', 'ef3-import-and-export'); ?></label>
                    <?php endif; ?>

                    <?php do_action('ef3-export-action-list-after'); ?>

                </fieldset>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <div class="redux_field_th">
                        <?php esc_html_e('Export Demo', 'ef3-import-and-export'); ?><span class="spinner"></span>
                        <span class="description"><?php esc_html_e('Auto create demo files "your-theme/inc/demo-data/demo-name"', 'ef3-import-and-export'); ?></span>
                    </div>
                </th>
                <td>
                    <button type="button" class="button button-primary create-demo"><?php esc_html_e('Create Demo', 'ef3-import-and-export'); ?></button>
                    <button type="button" class="button button-primary download-demo"><?php esc_html_e('Download Demo', 'ef3-import-and-export'); ?></button>
                </td>
            </tr>

            <?php do_action('ef3-export-action-after'); ?>

        </tbody>
    </table>
</div><!-- export demo -->

<?php endif; ?>

<div class="redux-custom redux-notice-field redux-field-info" style="border-color:#0099d5;">
    <p class="redux-info-desc"><b><?php esc_html_e('Setting Export & Import', 'ef3-import-and-export'); ?></b><br><?php esc_attr_e('Import and export settings Theme Options.', 'ef3-import-and-export'); ?></p>
</div><!-- notice -->
