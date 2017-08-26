<?php $table = $vars['model']->table; ?>
<?php if (in_array("{$table}_image", array_keys($vars['model']->select_hash))) return load_view('card_image', $vars, $vars['model']->table); ?>
<?php $alias = $vars['model']->alias; ?>
<?php $row = $vars['row']; ?>
<?php $id = $row[$vars['model']->primary_key]; ?>
    <div class="well">
      <h3 style="margin:0;">
<?php if (in_array('view', array_keys($vars['model']->action_hash))): ?>
        <a href="<?php href("{$table}/view/{$id}"); ?>"><?php h($row[$vars['model']->display]); ?></a>
<?php else: ?>
        <?php h($row[$vars['model']->display]); ?>
<?php endif; ?>
      </h3>
<?php if (in_array('edit', array_keys($vars['model']->action_hash)) OR in_array('delete', array_keys($vars['model']->action_hash))): ?>
      <div class="text-right" style="margin-top:-35px">
<?php foreach ($vars['model']->action_hash as $key => $row_action): ?>
<?php if (in_array($row_action, ['row', 'view'])): ?>
<?php if ($key !== 'view'): ?>
        <a href="<?php href("{$table}/{$key}/{$id}"); ?>" class="btn btn-default"><?php l("crud_{$key}"); ?></a>
<?php endif; ?>
<?php elseif ($row_action === 'edit'): ?>
        <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php elseif ($row_action === 'delete'): ?>
        <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php endif; ?>
<?php endforeach; ?>
      </div>
<?php endif; ?>
      <table class="table" style="margin:0;">
<?php foreach ($vars['model']->select_hash as $key => $select): ?>
<?php if (in_array($key, $vars['model']->hidden_list)) continue; ?>
<?php if ($key === $vars['model']->display) continue; ?>
        <tr>
          <td><?php load_view('col', ['row' => $row, 'key' => $key], $vars['model']->table); ?></td>
        </tr> 
<?php endforeach; ?>
      </table>
    </div>
