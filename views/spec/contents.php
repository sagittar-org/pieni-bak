<h3><a href="<?php href('spec'); ?>"><?php l('project_name'); ?> <?php l('spec'); ?></a></h3>
<?php foreach (config('uri')['actor_hash'] as $key => $actor): ?>
<h4><?php l("actor_{$key}"); ?></h4>
<table class="table">
<?php foreach (config('uri')['table_list'] as $class): ?>
<?php
$model = load_model($class, [
	'actor' => $key,
	'class' => $class,
	'alias' => $class,
	'auth'   => ['id' => 'AUTH_ID', 'name' => 'AUTH_NAME'],
]);
?>
<?php if (in_array('index', $model->action_list) OR in_array('view', $model->action_list)): ?>
<tr>
<th style="width:100px; white-space:nowrap;"><a href="<?php href("spec/table/{$key}/{$class}"); ?>"><?php l($class); ?></a></th>
<td>
<?php foreach ($model->action_list as $action): ?>
<?php l("crud_{$action}"); ?>&nbsp;
<?php endforeach; ?>
</td>
</tr>
<?php endif; ?>
<?php unset($GLOBALS['models'][$model->alias]); ?>
<?php endforeach; ?>
</table>
<?php endforeach; ?>
