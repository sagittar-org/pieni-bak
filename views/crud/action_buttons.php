<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
<?php if (in_array($vars['row_action'], ['ajax'])): ?>
            <button onclick="$.ajax({url: '<?php href("{$table}/{$vars['key']}/{$vars['id']}"); ?>', success: function(){<?php if (isset($vars['model']->success_hash[$vars['key']])) echo $vars['model']->success_hash[$vars['key']]; ?>},});" class="btn btn-default"><?php l("crud_{$vars['key']}"); ?></button>
<?php elseif (in_array($vars['row_action'], ['row', 'view'])): ?>
            <a href="<?php href("{$table}/{$vars['key']}/{$vars['id']}"); ?>" class="btn btn-default"><?php l("crud_{$vars['key']}"); ?></a>
<?php elseif ($vars['row_action'] === 'edit'): ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($vars['key'])); ?>Show<?php h($vars['id']); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($vars['key'])); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($vars['key'])); ?>('<?php h($vars['id']); ?>');"><?php l("crud_{$vars['key']}"); ?></button>
<?php elseif ($vars['row_action'] === 'delete'): ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($vars['key'])); ?>Show<?php h($vars['id']); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($vars['key'])); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($vars['key'])); ?>('<?php h($vars['id']); ?>');"><?php l("crud_{$vars['key']}"); ?></button>
<?php endif; ?>
