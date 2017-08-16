<?php
$action_list = ['index', 'view', 'add', 'edit', 'delete'];
$model = load_model($vars['class'], [
	'actor'  => $vars['actor'],
	'class'  => $vars['class'],
	'alias'  => $vars['class'],
	'method' => 'spec',
	'auth'   => ['id' => 'AUTH_ID', 'name' => 'AUTH_NAME'],
]);
?>
<h3><?php l("actor_{$vars['actor']}"); ?> / <?php l($vars['class']); ?></h3>
<?php load_view('matrix', ['actor' => $vars['actor'], 'class' => $vars['class'], 'model' => $model, 'action_list'=> $action_list], 'spec'); ?>

<?php foreach ($model->has_hash as $key => $has): ?>
<hr>
<h3><?php l("actor_{$vars['actor']}"); ?> / <?php l($vars['class']); ?> / <?php l($key); ?></h3>
<?php
$has_model = load_model($has, [
	'actor'     => $vars['actor'],
	'class'     => $has,
	'alias'     => $key,
	'auth'      => ['id' => 'AUTH_ID', 'name' => 'AUTH_NAME'],
	'parent_id' => 'PARENT_ID',
]);
?>
<?php load_view('matrix', ['actor' => $vars['actor'], 'class' => $has, 'model' => $has_model, 'action_list'=> $action_list], 'spec'); ?>
<?php endforeach; ?>
