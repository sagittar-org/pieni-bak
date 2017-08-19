<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
      <table class="table">
        <tr>
<?php foreach ($vars['model']->select_hash as $key => $select): ?>
<?php if (in_array($key, $vars['model']->hidden_list)) continue; ?>
          <th><?php l($key); ?></th>
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
<?php foreach (array_merge($vars['model']->action_hash, $vars['model']->row_action_hash) as $key => $row_action): ?>
<?php if ($row_action !== 'view') continue; ?>
            <a href="<?php href("{$table}/{$key}/{$id}"); ?>" class="btn btn-default"><?php l("crud_{$key}"); ?></a>
<?php endforeach; ?>
<?php foreach (array_merge($vars['model']->action_hash, $vars['model']->row_action_hash) as $key => $row_action): ?>
<?php if ($row_action !== 'edit') continue; ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php endforeach; ?>
<?php foreach (array_merge($vars['model']->action_hash, $vars['model']->row_action_hash) as $key => $row_action): ?>
<?php if ($row_action !== 'delete') continue; ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php endforeach; ?>
          </td>
        </tr>
<?php endwhile; ?>
      </table>
