<?php
foreach (array_keys($vars['model']->action_hash) as $action)
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
<?php foreach (array_keys($vars['model']->action_hash) as $action): ?>
<th class="text-center" title="<?php h($action); ?>"><?php l("crud_{$action}"); ?></th>
<?php endforeach; ?>
</tr>
<?php $key_list = array_keys($vars['model']->select_hash); ?>
<?php foreach ($vars['model']->set_list as $key) if ( ! in_array($key, $key_list)) $key_list[] = $key; ?>
<?php foreach (array_keys($vars['model']->fixed_hash) as $key) if ( ! in_array($key, $key_list)) $key_list[] = $key; ?>
<?php //$key_list = array_unique(array_merge(array_keys($vars['model']->select_hash), $vars['model']->set_list, array_keys($vars['model']->fixed_hash))); ?>
<?php foreach ($key_list as $key): ?>
<tr>
<td title="<?php h($key); ?>"><?php l($key); ?></td>
<?php foreach ($vars['model']->action_hash as $action): ?>
<?php $action_model = model("{$vars['model']->alias}_{$action}"); ?>
<?php
/*
                index   view    add     edit    delete
select_hash     Show    Show            Show    Show
hidden_list     Hidden  Hidden          Hidden  Hidden
set_list                        Affect  Affect
fixed_hash                      Fixed
*/
$class = 'danger';
$value = 'No';
$title = '';
switch ($action)
{
case 'table':
case 'row':
	$class = '';
	$value = '-';
	$title = '';
	break;
case 'index':
case 'view':
	if (in_array($key, $action_model->hidden_list))
	{
		$class = 'warning';
		$value = 'Hidden';
		$title = '';
	}
	else if (in_array($key, array_keys($action_model->select_hash)))
	{
		$class = 'info';
		$value = 'Show';
		$title = '';
	}
	break;
case 'add':
	if (in_array($key, array_keys($action_model->fixed_hash)))
	{
		$class = '';
		$value = 'Fixed';
		$title = $action_model->fixed_hash[$key];
	}
	else if (in_array($key, $action_model->set_list))
	{
		$class = 'success';
		$value = 'Affect';
		$title = '';
	}
	break;
case 'edit':
	if (in_array($key, $action_model->hidden_list))
	{
		$class = 'warning';
		$value = 'Hidden';
		$title = '';
	}
	else if (in_array($key, $action_model->set_list))
	{
		$class = 'success';
		$value = 'Affect';
		$title = '';
	}
	else if (in_array($key, array_keys($action_model->select_hash)))
	{
		$class = 'info';
		$value = 'Show';
		$title = '';
	}
	break;
case 'delete':
	if (in_array($key, $action_model->hidden_list))
	{
		$class = 'warning';
		$value = 'Hidden';
		$title = '';
	}
	else if (in_array($key, array_keys($action_model->select_hash)))
	{
		$class = 'info';
		$value = 'Show';
		$title = '';
	}
	break;
}
?>
<td class="text-center bg-<?php h($class); ?>" title="<?php h($title); ?>"><?php h($value); ?></td>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
</table>
</div>
