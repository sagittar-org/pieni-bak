<?php if (count($vars['model']->where_hash) === 0) return; ?>
<?php $alias = $vars['model']->alias; ?>
<?php $where_hash = $_SESSION[uri('actor')]['index'][$vars['model']->alias]['where_hash']; ?>
      <form method="post" action="<?php href(uri('uri_string'), FALSE, FALSE); ?>">
<?php foreach ($vars['model']->where_hash as $key => $where): ?>
        <div class="form-group">
          <label><?php l("where_hash_{$key}"); ?></label>
<?php /* テーブル */ ?>
<?php if ($key === 'directive_table'): ?>
<select name="<?php h("{$alias}_where_hash_{$key}"); ?>" class="form-control" onchange="this.form.submit();">
<option value=""></option>
<?php foreach (config('uri')['table_list'] as $value): ?>
<option value="<?php h($value); ?>"<?php h(isset($where_hash[$key]) && $value === $where_hash[$key] ?  ' selected' : ''); ?>><?php l($value); ?></option>
<?php endforeach; ?>
</select>
<?php /* アクター */ ?>
<?php elseif ($key === 'directive_actor'): ?>
<select name="<?php h("{$alias}_where_hash_{$key}"); ?>" class="form-control" onchange="this.form.submit();">
<option value=""></option>
<?php foreach (array_reverse(array_keys(config('uri')['actor_hash'])) as $value): ?>
<option value="<?php h($value); ?>"<?php h(isset($where_hash[$key]) && $value === $where_hash[$key] ?  ' selected' : ''); ?>><?php l($value); ?></option>
<?php endforeach; ?>
</select>
<?php /* アクション */ ?>
<?php elseif ($key === 'directive_action'): ?>
<select name="<?php h("{$alias}_where_hash_{$key}"); ?>" class="form-control" onchange="this.form.submit();">
<option value=""></option>
<?php foreach (config('uri')['action_list'] as $value): ?>
<option value="<?php h($value); ?>"<?php h(isset($where_hash[$key]) && $value === $where_hash[$key] ?  ' selected' : ''); ?>><?php l("crud_{$value}"); ?></option>
<?php endforeach; ?>
</select>
<?php /* エイリアス */ ?>
<?php elseif ($key === 'directive_alias'): ?>
<select name="<?php h("{$alias}_where_hash_{$key}"); ?>" class="form-control" onchange="this.form.submit();">
<option value=""></option>
<?php foreach (array_merge(config('uri')['table_list'], config('uri')['alias_list']) as $value): ?>
<option value="<?php h($value); ?>"<?php h(isset($where_hash[$key]) && $value === $where_hash[$key] ?  ' selected' : ''); ?>><?php l($value); ?> (<?php h($value); ?>)</option>
<?php endforeach; ?>
</select>
<?php /* メソッド */ ?>
<?php elseif ($key === 'directive_method'): ?>
<select name="<?php h("{$alias}_where_hash_{$key}"); ?>" class="form-control" onchange="this.form.submit();">
<option value=""></option>
<?php foreach (['overwrite', 'append', 'remove'] as $value): ?>
<option value="<?php h($value); ?>"<?php h(isset($where_hash[$key]) && $value === $where_hash[$key] ?  ' selected' : ''); ?>><?php l($value); ?></option>
<?php endforeach; ?>
</select>
<?php /* ディレクティブ */ ?>
<?php elseif ($key === 'directive_directive'): ?>
<select name="<?php h("{$alias}_where_hash_{$key}"); ?>" class="form-control" onchange="this.form.submit();">
<option value=""></option>
<?php foreach (['primary_key', 'display', 'use_card', 'has_hash', 'action_list', 'row_action_hash', 'select_hash', 'hidden_list', 'set_list', 'fixed_hash', 'success_hash', 'join_hash', 'where_list', 'where_hash', 'order_by_hash', 'limit_list'] as $value): ?>
<option value="<?php h($value); ?>"<?php h(isset($where_hash[$key]) && $value === $where_hash[$key] ?  ' selected' : ''); ?>><?php l($value); ?></option>
<?php endforeach; ?>
</select>
<?php /* デフォルト */ ?>
<?php else: ?>
          <input type="text" name="<?php h("{$alias}_where_hash_{$key}"); ?>" value="<?php h(isset($where_hash[$key]) ? $where_hash[$key] : ''); ?>" class="form-control">
<?php endif; ?>
        </div>
<?php endforeach; ?>
        <div class="text-right">
          <button type="submit" class="btn btn-primary"><?php l('crud_search'); ?></button>
          <button type="submit" name="<?php h($alias); ?>_clear" class="btn btn-default"><?php l('crud_clear'); ?></button>
        </div>
      </form>
