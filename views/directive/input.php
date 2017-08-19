<?php /* テーブル */ ?>
<?php if ($vars['key'] === 'directive_table'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<?php foreach (config('uri')['table_list'] as $key): ?>
<?php if ($key === 'directive') continue; ?>
<option value="<?php h($key); ?>"><?php l($key); ?></option>
<?php endforeach; ?>
</select>
<?php /* アクター */ ?>
<?php elseif ($vars['key'] === 'directive_actor'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<option value=""></option>
<?php foreach (array_reverse(array_keys(config('uri')['actor_hash'])) as $key): ?>
<option value="<?php h($key); ?>"><?php l($key); ?></option>
<?php endforeach; ?>
</select>
<?php /* アクション */ ?>
<?php elseif ($vars['key'] === 'directive_action'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<option value=""></option>
<?php foreach (array_keys(config('uri')['action_hash']) as $key): ?>
<option value="<?php h($key); ?>"><?php l("crud_{$key}"); ?></option>
<?php endforeach; ?>
</select>
<?php /* エイリアス */ ?>
<?php elseif ($vars['key'] === 'directive_alias'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<option value=""></option>
<?php foreach (array_merge(config('uri')['table_list'], config('uri')['alias_list']) as $key): ?>
<option value="<?php h($key); ?>"><?php l($key); ?> (<?php h($key); ?>)</option>
<?php endforeach; ?>
</select>
<?php /* メソッド */ ?>
<?php elseif ($vars['key'] === 'directive_method'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<?php foreach (['overwrite', 'append', 'remove'] as $key): ?>
<option value="<?php h($key); ?>"><?php l($key); ?></option>
<?php endforeach; ?>
</select>
<?php /* ディレクティブ */ ?>
<?php elseif ($vars['key'] === 'directive_directive'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<option value=""></option>
<?php foreach (['primary_key', 'display', 'use_card', 'has_hash', 'action_hash', 'select_hash', 'hidden_list', 'set_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'] as $key): ?>
<option value="<?php h($key); ?>"><?php l($key); ?></option>
<?php endforeach; ?>
</select>
<?php /* デフォルト */ ?>
<?php else: ?>
<input type="text" class="form-control" name="<?php h($vars['key']); ?>">
<?php endif; ?>
