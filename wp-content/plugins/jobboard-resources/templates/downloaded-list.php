<?php
/**
 * @Template: downloaded-list.php
 * @since: 1.0.0
 * @author: KP
 * @descriptions:
 * @create: 20-Dec-17
 */
?>
<h3><?php echo esc_html__('Recruitment Resources Downloaded') ?></h3>
<?php
foreach ($jr as $item):
    ?>
    <div class="row">
        <div class="jr-dl-id"><?php echo '#'.$item['dl_id']?></div>
        <div class="jr-dl-email"><?php echo $item['email']?></div>
        <div class="jr-dl-resources-id"><?php echo $item['jr_id']?></div>
    </div>
<?php
endforeach;
?>
