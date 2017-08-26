<?php foreach ($vars['model']->action_hash as $key => $row_action): ?>
<?php if ( ! in_array($row_action, ['edit', 'delete'])) continue; ?>
<?php load_view([$key, $row_action], array_merge($vars, ['key' => $key]), $vars['model']->table); ?>
<?php endforeach; ?>
