<?php
/**
 * The Template for displaying input text.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/fields/fields/video.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        Quan
 * @package    JobBoard/Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (isset($value) && !empty($value) && wp_http_validate_url($value)):
    if (strpos($value, 'youtube') !== false):
        preg_match('/v=(.*)/', $value, $video);
        ?>
        <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?php echo $video[1]; ?>" frameborder="0"
                allowfullscreen></iframe>
        <?php
    else:
        if (strpos($value, '.mp4')):
            ?>
            <video width="100%" height="315" controls>
                <source src="<?php echo $value; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <?php
        endif;
    endif;

endif;
?>
