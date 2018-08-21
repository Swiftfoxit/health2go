<?php
/*
Name: Default
Version: 1.0.0
*/
?>

<?php foreach ($twitter as $index => $item): ?>

	<?php
	/* open row. */
	if ($row_index == 0 ): ?><div class="news-twitter-item"><?php endif; ?>

		<div class="item-content">
			<p><?php
				$tweet_content = $item['text'];
				$tweet_content = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $tweet_content);
				$tweet_content = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $tweet_content);
				echo wp_kses_post($tweet_content); ?>
			</p>
			<a href="http://twitter.com/<?php echo esc_attr($item['user']['screen_name']); ?>/statuses/<?php echo esc_attr($item['id_str']); ?>"><?php esc_html_e('Read More', 'news-twitter') ?></a>
		</div>

	<?php $row_index++; ?>

	<?php
	/* close row. */
	if ($row_index == $row || $items_count == $index ): $row_index = 0; ?></div><?php endif; ?>

<?php endforeach; ?>