<a class="pull-right" data-toggle="collapse" href="#<?php h($vars['model']->alias); ?>_propaties"><span class="caret"></span></a>
<h4><?php l('directive'); ?></h4>
<div class="collapse in" id="<?php h($vars['model']->alias); ?>_propaties">
<table class="table">
<tr>
<th><?php l('primary_key'); ?></th>
<td><?php l($vars['model']->primary_key); ?></td>
</tr>
<tr>
<th><?php l('display'); ?></th>
<td><?php l($vars['model']->display); ?></td>
</tr>
<tr>
<th><?php l('use_card'); ?></th>
<td><?php h($vars['model']->use_card === TRUE ? 'Yes' : 'No'); ?></td>
</tr>
<tr>
<th><?php l('join_hash'); ?></th>
<td>
<?php foreach ($vars['model']->join_hash as $key => $join): ?>
<div title="<?php h("{$join['table']} AS `{$key}` ON{$join['cond']}"); ?>"><?php l($key); ?></div>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th><?php l('where_list'); ?></th>
<td>
<?php foreach ($vars['model']->where_list as $where): ?>
<div><?php l($where); ?></div>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th><?php l('where_hash'); ?></th>
<td>
<?php foreach ($vars['model']->where_hash as $key => $where): ?>
<div title="<?php h($where); ?>"><?php l($key); ?></div>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th><?php l('order_by_hash'); ?></th>
<td>
<?php foreach ($vars['model']->order_by_hash as $key => $order_by): ?>
<div title="<?php h($order_by); ?>"><?php l($key); ?></div>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th><?php l('limit_list'); ?></th>
<td><?php h(implode(', ', $vars['model']->limit_list)); ?></td>
</tr>
</table>
</div>
