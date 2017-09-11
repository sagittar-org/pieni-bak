<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
      <table class="table">
        <tr>
<?php foreach ($vars['model']->select_hash as $key => $select): ?>
<?php if (in_array($key, $vars['model']->hidden_list)) continue; ?>
          <th style="white-space:nowrap;<?php if (preg_match('/_id$/', $key) OR preg_match('/_price$/', $key) OR preg_match('/_amount$/', $key) OR preg_match('/_total$/', $key)): ?> text-align:right;<?php endif; ?>"><?php l($key); ?></th>
<?php endforeach; ?>
          <th style="white-space:nowrap; width:0; text-align:center;"><?php l('crud_actions'); ?></th>
        </tr>

<?php while (($row = $vars['model']->row()) !== NULL): ?>
<?php $id = $row["{$vars['model']->primary_key}"]; ?>
        <tr>
<?php foreach ($vars['model']->select_hash as $key => $select): ?>
<?php if (in_array($key, $vars['model']->hidden_list)) continue; ?>
          <td><?php load_view('col', ['row' => $row, 'key' => $key], $table); ?></td>
<?php endforeach; ?>
          <td style="white-space:nowrap; width:0; text-align:right;">
<?php foreach ($vars['model']->action_hash as $key => $row_action): ?>
<?php if (in_array($row_action, ['row', 'view'])): ?>
            <a href="<?php href("{$table}/{$key}/{$id}"); ?>" class="btn btn-default"><?php l("crud_{$key}"); ?></a>
<?php elseif ($row_action === 'edit'): ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php elseif ($row_action === 'delete'): ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php endif; ?>
<?php endforeach; ?>
          </td>
        </tr>
<?php endwhile; ?>
      </table>
