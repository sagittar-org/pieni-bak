<?php foreach ($vars['model']->action_hash as $key => $row_action): ?>
<?php if ($row_action !== 'edit') continue; ?>
<?php load_view('edit', array_merge($vars, ['key' => $key]), $vars['model']->table); ?>
<?php endforeach; ?>

<?php load_view('delete', $vars, $vars['model']->table); ?>
