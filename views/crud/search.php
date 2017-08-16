<?php if (count($vars['model']->where_hash) === 0) return; ?>
<?php $alias = $vars['model']->alias; ?>
<?php $where_hash = $_SESSION[uri('actor')]['index'][$vars['model']->alias]['where_hash']; ?>
<?php /* 簡易フリーワード検索 */ ?>
<?php if (in_array('simple', array_keys($vars['model']->where_hash))): ?>
      <form method="post" action="<?php href(uri('uri_string'), FALSE, FALSE); ?>">
        <div class="input-group">
          <input type="text" name="<?php h("{$alias}_where_hash_simple"); ?>" value="<?php h(isset($where_hash['simple']) ? $where_hash['simple'] : ''); ?>" class="form-control">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
            <button type="submit" name="<?php h($alias); ?>_clear" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></button>
          </span>
        </div><!-- /input-group -->
      </form>
<?php /* 通常検索 */ ?>
<?php else: ?>
      <form method="post" action="<?php href(uri('uri_string'), FALSE, FALSE); ?>">
<?php   foreach ($vars['model']->where_hash as $key => $where): ?>
        <div class="form-group">
          <label><?php l("where_hash_{$key}"); ?></label>
          <input type="text" name="<?php h("{$alias}_where_hash_{$key}"); ?>" value="<?php h(isset($where_hash[$key]) ? $where_hash[$key] : ''); ?>" class="form-control">
        </div>
<?php   endforeach; ?>
        <div class="text-right">
          <button type="submit" class="btn btn-primary"><?php l('crud_search'); ?></button>
          <button type="submit" name="<?php h($alias); ?>_clear" class="btn btn-default"><?php l('crud_clear'); ?></button>
        </div>
      </form>
<?php endif; ?>
