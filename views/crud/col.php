<?php /* 日付 */ ?>
<?php if (preg_match('/_created$/', $vars['key']) OR preg_match('/_datetime$/', $vars['key'])): ?>
<?php h(date('Y年m月d日 H:i', strtotime($vars['row'][$vars['key']]))); ?>
<?php /* テキスト */ ?>
<?php elseif (preg_match('/_text$/', $vars['key'])): ?>
<?php echo nl2br(preg_replace('/(http\S+)/', '<a href="$1">$1</a>', str_replace('SITE_URL/', site_url('', FALSE, FALSE), $vars['row'][$vars['key']]))); ?>
<?php /* 画像 */ ?>
<?php elseif (preg_match('/_image$/', $vars['key'])): ?>
<img src="<?php h($vars['row'][$vars['key']] !== '' ? $vars['row'][$vars['key']] : direct('no-image.svg', TRUE)); ?>" class="img-thumbnail" style="<?php h(isset($vars['style']) ? $vars['style'] : 'width:100px;height:100px;object-fit:cover;'); ?>">
<?php /* URL */ ?>
<?php elseif (preg_match('/_url$/', $vars['key'])): ?>
<a href="<?php h($vars['row'][$vars['key']]); ?>"><?php h($vars['row'][$vars['key']]); ?></a>
<?php /* 公開状態 */ ?>
<?php elseif (preg_match('/_public$/', $vars['key'])): ?>
<?php l($vars['row'][$vars['key']]); ?>
<?php /* デフォルト */ ?>
<?php else: ?>
<?php h($vars['row'][$vars['key']]); ?>
<?php endif; ?>
