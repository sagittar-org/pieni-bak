<?php /* テーブル */ ?>
<?php if ($vars['key'] === 'directive_table'): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<?php foreach (config('uri')['table_list'] as $value): ?>
<option value="<?php h($value); ?>"><?php l($value); ?></option>
<?php endforeach; ?>
</select>
<?php /* デフォルト */ ?>
<?php else: ?>
<input type="text" class="form-control" name="<?php h($vars['key']); ?>">
<?php endif; ?>
