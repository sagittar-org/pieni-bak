<?php /* テーブル */ ?>
<?php if ($vars['key'] === 'directive_table'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<?php foreach (config('uri')['table_list'] as $key): ?>
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
<?php /* デフォルト */ ?>
<?php else: ?>
<input type="text" class="form-control" name="<?php h($vars['key']); ?>">
<?php endif; ?>
