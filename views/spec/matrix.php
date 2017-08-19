<?php
$action_list = ['index', 'view', 'add', 'edit', 'delete'];
foreach ($action_list as $action)
{
	load_model($vars['class'], [
		'actor'  => $vars['actor'],
		'class'  => $vars['class'],
		'alias'  => $vars['model']->alias,
		'method' => $action,
		'auth'   => ['id' => 'AUTH_ID', 'name' => 'AUTH_NAME'],
	], "{$vars['model']->alias}_{$action}");
}
?>
<a class="pull-right" data-toggle="collapse" href="#<?php h($vars['model']->alias); ?>_matrix"><span class="caret"></span></a>
<h4><?php l('matrix'); ?></h4>
<div class="collapse in" id="<?php h($vars['model']->alias); ?>_matrix">
<table class="table table-bordered">
<tr>
<th><?php l('field'); ?></th>
<?php foreach ($action_list as $action): ?>
<?php if ( ! in_array($action, $vars['model']->action_list)) continue; ?>
<th class="text-center"><?php l("crud_{$action}"); ?></th>
<?php endforeach; ?>
</tr>
<?php foreach (array_unique(array_merge(array_keys($vars['model']->select_hash), $vars['model']->set_list, array_keys($vars['model']->fixed_hash))) as $key): ?>
<tr>
<td title="<?php h($key); ?>"><?php l($key); ?></td>
<?php foreach ($action_list as $action): ?>
<?php $action_model = model("{$vars['model']->alias}_{$action}"); ?>
<?php if ( ! in_array($action, $vars['model']->action_list)) continue; ?>
<?php
if ($action === 'add' && in_array($key, array_keys($action_model->fixed_hash)))
{
	$class = 'warning';
	$value = 'Fixed';
	$title = $action_model->fixed_hash[$key];
}
else if (in_array($key, $action_model->hidden_list))
{
	$class = 'warning';
	$value = 'Hidden';
	$title = '';
}
else if (($action === 'add' OR $action === 'edit') && in_array($key, $action_model->set_list))
{
	$class = 'success';
	$value = 'Yes';
	$title = '';
}
else if ( ! in_array($key, array_keys($action_model->select_hash)))
{
	$class = 'danger';
	$value = 'No';
	$title = '';
}
else if ($action === 'delete' OR $action === 'edit' && ! in_array($key, $action_model->set_list))
{
	$class = 'info';
	$value = 'Show';
	$title = '';
}
else
{
	$class = 'success';
	$value = 'Yes';
	$title = '';
}
?>
<td class="text-center bg-<?php h($class); ?>" title="<?php h($title); ?>"><?php h($value); ?></td>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
</table>
</div>
