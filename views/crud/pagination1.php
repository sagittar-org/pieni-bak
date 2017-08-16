<?php $alias = $vars['model']->alias; ?>
<?php $order_by = $vars['model']->session['order_by']; ?>
<?php $limit = $vars['model']->session['limit']; ?>
<?php $offset = $vars['model']->session['offset']; ?>
<?php if ($vars['model']->num_rows > 0): ?>
      <form method="post" action="<?php href(uri('uri_string'), FALSE, FALSE); ?>">
        <span><?php l('crud_pagination', [number_format($vars['model']->num_rows), number_format($offset / $limit + 1)]); ?></span>

<?php if (count($vars['model']->order_by_hash) > 1): ?>
        / <?php l('crud_order_by'); ?>:
        <select name="<?php h($alias); ?>_order_by" onchange="this.form.submit();">
<?php foreach ($vars['model']->order_by_hash as $key => $o): ?>
          <option value="<?php h($key); ?>"<?php if ($key === $order_by): ?> selected<?php endif; ?>><?php l($key); ?></option>
<?php endforeach; ?>
        </select>
<?php endif; ?>

<?php if (count($vars['model']->limit_list) > 1): ?>
        / <?php l('crud_per_page'); ?>:
        <select name="<?php h($alias); ?>_limit" onchange="this.form.submit();">
<?php foreach ($vars['model']->limit_list as $l): ?>
          <option value="<?php h($l); ?>"<?php if ($l === intval($limit)): ?> selected<?php endif; ?>><?php h($l); ?></option>
<?php endforeach; ?>
        </select>
<?php endif; ?>
      </form>
<?php else: ?>
      <div class="well">
        <?php l('crud_no_match', [l($alias, [], TRUE)]); ?>
      </div>
<?php endif; ?>
