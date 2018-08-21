<?php
/*
 Name: Style 1
 */
?>
<?php foreach ($twitter as $index => $item): ?>

	<?php
	/* open row. */
	if ($row_index == 0 ): ?><div class="news-twitter-item"><?php endif; ?>

	<div class="item-content">
		<div class="news-twitter-icon">
			<i class="fa fa-twitter"></i>
		</div>
		<div class="news-twitter-item-inner">
			<?php

			$tweet_content = $item['text'];
			$tweet_content = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $tweet_content);
			$tweet_content = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $tweet_content);
			?>
			<div class="news-twitter-content">
				<?php echo wp_kses_post($tweet_content); ?>
			</div>
			<?php
			$twitterTime = strtotime($item['created_at']);
			$timeAgo = recruitment_ago($twitterTime);
			?>

			<a href="http://twitter.com/<?php echo esc_attr($item['user']['screen_name']); ?>/statuses/<?php echo esc_attr($item['id_str']); ?>" class="jtwt_date"><?php echo esc_html($timeAgo); ?></a>
		</div>
	</div>

	<?php $row_index++; ?>

	<?php
	/* close row. */
	if ($row_index == $row || $items_count == $index ): $row_index = 0; ?></div><?php endif; ?>

<?php endforeach; ?>
