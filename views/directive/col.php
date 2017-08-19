<?php /* 値 */ ?>
<?php if ($vars['key'] === 'directive_value'): ?>
<?php h($vars['row'][$vars['key']]); ?>
<?php /* デフォルト */ ?>
<?php else: ?>
<span title="<?php h($vars['row'][$vars['key']]); ?>"><?php l($vars['row'][$vars['key']]); ?></span>
<?php endif; ?>
